<?php
namespace App\Models;

use App\Enums\PaymentMethod;
use App\Enums\PaymentStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payments extends Model
{
    use HasFactory;

    protected $fillable = [
        'sale_id',
        'method',
        'status',
        'amount',
        'change_given',
        'reference',
        'provider',
        'account_number',
        'paid_at',
    ];

    protected function casts(): array
    {
        return [
            'method'       => PaymentMethod::class,
            'status'       => PaymentStatus::class,
            'amount'       => 'decimal:2',
            'change_given' => 'decimal:2',
            'paid_at'      => 'datetime',
        ];
    }

    // -------------------------------------------------------------------------
    // Relationships
    // -------------------------------------------------------------------------

    public function sale(): BelongsTo
    {
        return $this->belongsTo(Sales::class);
    }

    // -------------------------------------------------------------------------
    // Helpers
    // -------------------------------------------------------------------------

    /**
     * Check if this payment has been successfully confirmed.
     */
    public function isCompleted(): bool
    {
        return $this->status === PaymentStatus::Completed;
    }

    /**
     * Check if this is a cash payment.
     * Used to determine whether to prompt for change calculation.
     */
    public function isCash(): bool
    {
        return $this->method === PaymentMethod::Cash;
    }

    /**
     * Check if this is a mobile money payment.
     */
    public function isMobileMoney(): bool
    {
        return $this->method === PaymentMethod::MobileMoney;
    }

    /**
     * Get a human-readable label for the payment method and provider.
     * e.g. "MTN MoMo", "Visa Card", "Cash"
     */
    public function methodLabel(): string
    {
        return match ($this->method) {
            PaymentMethod::Cash        => 'Cash',
            PaymentMethod::Card        => trim(($this->provider ?? '') . ' Card'),
            PaymentMethod::MobileMoney => trim(($this->provider ?? '') . ' MoMo'),
        };
    }

    /**
     * Scope: only completed payments — for reconciliation and reporting.
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', PaymentStatus::Completed);
    }

    /**
     * Scope: filter by payment method.
     */
    public function scopeByMethod($query, PaymentMethod $method)
    {
        return $query->where('method', $method->value);
    }
}
