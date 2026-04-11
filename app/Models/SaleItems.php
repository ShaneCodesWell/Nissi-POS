<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SaleItems extends Model
{
    use HasFactory;

    protected $fillable = [
        'sale_id',
        'product_variant_id',
        'product_name',
        'variant_name',
        'sku',
        'quantity',
        'unit_price',
        'cost_price',
        'discount_amount',
        'tax_amount',
        'line_total',
        'discount_id',
    ];

    protected function casts(): array
    {
        return [
            'quantity'        => 'integer',
            'unit_price'      => 'decimal:2',
            'cost_price'      => 'decimal:2',
            'discount_amount' => 'decimal:2',
            'tax_amount'      => 'decimal:2',
            'line_total'      => 'decimal:2',
        ];
    }

    // -------------------------------------------------------------------------
    // Relationships
    // -------------------------------------------------------------------------

    public function sale(): BelongsTo
    {
        return $this->belongsTo(Sales::class);
    }

    /**
     * The live product variant record.
     * Note: always use the snapshot columns (product_name, unit_price, sku)
     * for display and reporting — not this relationship — to ensure
     * historical accuracy. This relationship is for cross-referencing only.
     */
    public function variant(): BelongsTo
    {
        return $this->belongsTo(ProductVariant::class, 'product_variant_id');
    }

    public function discount(): BelongsTo
    {
        return $this->belongsTo(Discount::class);
    }

    // -------------------------------------------------------------------------
    // Helpers
    // -------------------------------------------------------------------------

    /**
     * Calculate gross profit for this line item.
     * (unit_price - cost_price) * quantity - discount
     */
    public function grossProfit(): float
    {
        return round(
            (($this->unit_price - $this->cost_price) * $this->quantity) - $this->discount_amount,
            2
        );
    }

    /**
     * Calculate the profit margin percentage for this line item.
     * Returns null if unit_price is zero to avoid division by zero.
     */
    public function marginPercentage(): ?float
    {
        if ($this->unit_price <= 0) {
            return null;
        }

        return round(
            (($this->unit_price - $this->cost_price) / $this->unit_price) * 100,
            2
        );
    }

    /**
     * Get a human-readable display name for this line item.
     * Combines product name and variant name if present.
     */
    public function displayName(): string
    {
        return $this->variant_name
            ? "{$this->product_name} — {$this->variant_name}"
            : $this->product_name;
    }
}
