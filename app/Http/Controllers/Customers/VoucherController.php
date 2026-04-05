<?php
namespace App\Http\Controllers\Customers;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\DiscountCode;
use App\Models\Organization;
use App\Services\LoyaltyService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class VoucherController extends Controller
{
    public function __construct(
        protected LoyaltyService $loyaltyService,
    ) {}

    /**
     * List all vouchers assigned to a customer.
     * Supports filtering by status.
     *
     * GET /organizations/{organization}/customers/{customer}/vouchers
     */
    public function index(
        Request $request,
        Organization $organization,
        Customer $customer,
    ): JsonResponse {
        $this->authoriseCustomer($organization, $customer);

        $request->validate([
            'status' => ['sometimes', 'string', 'in:assigned,redeemed,expired,revoked'],
        ]);

        $query = $customer->discountCodes()
            ->with(['discountCode.discount'])
            ->latest('assigned_at');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        return response()->json([
            'vouchers' => $query->get(),
        ]);
    }

    /**
     * Assign a discount code to a customer.
     *
     * POST /organizations/{organization}/customers/{customer}/vouchers
     */
    public function assign(
        Request $request,
        Organization $organization,
        Customer $customer,
    ): JsonResponse {
        $this->authoriseCustomer($organization, $customer);

        $request->validate([
            'discount_code_id' => ['required', 'integer', 'exists:discount_codes,id'],
            'expires_at'       => ['sometimes', 'nullable', 'date', 'after:today'],
        ]);

        $code   = DiscountCode::findOrFail($request->discount_code_id);
        $expiry = $request->filled('expires_at')
            ? \Carbon\Carbon::parse($request->expires_at)
            : null;

        $voucher = $this->loyaltyService->assignVoucher($customer, $code, $expiry);

        return response()->json([
            'message' => 'Voucher assigned.',
            'voucher' => $voucher->load('discountCode.discount'),
        ], 201);
    }

    /**
     * Revoke an assigned voucher from a customer.
     *
     * DELETE /organizations/{organization}/customers/{customer}/vouchers/{voucher}
     */
    public function revoke(
        Organization $organization,
        Customer $customer,
        \App\Models\CustomerDiscountCode $voucher,
    ): JsonResponse {
        $this->authoriseCustomer($organization, $customer);

        $voucher->markRevoked();

        return response()->json(['message' => 'Voucher revoked.']);
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
