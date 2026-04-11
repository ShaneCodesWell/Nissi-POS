<?php
namespace App\Http\Controllers\Terminal;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TerminalSelectController extends Controller
{
    /**
     * Show the terminal selection screen.
     * Lists all active locations and their terminals for the logged-in cashier.
     *
     * GET /terminal
     */
    public function index(Request $request): View
    {
        $locations = $request->user()
            ->locations()
            ->wherePivot('is_active', true)
            ->with('activeTerminals')
            ->get();

        return view('terminal.select', compact('locations'));
    }
}
