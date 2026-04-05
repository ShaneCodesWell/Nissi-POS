<?php
namespace App\Http\Controllers\Terminal;

use App\Http\Controllers\Controller;
use App\Http\Requests\Terminal\ProcessPaymentRequest;
use App\Models\Sales;
use App\Models\Terminal;
use App\Services\PaymentService;
use Illuminate\Http\JsonResponse;

class PaymentsController extends Controller
{
    public function __construct(
        protected PaymentService $paymentService,
    ) {}

    // -------------------------------------------------------------------------
    // Single payment methods
    // -------------------------------------------------------------------------

    /**
     * Record a cash payment against a sale.
     * Accepts the amount tendered — change is calculated automatically.
     *
     * POST /terminal/{terminal}/sales/{sale}/payments/cash
     */
    public function cash(ProcessPaymentRequest $request, Terminal $terminal, Sales $sale): JsonResponse
    {
        $this->authoriseSale($terminal, $sale);

        $payment = $this->paymentService->recordCash(
            sale: $sale,
            amountTendered: $request->amount,
        );

        return response()->json([
            'message'     => 'Cash payment recorded.',
            'payment'     => $payment,
            'change_due'  => $payment->change_given,
            'outstanding' => $sale->fresh()->amountOutstanding(),
        ]);
    }

    /**
     * Record a card payment against a sale.
     *
     * POST /terminal/{terminal}/sales/{sale}/payments/card
     */
    public function card(ProcessPaymentRequest $request, Terminal $terminal, Sales $sale): JsonResponse
    {
        $this->authoriseSale($terminal, $sale);

        $payment = $this->paymentService->recordCard(
            sale: $sale,
            amount: $request->amount,
            provider: $request->provider,
            reference: $request->reference,
            accountNumber: $request->account_number,
        );

        return response()->json([
            'message'     => 'Card payment recorded.',
            'payment'     => $payment,
            'outstanding' => $sale->fresh()->amountOutstanding(),
        ]);
    }

    /**
     * Record a mobile money payment against a sale.
     *
     * POST /terminal/{terminal}/sales/{sale}/payments/mobile-money
     */
    public function mobileMoney(ProcessPaymentRequest $request, Terminal $terminal, Sales $sale): JsonResponse
    {
        $this->authoriseSale($terminal, $sale);

        $payment = $this->paymentService->recordMobileMoney(
            sale: $sale,
            amount: $request->amount,
            provider: $request->provider,
            reference: $request->reference,
            accountNumber: $request->account_number,
        );

        return response()->json([
            'message'     => 'Mobile money payment recorded.',
            'payment'     => $payment,
            'outstanding' => $sale->fresh()->amountOutstanding(),
        ]);
    }

    // -------------------------------------------------------------------------
    // Split payments
    // -------------------------------------------------------------------------

    /**
     * Record a split payment covering multiple methods in one call.
     * The terminal UI sends all payment legs together once the cashier
     * has confirmed the split amounts.
     *
     * POST /terminal/{terminal}/sales/{sale}/payments/split
     */
    public function split(ProcessPaymentRequest $request, Terminal $terminal, Sales $sale): JsonResponse
    {
        $this->authoriseSale($terminal, $sale);

        $payments = $this->paymentService->recordSplit(
            sale: $sale,
            payments: $request->payments,
        );

        return response()->json([
            'message'     => 'Split payment recorded.',
            'payments'    => $payments,
            'outstanding' => $sale->fresh()->amountOutstanding(),
        ]);
    }

    // -------------------------------------------------------------------------
    // Balance enquiry
    // -------------------------------------------------------------------------

    /**
     * Get the outstanding balance remaining on a sale.
     * Called by the terminal UI to update the balance display
     * after each partial payment in a split flow.
     *
     * GET /terminal/{terminal}/sales/{sale}/payments/outstanding
     */
    public function outstanding(Terminal $terminal, Sales $sale): JsonResponse
    {
        $this->authoriseSale($terminal, $sale);

        return response()->json([
            'total_amount' => (float) $sale->total_amount,
            'amount_paid'  => $this->paymentService->totalPaid($sale),
            'outstanding'  => $sale->amountOutstanding(),
            'breakdown'    => $this->paymentService->paymentBreakdown($sale),
        ]);
    }

    // -------------------------------------------------------------------------
    // Internal helpers
    // -------------------------------------------------------------------------

    protected function authoriseSale(Terminal $terminal, Sales $sale): void
    {
        if ($sale->terminal_id !== $terminal->id) {
            abort(403, 'This sale does not belong to the current terminal.');
        }
    }
}
