<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Vendor;
use App\Models\User;
use App\Http\Requests\SuperAdmin\StoreVendorUserRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class VendorUserController extends Controller
{
    /**
     * Show the form for creating a new resource.
     */
    public function create(Vendor $vendor)
    {
        $branches = $vendor->branches()->where('is_active', true)->get();
        return view('superadmin.vendors.users.create', compact('vendor', 'branches'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreVendorUserRequest $request, Vendor $vendor)
    {
        $validated = $request->validated();

        try {
            DB::transaction(function () use ($validated, $vendor) {
                // 1. Create global user with role 'user'
                $user = User::create([
                    'name' => $validated['name'],
                    'email' => $validated['email'],
                    'password' => Hash::make($validated['password']),
                    'role' => 'user',
                ]);

                // 2. Attach relationship to vendor via vendor_users pivot
                $vendor->users()->attach($user->id, [
                    'branch_id' => $validated['role'] === 'owner' ? null : $validated['branch_id'],
                    'role' => $validated['role'],
                    'is_active' => $validated['is_active'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            });

            return redirect()
                ->route('superadmin.vendors.show', $vendor)
                ->with('success', 'Akun pengguna vendor berhasil dibuat.');

        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Gagal membuat akun: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Vendor $vendor, User $user)
    {
        try {
            DB::transaction(function () use ($vendor, $user) {
                // 1. Detach pivot relation
                $vendor->users()->detach($user->id);

                // 2. Safely delete the user record to avoid orphans in the system
                $user->delete();
            });

            return redirect()
                ->route('superadmin.vendors.show', $vendor)
                ->with('success', 'Akun pengguna vendor berhasil dihapus.');

        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Gagal menghapus akun pengguna: ' . $e->getMessage());
        }
    }
}
