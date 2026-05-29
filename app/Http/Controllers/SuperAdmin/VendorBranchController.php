<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Vendor;
use App\Models\Branch;
use App\Http\Requests\SuperAdmin\StoreVendorBranchRequest;
use Illuminate\Support\Facades\DB;

class VendorBranchController extends Controller
{
    /**
     * Show the form for creating a new resource.
     */
    public function create(Vendor $vendor)
    {
        return view('superadmin.vendors.branches.create', compact('vendor'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreVendorBranchRequest $request, Vendor $vendor)
    {
        try {
            $validated = $request->validated();
            
            $vendor->branches()->create($validated);

            return redirect()
                ->route('superadmin.vendors.show', $vendor)
                ->with('success', 'Cabang vendor berhasil ditambahkan.');

        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Gagal menambahkan cabang: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Vendor $vendor, Branch $branch)
    {
        return view('superadmin.vendors.branches.edit', compact('vendor', 'branch'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreVendorBranchRequest $request, Vendor $vendor, Branch $branch)
    {
        try {
            $validated = $request->validated();
            
            $branch->update($validated);

            return redirect()
                ->route('superadmin.vendors.show', $vendor)
                ->with('success', 'Data cabang vendor berhasil diperbarui.');

        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Gagal memperbarui cabang: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Vendor $vendor, Branch $branch)
    {
        try {
            // Safety Check 1: Check for existing transactions
            $hasTransactions = \App\Models\Transaction::withoutGlobalScopes()
                ->where('branch_id', $branch->id)
                ->exists();

            if ($hasTransactions) {
                return redirect()
                    ->route('superadmin.vendors.show', $vendor)
                    ->with('error', 'Cabang tidak bisa dihapus karena memiliki riwayat transaksi aktif.');
            }

            // Safety Check 2: Check for registered personnel
            $hasUsers = \App\Models\VendorUser::where('branch_id', $branch->id)->exists();
            if ($hasUsers) {
                return redirect()
                    ->route('superadmin.vendors.show', $vendor)
                    ->with('error', 'Cabang tidak bisa dihapus karena masih memiliki tim/staf yang ditugaskan.');
            }

            $branch->delete();

            return redirect()
                ->route('superadmin.vendors.show', $vendor)
                ->with('success', 'Cabang vendor berhasil dihapus.');

        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Gagal menghapus cabang: ' . $e->getMessage());
        }
    }
}
