<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{
    /** @use HasFactory<\Database\Factories\CustomerFactory> */
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'organization_id',
        'loyalty_tier_id',
        'first_name',
        'last_name',
        'email',
        'phone',
        'date_of_birth',
        'gender',
        'address',
        'city',
        'points_balance',
        'points_earned_total',
        'points_redeemed_total',
        'total_spend',
        'total_transactions',
        'last_purchase_at',
        'notes',
        'marketing_opt_in',
        'is_active',
    ];

    protected $hidden = [
        'notes', // Internal CRM notes — exclude from API responses by default
    ];

    protected function casts(): array
    {
        return [
            'date_of_birth'         => 'date',
            'points_balance'        => 'integer',
            'points_earned_total'   => 'integer',
            'points_redeemed_total' => 'integer',
            'total_spend'           => 'decimal:2',
            'total_transactions'    => 'integer',
            'last_purchase_at'      => 'datetime',
            'marketing_opt_in'      => 'boolean',
            'is_active'             => 'boolean',
        ];
    }

    // -------------------------------------------------------------------------
    // Relationships
    // -------------------------------------------------------------------------

    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    public function loyaltyTier(): BelongsTo
    {
        return $this->belongsTo(LoyaltyTiers::class);
    }

    public function sales(): HasMany
    {
        return $this->hasMany(Sales::class);
    }

    public function loyaltyTransactions(): HasMany
    {
        return $this->hasMany(LoyaltyTransaction::class);
    }

    public function discountCodes(): HasMany
    {
        return $this->hasMany(CustomerDiscountCode::class);
    }

    // -------------------------------------------------------------------------
    // Helpers
    // -------------------------------------------------------------------------

    /**
     * Get the customer's full name.
     */
    public function fullName(): string
    {
        return trim("{$this->first_name} {$this->last_name}");
    }

    /**
     * Get the customer's initials for avatar display in the UI.
     * e.g. "Kwame Mensah" => "KM"
     */
    public function initials(): string
    {
        $first = strtoupper(substr($this->first_name, 0, 1));
        $last  = $this->last_name ? strtoupper(substr($this->last_name, 0, 1)) : '';

        return $first . $last;
    }

    /**
     * Check if today is the customer's birthday.
     * Useful for triggering birthday bonus points or discounts.
     */
    public function isBirthday(): bool
    {
        if (! $this->date_of_birth) {
            return false;
        }

        return $this->date_of_birth->format('m-d') === now()->format('m-d');
    }

    /**
     * Resolve which loyalty tier this customer should currently be on,
     * based on their live points_balance.
     * Returns null if no tier threshold is met.
     *
     * Call this after every purchase to check if a tier upgrade is needed.
     */
    public function resolveEligibleTier(): ?LoyaltyTiers
    {
        return LoyaltyTiers::where('organization_id', $this->organization_id)
            ->ordered()
            ->where('min_points', '<=', $this->points_balance)
            ->orderByDesc('min_points')
            ->first();
    }

    /**
     * Check if the customer is eligible for a tier upgrade
     * compared to their currently assigned tier.
     */
    public function isEligibleForUpgrade(): bool
    {
        $eligible = $this->resolveEligibleTier();

        if (! $eligible) {
            return false;
        }

        // Already on this tier or higher
        if ($this->loyaltyTier && $this->loyaltyTier->min_points >= $eligible->min_points) {
            return false;
        }

        return true;
    }

    /**
     * How many more points the customer needs to reach the next tier.
     * Returns 0 if they are already on the highest tier.
     */
    public function pointsToNextTier(): int
    {
        $nextTier = LoyaltyTiers::where('organization_id', $this->organization_id)
            ->where('is_active', true)
            ->where('min_points', '>', $this->points_balance)
            ->orderBy('min_points')
            ->first();

        if (! $nextTier) {
            return 0;
        }

        return $nextTier->min_points - $this->points_balance;
    }

    /**
     * Get only assigned (unused) voucher codes for this customer.
     */
    public function availableDiscountCodes(): HasMany
    {
        return $this->hasMany(CustomerDiscountCode::class)
            ->where('status', 'assigned')
            ->where(function ($q) {
                $q->whereNull('expires_at')
                    ->orWhere('expires_at', '>=', now());
            });
    }

    /**
     * Scope: search customers by name, email, or phone.
     * Used in the terminal's customer lookup panel.
     */
    public function scopeSearch($query, string $term)
    {
        return $query->where(function ($q) use ($term) {
            $q->where('first_name', 'like', "%{$term}%")
                ->orWhere('last_name', 'like', "%{$term}%")
                ->orWhere('email', 'like', "%{$term}%")
                ->orWhere('phone', 'like', "%{$term}%");
        });
    }

    /**
     * Scope: customers with a birthday today.
     * Use in a scheduled command to send birthday rewards.
     */
    public function scopeBirthdayToday($query)
    {
        return $query->whereNotNull('date_of_birth')
            ->whereRaw('DATE_FORMAT(date_of_birth, "%m-%d") = ?', [now()->format('m-d')]);
    }
}
