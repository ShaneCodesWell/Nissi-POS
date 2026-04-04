<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LoyaltyTiers extends Model
{
    /** @use HasFactory<\Database\Factories\LoyaltyTiersFactory> */
    use HasFactory;

    protected $fillable = [
        'organization_id',
        'name',
        'description',
        'min_points',
        'points_multiplier',
        'discount_percentage',
        'color',
        'sort_order',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'min_points'          => 'integer',
            'points_multiplier'   => 'decimal:2',
            'discount_percentage' => 'decimal:2',
            'sort_order'          => 'integer',
            'is_active'           => 'boolean',
        ];
    }

    // -------------------------------------------------------------------------
    // Relationships
    // -------------------------------------------------------------------------

    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    public function customers(): HasMany
    {
        return $this->hasMany(Customer::class);
    }

    // -------------------------------------------------------------------------
    // Helpers
    // -------------------------------------------------------------------------

    /**
     * Calculate how many points a purchase earns under this tier.
     * Base rate is 1 point per whole currency unit spent,
     * multiplied by this tier's multiplier.
     *
     * e.g. GHS 120 purchase on Gold tier (1.5x) = 180 points
     */
    public function calculatePointsEarned(float $amountSpent): int
    {
        return (int) floor($amountSpent * $this->points_multiplier);
    }

    /**
     * Calculate the automatic tier discount for a given order amount.
     * Returns the deduction amount, not the percentage.
     */
    public function calculateTierDiscount(float $orderAmount): float
    {
        return round($orderAmount * ($this->discount_percentage / 100), 2);
    }

    /**
     * Check if a customer with a given points balance qualifies for this tier.
     */
    public function qualifies(int $pointsBalance): bool
    {
        return $pointsBalance >= $this->min_points;
    }

    /**
     * Scope: active tiers ordered from lowest to highest.
     * Use this when resolving which tier a customer belongs to.
     */
    public function scopeOrdered($query)
    {
        return $query->where('is_active', true)
            ->orderBy('sort_order');
    }
}
