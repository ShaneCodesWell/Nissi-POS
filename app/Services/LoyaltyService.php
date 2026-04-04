<?php
namespace App\Services;

use App\Enums\LoyaltyTransactionType;
use App\Enums\VoucherStatus;
use App\Models\Customer;
use App\Models\DiscountCode;
use App\Models\LoyaltyTransaction;
use App\Models\Sales;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class LoyaltyService
{
    // -------------------------------------------------------------------------
    // Earning points
    // -------------------------------------------------------------------------

    /**
     * Award loyalty points to a customer for a completed sale.
     * Uses the customer's current tier multiplier to calculate points.
     * Also checks for a tier upgrade after points are credited.
     * Called automatically by SaleService::complete().
     */
    public function awardPointsForSale(Sales $sale): ?LoyaltyTransaction
    {
        $customer = $sale->customer;

        if (! $customer) {
            return null;
        }

        $customer->loadMissing('loyaltyTier');

        // Calculate points based on the tier multiplier
        // If the customer has no tier, default to 1x multiplier
        $multiplier   = $customer->loyaltyTier?->points_multiplier ?? 1.0;
        $pointsEarned = (int) floor((float) $sale->total_amount * $multiplier);

        if ($pointsEarned <= 0) {
            return null;
        }

        $transaction = DB::transaction(function () use ($customer, $sale, $pointsEarned) {
            $transaction = $this->creditPoints(
                customer: $customer,
                points: $pointsEarned,
                type: LoyaltyTransactionType::Earn,
                description: "Purchase {$sale->sale_number}",
                sale: $sale,
            );

            // Check if the customer qualifies for a tier upgrade
            $this->checkAndUpgradeTier($customer);

            return $transaction;
        });

        return $transaction;
    }

    /**
     * Award a manual bonus to a customer.
     * Used for birthday bonuses, promotional credits, or staff adjustments.
     *
     * @throws \InvalidArgumentException if points is not positive
     */
    public function awardBonus(
        Customer $customer,
        int $points,
        string $description,
        ?User $staffMember = null,
    ): LoyaltyTransaction {
        if ($points <= 0) {
            throw new \InvalidArgumentException('Bonus points must be a positive integer.');
        }

        return DB::transaction(function () use ($customer, $points, $description, $staffMember) {
            $transaction = $this->creditPoints(
                customer: $customer,
                points: $points,
                type: LoyaltyTransactionType::Bonus,
                description: $description,
                createdBy: $staffMember,
            );

            $this->checkAndUpgradeTier($customer);

            return $transaction;
        });
    }

    // -------------------------------------------------------------------------
    // Redeeming points
    // -------------------------------------------------------------------------

    /**
     * Redeem a customer's loyalty points at checkout.
     * Points are converted to a monetary deduction — the conversion
     * rate is 100 points = 1 currency unit (adjust to your business rules).
     *
     * Returns the monetary value of the redemption so SaleService
     * can apply it as a discount.
     *
     * @throws \InvalidArgumentException if customer has insufficient points
     * @throws \InvalidArgumentException if points is not positive
     */
    public function redeemPoints(
        Customer $customer,
        int $points,
        Sales $sale,
    ): float {
        if ($points <= 0) {
            throw new \InvalidArgumentException('Redemption points must be a positive integer.');
        }

        if ($customer->points_balance < $points) {
            throw new \InvalidArgumentException(
                "Customer only has {$customer->points_balance} points. "
                . "Cannot redeem {$points}."
            );
        }

        $monetaryValue = $this->pointsToMonetaryValue($points);

        DB::transaction(function () use ($customer, $points, $sale, $monetaryValue) {
            $this->debitPoints(
                customer: $customer,
                points: $points,
                type: LoyaltyTransactionType::Redeem,
                description: "Points redeemed on {$sale->sale_number}",
                sale: $sale,
            );
        });

        return $monetaryValue;
    }

    /**
     * Reverse points earned from a sale that has been refunded.
     * Creates an adjustment transaction to debit the originally earned points.
     * Called automatically by SaleService::refund().
     */
    public function reversePointsForSale(Sales $sale): ?LoyaltyTransaction
    {
        $customer = $sale->customer;

        if (! $customer) {
            return null;
        }

        // Find the original earn transaction for this sale
        $earnTransaction = LoyaltyTransaction::where('sale_id', $sale->id)
            ->where('type', LoyaltyTransactionType::Earn->value)
            ->first();

        if (! $earnTransaction) {
            return null;
        }

        // Only reverse up to the customer's current balance
        // to avoid going negative
        $pointsToReverse = min(
            $earnTransaction->points,
            $customer->points_balance
        );

        if ($pointsToReverse <= 0) {
            return null;
        }

        return DB::transaction(function () use ($customer, $sale, $pointsToReverse) {
            $transaction = $this->debitPoints(
                customer: $customer,
                points: $pointsToReverse,
                type: LoyaltyTransactionType::Adjustment,
                description: "Points reversed — refund on {$sale->sale_number}",
                sale: $sale,
            );

            // Re-evaluate tier after points are removed
            $this->checkAndUpgradeTier($customer);

            return $transaction;
        });
    }

    // -------------------------------------------------------------------------
    // Tier management
    // -------------------------------------------------------------------------

    /**
     * Check if a customer is eligible for a tier change and apply it.
     * Called after every points credit or debit.
     * Fires silently — no exception if no change is needed.
     */
    public function checkAndUpgradeTier(Customer $customer): bool
    {
        $customer->refresh();

        $eligibleTier = $customer->resolveEligibleTier();

        // No change needed if already on the correct tier
        if ($eligibleTier?->id === $customer->loyalty_tier_id) {
            return false;
        }

        $customer->update(['loyalty_tier_id' => $eligibleTier?->id]);

        return true;
    }

    // -------------------------------------------------------------------------
    // Voucher assignment
    // -------------------------------------------------------------------------

    /**
     * Assign a discount code to a specific customer.
     * Used by staff or automated campaigns to issue targeted vouchers.
     *
     * @throws \RuntimeException if the code is already assigned to this customer
     * @throws \RuntimeException if the code is not valid
     */
    public function assignVoucher(
        Customer $customer,
        DiscountCode $code,
        ? \DateTime $expiresAt = null,
    ) : \App\Models\CustomerDiscountCode {
        if (! $code->isValid()) {
            throw new \RuntimeException(
                "Discount code \"{$code->code}\" is not currently valid."
            );
        }

        if ($code->isAssignedTo($customer)) {
            throw new \RuntimeException(
                "Code \"{$code->code}\" is already assigned to this customer."
            );
        }

        return $customer->discountCodes()->create([
            'discount_code_id' => $code->id,
            'status'           => VoucherStatus::Assigned,
            'assigned_at'      => now(),
            'expires_at'       => $expiresAt,
        ]);
    }

    // -------------------------------------------------------------------------
    // Points conversion
    // -------------------------------------------------------------------------

    /**
     * Convert a points amount to its monetary equivalent.
     * Current rate: 100 points = 1 currency unit.
     * Adjust this rate to match your business rules.
     */
    public function pointsToMonetaryValue(int $points): float
    {
        return round($points / 100, 2);
    }

    /**
     * Convert a monetary amount to its points equivalent.
     * Useful for showing "you would earn X points" previews at the terminal.
     */
    public function monetaryValueToPoints(float $amount, float $multiplier = 1.0): int
    {
        return (int) floor($amount * $multiplier);
    }

    // -------------------------------------------------------------------------
    // Internal helpers
    // -------------------------------------------------------------------------

    /**
     * Credit points to a customer and create a loyalty transaction record.
     * Updates the three summary columns on the customer atomically.
     * All writes happen inside the caller's DB::transaction().
     */
    protected function creditPoints(
        Customer $customer,
        int $points,
        LoyaltyTransactionType $type,
        string $description = '',
        ?Sales $sale = null,
        ?User $createdBy = null,
    ): LoyaltyTransaction {
        // Lock the customer row to prevent race conditions
        // when two terminals process the same customer simultaneously
        $customer = Customer::lockForUpdate()->find($customer->id);

        $balanceAfter = $customer->points_balance + $points;

        $transaction = LoyaltyTransaction::create([
            'customer_id'     => $customer->id,
            'organization_id' => $customer->organization_id,
            'sale_id'         => $sale?->id,
            'type'            => $type,
            'points'          => $points, // Positive value
            'balance_after'   => $balanceAfter,
            'description'     => $description,
            'created_by'      => $createdBy?->id,
        ]);

        $customer->update([
            'points_balance'      => $balanceAfter,
            'points_earned_total' => $customer->points_earned_total + $points,
        ]);

        return $transaction;
    }

    /**
     * Debit points from a customer and create a loyalty transaction record.
     * Points value stored as negative integer in the ledger.
     */
    protected function debitPoints(
        Customer $customer,
        int $points,
        LoyaltyTransactionType $type,
        string $description = '',
        ?Sales $sale = null,
        ?User $createdBy = null,
    ): LoyaltyTransaction {
        $customer = Customer::lockForUpdate()->find($customer->id);

        $balanceAfter = max(0, $customer->points_balance - $points);

        $transaction = LoyaltyTransaction::create([
            'customer_id'     => $customer->id,
            'organization_id' => $customer->organization_id,
            'sale_id'         => $sale?->id,
            'type'            => $type,
            'points'          => -$points, // Stored as negative
            'balance_after'   => $balanceAfter,
            'description'     => $description,
            'created_by'      => $createdBy?->id,
        ]);

        $customer->update([
            'points_balance'        => $balanceAfter,
            'points_redeemed_total' => $customer->points_redeemed_total + $points,
        ]);

        return $transaction;
    }
}
