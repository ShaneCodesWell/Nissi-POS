<?php
namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Enums\UserRole;
use App\Models\Sales;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;
 
    protected $fillable = [
        'name',
        'email',
        'password',
        'is_admin',
    ];
 
    protected $hidden = [
        'password',
        'remember_token',
    ];
 
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
            'is_admin'          => 'boolean',
        ];
    }
 
    // -------------------------------------------------------------------------
    // Relationships
    // -------------------------------------------------------------------------
 
    public function locations(): BelongsToMany
    {
        return $this->belongsToMany(Location::class, 'user_location')
                    ->withPivot('role', 'is_active')
                    ->withTimestamps();
    }
 
    public function sales(): HasMany
    {
        return $this->hasMany(Sales::class);
    }
 
    public function loyaltyAdjustments(): HasMany
    {
        return $this->hasMany(LoyaltyTransaction::class, 'created_by');
    }
 
    // -------------------------------------------------------------------------
    // Helpers
    // -------------------------------------------------------------------------
 
    /**
     * Check if this user is a system admin.
     */
    public function isAdmin(): bool
    {
        return $this->is_admin === true;
    }
 
    /**
     * Get the user's role at a specific location.
     */
    public function roleAt(Location $location): ?UserRole
    {
        $pivot = $this->locations->find($location->id)?->pivot;
        return $pivot ? UserRole::from($pivot->role) : null;
    }
 
    /**
     * Get the first active location this user is assigned to.
     * Used after login to redirect a cashier to their terminal selection.
     */
    public function primaryLocation(): ?Location
    {
        return $this->locations()
                    ->wherePivot('is_active', true)
                    ->first();
    }
 
    /**
     * Check if user is active at a given location.
     */
    public function isActiveAt(Location $location): bool
    {
        return $this->locations()
                    ->wherePivot('location_id', $location->id)
                    ->wherePivot('is_active', true)
                    ->exists();
    }
}
