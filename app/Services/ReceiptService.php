<?php

namespace App\Services;

use App\Models\Receipts;
use App\Models\Sales;
use Illuminate\Support\Str;

class ReceiptService
{
    // -------------------------------------------------------------------------
    // Generating a receipt
    // -------------------------------------------------------------------------
 
    /**
     * Generate and persist a receipt for a completed sale.
     * Builds a full frozen snapshot of all data at the moment of completion.
     * Called automatically by SaleService::complete().
     *
     * @throws \RuntimeException if a receipt already exists for this sale
     */
    public function generateForSale(Sales $sale): Receipts
    {
        if ($sale->receipt()->exists()) {
            throw new \RuntimeException(
                "A receipt already exists for sale {$sale->sale_number}."
            );
        }
 
        $snapshot = $this->buildSnapshot($sale);
 
        return Receipts::create([
            'sale_id'         => $sale->id,
            'receipt_number'  => $this->generateReceiptNumber($sale),
            'snapshot'        => $snapshot,
            'delivery_method' => 'none',
        ]);
    }
 
    // -------------------------------------------------------------------------
    // Delivering a receipt
    // -------------------------------------------------------------------------
 
    /**
     * Mark a receipt as printed.
     * No external service call needed — just updates the delivery record.
     */
    public function markPrinted(Receipts $receipt): Receipts
    {
        $receipt->update([
            'delivery_method' => 'print',
            'sent_at'         => now(),
        ]);
 
        return $receipt->fresh();
    }
 
    /**
     * Mark a receipt as sent via email.
     * The actual email dispatch is handled by a Mailable/Job — this
     * method only records that delivery was requested.
     */
    public function markSentByEmail(Receipts $receipt, string $email): Receipts
    {
        $receipt->update([
            'delivery_method' => 'email',
            'sent_to'         => $email,
            'sent_at'         => now(),
        ]);
 
        return $receipt->fresh();
    }
 
    /**
     * Mark a receipt as sent via SMS.
     */
    public function markSentBySms(Receipts $receipt, string $phone): Receipts
    {
        $receipt->update([
            'delivery_method' => 'sms',
            'sent_to'         => $phone,
            'sent_at'         => now(),
        ]);
 
        return $receipt->fresh();
    }
 
    // -------------------------------------------------------------------------
    // Building the snapshot
    // -------------------------------------------------------------------------
 
    /**
     * Build the complete frozen JSON snapshot for a sale.
     * This captures every piece of data needed to reproduce the receipt
     * accurately at any point in the future, regardless of changes to
     * products, prices, staff, or business details.
     */
    protected function buildSnapshot(Sales $sale): array
    {
        // Eager load everything needed in one go
        $sale->loadMissing([
            'organization',
            'location',
            'terminal',
            'cashier',
            'customer.loyaltyTier',
            'items.variant.product',
            'payments',
            'discount',
        ]);
 
        return [
            'receipt_generated_at' => now()->toIso8601String(),
 
            // ── Business details ──────────────────────────────────────────────
            // Frozen at time of sale — reprinting always shows the correct
            // business name/address even if the org updates their details later
            'business' => [
                'name'     => $sale->organization->name,
                'address'  => $sale->location->address,
                'city'     => $sale->location->city,
                'phone'    => $sale->location->phone,
                'email'    => $sale->location->email,
                'currency' => $sale->organization->currency,
            ],
 
            // ── Sale metadata ─────────────────────────────────────────────────
            'sale' => [
                'sale_number'  => $sale->sale_number,
                'status'       => $sale->status->value,
                'completed_at' => $sale->completed_at?->toIso8601String(),
                'terminal'     => $sale->terminal?->name,
                'cashier'      => $sale->cashier?->name,
                'notes'        => $sale->notes,
            ],
 
            // ── Customer ──────────────────────────────────────────────────────
            'customer' => $sale->customer ? [
                'id'           => $sale->customer->id,
                'name'         => $sale->customer->fullName(),
                'phone'        => $sale->customer->phone,
                'email'        => $sale->customer->email,
                'loyalty_tier' => $sale->customer->loyaltyTier?->name,
                'points_before'=> $sale->customer->points_balance,
            ] : null,
 
            // ── Line items ────────────────────────────────────────────────────
            // Always built from snapshot columns on sale_items, never from
            // live product/variant data
            'items' => $sale->items->map(fn ($item) => [
                'product_name'    => $item->product_name,
                'variant_name'    => $item->variant_name,
                'sku'             => $item->sku,
                'quantity'        => $item->quantity,
                'unit_price'      => (float) $item->unit_price,
                'discount_amount' => (float) $item->discount_amount,
                'tax_amount'      => (float) $item->tax_amount,
                'line_total'      => (float) $item->line_total,
            ])->toArray(),
 
            // ── Totals ────────────────────────────────────────────────────────
            'totals' => [
                'subtotal'        => (float) $sale->subtotal,
                'discount_amount' => (float) $sale->discount_amount,
                'discount_code'   => $sale->discount_code,
                'tax_amount'      => (float) $sale->tax_amount,
                'total_amount'    => (float) $sale->total_amount,
            ],
 
            // ── Payments ──────────────────────────────────────────────────────
            'payments' => $sale->payments->map(fn ($payment) => [
                // 'method'         => $payment->method->value,
                'method'         => $payment->method,
                'provider'       => $payment->provider,
                'amount'         => (float) $payment->amount,
                'change_given'   => (float) $payment->change_given,
                'reference'      => $payment->reference,
                'account_number' => $payment->account_number,
                'paid_at'        => $payment->paid_at?->toIso8601String(),
            ])->toArray(),
        ];
    }
 
    // -------------------------------------------------------------------------
    // Internal helpers
    // -------------------------------------------------------------------------
 
    /**
     * Generate a unique receipt number derived from the sale number.
     * Format: RCP-{LOCATION_CODE}-{YYYYMMDD}-{5 random chars}
     * e.g. RCP-ACR01-20250401-P9QR2
     */
    protected function generateReceiptNumber(Sales $sale): string
    {
        $locationCode = strtoupper($sale->location->code ?? 'LOC');
        $date         = now()->format('Ymd');
        $random       = strtoupper(Str::random(5));
 
        return "RCP-{$locationCode}-{$date}-{$random}";
    }
}
