<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Location;
use App\Models\Terminal;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthController extends Controller
{
    // -------------------------------------------------------------------------
    // Login
    // -------------------------------------------------------------------------
 
    /**
     * Show the login form.
     * Redirect away if already authenticated.
     *
     * GET /login
     */
    public function showLogin(): View|RedirectResponse
    {
        if (Auth::check()) {
            return $this->redirectAfterLogin(Auth::user());
        }
 
        return view('auth.login');
    }
 
    /**
     * Handle login form submission.
     *
     * POST /login
     */
    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);
 
        if (! Auth::attempt($credentials, $request->boolean('remember'))) {
            return back()
                ->withInput($request->only('email'))
                ->withErrors(['email' => 'These credentials do not match our records.']);
        }
 
        $request->session()->regenerate();
 
        return $this->redirectAfterLogin(Auth::user());
    }
 
    /**
     * Log the user out.
     *
     * POST /logout
     */
    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
 
        $request->session()->invalidate();
        $request->session()->regenerateToken();
 
        return redirect()->route('login');
    }
 
    // -------------------------------------------------------------------------
    // Internal helpers
    // -------------------------------------------------------------------------
 
    /**
     * Redirect the user to the correct area after login based on their role.
     *
     * Admins    → /admin dashboard
     * Cashiers  → terminal selection if multiple locations,
     *             or directly to their terminal if only one
     */
    protected function redirectAfterLogin($user): RedirectResponse
    {
        if ($user->isAdmin()) {
            return redirect()->route('admin.index');
        }
 
        // For cashiers — send them to terminal selection
        // If they only have one location with one terminal, go straight there
        $locations = $user->locations()->wherePivot('is_active', true)->with('activeTerminals')->get();
 
        if ($locations->count() === 1) {
            $terminals = $locations->first()->activeTerminals;
 
            if ($terminals->count() === 1) {
                return redirect()->route('pos.index', $terminals->first());
            }
        }
 
        // Multiple locations or terminals — send to selection page
        return redirect()->route('terminal.select');
    }
}
