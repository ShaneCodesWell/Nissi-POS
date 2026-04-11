<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsAdmin
{
    /**
     * Redirect non-admin users away from admin routes.
     * Logged-in cashiers get a 403. Guests get sent to login.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (! Auth::check()) {
            return redirect()->route('login')
                ->with('error', 'Please log in to continue.');
        }

        if (! Auth::user()->isAdmin()) {
            abort(403, 'You do not have permission to access this area.');
        }

        return $next($request);
    }
}
