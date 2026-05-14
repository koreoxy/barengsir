<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetActiveBranch
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Allow super admin to bypass branch selection if accessing superadmin routes
        if (auth()->check() && auth()->user()->isSuperAdmin() && $request->is('superadmin*')) {
            return $next($request);
        }

        // If user is authenticated but hasn't selected a branch
        if (auth()->check() && !session()->has('active_branch_id')) {
            // Except for the selection page itself
            if (!$request->routeIs('branch.select') && !$request->routeIs('branch.set')) {
                return redirect()->route('branch.select');
            }
        }

        return $next($request);
    }
}
