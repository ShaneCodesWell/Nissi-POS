<?php
namespace App\Services;

use App\Enums\PaymentMethod;
use App\Enums\PaymentStatus;
use App\Models\Payments;
use App\Models\Sales;
use Illuminate\Support\Facades\DB;

class PaymentService
{
    // -------------------------------------------------------------------------
    // Recording payments
    // -------------------------------------------------------------------------

    /**
     * Record a cash payment against a sale.
     * Automatically calculates and stores change due.
     *
     * @throws \InvalidArgumentException if amount tendered is less than outstanding balance
     */
    public function recordCash(Sales $sale, float $amountTendered): Payments
    {
        $outstanding = $sale->amountOutstanding();

        if ($amountTendered < $outstanding) {
            throw new \InvalidArgumentException(
                "Cash tendered ({$amountTendered}) is less than the amount outstanding ({$outstanding})."
            );
        }

        $changeGiven = round($amountTendered - $outstanding, 2);

        return $this->record($sale, [
            'method'       => PaymentMethod::Cash,
            'amount'       => $outstanding, // Only record the exact amount owed, not the tender
            'change_given' => $changeGiven,
            'paid_at'      => now(),
        ]);
    }

    /**
     * Record a card payment against a sale.
     *
     * @throws \InvalidArgumentException if amount exceeds outstanding balance
     */
    public function recordCard(
        Sales $sale,
        float $amount,
        ?string $provider = null,      // e.g. "Visa", "Mastercard"
        ?string $reference = null,     // Auth code from card machine
        ?string $accountNumber = null, // Last 4 digits
    ): Payments {
        $this->assertAmountValid($sale, $amount);

        return $this->record($sale, [
            'method'         => PaymentMethod::Card,
            'amount'         => $amount,
            'provider'       => $provider,
            'reference'      => $reference,
            'account_number' => $accountNumber,
            'paid_at'        => now(),
        ]);
    }

    /**
     * Record a mobile money payment against a sale.
     *
     * @throws \InvalidArgumentException if amount exceeds outstanding balance
     */
    public function recordMobileMoney(
        Sales $sale,
        float $amount,
        ?string $provider = null,      // e.g. "MTN", "Vodafone", "AirtelTigo"
        ?string $reference = null,     // MoMo transaction ID
        ?string $accountNumber = null, // Customer's MoMo number (masked)
    ): Payments {
        $this->assertAmountValid($sale, $amount);

        return $this->record($sale, [
            'method'         => PaymentMethod::MobileMoney,
            'amount'         => $amount,
            'provider'       => $provider,
            'reference'      => $reference,
            'account_number' => $accountNumber,
            'paid_at'        => now(),
        ]);
    }

    // -------------------------------------------------------------------------
    // Split payment helper
    // -------------------------------------------------------------------------

    /**
     * Record a split payment in one call.
     * Accepts an array of payment instructions and processes each in sequence.
     * The total of all payments must cover the full outstanding balance.
     *
     * Usage:
     * $paymentService->recordSplit($sale, [
     *     ['method' => 'cash',         'amount' => 50.00],
     *     ['method' => 'mobile_money', 'amount' => 30.00, 'provider' => 'MTN', 'reference' => 'TXN123'],
     * ]);
     *
     * @throws \InvalidArgumentException if payments do not cover the outstanding balance
     */
    public function recordSplit(Sales $sale, array $payments): array
    {
        $totalOffered = collect($payments)->sum('amount');
        $outstanding  = $sale->amountOutstanding();

        if (round($totalOffered, 2) < round($outstanding, 2)) {
            throw new \InvalidArgumentException(
                "Total payments offered ({$totalOffered}) do not cover the outstanding balance ({$outstanding})."
            );
        }

        $recorded = [];

        DB::transaction(function () use ($sale, $payments, &$recorded) {
            foreach ($payments as $payment) {
                $method = PaymentMethod::from($payment['method']);

                $recorded[] = match ($method) {
                    PaymentMethod::Cash        => $this->recordCash(
                        $sale,
                        $payment['amount']
                    ),
                    PaymentMethod::Card        => $this->recordCard(
                        $sale,
                        $payment['amount'],
                        $payment['provider'] ?? null,
                        $payment['reference'] ?? null,
                        $payment['account_number'] ?? null,
                    ),
                    PaymentMethod::MobileMoney => $this->recordMobileMoney(
                        $sale,
                        $payment['amount'],
                        $payment['provider'] ?? null,
                        $payment['reference'] ?? null,
                        $payment['account_number'] ?? null,
                    ),
                };
            }
        });

        return $recorded;
    }

    // -------------------------------------------------------------------------
    // Refunding payments
    // -------------------------------------------------------------------------

    /**
     * Mark all completed payments on a sale as refunded.
     * Called by SaleService::refund() as part of the full refund flow.
     */
    public function refundAll(Sales $sale): void
    {
        $sale->payments()
            ->where('status', PaymentStatus::Completed)
            ->update(['status' => PaymentStatus::Refunded]);
    }

    // -------------------------------------------------------------------------
    // Summary helpers
    // -------------------------------------------------------------------------

    /**
     * Get the total amount paid against a sale across all confirmed payments.
     */
    public function totalPaid(Sales $sale): float
    {
        return (float) $sale->payments()
            ->where('status', PaymentStatus::Completed)
            ->sum('amount');
    }

    /**
     * Get a breakdown of payments by method for display on the receipt
     * or end-of-day reconciliation.
     *
     * Returns e.g.:
     * [
     *   'cash'         => 50.00,
     *   'mobile_money' => 30.00,
     * ]
     */
    public function paymentBreakdown(Sales $sale): array
    {
        return $sale->payments()
            ->where('status', PaymentStatus::Completed)
            ->get()
            ->groupBy(fn($p) => $p->method->value)
            ->map(fn($group) => round($group->sum('amount'), 2))
            ->toArray();
    }

    /**
     * Get the total change given across all cash payments on a sale.
     * Useful for cash drawer reconciliation.
     */
    public function totalChangeGiven(Sales $sale): float
    {
        return (float) $sale->payments()
            ->where('method', PaymentMethod::Cash->value)
            ->where('status', PaymentStatus::Completed)
            ->sum('change_given');
    }

    // -------------------------------------------------------------------------
    // Internal helpers
    // -------------------------------------------------------------------------

    /**
     * Persist a payment record and mark it as completed immediately.
     * All payment recording goes through here.
     */
    protected function record(Sales $sale, array $attributes): Payments
    {
        return DB::transaction(function () use ($sale, $attributes) {
            return $sale->payments()->create(array_merge($attributes, [
                'status' => PaymentStatus::Completed,
            ]));
        });
    }

    /**
     * Assert that a payment amount is valid for a given sale.
     * Amount must be positive and not exceed the outstanding balance.
     *
     * @throws \InvalidArgumentException
     */
    protected function assertAmountValid(Sales $sale, float $amount): void
    {
        if ($amount <= 0) {
            throw new \InvalidArgumentException(
                "Payment amount must be greater than zero."
            );
        }

        $outstanding = $sale->amountOutstanding();

        if (round($amount, 2) > round($outstanding, 2)) {
            throw new \InvalidArgumentException(
                "Payment amount ({$amount}) exceeds the outstanding balance ({$outstanding}). "
                . "Use recordCash() if you need to handle overpayment and change."
            );
        }
    }
}
