<?php
namespace App\Models;

use App\Enums\SaleStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Sales extends Model
{
    /** @use HasFactory<\Database\Factories\SalesFactory> */
    use HasFactory, SoftDeletes;

    protected $casts = [
        'status' => SaleStatus::class,
    ];

    protected $fillable = [
        'organization_id',
        'location_id',
        'terminal_id',
        'user_id',
        'customer_id',
        'sale_number',
        'status',
        'subtotal',
        'discount_amount',
        'tax_amount',
        'total_amount',
        'discount_id',
        'discount_code',
        'notes',
        'completed_at',
    ];

    protected function casts(): array
    {
        return [
            'status'          => SaleStatus::class,
            'subtotal'        => 'decimal:2',
            'discount_amount' => 'decimal:2',
            'tax_amount'      => 'decimal:2',
            'total_amount'    => 'decimal:2',
            'completed_at'    => 'datetime',
        ];
    }

    // Relationships
    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    public function location(): BelongsTo
    {
        return $this->belongsTo(Location::class);
    }

    public function terminal(): BelongsTo
    {
        return $this->belongsTo(Terminal::class);
    }

    /**
     * The cashier who processed this sale.
     */
    public function cashier(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function discount(): BelongsTo
    {
        return $this->belongsTo(Discount::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(SaleItems::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payments::class);
    }

    public function receipt(): HasOne
    {
        return $this->hasOne(Receipts::class);
    }

    public function loyaltyTransactions(): HasMany
    {
        return $this->hasMany(LoyaltyTransaction::class);
    }

    // Helpers

    /**
     * Check if the sale has been fully paid.
     * Total payments received >= total amount due.
     */
    public function isFullyPaid(): bool
    {
        $totalPaid = $this->payments()
            ->where('status', 'completed')
            ->sum('amount');

        return $totalPaid >= $this->total_amount;
    }

    /**
     * Get the outstanding balance still owed on this sale.
     * Useful for split payment flows at the terminal.
     */
    public function amountOutstanding(): float
    {
        $totalPaid = $this->payments()
            ->where('status', 'completed')
            ->sum('amount');

        return max(0, (float) $this->total_amount - (float) $totalPaid);
    }

    /**
     * Check if this sale belongs to a walk-in (anonymous) customer.
     */
    public function isWalkIn(): bool
    {
        return is_null($this->customer_id);
    }

    /**
     * Check if this sale is in a terminal-editable state.
     * Completed, voided, and refunded sales must not be modified.
     */
    public function isEditable(): bool
    {
        return $this->status === SaleStatus::Pending;
    }

    /**
     * Get the total number of individual units across all line items.
     */
    public function totalItemCount(): int
    {
        return (int) $this->items()->sum('quantity');
    }

    /**
     * Scope: only completed sales — for use in reports.
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', SaleStatus::Completed);
    }

    /**
     * Scope: sales within a date range.
     */
    public function scopeBetweenDates($query, string $from, string $to)
    {
        return $query->whereBetween('completed_at', [$from, $to]);
    }

    /**
     * Scope: sales at a specific location.
     */
    public function scopeAtLocation($query, int $locationId)
    {
        return $query->where('location_id', $locationId);
    }
}
