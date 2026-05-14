<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class SuperAdminSessionController extends Controller
{
    public function create(): \Illuminate\View\View
    {
        return view('auth.superadmin-login');
    }

    public function store(Request $request): \Illuminate\Http\RedirectResponse
    {
        $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);

        // Coba login
        if (!Auth::attempt($request->only('email', 'password'), $request->boolean('remember'))) {
            throw ValidationException::withMessages([
                'email' => __('auth.failed'),
            ]);
        }

        // Verifikasi bahwa user yang login benar-benar super_admin
        if (Auth::user()->role !== 'super_admin') {
            Auth::logout();
            $request->session()->invalidate();
            throw ValidationException::withMessages([
                'email' => 'Akun ini tidak memiliki akses Super Admin.',
            ]);
        }

        $request->session()->regenerate();

        return redirect()->intended(route('superadmin.dashboard'));
    }

    public function destroy(Request $request): \Illuminate\Http\RedirectResponse
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect()->route('superadmin.login');
    }
}
