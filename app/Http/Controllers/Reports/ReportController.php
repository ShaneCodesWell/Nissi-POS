<?php
namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use App\Models\Location;
use App\Models\Organization;
use App\Services\ReportService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function __construct(
        protected ReportService $reportService,
    ) {}

    /**
     * Sales summary for a location over a date range.
     * Defaults to the current month if no range is provided.
     *
     * GET /locations/{location}/reports/summary
     */
    public function summary(Request $request, Location $location): JsonResponse
    {
        [$from, $to] = $this->resolveDateRange($request);

        return response()->json(
            $this->reportService->summarise($location, $from, $to)
        );
    }

    /**
     * Organisation-wide summary across all locations.
     *
     * GET /organizations/{organization}/reports/summary
     */
    public function organizationSummary(Request $request, Organization $organization): JsonResponse
    {
        [$from, $to] = $this->resolveDateRange($request);

        return response()->json(
            $this->reportService->summariseOrganization($organization, $from, $to)
        );
    }

    /**
     * Daily revenue breakdown for a location.
     *
     * GET /locations/{location}/reports/revenue/daily
     */
    public function revenueByDay(Request $request, Location $location): JsonResponse
    {
        [$from, $to] = $this->resolveDateRange($request);

        return response()->json([
            'data' => $this->reportService->revenueByDay($location, $from, $to),
        ]);
    }

    /**
     * Weekly revenue breakdown for a location.
     *
     * GET /locations/{location}/reports/revenue/weekly
     */
    public function revenueByWeek(Request $request, Location $location): JsonResponse
    {
        [$from, $to] = $this->resolveDateRange($request);

        return response()->json([
            'data' => $this->reportService->revenueByWeek($location, $from, $to),
        ]);
    }

    /**
     * Monthly revenue breakdown for a location.
     *
     * GET /locations/{location}/reports/revenue/monthly
     */
    public function revenueByMonth(Request $request, Location $location): JsonResponse
    {
        [$from, $to] = $this->resolveDateRange($request);

        return response()->json([
            'data' => $this->reportService->revenueByMonth($location, $from, $to),
        ]);
    }

    /**
     * Top-selling products for a location.
     *
     * GET /locations/{location}/reports/products
     */
    public function topProducts(Request $request, Location $location): JsonResponse
    {
        $request->validate([
            'limit' => ['sometimes', 'integer', 'min:1', 'max:50'],
        ]);

        [$from, $to] = $this->resolveDateRange($request);

        return response()->json([
            'data' => $this->reportService->topProducts(
                $location, $from, $to, $request->limit ?? 10
            ),
        ]);
    }

    /**
     * Top-selling categories for a location.
     *
     * GET /locations/{location}/reports/categories
     */
    public function topCategories(Request $request, Location $location): JsonResponse
    {
        $request->validate([
            'limit' => ['sometimes', 'integer', 'min:1', 'max:50'],
        ]);

        [$from, $to] = $this->resolveDateRange($request);

        return response()->json([
            'data' => $this->reportService->topCategories(
                $location, $from, $to, $request->limit ?? 10
            ),
        ]);
    }

    /**
     * Payment method breakdown for a location.
     * Used for end-of-day cash drawer reconciliation.
     *
     * GET /locations/{location}/reports/payments
     */
    public function paymentBreakdown(Request $request, Location $location): JsonResponse
    {
        [$from, $to] = $this->resolveDateRange($request);

        return response()->json([
            'data' => $this->reportService->paymentMethodBreakdown($location, $from, $to),
        ]);
    }

    /**
     * Cashier performance metrics for a location.
     *
     * GET /locations/{location}/reports/cashiers
     */
    public function cashierPerformance(Request $request, Location $location): JsonResponse
    {
        [$from, $to] = $this->resolveDateRange($request);

        return response()->json([
            'data' => $this->reportService->cashierPerformance($location, $from, $to),
        ]);
    }

    // -------------------------------------------------------------------------
    // Internal helpers
    // -------------------------------------------------------------------------

    /**
     * Resolve the date range from the request.
     * Defaults to the current month if from/to are not supplied.
     */
    protected function resolveDateRange(Request $request): array
    {
        $request->validate([
            'from' => ['sometimes', 'date'],
            'to'   => ['sometimes', 'date', 'after_or_equal:from'],
        ]);

        if ($request->filled('from') && $request->filled('to')) {
            return $this->reportService->parseDateRange($request->from, $request->to);
        }

        return $this->reportService->thisMonth();
    }
}
