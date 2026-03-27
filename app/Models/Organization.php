<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Organization extends Model
{
    /** @use HasFactory<\Database\Factories\OrganizationFactory> */
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'email',
        'phone',
        'address',
        'city',
        'country',
        'currency',
        'timezone',
        'logo_path',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    // Relationships
    public function locations(): HasMany
    {
        return $this->hasMany(Location::class);
    }
 
    public function categories(): HasMany
    {
        return $this->hasMany(Category::class);
    }
 
    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }
 
    public function discounts(): HasMany
    {
        return $this->hasMany(Discount::class);
    }
 
    public function customers(): HasMany
    {
        return $this->hasMany(Customer::class);
    }
 
    public function loyaltyTiers(): HasMany
    {
        return $this->hasMany(LoyaltyTiers::class);
    }
 
    public function sales(): HasMany
    {
        return $this->hasMany(Sales::class);
    }

    // Helpers
    public function activeLocations(): HasMany
    {
        return $this->hasMany(Location::class)->where('is_active', true);
    }
}
