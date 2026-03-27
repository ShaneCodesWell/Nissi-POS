<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;


class ProductVariant extends Model
{
    /** @use HasFactory<\Database\Factories\ProductVariantFactory> */
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'product_id',
        'name',
        'sku',
        'barcode',
        'price',
        'cost_price',
        'attributes',
        'image_path',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'price'      => 'decimal:2',
            'cost_price' => 'decimal:2',
            'attributes' => 'array',       // JSON column auto-cast to/from PHP array
            'is_active'  => 'boolean',
        ];
    }
 
    // Relationships

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * All inventory records for this variant across every location.
     */
    public function inventory(): HasMany
    {
        return $this->hasMany(Inventory::class);
    }
 
    /**
     * Inventory record for a specific location.
     * Usage: $variant->inventoryAt($location)
     */
    public function inventoryAt(Location $location): HasOne
    {
        return $this->hasOne(Inventory::class)
                    ->where('location_id', $location->id);
    }
 
    public function saleItems(): HasMany
    {
        return $this->hasMany(SaleItems::class);
    }
 
    // Helpers
 
    /**
     * Get stock quantity at a specific location.
     * Returns 0 if no inventory record exists yet.
     */
    public function stockAt(Location $location): int
    {
        return $this->inventory()
                    ->where('location_id', $location->id)
                    ->value('quantity_on_hand') ?? 0;
    }
 
    /**
     * Check if this variant is in stock at a specific location.
     */
    public function inStockAt(Location $location): bool
    {
        return $this->stockAt($location) > 0;
    }
 
    /**
     * Check if this variant is below the low stock threshold
     * at a specific location.
     */
    public function isLowStockAt(Location $location): bool
    {
        $record = $this->inventory()
                       ->where('location_id', $location->id)
                       ->first();
 
        if (! $record) {
            return false;
        }
 
        return $record->quantity_on_hand <= $record->low_stock_threshold;
    }
 
    /**
     * Calculate the profit margin percentage for this variant.
     * Returns null if cost_price is zero to avoid division by zero.
     */
    public function marginPercentage(): ?float
    {
        if ($this->cost_price <= 0) {
            return null;
        }
 
        return round(
            (($this->price - $this->cost_price) / $this->price) * 100,
            2
        );
    }
 
    /**
     * Get a specific attribute from the JSON attributes column.
     * Usage: $variant->getAttribute('color') => 'Red'
     */
    // public function getAttributeValue(string $key): mixed
    // {
    //     return $this->attributes[$key] ?? null;
    // }
}
