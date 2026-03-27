<?php
namespace App\Models;

use App\Enums\DiscountScope;
use App\Enums\DiscountType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Discount extends Model
{
    /** @use HasFactory<\Database\Factories\DiscountFactory> */
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'organization_id',
        'name',
        'description',
        'type',
        'value',
        'scope',
        'minimum_order_amount',
        'max_uses',
        'used_count',
        'is_active',
        'starts_at',
        'expires_at',
    ];

    protected function casts(): array
    {
        return [
            'type'                 => DiscountType::class,
            'scope'                => DiscountScope::class,
            'value'                => 'decimal:2',
            'minimum_order_amount' => 'decimal:2',
            'max_uses'             => 'integer',
            'used_count'           => 'integer',
            'is_active'            => 'boolean',
            'starts_at'            => 'datetime',
            'expires_at'           => 'datetime',
        ];
    }

    // -------------------------------------------------------------------------
    // Relationships
    // -------------------------------------------------------------------------

    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    public function codes(): HasMany
    {
        return $this->hasMany(DiscountCode::class);
    }

    public function sales(): HasMany
    {
        return $this->hasMany(Sales::class);
    }

    public function saleItems(): HasMany
    {
        return $this->hasMany(SaleItems::class);
    }

    // Helpers

    /**
     * Check if this discount is currently valid and usable.
     * Validates active flag, date window, and usage cap all at once.
     */
    public function isValid(): bool
    {
        if (! $this->is_active) {
            return false;
        }

        $now = now();

        if ($this->starts_at && $this->starts_at->isFuture()) {
            return false;
        }

        if ($this->expires_at && $this->expires_at->isPast()) {
            return false;
        }

        if (! is_null($this->max_uses) && $this->used_count >= $this->max_uses) {
            return false;
        }

        return true;
    }

    /**
     * Check if a given order subtotal meets the minimum order requirement.
     */
    public function appliesTo(float $orderSubtotal): bool
    {
        return $orderSubtotal >= (float) $this->minimum_order_amount;
    }

    /**
     * Calculate the discount amount to deduct from a given price.
     * Works for both percentage and fixed discount types.
     *
     * @param  float  $amount  The price to apply the discount to
     * @return float           The amount to deduct (never exceeds $amount)
     */
    public function calculateDeduction(float $amount): float
    {
        $deduction = match ($this->type) {
            DiscountType::Percentage => round($amount * ($this->value / 100), 2),
            DiscountType::Fixed      => min((float) $this->value, $amount),
        };

        return min($deduction, $amount);
    }

    /**
     * Increment the global used_count on this discount rule.
     * Called each time any code under this discount is redeemed.
     */
    public function recordUse(): void
    {
        $this->increment('used_count');
    }

    /**
     * Check if this discount applies at the order level.
     */
    public function isOrderLevel(): bool
    {
        return $this->scope === DiscountScope::Order;
    }

    /**
     * Check if this discount applies at the individual item level.
     */
    public function isItemLevel(): bool
    {
        return $this->scope === DiscountScope::Item;
    }

    /**
     * Scope: only currently valid discounts.
     * Combines active flag, date window, and usage cap checks at DB level.
     * Use this in controllers rather than filtering in PHP.
     */
    public function scopeValid($query)
    {
        return $query->where('is_active', true)
            ->where(function ($q) {
                $q->whereNull('starts_at')
                    ->orWhere('starts_at', '<=', now());
            })
            ->where(function ($q) {
                $q->whereNull('expires_at')
                    ->orWhere('expires_at', '>=', now());
            })
            ->where(function ($q) {
                $q->whereNull('max_uses')
                    ->orWhereColumn('used_count', '<', 'max_uses');
            });
    }

    /**
     * Scope: order-level discounts only.
     */
    public function scopeOrderLevel($query)
    {
        return $query->where('scope', DiscountScope::Order->value);
    }

    /**
     * Scope: item-level discounts only.
     */
    public function scopeItemLevel($query)
    {
        return $query->where('scope', DiscountScope::Item->value);
    }
}
