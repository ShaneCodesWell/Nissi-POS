<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Inventory extends Model
{
    /** @use HasFactory<\Database\Factories\InventoryFactory> */
    use HasFactory;

    protected $table = 'inventory';

    protected $fillable = [
        'product_variant_id',
        'location_id',
        'quantity_on_hand',
        'low_stock_threshold',
        'reorder_quantity',
    ];

    protected function casts(): array
    {
        return [
            'quantity_on_hand'    => 'integer',
            'low_stock_threshold' => 'integer',
            'reorder_quantity'    => 'integer',
        ];
    }
 
    // Relationships
 
    public function variant(): BelongsTo
    {
        return $this->belongsTo(ProductVariant::class, 'product_variant_id');
    }
 
    public function location(): BelongsTo
    {
        return $this->belongsTo(Location::class);
    }
 
    // Helpers
 
    /**
     * Check if this inventory record is at or below the low stock threshold.
     */
    public function isLowStock(): bool
    {
        return $this->quantity_on_hand <= $this->low_stock_threshold;
    }
 
    /**
     * Check if this item is completely out of stock.
     */
    public function isOutOfStock(): bool
    {
        return $this->quantity_on_hand <= 0;
    }
 
    /**
     * Decrement stock by a given quantity.
     * Called when a sale is completed.
     * Throws an exception if stock would go negative.
     */
    public function decrementStock(int $quantity): void
    {
        if ($this->quantity_on_hand < $quantity) {
            throw new \RuntimeException(
                "Insufficient stock for variant ID {$this->product_variant_id} "
                . "at location ID {$this->location_id}. "
                . "Available: {$this->quantity_on_hand}, Requested: {$quantity}."
            );
        }
 
        $this->decrement('quantity_on_hand', $quantity);
    }
 
    /**
     * Increment stock by a given quantity.
     * Called when stock is received or a sale is refunded.
     */
    public function incrementStock(int $quantity): void
    {
        $this->increment('quantity_on_hand', $quantity);
    }
}
