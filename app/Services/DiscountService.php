<?php
namespace App\Services;

use App\Enums\DiscountScope;
use App\Models\Discount;
use App\Models\Sales;

class DiscountService
{
    // -------------------------------------------------------------------------
    // Applying discounts to a sale
    // -------------------------------------------------------------------------

    /**
     * Apply a discount rule to a sale and return the total deduction amount.
     * Delegates to order-level or item-level application based on scope.
     * Called by SaleService::applyDiscountCode().
     */
    public function applyToSale(Sales $sale, Discount $discount): float
    {
        return match ($discount->scope) {
            DiscountScope::Order => $this->applyOrderLevel($sale, $discount),
            DiscountScope::Item  => $this->applyItemLevel($sale, $discount),
        };
    }

    // -------------------------------------------------------------------------
    // Order-level discounts
    // -------------------------------------------------------------------------

    /**
     * Apply a discount to the entire order subtotal.
     * Updates the sale's discount_amount column only — line items
     * are not touched for order-level discounts.
     *
     * Returns the deduction amount applied.
     */
    protected function applyOrderLevel(Sales $sale, Discount $discount): float
    {
        $deduction = $discount->calculateDeduction((float) $sale->subtotal);

        $sale->update(['discount_amount' => $deduction]);

        return $deduction;
    }

    // -------------------------------------------------------------------------
    // Item-level discounts
    // -------------------------------------------------------------------------

    /**
     * Apply a discount to each individual line item.
     * Updates each sale_item's discount_amount and line_total,
     * then returns the sum of all item deductions.
     *
     * Item-level discounts are useful for "10% off all items"
     * promotions where you want the per-item discount shown on the receipt.
     */
    protected function applyItemLevel(Sales $sale, Discount $discount): float
    {
        $sale->loadMissing('items');

        $totalDeduction = 0;

        foreach ($sale->items as $item) {
            $itemSubtotal = (float) $item->unit_price * $item->quantity;
            $deduction    = $discount->calculateDeduction($itemSubtotal);

            $item->update([
                'discount_amount' => $deduction,
                'discount_id'     => $discount->id,
                'line_total'      => max(0, $itemSubtotal - $deduction + (float) $item->tax_amount),
            ]);

            $totalDeduction += $deduction;
        }

        return round($totalDeduction, 2);
    }

    // -------------------------------------------------------------------------
    // Validation helpers
    // -------------------------------------------------------------------------

    /**
     * Validate a discount code string against a sale and return
     * the resolved Discount model if valid.
     *
     * This is the entry point for the terminal's "Apply Code" flow.
     * Returns an array with 'valid' boolean, 'discount' model if valid,
     * and a 'message' string for display at the terminal.
     *
     * Usage:
     * $result = $discountService->validateCodeForSale($sale, 'SAVE20');
     * if ($result['valid']) { ... }
     */
    public function validateCodeForSale(Sales $sale, string $code): array
    {
        $discountCode = \App\Models\DiscountCode::with('discount')
            ->forCode($code)
            ->valid()
            ->first();

        if (! $discountCode) {
            return [
                'valid'   => false,
                'message' => 'This code is invalid or has expired.',
            ];
        }

        $discount = $discountCode->discount;

        if (! $discount->appliesTo((float) $sale->subtotal)) {
            $min = number_format($discount->minimum_order_amount, 2);
            return [
                'valid'   => false,
                'message' => "Minimum order amount of {$min} required for this code.",
            ];
        }

        // Calculate a preview of the deduction without applying it
        $preview = $discount->scope === DiscountScope::Order
            ? $discount->calculateDeduction((float) $sale->subtotal)
            : $this->previewItemLevelDeduction($sale, $discount);

        return [
            'valid'    => true,
            'message'  => "Code applied — you save " . number_format($preview, 2),
            'discount' => $discount,
            'code'     => $discountCode,
            'preview'  => $preview,
        ];
    }

    /**
     * Calculate what the total item-level deduction would be
     * without actually updating any records.
     * Used by validateCodeForSale() to show a preview at the terminal.
     */
    protected function previewItemLevelDeduction(Sales $sale, Discount $discount): float
    {
        $sale->loadMissing('items');

        return round(
            $sale->items->sum(function ($item) use ($discount) {
                $itemSubtotal = (float) $item->unit_price * $item->quantity;
                return $discount->calculateDeduction($itemSubtotal);
            }),
            2
        );
    }

    // -------------------------------------------------------------------------
    // Clearing item-level discounts
    // -------------------------------------------------------------------------

    /**
     * Remove item-level discounts from all line items on a sale
     * and restore their original line totals.
     * Called by SaleService::removeDiscount() when an item-scoped
     * discount is cleared.
     */
    public function clearItemDiscounts(Sales $sale): void
    {
        $sale->loadMissing('items');

        foreach ($sale->items as $item) {
            $originalLineTotal = (float) $item->unit_price * $item->quantity;

            $item->update([
                'discount_amount' => 0,
                'discount_id'     => null,
                'line_total'      => $originalLineTotal,
            ]);
        }
    }
}
