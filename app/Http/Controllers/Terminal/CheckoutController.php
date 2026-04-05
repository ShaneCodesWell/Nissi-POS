<?php

namespace App\Http\Controllers\Terminal;

use App\Http\Controllers\Controller;
use App\Http\Requests\Terminal\ApplyDiscountRequest;
use App\Models\Sale;
use App\Models\Sales;
use App\Models\Terminal;
use App\Services\DiscountService;
use App\Services\SaleService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    public function __construct(
        protected SaleService     $saleService,
        protected DiscountService $discountService,
    ) {}

    // -------------------------------------------------------------------------
    // Discount management
    // -------------------------------------------------------------------------

    /**
     * Validate a discount code and return a preview of the deduction
     * without applying it yet. Used by the terminal UI to show
     * "You save GHS 12.00" before the cashier confirms.
     *
     * POST /terminal/{terminal}/sales/{sale}/discount/preview
     */
    public function previewDiscount(
        ApplyDiscountRequest $request,
        Terminal             $terminal,
        Sales                $sale,
    ): JsonResponse {
        $this->authoriseSale($terminal, $sale);

        $result = $this->discountService->validateCodeForSale($sale, $request->code);

        return response()->json($result);
    }

    /**
     * Apply a validated discount code to the sale.
     *
     * POST /terminal/{terminal}/sales/{sale}/discount
     */
    public function applyDiscount(
        ApplyDiscountRequest $request,
        Terminal             $terminal,
        Sales                $sale,
    ): JsonResponse {
        $this->authoriseSale($terminal, $sale);

        $sale = $this->saleService->applyDiscountCode($sale, $request->code);

        return response()->json([
            'message' => 'Discount applied.',
            'sale'    => $sale,
        ]);
    }

    /**
     * Remove the applied discount from a sale.
     *
     * DELETE /terminal/{terminal}/sales/{sale}/discount
     */
    public function removeDiscount(Terminal $terminal, Sales $sale): JsonResponse
    {
        $this->authoriseSale($terminal, $sale);

        $sale = $this->saleService->removeDiscount($sale);

        return response()->json([
            'message' => 'Discount removed.',
            'sale'    => $sale,
        ]);
    }

    // -------------------------------------------------------------------------
    // Completing a sale
    // -------------------------------------------------------------------------

    /**
     * Complete a fully paid sale.
     * Decrements inventory, awards loyalty points, and generates the receipt.
     * This is the final step in the checkout flow.
     *
     * POST /terminal/{terminal}/sales/{sale}/complete
     */
    public function complete(Terminal $terminal, Sales $sale): JsonResponse
    {
        $this->authoriseSale($terminal, $sale);

        $sale = $this->saleService->complete($sale);

        return response()->json([
            'message' => 'Sale completed.',
            'sale'    => $sale->load(['items', 'payments', 'receipt', 'customer.loyaltyTier']),
        ]);
    }

    // -------------------------------------------------------------------------
    // Receipt delivery
    // -------------------------------------------------------------------------

    /**
     * Get the receipt for a completed sale.
     * Returns the full snapshot for rendering in the receipt modal.
     *
     * GET /terminal/{terminal}/sales/{sale}/receipt
     */
    public function receipt(Terminal $terminal, Sales $sale): JsonResponse
    {
        $this->authoriseSale($terminal, $sale);

        $receipt = $sale->receipt;

        if (! $receipt) {
            return response()->json(['message' => 'No receipt found for this sale.'], 404);
        }

        return response()->json(['receipt' => $receipt]);
    }

    /**
     * Mark a receipt as printed and return it.
     *
     * POST /terminal/{terminal}/sales/{sale}/receipt/print
     */
    public function printReceipt(Terminal $terminal, Sales $sale): JsonResponse
    {
        $this->authoriseSale($terminal, $sale);

        $receipt = $sale->receipt;

        if (! $receipt) {
            return response()->json(['message' => 'No receipt found for this sale.'], 404);
        }

        // Actual print job dispatched to a queue — just mark it here
        $receipt->update([
            'delivery_method' => 'print',
            'sent_at'         => now(),
        ]);

        return response()->json([
            'message' => 'Receipt marked as printed.',
            'receipt' => $receipt->fresh(),
        ]);
    }

    // -------------------------------------------------------------------------
    // Voiding a sale
    // -------------------------------------------------------------------------

    /**
     * Void a pending sale that has not yet been completed.
     *
     * POST /terminal/{terminal}/sales/{sale}/void
     */
    public function void(Request $request, Terminal $terminal, Sales $sale): JsonResponse
    {
        $this->authoriseSale($terminal, $sale);

        $request->validate([
            'reason' => ['sometimes', 'string', 'max:255'],
        ]);

        $sale = $this->saleService->void($sale, $request->reason ?? '');

        return response()->json([
            'message' => 'Sale voided.',
            'sale'    => $sale,
        ]);
    }

    // -------------------------------------------------------------------------
    // Internal helpers
    // -------------------------------------------------------------------------

    protected function authoriseSale(Terminal $terminal, Sales $sale): void
    {
        if ($sale->terminal_id !== $terminal->id) {
            abort(403, 'This sale does not belong to the current terminal.');
        }
    }
}