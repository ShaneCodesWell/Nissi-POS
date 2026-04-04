<?php
namespace App\Services;

use App\Enums\PaymentMethod;
use App\Enums\SaleStatus;
use App\Models\Location;
use App\Models\Organization;
use App\Models\Sales;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class ReportService
{
    // -------------------------------------------------------------------------
    // Sales summary
    // -------------------------------------------------------------------------

    /**
     * Get a high-level sales summary for a location over a date range.
     * This is the primary data source for the reports dashboard.
     *
     * Returns:
     *   - total_sales:       number of completed transactions
     *   - total_revenue:     sum of total_amount across completed sales
     *   - total_discount:    sum of discount_amount given
     *   - total_tax:         sum of tax collected
     *   - total_items_sold:  sum of all quantities across line items
     *   - average_sale:      mean sale value
     *   - period_from/to:    the date range queried
     */
    public function summarise(
        Location $location,
        Carbon $from,
        Carbon $to,
    ): array {
        $base = Sales::completed()
            ->atLocation($location->id)
            ->betweenDates($from, $to);

        $totals = $base->selectRaw('
            COUNT(*)                    AS total_sales,
            COALESCE(SUM(total_amount),    0) AS total_revenue,
            COALESCE(SUM(discount_amount), 0) AS total_discount,
            COALESCE(SUM(tax_amount),      0) AS total_tax
        ')->first();

        $itemsSold = DB::table('sale_items')
            ->join('sales', 'sales.id', '=', 'sale_items.sale_id')
            ->where('sales.location_id', $location->id)
            ->where('sales.status', SaleStatus::Completed->value)
            ->whereBetween('sales.completed_at', [$from, $to])
            ->sum('sale_items.quantity');

        $totalSales   = (int) $totals->total_sales;
        $totalRevenue = (float) $totals->total_revenue;

        return [
            'period_from'      => $from->toDateString(),
            'period_to'        => $to->toDateString(),
            'total_sales'      => $totalSales,
            'total_revenue'    => round($totalRevenue, 2),
            'total_discount'   => round((float) $totals->total_discount, 2),
            'total_tax'        => round((float) $totals->total_tax, 2),
            'total_items_sold' => (int) $itemsSold,
            'average_sale'     => $totalSales > 0
                ? round($totalRevenue / $totalSales, 2)
                : 0.00,
        ];
    }

    /**
     * Get a summary across all locations for an organisation.
     * Returns the same shape as summarise() but with a per-location
     * breakdown appended.
     */
    public function summariseOrganization(
        Organization $organization,
        Carbon $from,
        Carbon $to,
    ): array {
        $locations  = $organization->activeLocations()->get();
        $breakdown  = [];
        $grandTotal = [
            'total_sales'      => 0,
            'total_revenue'    => 0.0,
            'total_discount'   => 0.0,
            'total_tax'        => 0.0,
            'total_items_sold' => 0,
        ];

        foreach ($locations as $location) {
            $summary = $this->summarise($location, $from, $to);

            $breakdown[] = array_merge(['location' => $location->name], $summary);

            $grandTotal['total_sales']      += $summary['total_sales'];
            $grandTotal['total_revenue']    += $summary['total_revenue'];
            $grandTotal['total_discount']   += $summary['total_discount'];
            $grandTotal['total_tax']        += $summary['total_tax'];
            $grandTotal['total_items_sold'] += $summary['total_items_sold'];
        }

        $grandTotal['average_sale']  = $grandTotal['total_sales'] > 0
            ? round($grandTotal['total_revenue'] / $grandTotal['total_sales'], 2)
            : 0.00;

        return [
            'period_from' => $from->toDateString(),
            'period_to'   => $to->toDateString(),
            'grand_total' => $grandTotal,
            'by_location' => $breakdown,
        ];
    }

    // -------------------------------------------------------------------------
    // Revenue over time
    // -------------------------------------------------------------------------

    /**
     * Get daily revenue totals for a location over a date range.
     * Returns an array of { date, total_sales, total_revenue } rows,
     * one per day. Days with no sales are included as zero rows so
     * charts render correctly without gaps.
     */
    public function revenueByDay(
        Location $location,
        Carbon $from,
        Carbon $to,
    ): array {
        $rows = Sales::completed()
            ->atLocation($location->id)
            ->betweenDates($from, $to)
            ->selectRaw('
                        DATE(completed_at)          AS date,
                        COUNT(*)                    AS total_sales,
                        COALESCE(SUM(total_amount), 0) AS total_revenue
                    ')
            ->groupByRaw('DATE(completed_at)')
            ->orderByRaw('DATE(completed_at)')
            ->get()
            ->keyBy('date');

        // Fill in zero rows for days with no sales so charts have no gaps
        $result = [];
        $cursor = $from->copy();

        while ($cursor->lte($to)) {
            $dateKey = $cursor->toDateString();
            $row     = $rows->get($dateKey);

            $result[] = [
                'date'          => $dateKey,
                'total_sales'   => $row ? (int) $row->total_sales : 0,
                'total_revenue' => $row ? round((float) $row->total_revenue, 2) : 0.00,
            ];

            $cursor->addDay();
        }

        return $result;
    }

    /**
     * Get weekly revenue totals for a location.
     * Groups by ISO week number. Useful for month-on-month comparisons.
     */
    public function revenueByWeek(
        Location $location,
        Carbon $from,
        Carbon $to,
    ): array {
        return Sales::completed()
            ->atLocation($location->id)
            ->betweenDates($from, $to)
            ->selectRaw('
                       YEARWEEK(completed_at, 1)   AS week,
                       MIN(DATE(completed_at))      AS week_start,
                       COUNT(*)                     AS total_sales,
                       COALESCE(SUM(total_amount),  0) AS total_revenue
                   ')
            ->groupByRaw('YEARWEEK(completed_at, 1)')
            ->orderByRaw('YEARWEEK(completed_at, 1)')
            ->get()
            ->map(fn($row) => [
                'week'          => $row->week,
                'week_start'    => $row->week_start,
                'total_sales'   => (int) $row->total_sales,
                'total_revenue' => round((float) $row->total_revenue, 2),
            ])
            ->toArray();
    }

    /**
     * Get monthly revenue totals for a location.
     * Useful for year-over-year trend lines.
     */
    public function revenueByMonth(
        Location $location,
        Carbon $from,
        Carbon $to,
    ): array {
        return Sales::completed()
            ->atLocation($location->id)
            ->betweenDates($from, $to)
            ->selectRaw('
                       DATE_FORMAT(completed_at, "%Y-%m") AS month,
                       COUNT(*)                           AS total_sales,
                       COALESCE(SUM(total_amount),        0) AS total_revenue
                   ')
            ->groupByRaw('DATE_FORMAT(completed_at, "%Y-%m")')
            ->orderByRaw('DATE_FORMAT(completed_at, "%Y-%m")')
            ->get()
            ->map(fn($row) => [
                'month'         => $row->month,
                'total_sales'   => (int) $row->total_sales,
                'total_revenue' => round((float) $row->total_revenue, 2),
            ])
            ->toArray();
    }

    // -------------------------------------------------------------------------
    // Product performance
    // -------------------------------------------------------------------------

    /**
     * Get the top-selling products by quantity sold for a location.
     * Returns product name, SKU, units sold, revenue, and gross profit.
     */
    public function topProducts(
        Location $location,
        Carbon $from,
        Carbon $to,
        int $limit = 10,
    ): array {
        return DB::table('sale_items')
            ->join('sales', 'sales.id', '=', 'sale_items.sale_id')
            ->where('sales.location_id', $location->id)
            ->where('sales.status', SaleStatus::Completed->value)
            ->whereBetween('sales.completed_at', [$from, $to])
            ->selectRaw('
                     sale_items.product_name,
                     sale_items.sku,
                     SUM(sale_items.quantity)                                        AS units_sold,
                     COALESCE(SUM(sale_items.line_total), 0)                         AS revenue,
                     COALESCE(SUM(
                         (sale_items.unit_price - sale_items.cost_price)
                         * sale_items.quantity
                         - sale_items.discount_amount
                     ), 0)                                                           AS gross_profit
                 ')
            ->groupBy('sale_items.product_name', 'sale_items.sku')
            ->orderByDesc('units_sold')
            ->limit($limit)
            ->get()
            ->map(fn($row) => [
                'product_name' => $row->product_name,
                'sku'          => $row->sku,
                'units_sold'   => (int) $row->units_sold,
                'revenue'      => round((float) $row->revenue, 2),
                'gross_profit' => round((float) $row->gross_profit, 2),
            ])
            ->toArray();
    }

    /**
     * Get the top-selling categories by revenue for a location.
     */
    public function topCategories(
        Location $location,
        Carbon $from,
        Carbon $to,
        int $limit = 10,
    ): array {
        return DB::table('sale_items')
            ->join('sales', 'sales.id', '=', 'sale_items.sale_id')
            ->join('product_variants', 'product_variants.id', '=', 'sale_items.product_variant_id')
            ->join('products', 'products.id', '=', 'product_variants.product_id')
            ->join('categories', 'categories.id', '=', 'products.category_id')
            ->where('sales.location_id', $location->id)
            ->where('sales.status', SaleStatus::Completed->value)
            ->whereBetween('sales.completed_at', [$from, $to])
            ->selectRaw('
                     categories.name                             AS category_name,
                     COUNT(DISTINCT sales.id)                    AS total_sales,
                     SUM(sale_items.quantity)                    AS units_sold,
                     COALESCE(SUM(sale_items.line_total), 0)     AS revenue
                 ')
            ->groupBy('categories.id', 'categories.name')
            ->orderByDesc('revenue')
            ->limit($limit)
            ->get()
            ->map(fn($row) => [
                'category_name' => $row->category_name,
                'total_sales'   => (int) $row->total_sales,
                'units_sold'    => (int) $row->units_sold,
                'revenue'       => round((float) $row->revenue, 2),
            ])
            ->toArray();
    }

    // -------------------------------------------------------------------------
    // Payment method breakdown
    // -------------------------------------------------------------------------

    /**
     * Get a breakdown of revenue by payment method for a location.
     * Returns totals for cash, card, and mobile money separately.
     * Useful for end-of-day cash drawer reconciliation.
     */
    public function paymentMethodBreakdown(
        Location $location,
        Carbon $from,
        Carbon $to,
    ): array {
        $rows = DB::table('payments')
            ->join('sales', 'sales.id', '=', 'payments.sale_id')
            ->where('sales.location_id', $location->id)
            ->where('sales.status', SaleStatus::Completed->value)
            ->where('payments.status', 'completed')
            ->whereBetween('sales.completed_at', [$from, $to])
            ->selectRaw('
                      payments.method,
                      COUNT(DISTINCT sales.id)            AS transaction_count,
                      COALESCE(SUM(payments.amount),    0) AS total_amount,
                      COALESCE(SUM(payments.change_given), 0) AS total_change
                  ')
            ->groupBy('payments.method')
            ->get()
            ->keyBy('method');

        // Return all methods even if zero — ensures consistent shape
        return collect(PaymentMethod::cases())->map(function ($method) use ($rows) {
            $row = $rows->get($method->value);
            return [
                'method'            => $method->value,
                'transaction_count' => $row ? (int) $row->transaction_count : 0,
                'total_amount'      => $row ? round((float) $row->total_amount, 2) : 0.00,
                'total_change'      => $row ? round((float) $row->total_change, 2) : 0.00,
            ];
        })->values()->toArray();
    }

    // -------------------------------------------------------------------------
    // Cashier performance
    // -------------------------------------------------------------------------

    /**
     * Get per-cashier performance metrics for a location.
     * Useful for manager dashboards and staff review.
     */
    public function cashierPerformance(
        Location $location,
        Carbon $from,
        Carbon $to,
    ): array {
        return Sales::completed()
            ->atLocation($location->id)
            ->betweenDates($from, $to)
            ->join('users', 'users.id', '=', 'sales.user_id')
            ->selectRaw('
                       users.id                            AS cashier_id,
                       users.name                          AS cashier_name,
                       COUNT(sales.id)                     AS total_sales,
                       COALESCE(SUM(sales.total_amount), 0)   AS total_revenue,
                       COALESCE(AVG(sales.total_amount), 0)   AS average_sale,
                       COALESCE(SUM(sales.discount_amount), 0) AS total_discount_given
                   ')
            ->groupBy('users.id', 'users.name')
            ->orderByDesc('total_revenue')
            ->get()
            ->map(fn($row) => [
                'cashier_id'           => $row->cashier_id,
                'cashier_name'         => $row->cashier_name,
                'total_sales'          => (int) $row->total_sales,
                'total_revenue'        => round((float) $row->total_revenue, 2),
                'average_sale'         => round((float) $row->average_sale, 2),
                'total_discount_given' => round((float) $row->total_discount_given, 2),
            ])
            ->toArray();
    }

    // -------------------------------------------------------------------------
    // Convenience date range builders
    // -------------------------------------------------------------------------

    /**
     * Get the Carbon date range for today.
     */
    public function today(): array
    {
        return [
            Carbon::today()->startOfDay(),
            Carbon::today()->endOfDay(),
        ];
    }

    /**
     * Get the Carbon date range for the current week (Mon–Sun).
     */
    public function thisWeek(): array
    {
        return [
            Carbon::now()->startOfWeek(),
            Carbon::now()->endOfWeek(),
        ];
    }

    /**
     * Get the Carbon date range for the current month.
     */
    public function thisMonth(): array
    {
        return [
            Carbon::now()->startOfMonth(),
            Carbon::now()->endOfMonth(),
        ];
    }

    /**
     * Get the Carbon date range for the current year.
     */
    public function thisYear(): array
    {
        return [
            Carbon::now()->startOfYear(),
            Carbon::now()->endOfYear(),
        ];
    }

    /**
     * Parse a from/to date string pair into Carbon instances.
     * Used by controllers to convert request input into Carbon objects.
     *
     * @throws \InvalidArgumentException if dates are invalid or range is reversed
     */
    public function parseDateRange(string $from, string $to): array
    {
        $carbonFrom = Carbon::parse($from)->startOfDay();
        $carbonTo   = Carbon::parse($to)->endOfDay();

        if ($carbonFrom->gt($carbonTo)) {
            throw new \InvalidArgumentException(
                'The from date must be before or equal to the to date.'
            );
        }

        return [$carbonFrom, $carbonTo];
    }
}
