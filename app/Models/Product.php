<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    /** @use HasFactory<\Database\Factories\ProductFactory> */
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'organization_id',
        'category_id',
        'name',
        'slug',
        'description',
        'image_path',
        'has_variants',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'has_variants' => 'boolean',
            'is_active'    => 'boolean',
        ];
    }

    // Relationships
    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function variants(): HasMany
    {
        return $this->hasMany(ProductVariant::class);
    }

    /**
     * Active variants only — what the terminal should display.
     */
    public function activeVariants(): HasMany
    {
        return $this->hasMany(ProductVariant::class)
            ->where('is_active', true);
    }

    /**
     * The single default variant for products without multiple variants.
     * Equivalent to calling variants()->first() but semantically clearer.
     */
    public function defaultVariant(): HasMany
    {
        return $this->hasMany(ProductVariant::class)
            ->whereNull('name')
            ->limit(1);
    }

    /**
     * Access inventory records across all variants of this product
     * directly, without manually joining through product_variants.
     */
    public function inventory(): HasManyThrough
    {
        return $this->hasManyThrough(Inventory::class, ProductVariant::class);
    }

    // Helpers

    /**
     * Get the lowest selling price across all active variants.
     * Useful for displaying a "from GHS X" price on product cards.
     */
    public function lowestPrice(): float
    {
        return (float) $this->activeVariants()->min('price');
    }

    /**
     * Get the highest selling price across all active variants.
     */
    public function highestPrice(): float
    {
        return (float) $this->activeVariants()->max('price');
    }

    /**
     * Check if any variant of this product is low on stock
     * at a given location.
     */
    public function hasLowStock(Location $location): bool
    {
        return $this->inventory()
            ->where('location_id', $location->id)
            ->whereColumn('quantity_on_hand', '<=', 'low_stock_threshold')
            ->exists();
    }

    /**
     * Check if any variant of this product is completely out of stock
     * at a given location.
     */
    public function isOutOfStock(Location $location): bool
    {
        return ! $this->inventory()
            ->where('location_id', $location->id)
            ->where('quantity_on_hand', '>', 0)
            ->exists();
    }
}
