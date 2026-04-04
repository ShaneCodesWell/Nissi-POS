<?php
namespace App\Models;

use App\Enums\LoyaltyTransactionType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LoyaltyTransaction extends Model
{
    /** @use HasFactory<\Database\Factories\LoyaltyTransactionFactory> */
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'organization_id',
        'sale_id',
        'type',
        'points',
        'balance_after',
        'description',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'type'          => LoyaltyTransactionType::class,
            'points'        => 'integer', // Signed — positive = earn, negative = deduct
            'balance_after' => 'integer',
        ];
    }

    // -------------------------------------------------------------------------
    // Relationships
    // -------------------------------------------------------------------------

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    public function sale(): BelongsTo
    {
        return $this->belongsTo(Sales::class);
    }

    /**
     * The staff member who created a manual adjustment, if any.
     */
    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // -------------------------------------------------------------------------
    // Helpers
    // -------------------------------------------------------------------------

    /**
     * Check if this transaction added points to the customer's balance.
     */
    public function isCredit(): bool
    {
        return $this->points > 0;
    }

    /**
     * Check if this transaction deducted points from the customer's balance.
     */
    public function isDebit(): bool
    {
        return $this->points < 0;
    }

    /**
     * Get the absolute point value regardless of direction.
     * Useful for display e.g. showing "−50 pts" rather than "−−50 pts".
     */
    public function absolutePoints(): int
    {
        return abs($this->points);
    }

    /**
     * Get a formatted display string for the transaction.
     * e.g. "+180 pts — Purchase #ORD-00042"
     *      "−200 pts — Points redeemed at checkout"
     */
    public function displaySummary(): string
    {
        $sign   = $this->isCredit() ? '+' : '−';
        $points = $this->absolutePoints();
        $desc   = $this->description ?? $this->type->value;

        return "{$sign}{$points} pts — {$desc}";
    }

    /**
     * Scope: earn transactions only.
     */
    public function scopeEarns($query)
    {
        return $query->where('type', LoyaltyTransactionType::Earn->value);
    }

    /**
     * Scope: redeem transactions only.
     */
    public function scopeRedemptions($query)
    {
        return $query->where('type', LoyaltyTransactionType::Redeem->value);
    }

    /**
     * Scope: manual adjustments made by staff.
     */
    public function scopeAdjustments($query)
    {
        return $query->where('type', LoyaltyTransactionType::Adjustment->value);
    }

    /**
     * Scope: transactions within a date range.
     */
    public function scopeBetweenDates($query, string $from, string $to)
    {
        return $query->whereBetween('created_at', [$from, $to]);
    }
}
