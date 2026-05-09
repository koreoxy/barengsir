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
        if (!auth()->check() || auth()->user()->role !== $role) {
            if (auth()->check()) {
                // User is logged in but wrong role
                if (auth()->user()->role === 'admin') {
                    return redirect()->route('dashboard');
                }
                return redirect()->route('home');
            }
            return redirect()->route('login');
        }

        return $next($request);
    }
}
