<?php
namespace App\Http\Controllers\Terminal;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSalesRequest;
use App\Http\Requests\Terminal\CreateSaleRequest;
use App\Http\Requests\UpdateSalesRequest;
use App\Models\Customer;
use App\Models\ProductVariant;
use App\Models\Sales;
use App\Models\Terminal;
use App\Services\SaleService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SalesController extends Controller
{
    public function __construct(
        protected SaleService $saleService,
    ) {}

    // -------------------------------------------------------------------------
    // Opening a sale
    // -------------------------------------------------------------------------

    /**
     * Open a new pending sale at a terminal.
     *
     * POST /terminal/{terminal}/sales
     */
    public function open(CreateSaleRequest $request, Terminal $terminal): JsonResponse
    {
        $customer = $request->customer_id
            ? Customer::findOrFail($request->customer_id)
            : null;

        $sale = $this->saleService->open(
            location: $terminal->location,
            cashier: $request->user(),
            terminal: $terminal,
            customer: $customer,
        );

        return response()->json([
            'message' => 'Sale opened.',
            'sale'    => $sale->load(['items', 'customer', 'payments']),
        ], 201);
    }

    // -------------------------------------------------------------------------
    // Viewing a sale
    // -------------------------------------------------------------------------

    /**
     * Get the current state of a sale with all its line items and payments.
     * Called by the terminal UI to refresh after every change.
     *
     * GET /terminal/{terminal}/sales/{sale}
     */
    public function show(Terminal $terminal, Sales $sale): JsonResponse
    {
        $this->authoriseSale($terminal, $sale);

        return response()->json([
            'sale' => $sale->load(['items', 'customer.loyaltyTier', 'payments', 'discount']),
        ]);
    }

    // -------------------------------------------------------------------------
    // Line item management
    // -------------------------------------------------------------------------

    /**
     * Add a product variant to the sale.
     *
     * POST /terminal/{terminal}/sales/{sale}/items
     */
    public function addItem(Request $request, Terminal $terminal, Sales $sale): JsonResponse
    {
        $this->authoriseSale($terminal, $sale);

        $request->validate([
            'product_variant_id' => ['required', 'integer', 'exists:product_variants,id'],
            'quantity'           => ['sometimes', 'integer', 'min:1'],
        ]);

        $variant  = ProductVariant::findOrFail($request->product_variant_id);
        $quantity = $request->quantity ?? 1;

        $sale = $this->saleService->addItem($sale, $variant, $quantity);

        return response()->json([
            'message' => 'Item added.',
            'sale'    => $sale,
        ]);
    }

    /**
     * Update the quantity of a line item.
     * Sending quantity of 0 removes the item entirely.
     *
     * PATCH /terminal/{terminal}/sales/{sale}/items/{saleItem}
     */
    public function updateItem(
        Request $request,
        Terminal $terminal,
        Sales $sale,
        int $saleItemId,
    ): JsonResponse {
        $this->authoriseSale($terminal, $sale);

        $request->validate([
            'quantity' => ['required', 'integer', 'min:0'],
        ]);

        $sale = $this->saleService->updateItemQuantity(
            $sale,
            $saleItemId,
            $request->quantity
        );

        return response()->json([
            'message' => 'Item updated.',
            'sale'    => $sale,
        ]);
    }

    /**
     * Remove a line item from the sale entirely.
     *
     * DELETE /terminal/{terminal}/sales/{sale}/items/{saleItem}
     */
    public function removeItem(
        Terminal $terminal,
        Sales $sale,
        int $saleItemId,
    ): JsonResponse {
        $this->authoriseSale($terminal, $sale);

        $sale = $this->saleService->removeItem($sale, $saleItemId);

        return response()->json([
            'message' => 'Item removed.',
            'sale'    => $sale,
        ]);
    }

    // -------------------------------------------------------------------------
    // Customer attachment
    // -------------------------------------------------------------------------

    /**
     * Attach a customer to an open sale.
     *
     * POST /terminal/{terminal}/sales/{sale}/customer
     */
    public function attachCustomer(
        Request $request,
        Terminal $terminal,
        Sales $sale,
    ): JsonResponse {
        $this->authoriseSale($terminal, $sale);

        $request->validate([
            'customer_id' => ['required', 'integer', 'exists:customers,id'],
        ]);

        $customer = Customer::findOrFail($request->customer_id);
        $sale     = $this->saleService->attachCustomer($sale, $customer);

        return response()->json([
            'message' => 'Customer attached.',
            'sale'    => $sale->load('customer.loyaltyTier'),
        ]);
    }

    /**
     * Detach the customer from a sale, converting it to a walk-in.
     *
     * DELETE /terminal/{terminal}/sales/{sale}/customer
     */
    public function detachCustomer(Terminal $terminal, Sales $sale): JsonResponse
    {
        $this->authoriseSale($terminal, $sale);

        $sale = $this->saleService->detachCustomer($sale);

        return response()->json([
            'message' => 'Customer detached.',
            'sale'    => $sale,
        ]);
    }

    // -------------------------------------------------------------------------
    // Internal helpers
    // -------------------------------------------------------------------------

    /**
     * Ensure the sale belongs to the terminal making the request.
     * Prevents one terminal from modifying another terminal's open sale.
     */
    protected function authoriseSale(Terminal $terminal, Sales $sale): void
    {
        if ($sale->terminal_id !== $terminal->id) {
            abort(403, 'This sale does not belong to the current terminal.');
        }
    }
}
