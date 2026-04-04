<?php
namespace App\Models;

use App\Enums\VoucherStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CustomerDiscountCode extends Model
{
    /** @use HasFactory<\Database\Factories\CustomerDiscountCodeFactory> */
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'discount_code_id',
        'sale_id',
        'status',
        'assigned_at',
        'redeemed_at',
        'expires_at',
    ];

    protected function casts(): array
    {
        return [
            'status'      => VoucherStatus::class,
            'assigned_at' => 'datetime',
            'redeemed_at' => 'datetime',
            'expires_at'  => 'datetime',
        ];
    }

    // -------------------------------------------------------------------------
    // Relationships
    // -------------------------------------------------------------------------

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function discountCode(): BelongsTo
    {
        return $this->belongsTo(DiscountCode::class);
    }

    /**
     * The sale this voucher was redeemed on.
     * Null until the voucher is used at checkout.
     */
    public function sale(): BelongsTo
    {
        return $this->belongsTo(Sales::class);
    }

    // -------------------------------------------------------------------------
    // Helpers
    // -------------------------------------------------------------------------

    /**
     * Check if this assigned voucher is still usable right now.
     * Validates status, expiry, and the underlying code's own validity.
     */
    public function isUsable(): bool
    {
        if ($this->status !== VoucherStatus::Assigned) {
            return false;
        }

        if ($this->expires_at && $this->expires_at->isPast()) {
            return false;
        }

        // Also verify the underlying code is still valid
        return $this->discountCode->isValid();
    }

    /**
     * Mark this voucher as redeemed against a given sale.
     * Always call inside a DB::transaction() alongside
     * DiscountCode::recordUse().
     */
    public function markRedeemed(Sales $sale): void
    {
        $this->update([
            'status'      => VoucherStatus::Redeemed,
            'sale_id'     => $sale->id,
            'redeemed_at' => now(),
        ]);
    }

    /**
     * Mark this voucher as expired.
     * Called by a scheduled command that sweeps past-expiry assignments.
     */
    public function markExpired(): void
    {
        $this->update(['status' => VoucherStatus::Expired]);
    }

    /**
     * Mark this voucher as revoked by staff.
     */
    public function markRevoked(): void
    {
        $this->update(['status' => VoucherStatus::Revoked]);
    }

    /**
     * Scope: only usable (assigned, non-expired) vouchers.
     */
    public function scopeUsable($query)
    {
        return $query->where('status', VoucherStatus::Assigned->value)
            ->where(function ($q) {
                $q->whereNull('expires_at')
                    ->orWhere('expires_at', '>=', now());
            });
    }

    /**
     * Scope: vouchers that have passed their expiry but are still
     * marked as assigned. Used by the expiry sweep command.
     */
    public function scopeExpiredUnsettled($query)
    {
        return $query->where('status', VoucherStatus::Assigned->value)
            ->where('expires_at', '<', now());
    }
}
