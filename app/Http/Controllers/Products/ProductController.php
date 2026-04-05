<?php
namespace App\Http\Controllers\Products;

use App\Http\Controllers\Controller;
use App\Http\Requests\Products\StoreProductRequest;
use App\Models\Organization;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    /**
     * Paginated product list for an organization.
     * Supports filtering by category and active status.
     *
     * GET /organizations/{organization}/products
     */
    public function index(Request $request, Organization $organization): JsonResponse
    {
        $request->validate([
            'category_id' => ['sometimes', 'integer', 'exists:categories,id'],
            'active_only' => ['sometimes', 'boolean'],
            'search'      => ['sometimes', 'string', 'min:2'],
            'per_page'    => ['sometimes', 'integer', 'min:10', 'max:100'],
        ]);

        $query = $organization->products()
            ->with(['category', 'activeVariants'])
            ->orderBy('name');

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->boolean('active_only', true)) {
            $query->where('is_active', true);
        }

        if ($request->filled('search')) {
            $query->where('name', 'like', "%{$request->search}%");
        }

        return response()->json($query->paginate($request->per_page ?? 25));
    }

    /**
     * Get a single product with all its variants.
     *
     * GET /organizations/{organization}/products/{product}
     */
    public function show(Organization $organization, Product $product): JsonResponse
    {
        $this->authoriseProduct($organization, $product);

        return response()->json([
            'product' => $product->load(['category', 'variants']),
        ]);
    }

    /**
     * Create a new product with its initial variants.
     *
     * POST /organizations/{organization}/products
     */
    public function store(StoreProductRequest $request, Organization $organization): JsonResponse
    {
        $product = $organization->products()->create([
            'category_id'  => $request->category_id,
            'name'         => $request->name,
            'slug'         => Str::slug($request->name),
            'description'  => $request->description,
            'image_path'   => $request->image_path,
            'has_variants' => count($request->variants) > 1,
            'is_active'    => $request->boolean('is_active', true),
        ]);

        // Create the variants — at least one is always required
        foreach ($request->variants as $variantData) {
            $product->variants()->create([
                'name'       => $variantData['name'] ?? null,
                'sku'        => $variantData['sku'],
                'barcode'    => $variantData['barcode'] ?? null,
                'price'      => $variantData['price'],
                'cost_price' => $variantData['cost_price'] ?? 0,
                'attributes' => $variantData['attributes'] ?? null,
                'is_active'  => true,
            ]);
        }

        return response()->json([
            'message' => 'Product created.',
            'product' => $product->load('variants'),
        ], 201);
    }

    /**
     * Update a product's details.
     * Variant updates are handled separately via their own endpoints.
     *
     * PUT /organizations/{organization}/products/{product}
     */
    public function update(
        StoreProductRequest $request,
        Organization $organization,
        Product $product,
    ): JsonResponse {
        $this->authoriseProduct($organization, $product);

        $product->update([
            'category_id' => $request->category_id,
            'name'        => $request->name,
            'slug'        => Str::slug($request->name),
            'description' => $request->description,
            'image_path'  => $request->image_path,
            'is_active'   => $request->boolean('is_active', $product->is_active),
        ]);

        return response()->json([
            'message' => 'Product updated.',
            'product' => $product->fresh(['category', 'variants']),
        ]);
    }

    /**
     * Soft delete a product and all its variants.
     *
     * DELETE /organizations/{organization}/products/{product}
     */
    public function destroy(Organization $organization, Product $product): JsonResponse
    {
        $this->authoriseProduct($organization, $product);

        // Soft delete all variants first to deactivate them at the terminal
        $product->variants()->update(['is_active' => false]);
        $product->variants()->delete();
        $product->delete();

        return response()->json(['message' => 'Product deleted.']);
    }

    // -------------------------------------------------------------------------
    // Internal helpers
    // -------------------------------------------------------------------------

    protected function authoriseProduct(Organization $organization, Product $product): void
    {
        if ($product->organization_id !== $organization->id) {
            abort(404);
        }
    }
}
