<?php
namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Models\LoyaltyTiers;
use App\Models\Organization;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LoyaltyTiersController extends Controller
{
    /**
     * List all loyalty tiers for an organization ordered lowest to highest.
     *
     * GET /organizations/{organization}/loyalty-tiers
     */
    public function index(Organization $organization): JsonResponse
    {
        $tiers = $organization->loyaltyTiers()
            ->withCount('customers')
            ->ordered()
            ->get();

        return response()->json(['tiers' => $tiers]);
    }

    /**
     * Create a new loyalty tier.
     *
     * POST /organizations/{organization}/loyalty-tiers
     */
    public function store(Request $request, Organization $organization): JsonResponse
    {
        $request->validate([
            'name'                => ['required', 'string', 'max:100'],
            'description'         => ['sometimes', 'nullable', 'string'],
            'min_points'          => ['required', 'integer', 'min:0'],
            'points_multiplier'   => ['required', 'numeric', 'min:1'],
            'discount_percentage' => ['sometimes', 'numeric', 'min:0', 'max:100'],
            'color'               => ['sometimes', 'nullable', 'string', 'max:7'],
            'sort_order'          => ['sometimes', 'integer', 'min:0'],
        ]);

        $tier = $organization->loyaltyTiers()->create($request->validated());

        return response()->json([
            'message' => 'Loyalty tier created.',
            'tier'    => $tier,
        ], 201);
    }

    /**
     * Update a loyalty tier.
     *
     * PUT /organizations/{organization}/loyalty-tiers/{tier}
     */
    public function update(
        Request $request,
        Organization $organization,
        LoyaltyTiers $tier,
    ): JsonResponse {
        $request->validate([
            'name'                => ['sometimes', 'string', 'max:100'],
            'min_points'          => ['sometimes', 'integer', 'min:0'],
            'points_multiplier'   => ['sometimes', 'numeric', 'min:1'],
            'discount_percentage' => ['sometimes', 'numeric', 'min:0', 'max:100'],
            'color'               => ['sometimes', 'nullable', 'string', 'max:7'],
            'sort_order'          => ['sometimes', 'integer', 'min:0'],
            'is_active'           => ['sometimes', 'boolean'],
        ]);

        $tier->update($request->validated());

        return response()->json([
            'message' => 'Tier updated.',
            'tier'    => $tier->fresh(),
        ]);
    }

    /**
     * Delete a loyalty tier.
     * Customers on this tier will have their tier set to null.
     *
     * DELETE /organizations/{organization}/loyalty-tiers/{tier}
     */
    public function destroy(Organization $organization, LoyaltyTiers $tier): JsonResponse
    {
        // Detach customers before deleting — nullOnDelete handles this
        // at the DB level but being explicit is safer
        $tier->customers()->update(['loyalty_tier_id' => null]);
        $tier->delete();

        return response()->json(['message' => 'Tier deleted.']);
    }
}
