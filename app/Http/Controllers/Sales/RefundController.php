<?php

namespace App\Http\Controllers\Sales;

use App\Http\Controllers\Controller;
use App\Models\Location;
use App\Models\Sales;
use App\Services\PaymentService;
use App\Services\SaleService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class RefundController extends Controller
{
    public function __construct(
        protected SaleService    $saleService,
        protected PaymentService $paymentService,
    ) {}

    /**
     * Process a full refund on a completed sale.
     * Restores inventory, reverses loyalty points, and marks
     * all payments as refunded.
     *
     * POST /locations/{location}/sales/{sale}/refund
     */
    public function refund(Request $request, Location $location, Sales $sale): JsonResponse
    {
        $this->authoriseSale($location, $sale);

        $request->validate([
            'reason' => ['required', 'string', 'max:255'],
        ]);

        // Refund all payments first, then process the sale refund
        $this->paymentService->refundAll($sale);
        $sale = $this->saleService->refund($sale, $request->reason);

        return response()->json([
            'message' => 'Sale refunded successfully.',
            'sale'    => $sale->load(['items', 'payments']),
        ]);
    }

    // -------------------------------------------------------------------------
    // Internal helpers
    // -------------------------------------------------------------------------

    protected function authoriseSale(Location $location, Sales $sale): void
    {
        if ($sale->location_id !== $location->id) {
            abort(404);
        }
    }
}