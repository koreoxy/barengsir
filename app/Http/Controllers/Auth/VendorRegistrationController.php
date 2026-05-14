<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\User;
use App\Models\Vendor;
use App\Models\VendorUser;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password;

class VendorRegistrationController extends Controller
{
    public function create(): \Illuminate\View\View
    {
        return view('auth.register');
    }

    public function store(Request $request): \Illuminate\Http\RedirectResponse
    {
        // Validasi semua field
        $request->validate([
            // Data User (owner)
            'name'                  => ['required', 'string', 'max:255'],
            'email'                 => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users'],
            'password'              => ['required', 'confirmed', Password::defaults()],
            // Data Bisnis
            'business_name'         => ['required', 'string', 'max:255'],
            'business_phone'        => ['nullable', 'string', 'max:20'],
            'business_address'      => ['nullable', 'string', 'max:500'],
            // Data Cabang Pertama
            'branch_name'           => ['required', 'string', 'max:255'],
        ]);

        // Bungkus dalam DB Transaction agar atomik (jika satu gagal, semua rollback)
        DB::transaction(function () use ($request) {
            // 1. Buat User
            $user = User::create([
                'name'     => $request->name,
                'email'    => $request->email,
                'password' => Hash::make($request->password),
                'role'     => 'admin', // owner adalah admin di level sistem
            ]);

            // 2. Buat Vendor
            $vendor = Vendor::create([
                'name'    => $request->business_name,
                'slug'    => Str::slug($request->business_name) . '-' . Str::random(4),
                'email'   => $request->email,
                'phone'   => $request->business_phone,
                'address' => $request->business_address,
            ]);

            // 3. Buat Cabang Pertama
            $branch = Branch::create([
                'vendor_id' => $vendor->id,
                'name'      => $request->branch_name,
                'code'      => strtoupper(Str::random(6)),
            ]);

            // 4. Buat pivot VendorUser (owner di level vendor, bukan per cabang)
            VendorUser::create([
                'user_id'   => $user->id,
                'vendor_id' => $vendor->id,
                'branch_id' => null, // owner punya akses ke semua cabang
                'role'      => 'owner',
            ]);

            event(new Registered($user));
        });

        return redirect()->route('login')
            ->with('success', 'Pendaftaran vendor berhasil! Silakan login untuk memulai.');
    }
}
