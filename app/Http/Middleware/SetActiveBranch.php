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
        if (auth()->check() && !auth()->user()->isSuperAdmin()) {
            if (!session()->has('active_branch_id')) {
                // Except for the selection page itself
                if (!$request->routeIs('branch.select') && !$request->routeIs('branch.set')) {
                    if ($request->expectsJson() || $request->ajax()) {
                        return response()->json(['success' => false, 'message' => 'Sesi branch tidak aktif. Silakan pilih branch terlebih dahulu.'], 403);
                    }
                    return redirect()->route('branch.select');
                }
            } else {
                // Set timezone dinamis per branch
                $branchId = session('active_branch_id');
                $timezone = \App\Services\SettingService::get('store_timezone', 'Asia/Jakarta', $branchId);
                date_default_timezone_set($timezone);
                config(['app.timezone' => $timezone]);

                // If branch is selected, check if vendor is still active
                $vendorId = session('active_vendor_id');
                $vendorActive = \App\Models\Vendor::where('id', $vendorId)->where('is_active', true)->exists();
                
                if (!$vendorActive) {
                    auth()->logout();
                    session()->invalidate();
                    session()->regenerateToken();
                    return redirect()->route('login')->with('error', 'Akun bisnis Anda sedang ditangguhkan. Silakan hubungi administrator.');
                }
            }
        }

        return $next($request);
    }
}
