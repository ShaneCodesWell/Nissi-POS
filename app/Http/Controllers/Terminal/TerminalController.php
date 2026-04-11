<?php
namespace App\Http\Controllers\Terminal;

use App\Http\Controllers\Controller;
use App\Models\Location;
use App\Models\Terminal;
use App\Services\SaleService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TerminalController extends Controller
{
    public function index(Terminal $terminal)
    {
        return view('terminal.index', compact('terminal'));
    }

    public function __construct(
        protected SaleService $saleService,
    ) {}

    // -------------------------------------------------------------------------
    // Terminal session
    // -------------------------------------------------------------------------

    /**
     * Load the terminal session data needed to boot the POS UI.
     * Returns the terminal, location, active categories, and
     * all active products with their variants and stock levels.
     *
     * Called once when the terminal UI loads or refreshes.
     *
     * GET /terminal/{terminal}
     */
    public function session(Terminal $terminal): JsonResponse
    {
        $terminal->load('location.organization');

        $location = $terminal->location;

        // Load all active products with variants and their stock at this location
        $products = $location->organization
            ->products()
            ->with([
                'category',
                'activeVariants',
                'activeVariants.inventory' => fn($q) => $q->where('location_id', $location->id),
            ])
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        // Load category tree for the terminal sidebar
        $categories = $location->organization
            ->categories()
            ->with('children')
            ->whereNull('parent_id')
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        return response()->json([
            'terminal'   => $terminal,
            'location'   => $location,
            'products'   => $products,
            'categories' => $categories,
        ]);
    }

    // -------------------------------------------------------------------------
    // Customer lookup
    // -------------------------------------------------------------------------

    /**
     * Search for a customer by name, email, or phone.
     * Used by the customer lookup panel at the terminal.
     *
     * GET /terminal/{terminal}/customers/search?q=kwame
     */
    public function searchCustomers(Request $request, Terminal $terminal): JsonResponse
    {
        $request->validate(['q' => ['required', 'string', 'min:2']]);

        $customers = $terminal->location->organization
            ->customers()
            ->search($request->q)
            ->with('loyaltyTier')
            ->where('is_active', true)
            ->limit(10)
            ->get()
            ->map(fn($customer) => [
                'id'             => $customer->id,
                'name'           => $customer->fullName(),
                'initials'       => $customer->initials(),
                'phone'          => $customer->phone,
                'email'          => $customer->email,
                'points_balance' => $customer->points_balance,
                'loyalty_tier'   => $customer->loyaltyTier?->name,
                'tier_color'     => $customer->loyaltyTier?->color,
            ]);

        return response()->json(['customers' => $customers]);
    }

    // -------------------------------------------------------------------------
    // Pending sale recovery
    // -------------------------------------------------------------------------

    /**
     * Retrieve any pending (incomplete) sales for this terminal.
     * Allows a cashier to resume a sale after a page refresh or
     * browser restart.
     *
     * GET /terminal/{terminal}/pending-sales
     */
    public function pendingSales(Terminal $terminal): JsonResponse
    {
        $sales = $terminal->sales()
            ->with(['items', 'customer', 'payments'])
            ->where('status', 'pending')
            ->orderByDesc('created_at')
            ->get();

        return response()->json(['sales' => $sales]);
    }
}
