<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Setting;
use App\Services\SettingService;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
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

    public function updateSystemSettings(Request $request)
    {
        $branchId = session('active_branch_id');
        if (!$branchId) {
            return redirect()->back()->with('error', 'Cabang aktif tidak ditemukan.');
        }

        // 1. Validasi Input Pengaturan Sistem
        $request->validate([
            // General Settings
            'store_logo'      => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'store_currency'  => 'required|string|in:IDR,USD,SGD,EUR',
            'store_timezone'  => 'required|string|in:Asia/Jakarta,Asia/Makassar,Asia/Jayapura,UTC',
            
            // POS Settings
            'default_tax'     => 'required|numeric|min:0|max:100',
            'default_printer' => 'nullable|string|max:100',
            'receipt_format'  => 'required|string|in:58mm,80mm,A4',
        ]);

        $settingsData = [
            'store_currency'  => $request->store_currency,
            'store_timezone'  => $request->store_timezone,
            'default_tax'     => $request->default_tax,
            'default_printer' => $request->default_printer,
            'receipt_format'  => $request->receipt_format,
        ];

        // 2. Penanganan Upload File Logo Toko
        if ($request->hasFile('store_logo')) {
            $file = $request->file('store_logo');
            
            // Dapatkan logo lama untuk dihapus
            $oldLogoPath = SettingService::get('store_logo', null, $branchId);
            if ($oldLogoPath && Storage::disk('public')->exists($oldLogoPath)) {
                Storage::disk('public')->delete($oldLogoPath);
            }

            // Simpan logo baru secara terorganisir
            $filename = 'logo_' . $branchId . '_' . time() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('logos', $filename, 'public');
            
            $settingsData['store_logo'] = $path;
        }

        // 3. Batch Update dengan Service Class
        SettingService::setMany($settingsData, $branchId);

        return redirect()->back()->with('success', 'Pengaturan sistem berhasil disimpan.');
    }
}
