<?php
namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Models\Location;
use App\Models\Terminal;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TerminalSettingsController extends Controller
{
    /**
     * List terminals for a location.
     *
     * GET /locations/{location}/terminals
     */
    public function index(Location $location): JsonResponse
    {
        return response()->json([
            'terminals' => $location->terminals()->withCount('sales')->get(),
        ]);
    }

    /**
     * Create a terminal at a location.
     *
     * POST /locations/{location}/terminals
     */
    public function store(Request $request, Location $location): JsonResponse
    {
        $request->validate([
            'name'       => ['required', 'string', 'max:100'],
            'identifier' => ['sometimes', 'nullable', 'string', 'max:100'],
        ]);

        $terminal = $location->terminals()->create([
            'name'       => $request->name,
            'identifier' => $request->identifier,
            'is_active'  => true,
        ]);

        return response()->json([
            'message'  => 'Terminal created.',
            'terminal' => $terminal,
        ], 201);
    }

    /**
     * Update a terminal.
     *
     * PUT /locations/{location}/terminals/{terminal}
     */
    public function update(Request $request, Location $location, Terminal $terminal): JsonResponse
    {
        $request->validate([
            'name'       => ['sometimes', 'string', 'max:100'],
            'identifier' => ['sometimes', 'nullable', 'string'],
            'is_active'  => ['sometimes', 'boolean'],
        ]);

        $terminal->update($request->only('name', 'identifier', 'is_active'));

        return response()->json([
            'message'  => 'Terminal updated.',
            'terminal' => $terminal->fresh(),
        ]);
    }

    /**
     * Delete a terminal.
     *
     * DELETE /locations/{location}/terminals/{terminal}
     */
    public function destroy(Location $location, Terminal $terminal): JsonResponse
    {
        $terminal->delete();

        return response()->json(['message' => 'Terminal deleted.']);
    }
}
