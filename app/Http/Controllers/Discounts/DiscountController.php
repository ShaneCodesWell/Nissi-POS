<?php
namespace App\Http\Controllers\Discounts;

use App\Http\Controllers\Controller;
use App\Http\Requests\Discounts\StoreDiscountRequest;
use App\Models\Discount;
use App\Models\DiscountCode;
use App\Models\Organization;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DiscountController extends Controller
{
    /**
     * List all discount rules for an organization.
     *
     * GET /organizations/{organization}/discounts
     */
    public function index(Request $request, Organization $organization): JsonResponse
    {
        $request->validate([
            'valid_only' => ['sometimes', 'boolean'],
        ]);

        $query = $organization->discounts()
            ->withCount('codes')
            ->latest();

        if ($request->boolean('valid_only')) {
            $query->valid();
        }

        return response()->json(['discounts' => $query->get()]);
    }

    /**
     * Create a discount rule.
     *
     * POST /organizations/{organization}/discounts
     */
    public function store(StoreDiscountRequest $request, Organization $organization): JsonResponse
    {
        $discount = $organization->discounts()->create($request->validated());

        return response()->json([
            'message'  => 'Discount created.',
            'discount' => $discount,
        ], 201);
    }

    /**
     * Update a discount rule.
     *
     * PUT /organizations/{organization}/discounts/{discount}
     */
    public function update(
        StoreDiscountRequest $request,
        Organization $organization,
        Discount $discount,
    ): JsonResponse {
        $discount->update($request->validated());

        return response()->json([
            'message'  => 'Discount updated.',
            'discount' => $discount->fresh(),
        ]);
    }

    /**
     * Soft delete a discount rule.
     *
     * DELETE /organizations/{organization}/discounts/{discount}
     */
    public function destroy(Organization $organization, Discount $discount): JsonResponse
    {
        $discount->delete();

        return response()->json(['message' => 'Discount deleted.']);
    }
}

// =============================================================================

class DiscountCodeController extends Controller
{
    /**
     * List all codes under a discount rule.
     *
     * GET /organizations/{organization}/discounts/{discount}/codes
     */
    public function index(Organization $organization, Discount $discount): JsonResponse
    {
        $codes = $discount->codes()
            ->withCount('customerAssignments')
            ->latest()
            ->get();

        return response()->json(['codes' => $codes]);
    }

    /**
     * Create a voucher code under a discount rule.
     *
     * POST /organizations/{organization}/discounts/{discount}/codes
     */
    public function store(Request $request, Organization $organization, Discount $discount): JsonResponse
    {
        $request->validate([
            'code'       => ['required', 'string', 'max:50', 'unique:discount_codes,code'],
            'max_uses'   => ['sometimes', 'nullable', 'integer', 'min:1'],
            'expires_at' => ['sometimes', 'nullable', 'date', 'after:today'],
        ]);

        $code = $discount->codes()->create([
            'code'       => strtoupper($request->code),
            'max_uses'   => $request->max_uses,
            'is_active'  => true,
            'expires_at' => $request->expires_at,
        ]);

        return response()->json([
            'message' => 'Code created.',
            'code'    => $code,
        ], 201);
    }

    /**
     * Deactivate a voucher code.
     *
     * PATCH /organizations/{organization}/discounts/{discount}/codes/{code}
     */
    public function deactivate(
        Organization $organization,
        Discount $discount,
        DiscountCode $code,
    ): JsonResponse {
        $code->update(['is_active' => false]);

        return response()->json(['message' => 'Code deactivated.']);
    }

    /**
     * Delete a voucher code.
     *
     * DELETE /organizations/{organization}/discounts/{discount}/codes/{code}
     */
    public function destroy(
        Organization $organization,
        Discount $discount,
        DiscountCode $code,
    ): JsonResponse {
        $code->delete();

        return response()->json(['message' => 'Code deleted.']);
    }
}
