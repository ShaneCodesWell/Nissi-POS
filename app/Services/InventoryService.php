<?php
namespace App\Services;

use App\Models\Inventory;
use App\Models\Location;
use App\Models\ProductVariant;
use App\Models\SaleItems;
use Illuminate\Support\Facades\DB;

class InventoryService
{
    // -------------------------------------------------------------------------
    // Stock checks
    // -------------------------------------------------------------------------

    /**
     * Assert that sufficient stock exists for a variant at a location.
     * Throws a RuntimeException if stock is insufficient so the caller
     * (SaleService) can catch it and surface a clean error.
     *
     * @throws \RuntimeException if stock is insufficient
     */
    public function assertSufficientStock(
        ProductVariant $variant,
        Location $location,
        int $quantity,
    ): void {
        $available = $this->getStock($variant, $location);

        if ($available < $quantity) {
            throw new \RuntimeException(
                "Insufficient stock for \"{$variant->product->name}"
                . ($variant->name ? " ({$variant->name})" : '') . "\". "
                . "Available: {$available}, requested: {$quantity}."
            );
        }
    }

    /**
     * Get the current stock quantity for a variant at a location.
     * Returns 0 if no inventory record exists.
     */
    public function getStock(ProductVariant $variant, Location $location): int
    {
        return (int) Inventory::where('product_variant_id', $variant->id)
            ->where('location_id', $location->id)
            ->value('quantity_on_hand') ?? 0;
    }

    /**
     * Check if a variant is currently low on stock at a location.
     */
    public function isLowStock(ProductVariant $variant, Location $location): bool
    {
        $record = $this->getRecord($variant, $location);

        if (! $record) {
            return false;
        }

        return $record->quantity_on_hand <= $record->low_stock_threshold;
    }

    // -------------------------------------------------------------------------
    // Sale-driven decrements
    // -------------------------------------------------------------------------

    /**
     * Decrement stock for a single sale line item.
     * Called by SaleService::complete() for each item in the sale.
     * Always runs inside the parent transaction in SaleService.
     *
     * @throws \RuntimeException if stock is insufficient (via Inventory::decrementStock)
     */
    public function decrementForSaleItem(SaleItems $item, Location $location): void
    {
        $record = $this->getOrCreateRecord(
            $item->variant,
            $location
        );

        $record->decrementStock($item->quantity);
    }

    /**
     * Decrement stock directly by variant and quantity.
     * Used for manual stock adjustments from the inventory management UI.
     *
     * @throws \RuntimeException if stock is insufficient
     */
    public function decrement(
        ProductVariant $variant,
        Location $location,
        int $quantity,
        string $reason = '',
    ): Inventory {
        $record = $this->getOrCreateRecord($variant, $location);
        $record->decrementStock($quantity);

        return $record->fresh();
    }

    // -------------------------------------------------------------------------
    // Refund-driven increments
    // -------------------------------------------------------------------------

    /**
     * Restore stock for a single sale line item on refund.
     * Called by SaleService::refund() for each item in the sale.
     */
    public function incrementForRefund(SaleItems $item, Location $location): void
    {
        $record = $this->getOrCreateRecord($item->variant, $location);
        $record->incrementStock($item->quantity);
    }

    /**
     * Increment stock directly by variant and quantity.
     * Used when new stock is received at a location.
     */
    public function increment(
        ProductVariant $variant,
        Location $location,
        int $quantity,
        string $reason = '',
    ): Inventory {
        $record = $this->getOrCreateRecord($variant, $location);
        $record->incrementStock($quantity);

        return $record->fresh();
    }

    // -------------------------------------------------------------------------
    // Stock transfers between locations
    // -------------------------------------------------------------------------

    /**
     * Transfer stock from one location to another.
     * Runs as a single transaction — if either leg fails,
     * both legs roll back so stock is never lost or duplicated.
     *
     * @throws \RuntimeException if source location has insufficient stock
     */
    public function transfer(
        ProductVariant $variant,
        Location $fromLocation,
        Location $toLocation,
        int $quantity,
    ): array {
        $this->assertSufficientStock($variant, $fromLocation, $quantity);

        return DB::transaction(function () use ($variant, $fromLocation, $toLocation, $quantity) {
            $source      = $this->getOrCreateRecord($variant, $fromLocation);
            $destination = $this->getOrCreateRecord($variant, $toLocation);

            $source->decrementStock($quantity);
            $destination->incrementStock($quantity);

            return [
                'from' => $source->fresh(),
                'to'   => $destination->fresh(),
            ];
        });
    }

    // -------------------------------------------------------------------------
    // Threshold management
    // -------------------------------------------------------------------------

    /**
     * Update the low stock threshold and reorder quantity for a variant
     * at a specific location.
     */
    public function updateThresholds(
        ProductVariant $variant,
        Location $location,
        int $lowStockThreshold,
        int $reorderQuantity = 0,
    ): Inventory {
        $record = $this->getOrCreateRecord($variant, $location);

        $record->update([
            'low_stock_threshold' => $lowStockThreshold,
            'reorder_quantity'    => $reorderQuantity,
        ]);

        return $record->fresh();
    }

    /**
     * Get all inventory records that are at or below their low stock
     * threshold for a given location.
     * Used to power the low stock alerts dashboard.
     */
    public function getLowStockItems(Location $location): \Illuminate\Database\Eloquent\Collection
    {
        return Inventory::with(['variant.product'])
            ->where('location_id', $location->id)
            ->whereColumn('quantity_on_hand', '<=', 'low_stock_threshold')
            ->orderBy('quantity_on_hand')
            ->get();
    }

    /**
     * Get all out-of-stock inventory records for a location.
     */
    public function getOutOfStockItems(Location $location): \Illuminate\Database\Eloquent\Collection
    {
        return Inventory::with(['variant.product'])
            ->where('location_id', $location->id)
            ->where('quantity_on_hand', '<=', 0)
            ->get();
    }

    // -------------------------------------------------------------------------
    // Internal helpers
    // -------------------------------------------------------------------------

    /**
     * Get an inventory record for a variant at a location, or null if none exists.
     */
    protected function getRecord(
        ProductVariant $variant,
        Location $location,
    ): ?Inventory {
        return Inventory::where('product_variant_id', $variant->id)
            ->where('location_id', $location->id)
            ->first();
    }

    /**
     * Get an inventory record for a variant at a location.
     * Creates one with zero stock if it does not exist yet.
     * This ensures every variant always has an inventory record
     * at every location it is referenced from.
     */
    protected function getOrCreateRecord(
        ProductVariant $variant,
        Location $location,
    ): Inventory {
        return Inventory::firstOrCreate(
            [
                'product_variant_id' => $variant->id,
                'location_id'        => $location->id,
            ],
            [
                'quantity_on_hand'    => 0,
                'low_stock_threshold' => 5,
                'reorder_quantity'    => 0,
            ]
        );
    }
}
