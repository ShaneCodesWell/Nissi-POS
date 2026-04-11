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
    // Quick customer creation at the terminal
    // -------------------------------------------------------------------------

    /**
     * Create a new customer record quickly at the point of sale.
     * Only requires first name and phone — full profile can be
     * completed later from the admin CRM section.
     *
     * POST /terminal/{terminal}/customers
     */
    public function createCustomer(Request $request, Terminal $terminal): JsonResponse
    {
        $validated = $request->validate([
            'first_name' => ['required', 'string', 'max:100'],
            'last_name'  => ['sometimes', 'nullable', 'string', 'max:100'],
            'phone'      => [
                'required',
                'string',
                'max:30',
                // Phone must be unique within the organisation
                \Illuminate\Validation\Rule::unique('customers')
                    ->where('organization_id', $terminal->location->organization_id),
            ],
            'email'      => [
                'sometimes',
                'nullable',
                'email',
                \Illuminate\Validation\Rule::unique('customers')
                    ->where('organization_id', $terminal->location->organization_id),
            ],
        ], [
            'first_name.required' => 'Customer name is required.',
            'phone.required'      => 'A phone number is required.',
            'phone.unique'        => 'A customer with this phone number already exists.',
            'email.unique'        => 'A customer with this email already exists.',
        ]);

        $organization = $terminal->location->organization;

        // Assign the lowest loyalty tier automatically if one exists
        $defaultTier = $organization->loyaltyTiers()
            ->where('is_active', true)
            ->orderBy('min_points')
            ->first();

        $customer = $organization->customers()->create([
            'first_name'      => $validated['first_name'],
            'last_name'       => $validated['last_name'] ?? null,
            'phone'           => $validated['phone'],
            'email'           => $validated['email'] ?? null,
            'loyalty_tier_id' => $defaultTier?->id,
            'points_balance'  => 0,
            'is_active'       => true,
        ]);

        return response()->json([
            'message'  => 'Customer created.',
            'customer' => [
                'id'             => $customer->id,
                'name'           => $customer->fullName(),
                'initials'       => $customer->initials(),
                'phone'          => $customer->phone,
                'email'          => $customer->email,
                'points_balance' => 0,
                'loyalty_tier'   => $defaultTier?->name,
                'tier_color'     => $defaultTier?->color,
            ],
        ], 201);
    }

    // -------------------------------------------------------------------------
    // Pending sale recovery
    // -------------------------------------------------------------------------

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
