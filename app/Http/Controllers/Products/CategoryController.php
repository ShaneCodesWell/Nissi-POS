<?php

namespace App\Http\Controllers\Products;

use App\Http\Controllers\Controller;
// use App\Http\Requests\Products\StoreCategoryRequest;
use App\Http\Requests\StoreCategoryRequest;
use App\Models\Category;
use App\Models\Organization;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    /**
     * Get the full category tree for an organization.
     * Returns top-level categories with their children nested.
     *
     * GET /organizations/{organization}/categories
     */
    public function index(Organization $organization): JsonResponse
    {
        $categories = $organization->categories()
                                   ->with('allChildren')
                                   ->whereNull('parent_id')
                                   ->where('is_active', true)
                                   ->orderBy('sort_order')
                                   ->get();

        return response()->json(['categories' => $categories]);
    }

    /**
     * Create a new category or sub-category.
     *
     * POST /organizations/{organization}/categories
     */
    public function store(StoreCategoryRequest $request, Organization $organization): JsonResponse
    {
        $category = $organization->categories()->create([
            'parent_id'   => $request->parent_id,
            'name'        => $request->name,
            'slug'        => Str::slug($request->name),
            'description' => $request->description,
            'image_path'  => $request->image_path,
            'sort_order'  => $request->sort_order ?? 0,
            'is_active'   => $request->boolean('is_active', true),
        ]);

        return response()->json([
            'message'  => 'Category created.',
            'category' => $category,
        ], 201);
    }

    /**
     * Update a category.
     *
     * PUT /organizations/{organization}/categories/{category}
     */
    public function update(
        StoreCategoryRequest $request,
        Organization         $organization,
        Category             $category,
    ): JsonResponse {
        $this->authoriseCategory($organization, $category);

        $category->update([
            'parent_id'   => $request->parent_id,
            'name'        => $request->name,
            'slug'        => Str::slug($request->name),
            'description' => $request->description,
            'image_path'  => $request->image_path,
            'sort_order'  => $request->sort_order ?? $category->sort_order,
            'is_active'   => $request->boolean('is_active', $category->is_active),
        ]);

        return response()->json([
            'message'  => 'Category updated.',
            'category' => $category->fresh('children'),
        ]);
    }

    /**
     * Soft delete a category.
     * Child categories are re-parented to null (become top-level).
     *
     * DELETE /organizations/{organization}/categories/{category}
     */
    public function destroy(Organization $organization, Category $category): JsonResponse
    {
        $this->authoriseCategory($organization, $category);

        // Orphan children rather than cascade-delete them
        $category->children()->update(['parent_id' => null]);
        $category->delete();

        return response()->json(['message' => 'Category deleted.']);
    }

    // -------------------------------------------------------------------------
    // Internal helpers
    // -------------------------------------------------------------------------

    protected function authoriseCategory(Organization $organization, Category $category): void
    {
        if ($category->organization_id !== $organization->id) {
            abort(404);
        }
    }
}