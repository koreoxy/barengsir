<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        if (!auth()->check()) {
            // Check if this is a superadmin route to redirect to correct login
            if ($request->is('superadmin*')) {
                return redirect()->route('superadmin.login');
            }
            return redirect()->route('login');
        }

        if (auth()->user()->role !== $role) {
            // User is logged in but wrong role
            if (auth()->user()->isSuperAdmin()) {
                return redirect()->route('superadmin.dashboard');
            }

            if (auth()->user()->role === 'admin') {
                return redirect()->route('dashboard');
            }
            
            return redirect()->route('home');
        }

        return $next($request);
    }
}
