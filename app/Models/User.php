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
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
        ];
    }

    // Relationships
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

    public function roleAt(Location $location): ?UserRole
    {
        $pivot = $this->locations
                      ->find($location->id)
                      ?->pivot;
 
        return $pivot ? UserRole::from($pivot->role) : null;
    }

    public function isActiveAt(Location $location): bool
    {
        return $this->locations()
                    ->wherePivot('location_id', $location->id)
                    ->wherePivot('is_active', true)
                    ->exists();
    }
}
