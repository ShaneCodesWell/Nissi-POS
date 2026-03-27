<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class DiscountCode extends Model
{
    /** @use HasFactory<\Database\Factories\DiscountCodeFactory> */
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'discount_id',
        'code',
        'max_uses',
        'used_count',
        'is_active',
        'expires_at',
    ];

    protected function casts(): array
    {
        return [
            'max_uses'   => 'integer',
            'used_count' => 'integer',
            'is_active'  => 'boolean',
            'expires_at' => 'datetime',
        ];
    }

    // Relationships
    /**
     * The parent discount rule this code belongs to.
     */
    public function discount(): BelongsTo
    {
        return $this->belongsTo(Discount::class);
    }

    /**
     * Customers this code has been assigned to.
     */
    public function customerAssignments(): HasMany
    {
        return $this->hasMany(CustomerDiscountCode::class);
    }

    // Helpers
    /**
     * Check if this specific code is valid and usable right now.
     * Also checks the parent discount rule via isValid().
     */
    public function isValid(): bool
    {
        // Code-level checks first
        if (! $this->is_active) {
            return false;
        }

        if ($this->expires_at && $this->expires_at->isPast()) {
            return false;
        }

        if (! is_null($this->max_uses) && $this->used_count >= $this->max_uses) {
            return false;
        }

        // Then verify the parent discount rule itself is also valid
        return $this->discount->isValid();
    }

    /**
     * Increment the used_count on this code and record a use
     * on the parent discount rule as well.
     * Always call this inside a DB transaction.
     */
    public function recordUse(): void
    {
        $this->increment('used_count');
        $this->discount->recordUse();
    }

    /**
     * Check if this code has been assigned to a specific customer.
     */
    public function isAssignedTo(Customer $customer): bool
    {
        return $this->customerAssignments()
            ->where('customer_id', $customer->id)
            ->exists();
    }

    /**
     * Scope: only currently valid codes.
     * Mirrors Discount::scopeValid() but at the code level.
     */
    public function scopeValid($query)
    {
        return $query->where('is_active', true)
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
     * Scope: find a code by its code string, case-insensitively.
     * Usage: DiscountCode::forCode('SAVE20')->valid()->first()
     */
    public function scopeForCode($query, string $code)
    {
        return $query->whereRaw('LOWER(code) = ?', [strtolower($code)]);
    }
}
