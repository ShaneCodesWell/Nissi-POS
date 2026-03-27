<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Terminal extends Model
{
    /** @use HasFactory<\Database\Factories\TerminalFactory> */
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'location_id',
        'name',
        'identifier',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    // Relationships
    public function location(): BelongsTo
    {
        return $this->belongsTo(Location::class);
    }

    /**
     * Convenience: get the organization this terminal belongs to
     * without an extra query by going through location.
     */
    public function organization(): BelongsTo
    {
        return $this->location->organization();
    }

    public function sales(): HasMany
    {
        return $this->hasMany(Sales::class);
    }

    //Helpers
    /**
     * Total completed sales processed through this terminal today.
     */
    public function salesToday(): int
    {
        return $this->sales()
            ->whereDate('created_at', today())
            ->where('status', 'completed')
            ->count();
    }

}
