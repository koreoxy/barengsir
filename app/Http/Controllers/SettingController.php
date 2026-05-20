<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Setting;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class SettingController extends Controller
{
    public function index()
    {
        $branch = \App\Models\Branch::with('vendor')->find(session('active_branch_id'));
        $vendor = $branch ? $branch->vendor : null;
        
        return view('settings.index', compact('branch', 'vendor'));
    }

    public function updateStore(Request $request)
    {
        $request->validate([
            // Data Toko (Vendor)
            'vendor_name'    => 'required|string|max:255',
            'vendor_email'   => 'required|string|email|max:255',
            'vendor_phone'   => 'required|string|max:20',
            'vendor_address' => 'required|string|max:500',
            
            // Data Cabang (Branch)
            'branch_name'    => 'required|string|max:255',
            'branch_phone'   => 'required|string|max:20',
            'branch_address' => 'required|string|max:500',
        ]);

        $branch = \App\Models\Branch::with('vendor')->find(session('active_branch_id'));
        
        if ($branch) {
            \Illuminate\Support\Facades\DB::transaction(function () use ($request, $branch) {
                // A. Update data Cabang Aktif
                $branch->update([
                    'name'    => $request->branch_name,
                    'phone'   => $request->branch_phone,
                    'address' => $request->branch_address,
                ]);

                // B. Update data Toko/Vendor Induk
                if ($branch->vendor) {
                    $branch->vendor->update([
                        'name'    => $request->vendor_name,
                        'email'   => $request->vendor_email,
                        'phone'   => $request->vendor_phone,
                        'address' => $request->vendor_address,
                    ]);
                }

                // C. Sinkronkan Session agar perubahan langsung terlihat di UI
                session(['active_branch_name' => $branch->name]);
                session(['active_vendor_name' => $branch->vendor->name]);
            });
        }

        return redirect()->back()->with('success', 'Informasi toko dan cabang berhasil diperbarui.');
    }

    public function updateAccount(Request $request)
    {
        $user = $request->user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'current_password' => 'nullable|required_with:password|current_password',
            'password' => ['nullable', Password::defaults(), 'confirmed'],
        ]);

        $user->name = $request->name;
        $user->email = $request->email;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return redirect()->back()->with('success', 'Pengaturan akun berhasil diperbarui.');
    }
}
