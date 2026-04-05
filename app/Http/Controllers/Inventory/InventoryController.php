<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use App\Http\Requests\Inventory\AdjustInventoryRequest;
use App\Models\Location;
use App\Models\ProductVariant;
use App\Services\InventoryService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class InventoryController extends Controller
{
    public function __construct(
        protected InventoryService $inventoryService,
    ) {}

    /**
     * Get all inventory records for a location.
     * Supports filtering by low stock and out of stock.
     *
     * GET /locations/{location}/inventory
     */
    public function index(Request $request, Location $location): JsonResponse
    {
        $request->validate([
            'filter' => ['sometimes', 'string', 'in:low_stock,out_of_stock'],
        ]);

        $inventory = match ($request->filter) {
            'low_stock'    => $this->inventoryService->getLowStockItems($location),
            'out_of_stock' => $this->inventoryService->getOutOfStockItems($location),
            default        => \App\Models\Inventory::with(['variant.product'])
                                 ->where('location_id', $location->id)
                                 ->orderBy('quantity_on_hand')
                                 ->paginate(50),
        };

        return response()->json(['inventory' => $inventory]);
    }

    /**
     * Get the stock level for a single variant at a location.
     *
     * GET /locations/{location}/inventory/{variant}
     */
    public function show(Location $location, ProductVariant $variant): JsonResponse
    {
        $stock    = $this->inventoryService->getStock($variant, $location);
        $isLow    = $this->inventoryService->isLowStock($variant, $location);

        return response()->json([
            'variant'          => $variant->load('product'),
            'quantity_on_hand' => $stock,
            'is_low_stock'     => $isLow,
        ]);
    }

    /**
     * Manually adjust stock for a variant at a location.
     * Supports both increments (restock) and decrements (write-off).
     *
     * POST /locations/{location}/inventory/{variant}/adjust
     */
    public function adjust(
        AdjustInventoryRequest $request,
        Location               $location,
        ProductVariant         $variant,
    ): JsonResponse {
        $inventory = $request->type === 'increment'
            ? $this->inventoryService->increment($variant, $location, $request->quantity, $request->reason)
            : $this->inventoryService->decrement($variant, $location, $request->quantity, $request->reason);

        return response()->json([
            'message'   => 'Stock adjusted.',
            'inventory' => $inventory,
        ]);
    }

    /**
     * Transfer stock of a variant from one location to another.
     *
     * POST /inventory/transfer
     */
    public function transfer(Request $request): JsonResponse
    {
        $request->validate([
            'product_variant_id' => ['required', 'integer', 'exists:product_variants,id'],
            'from_location_id'   => ['required', 'integer', 'exists:locations,id'],
            'to_location_id'     => ['required', 'integer', 'exists:locations,id', 'different:from_location_id'],
            'quantity'           => ['required', 'integer', 'min:1'],
        ]);

        $variant  = ProductVariant::findOrFail($request->product_variant_id);
        $from     = Location::findOrFail($request->from_location_id);
        $to       = Location::findOrFail($request->to_location_id);

        $result = $this->inventoryService->transfer($variant, $from, $to, $request->quantity);

        return response()->json([
            'message' => "Transferred {$request->quantity} units from {$from->name} to {$to->name}.",
            'from'    => $result['from'],
            'to'      => $result['to'],
        ]);
    }

    /**
     * Update low stock threshold and reorder quantity for a variant
     * at a location.
     *
     * PATCH /locations/{location}/inventory/{variant}/thresholds
     */
    public function updateThresholds(
        Request        $request,
        Location       $location,
        ProductVariant $variant,
    ): JsonResponse {
        $request->validate([
            'low_stock_threshold' => ['required', 'integer', 'min:0'],
            'reorder_quantity'    => ['sometimes', 'integer', 'min:0'],
        ]);

        $inventory = $this->inventoryService->updateThresholds(
            $variant,
            $location,
            $request->low_stock_threshold,
            $request->reorder_quantity ?? 0,
        );

        return response()->json([
            'message'   => 'Thresholds updated.',
            'inventory' => $inventory,
        ]);
    }
}