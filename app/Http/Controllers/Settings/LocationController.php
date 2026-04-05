<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreLocationRequest;
use App\Models\Location;
use App\Models\Organization;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class LocationController extends Controller
{
    /**
     * List all locations for an organization.
     *
     * GET /organizations/{organization}/locations
     */
    public function index(Organization $organization): JsonResponse
    {
        $locations = $organization->locations()
                                  ->withCount(['terminals', 'activeUsers'])
                                  ->orderBy('name')
                                  ->get();

        return response()->json(['locations' => $locations]);
    }

    /**
     * Create a new location.
     *
     * POST /organizations/{organization}/locations
     */
    public function store(StoreLocationRequest $request, Organization $organization): JsonResponse
    {
        $location = $organization->locations()->create([
            'name'      => $request->name,
            'code'      => strtoupper($request->code),
            'address'   => $request->address,
            'city'      => $request->city,
            'phone'     => $request->phone,
            'email'     => $request->email,
            'is_active' => $request->boolean('is_active', true),
        ]);

        return response()->json([
            'message'  => 'Location created.',
            'location' => $location,
        ], 201);
    }

    /**
     * Update a location.
     *
     * PUT /organizations/{organization}/locations/{location}
     */
    public function update(
        StoreLocationRequest $request,
        Organization         $organization,
        Location             $location,
    ): JsonResponse {
        $this->authoriseLocation($organization, $location);

        $location->update($request->validated());

        return response()->json([
            'message'  => 'Location updated.',
            'location' => $location->fresh(),
        ]);
    }

    /**
     * Assign a user to a location with a role.
     *
     * POST /organizations/{organization}/locations/{location}/users
     */
    public function assignUser(
        Request      $request,
        Organization $organization,
        Location     $location,
    ): JsonResponse {
        $this->authoriseLocation($organization, $location);

        $request->validate([
            'user_id' => ['required', 'integer', 'exists:users,id'],
            'role'    => ['required', 'string'],
        ]);

        $user = User::findOrFail($request->user_id);

        // Sync the pivot — updates role if already assigned
        $location->users()->syncWithoutDetaching([
            $user->id => [
                'role'      => $request->role,
                'is_active' => true,
            ],
        ]);

        return response()->json(['message' => "{$user->name} assigned to {$location->name}."]);
    }

    /**
     * Remove a user from a location.
     *
     * DELETE /organizations/{organization}/locations/{location}/users/{user}
     */
    public function removeUser(
        Organization $organization,
        Location     $location,
        User         $user,
    ): JsonResponse {
        $this->authoriseLocation($organization, $location);

        $location->users()->detach($user->id);

        return response()->json(['message' => "{$user->name} removed from {$location->name}."]);
    }

    /**
     * Soft delete a location.
     *
     * DELETE /organizations/{organization}/locations/{location}
     */
    public function destroy(Organization $organization, Location $location): JsonResponse
    {
        $this->authoriseLocation($organization, $location);

        $location->delete();

        return response()->json(['message' => 'Location deleted.']);
    }

    // -------------------------------------------------------------------------
    // Internal helpers
    // -------------------------------------------------------------------------

    protected function authoriseLocation(Organization $organization, Location $location): void
    {
        if ($location->organization_id !== $organization->id) {
            abort(404);
        }
    }
}