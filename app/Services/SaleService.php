<?php
namespace App\Services;

use App\Enums\SaleStatus;
use App\Models\Customer;
use App\Models\Discount;
use App\Models\DiscountCode;
use App\Models\Location;
use App\Models\ProductVariant;
use App\Models\Sales;
use App\Models\Terminal;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class SaleService
{
    /**
     * Create a new class instance.
     */
    public function __construct(
        protected InventoryService $inventoryService,
        protected DiscountService $discountService,
        protected LoyaltyService $loyaltyService,
        protected ReceiptService $receiptService,
    ) {}

    // -------------------------------------------------------------------------
    // Opening a sale
    // -------------------------------------------------------------------------

    /**
     * Open a new pending sale at a terminal.
     * This creates the sale record immediately so items can be added
     * incrementally as the cashier scans them.
     */
    public function open(
        Location $location,
        User $cashier,
        ?Terminal $terminal = null,
        ?Customer $customer = null,
    ): Sales {
        return Sales::create([
            'organization_id' => $location->organization_id,
            'location_id'     => $location->id,
            'terminal_id'     => $terminal?->id,
            'user_id'         => $cashier->id,
            'customer_id'     => $customer?->id,
            'sale_number'     => $this->generateSaleNumber($location),
            'status'          => SaleStatus::Pending,
            'subtotal'        => 0,
            'discount_amount' => 0,
            'tax_amount'      => 0,
            'total_amount'    => 0,
        ]);
    }

    // -------------------------------------------------------------------------
    // Managing line items
    // -------------------------------------------------------------------------

    /**
     * Add a product variant to a sale or increment its quantity
     * if it already exists as a line item.
     *
     * @throws \RuntimeException if variant is out of stock
     * @throws \RuntimeException if sale is not editable
     */
    public function addItem(
        Sales $sale,
        ProductVariant $variant,
        int $quantity = 1,
    ): Sales {
        $this->assertEditable($sale);

        // Confirm stock is available before adding to the basket
        $this->inventoryService->assertSufficientStock(
            $variant,
            $sale->location,
            $quantity
        );

        DB::transaction(function () use ($sale, $variant, $quantity) {
            $existing = $sale->items()
                ->where('product_variant_id', $variant->id)
                ->first();

            if ($existing) {
                // Re-check stock for the combined quantity
                $this->inventoryService->assertSufficientStock(
                    $variant,
                    $sale->location,
                    $existing->quantity + $quantity
                );
                $existing->increment('quantity', $quantity);
                $existing->update([
                    'line_total' => $this->calculateLineTotal(
                        $existing->unit_price,
                        $existing->quantity + $quantity,
                        $existing->discount_amount
                    ),
                ]);
            } else {
                $sale->items()->create([
                    'product_variant_id' => $variant->id,
                    'product_name'       => $variant->product->name,
                    'variant_name'       => $variant->name,
                    'sku'                => $variant->sku,
                    'quantity'           => $quantity,
                    'unit_price'         => $variant->price,
                    'cost_price'         => $variant->cost_price,
                    'discount_amount'    => 0,
                    'tax_amount'         => 0,
                    'line_total'         => $variant->price * $quantity,
                ]);
            }

            $this->recalculate($sale);
        });

        return $sale->fresh(['items']);
    }

    /**
     * Remove a line item from a sale entirely.
     *
     * @throws \RuntimeException if sale is not editable
     */
    public function removeItem(Sales $sale, int $saleItemId): Sales
    {
        $this->assertEditable($sale);

        DB::transaction(function () use ($sale, $saleItemId) {
            $sale->items()->where('id', $saleItemId)->delete();
            $this->recalculate($sale);
        });

        return $sale->fresh(['items']);
    }

    /**
     * Update the quantity of a specific line item.
     * Setting quantity to 0 removes the item entirely.
     *
     * @throws \RuntimeException if sale is not editable
     * @throws \RuntimeException if insufficient stock
     */
    public function updateItemQuantity(
        Sales $sale,
        int $saleItemId,
        int $quantity,
    ): Sales {
        $this->assertEditable($sale);

        if ($quantity === 0) {
            return $this->removeItem($sale, $saleItemId);
        }

        DB::transaction(function () use ($sale, $saleItemId, $quantity) {
            $item    = $sale->items()->findOrFail($saleItemId);
            $variant = $item->variant;

            $this->inventoryService->assertSufficientStock(
                $variant,
                $sale->location,
                $quantity
            );

            $item->update([
                'quantity'   => $quantity,
                'line_total' => $this->calculateLineTotal(
                    $item->unit_price,
                    $quantity,
                    $item->discount_amount
                ),
            ]);

            $this->recalculate($sale);
        });

        return $sale->fresh(['items']);
    }

    // -------------------------------------------------------------------------
    // Applying discounts
    // -------------------------------------------------------------------------

    /**
     * Apply a discount code to the sale.
     * Validates the code, applies the deduction, and records the code used.
     *
     * @throws \InvalidArgumentException if code is invalid or does not meet minimum order amount
     */
    public function applyDiscountCode(Sales $sale, string $code): Sales
    {
        $this->assertEditable($sale);

        $discountCode = DiscountCode::with('discount')
            ->forCode($code)
            ->valid()
            ->firstOrFail();

        $discount = $discountCode->discount;

        if (! $discount->appliesTo($sale->subtotal)) {
            throw new \InvalidArgumentException(
                "Minimum order amount of {$discount->minimum_order_amount} not met."
            );
        }

        DB::transaction(function () use ($sale, $discount, $discountCode) {
            $deduction = $this->discountService->applyToSale($sale, $discount);

            $sale->update([
                'discount_id'     => $discount->id,
                'discount_code'   => $discountCode->code,
                'discount_amount' => $deduction,
            ]);

            $discountCode->recordUse();
            $this->recalculate($sale);
        });

        return $sale->fresh(['items']);
    }

    /**
     * Remove any applied discount from the sale.
     */
    public function removeDiscount(Sales $sale): Sales
    {
        $this->assertEditable($sale);

        // Clear line-level discounts if order-level code was applied
        $sale->items()->update(['discount_amount' => 0]);

        $sale->update([
            'discount_id'     => null,
            'discount_code'   => null,
            'discount_amount' => 0,
        ]);

        $this->recalculate($sale);

        return $sale->fresh(['items']);
    }

    // -------------------------------------------------------------------------
    // Completing a sale
    // -------------------------------------------------------------------------

    /**
     * Complete a sale after all payments have been confirmed.
     * This is the most critical method in the system — it:
     *   1. Marks the sale as completed
     *   2. Decrements inventory for every line item
     *   3. Earns loyalty points for the customer
     *   4. Upgrades the customer's tier if eligible
     *   5. Generates and stores the receipt snapshot
     *
     * Everything runs inside a single transaction. If any step fails,
     * the entire sale rolls back to pending.
     *
     * @throws \RuntimeException if sale is not fully paid
     */
    public function complete(Sales $sale): Sales
    {
        $this->assertEditable($sale);

        if (! $sale->isFullyPaid()) {
            throw new \RuntimeException(
                "Sale {$sale->sale_number} cannot be completed — outstanding balance of "
                . $sale->amountOutstanding() . " remains."
            );
        }

        DB::transaction(function () use ($sale) {
            // 1. Mark the sale as completed
            $sale->update([
                'status'       => SaleStatus::Completed,
                'completed_at' => now(),
            ]);

            // 2. Decrement inventory for each line item
            foreach ($sale->items as $item) {
                $this->inventoryService->decrementForSaleItem($item, $sale->location);
            }

            // 3. Award loyalty points and check for tier upgrade
            if ($sale->customer_id) {
                $this->loyaltyService->awardPointsForSale($sale);
            }

            // 4. Generate the frozen receipt snapshot
            $this->receiptService->generateForSale($sale);
        });

        return $sale->fresh(['items', 'payments', 'receipt']);
    }

    // -------------------------------------------------------------------------
    // Voiding a sale
    // -------------------------------------------------------------------------

    /**
     * Void a pending sale that has not yet been completed.
     * No inventory changes are needed since stock was never decremented.
     *
     * @throws \RuntimeException if sale is already completed or refunded
     */
    public function void(Sales $sale, string $reason = ''): Sales
    {
        if (! in_array($sale->status, [SaleStatus::Pending])) {
            throw new \RuntimeException(
                "Only pending sales can be voided. Use refund() for completed sales."
            );
        }

        $sale->update([
            'status' => SaleStatus::Voided,
            'notes'  => $reason ?: $sale->notes,
        ]);

        return $sale->fresh();
    }

    // -------------------------------------------------------------------------
    // Refunding a sale
    // -------------------------------------------------------------------------

    /**
     * Refund a completed sale in full.
     * This reverses inventory decrements and reverses loyalty points.
     *
     * @throws \RuntimeException if sale is not in completed status
     */
    public function refund(Sales $sale, string $reason = ''): Sales
    {
        if ($sale->status !== SaleStatus::Completed) {
            throw new \RuntimeException(
                "Only completed sales can be refunded."
            );
        }

        DB::transaction(function () use ($sale, $reason) {
            // 1. Restore inventory for every line item
            foreach ($sale->items as $item) {
                $this->inventoryService->incrementForRefund($item, $sale->location);
            }

            // 2. Reverse loyalty points if customer was attached
            if ($sale->customer_id) {
                $this->loyaltyService->reversePointsForSale($sale);
            }

            // 3. Mark the sale as refunded
            $sale->update([
                'status' => SaleStatus::Refunded,
                'notes'  => $reason ?: $sale->notes,
            ]);
        });

        return $sale->fresh();
    }

    // -------------------------------------------------------------------------
    // Attaching / detaching a customer
    // -------------------------------------------------------------------------

    /**
     * Attach a customer to an open sale.
     * Useful when a customer is identified after items are already scanned.
     */
    public function attachCustomer(Sales $sale, Customer $customer): Sales
    {
        $this->assertEditable($sale);

        $sale->update(['customer_id' => $customer->id]);

        return $sale->fresh();
    }

    /**
     * Detach the customer from a sale, making it a walk-in.
     */
    public function detachCustomer(Sales $sale): Sales
    {
        $this->assertEditable($sale);

        $sale->update(['customer_id' => null]);

        return $sale->fresh();
    }

    // -------------------------------------------------------------------------
    // Internal helpers
    // -------------------------------------------------------------------------

    /**
     * Recalculate and persist the sale totals from scratch based on
     * current line items. Always call this after any item change.
     */
    protected function recalculate(Sales $sale): void
    {
        $sale->loadMissing('items');

        $subtotal = $sale->items->sum('line_total');

        // Order-level discount is stored on the sale itself
        $discountAmount = $sale->discount_amount ?? 0;

        // Tax calculation placeholder — adjust to your tax rules
        $taxAmount = 0;

        $totalAmount = max(0, $subtotal - $discountAmount + $taxAmount);

        $sale->update([
            'subtotal'     => $subtotal,
            'tax_amount'   => $taxAmount,
            'total_amount' => $totalAmount,
        ]);
    }

    /**
     * Calculate the total for a single line item.
     */
    protected function calculateLineTotal(
        float $unitPrice,
        int $quantity,
        float $discountAmount = 0,
    ): float {
        return max(0, ($unitPrice * $quantity) - $discountAmount);
    }

    /**
     * Generate a unique, human-readable sale number.
     * Format: ORD-{LOCATION_CODE}-{YYYYMMDD}-{5 random chars}
     * e.g. ORD-ACR01-20250401-X7K2M
     */
    protected function generateSaleNumber(Location $location): string
    {
        $locationCode = strtoupper($location->code ?? 'LOC');
        $date         = now()->format('Ymd');
        $random       = strtoupper(Str::random(5));

        return "ORD-{$locationCode}-{$date}-{$random}";
    }

    /**
     * Assert that a sale is in a state that allows modifications.
     *
     * @throws \RuntimeException if the sale is not pending
     */
    protected function assertEditable(Sales $sale): void
    {
        if (! $sale->isEditable()) {
            throw new \RuntimeException(
                "Sale {$sale->sale_number} is {$sale->status->value} and cannot be modified."
            );
        }
    }
}
