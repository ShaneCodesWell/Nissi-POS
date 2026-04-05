<?php

namespace App\Http\Controllers\Sales;

use App\Http\Controllers\Controller;
use App\Models\Location;
use App\Models\Sales;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SaleHistoryController extends Controller
{
    /**
     * Paginated list of completed sales for a location.
     * Supports filtering by date range, cashier, status, and search
     * by sale number or customer name.
     *
     * GET /locations/{location}/sales
     */
    public function index(Request $request, Location $location): JsonResponse
    {
        $request->validate([
            'from'       => ['sometimes', 'date'],
            'to'         => ['sometimes', 'date', 'after_or_equal:from'],
            'status'     => ['sometimes', 'string'],
            'cashier_id' => ['sometimes', 'integer', 'exists:users,id'],
            'search'     => ['sometimes', 'string', 'min:2'],
            'per_page'   => ['sometimes', 'integer', 'min:10', 'max:100'],
        ]);

        $query = Sales::atLocation($location->id)
                     ->with(['cashier', 'customer', 'terminal'])
                     ->latest('completed_at');

        if ($request->filled('from') && $request->filled('to')) {
            $query->betweenDates($request->from, $request->to);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('cashier_id')) {
            $query->where('user_id', $request->cashier_id);
        }

        if ($request->filled('search')) {
            $term = $request->search;
            $query->where(function ($q) use ($term) {
                $q->where('sale_number', 'like', "%{$term}%")
                  ->orWhereHas('customer', fn ($cq) =>
                      $cq->where('first_name', 'like', "%{$term}%")
                         ->orWhere('last_name',  'like', "%{$term}%")
                  );
            });
        }

        $sales = $query->paginate($request->per_page ?? 25);

        return response()->json($sales);
    }

    /**
     * Single sale detail with all items, payments and receipt.
     *
     * GET /locations/{location}/sales/{sale}
     */
    public function show(Location $location, Sales $sale): JsonResponse
    {
        $this->authoriseSale($location, $sale);

        $sale->load([
            'items',
            'payments',
            'receipt',
            'customer.loyaltyTier',
            'cashier',
            'terminal',
            'discount',
        ]);

        return response()->json(['sale' => $sale]);
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