<?php
namespace App\Http\Controllers\Customers;

use App\Http\Controllers\Controller;
use App\Http\Requests\Customers\StoreCustomerRequest;
use App\Models\Customer;
use App\Models\Organization;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    /**
     * Paginated customer list for an organization.
     *
     * GET /organizations/{organization}/customers
     */
    public function index(Request $request, Organization $organization): JsonResponse
    {
        $request->validate([
            'search'   => ['sometimes', 'string', 'min:2'],
            'tier_id'  => ['sometimes', 'integer', 'exists:loyalty_tiers,id'],
            'per_page' => ['sometimes', 'integer', 'min:10', 'max:100'],
        ]);

        $query = $organization->customers()
            ->with('loyaltyTier')
            ->where('is_active', true)
            ->orderBy('first_name');

        if ($request->filled('search')) {
            $query->search($request->search);
        }

        if ($request->filled('tier_id')) {
            $query->where('loyalty_tier_id', $request->tier_id);
        }

        return response()->json($query->paginate($request->per_page ?? 25));
    }

    /**
     * Get a single customer's full profile including purchase history
     * summary and available discount codes.
     *
     * GET /organizations/{organization}/customers/{customer}
     */
    public function show(Organization $organization, Customer $customer): JsonResponse
    {
        $this->authoriseCustomer($organization, $customer);

        $customer->load([
            'loyaltyTier',
            'discountCodes.discountCode.discount',
        ]);

        return response()->json([
            'customer'          => $customer,
            'points_to_next'    => $customer->pointsToNextTier(),
            'is_birthday_today' => $customer->isBirthday(),
        ]);
    }

    /**
     * Create a new customer.
     *
     * POST /organizations/{organization}/customers
     */
    public function store(StoreCustomerRequest $request, Organization $organization): JsonResponse
    {
        $customer = $organization->customers()->create($request->validated());

        return response()->json([
            'message'  => 'Customer created.',
            'customer' => $customer,
        ], 201);
    }

    /**
     * Update a customer's details.
     *
     * PUT /organizations/{organization}/customers/{customer}
     */
    public function update(
        StoreCustomerRequest $request,
        Organization $organization,
        Customer $customer,
    ): JsonResponse {
        $this->authoriseCustomer($organization, $customer);

        $customer->update($request->validated());

        return response()->json([
            'message'  => 'Customer updated.',
            'customer' => $customer->fresh('loyaltyTier'),
        ]);
    }

    /**
     * Soft delete a customer.
     *
     * DELETE /organizations/{organization}/customers/{customer}
     */
    public function destroy(Organization $organization, Customer $customer): JsonResponse
    {
        $this->authoriseCustomer($organization, $customer);

        $customer->delete();

        return response()->json(['message' => 'Customer deleted.']);
    }

    // -------------------------------------------------------------------------
    // Internal helpers
    // -------------------------------------------------------------------------

    protected function authoriseCustomer(Organization $organization, Customer $customer): void
    {
        if ($customer->organization_id !== $organization->id) {
            abort(404);
        }
    }
}
