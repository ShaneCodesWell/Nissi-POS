<?php
namespace App\Http\Controllers\Customers;

use App\Http\Controllers\Controller;
use App\Http\Requests\Customers\RedeemPointsRequest;
use App\Models\Customer;
use App\Models\Organization;
use App\Models\Sales;
use App\Services\LoyaltyService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LoyaltyController extends Controller
{
    public function __construct(
        protected LoyaltyService $loyaltyService,
    ) {}

    /**
     * Get the loyalty transaction history for a customer.
     *
     * GET /organizations/{organization}/customers/{customer}/loyalty
     */
    public function history(
        Request $request,
        Organization $organization,
        Customer $customer,
    ): JsonResponse {
        $this->authoriseCustomer($organization, $customer);

        $request->validate([
            'per_page' => ['sometimes', 'integer', 'min:10', 'max:100'],
        ]);

        $transactions = $customer->loyaltyTransactions()
            ->with('sale')
            ->latest()
            ->paginate($request->per_page ?? 20);

        return response()->json([
            'points_balance'        => $customer->points_balance,
            'points_earned_total'   => $customer->points_earned_total,
            'points_redeemed_total' => $customer->points_redeemed_total,
            'transactions'          => $transactions,
        ]);
    }

    /**
     * Redeem loyalty points on an open sale.
     * Applies a monetary discount equal to the points value.
     *
     * POST /organizations/{organization}/customers/{customer}/loyalty/redeem
     */
    public function redeem(
        RedeemPointsRequest $request,
        Organization $organization,
        Customer $customer,
    ): JsonResponse {
        $this->authoriseCustomer($organization, $customer);

        $sale          = Sales::findOrFail($request->sale_id);
        $monetaryValue = $this->loyaltyService->redeemPoints(
            $customer,
            $request->points,
            $sale
        );

        return response()->json([
            'message' => "{$request->points} points redeemed.",
            'monetary_value' => $monetaryValue,
            'points_balance' => $customer->fresh()->points_balance,
        ]);
    }

    /**
     * Award manual bonus points to a customer.
     * Staff-only action — requires a description.
     *
     * POST /organizations/{organization}/customers/{customer}/loyalty/bonus
     */
    public function bonus(
        Request $request,
        Organization $organization,
        Customer $customer,
    ): JsonResponse {
        $this->authoriseCustomer($organization, $customer);

        $request->validate([
            'points'      => ['required', 'integer', 'min:1'],
            'description' => ['required', 'string', 'max:255'],
        ]);

        $transaction = $this->loyaltyService->awardBonus(
            $customer,
            $request->points,
            $request->description,
            $request->user(),
        );

        return response()->json([
            'message' => "Bonus of {$request->points} points awarded.",
            'transaction'    => $transaction,
            'points_balance' => $customer->fresh()->points_balance,
        ]);
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
