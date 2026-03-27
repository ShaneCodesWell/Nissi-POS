<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Receipts extends Model
{
    /** @use HasFactory<\Database\Factories\ReceiptsFactory> */
    use HasFactory;

    protected $fillable = [
        'sale_id',
        'receipt_number',
        'snapshot',
        'sent_to',
        'delivery_method',
        'sent_at',
    ];

    protected function casts(): array
    {
        return [
            'snapshot' => 'array', // Full JSON receipt — auto cast to/from PHP array
            'sent_at'  => 'datetime',
        ];
    }

    // Relationships
    public function sale(): BelongsTo
    {
        return $this->belongsTo(Sales::class);
    }

    // Helpers
    /**
     * Check if this receipt has been sent to the customer.
     */
    public function hasBeenSent(): bool
    {
        return ! is_null($this->sent_at);
    }

    /**
     * Retrieve a specific value from the receipt snapshot.
     * Usage: $receipt->fromSnapshot('total_amount')
     * Usage: $receipt->fromSnapshot('location.name')
     */
    public function fromSnapshot(string $key): mixed
    {
        return data_get($this->snapshot, $key);
    }

    /**
     * Get the line items array from the snapshot.
     * These are the frozen items as they appeared at checkout.
     */
    public function snapshotItems(): array
    {
        return $this->fromSnapshot('items') ?? [];
    }

    /**
     * Get the business details from the snapshot.
     * Used when reprinting a receipt — pulls from the frozen
     * org details at time of sale, not current org data.
     */
    public function snapshotBusiness(): array
    {
        return $this->fromSnapshot('business') ?? [];
    }

    /**
     * Get the payments breakdown from the snapshot.
     */
    public function snapshotPayments(): array
    {
        return $this->fromSnapshot('payments') ?? [];
    }
}
