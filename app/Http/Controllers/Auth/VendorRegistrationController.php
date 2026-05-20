<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\VendorRegisterRequest;
use App\Models\Branch;
use App\Models\User;
use App\Models\Vendor;
use App\Models\VendorUser;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class VendorRegistrationController extends Controller
{
    /**
     * Tampilkan halaman registrasi vendor.
     */
    public function create(): \Illuminate\View\View
    {
        return view('auth.register');
    }

    /**
     * Memproses pendaftaran vendor baru beserta cabang pertamanya.
     */
    public function store(VendorRegisterRequest $request): RedirectResponse
    {
        // Mendapatkan data yang telah tervalidasi secara aman
        $validated = $request->validated();

        // Menggunakan Database Transaction untuk menjamin atomisitas data
        DB::transaction(function () use ($validated) {
            // 1. Buat Akun User Owner (Role default: admin di level sistem)
            $user = User::create([
                'name'     => $validated['name'],
                'email'    => $validated['email'],
                'password' => Hash::make($validated['password']),
                'role'     => 'admin',
            ]);

            // 2. Buat Entitas Bisnis / Vendor
            $vendor = Vendor::create([
                'name'    => $validated['business_name'],
                'slug'    => Str::slug($validated['business_name']) . '-' . Str::lower(Str::random(4)),
                'email'   => $validated['email'],
                'phone'   => $validated['business_phone'],
                'address' => $validated['business_address'],
            ]);

            // 3. Buat Cabang Utama Pertama (Sinkronisasi detail alamat & telepon bisnis)
            $branch = Branch::create([
                'vendor_id' => $vendor->id,
                'name'      => $validated['branch_name'],
                'code'      => strtoupper(Str::random(6)),
                'phone'     => $validated['business_phone'],     // Sinkronisasi nomor telepon
                'address'   => $validated['business_address'],   // Sinkronisasi alamat fisik
            ]);

            // 4. Buat Pivot VendorUser (Pemilik memiliki role 'owner' dengan akses multi-branch)
            VendorUser::create([
                'user_id'   => $user->id,
                'vendor_id' => $vendor->id,
                'branch_id' => null, // null berarti memiliki hak akses ke semua cabang
                'role'      => 'owner',
            ]);

            event(new Registered($user));
        });

        return redirect()->route('login')
            ->with('success', 'Pendaftaran vendor berhasil! Silakan login untuk mengelola bisnis Anda.');
    }
}

