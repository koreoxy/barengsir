<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class BranchController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (session('active_role') !== 'owner') {
            abort(403, 'Hanya Owner yang dapat mengelola cabang.');
        }

        $branches = Branch::where('vendor_id', session('active_vendor_id'))->get();
        return view('vendor.branches.index', compact('branches'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if (session('active_role') !== 'owner') {
            abort(403, 'Hanya Owner yang dapat mengelola cabang.');
        }

        return view('vendor.branches.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if (session('active_role') !== 'owner') {
            abort(403, 'Hanya Owner yang dapat mengelola cabang.');
        }

        $vendorId = session('active_vendor_id');
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'code' => [
                'required',
                'string',
                'max:50',
                Rule::unique('branches', 'code'),
            ],
            'phone' => ['nullable', 'string', 'max:20'],
            'address' => ['nullable', 'string', 'max:500'],
            'is_active' => ['required', 'boolean'],
        ], [
            'code.unique' => 'Kode cabang ini sudah digunakan oleh cabang lain.',
        ]);

        $validated['vendor_id'] = $vendorId;
        Branch::create($validated);

        return redirect()
            ->route('vendor.branches.index')
            ->with('success', 'Cabang berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Branch $branch)
    {
        if (session('active_role') !== 'owner') {
            abort(403, 'Hanya Owner yang dapat mengelola cabang.');
        }

        if ($branch->vendor_id !== session('active_vendor_id')) {
            abort(403, 'Cabang ini tidak dimiliki oleh vendor Anda.');
        }

        abort(404); // No specific show page needed, redirect to index
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Branch $branch)
    {
        if (session('active_role') !== 'owner') {
            abort(403, 'Hanya Owner yang dapat mengelola cabang.');
        }

        if ($branch->vendor_id !== session('active_vendor_id')) {
            abort(403, 'Cabang ini tidak dimiliki oleh vendor Anda.');
        }

        return view('vendor.branches.edit', compact('branch'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Branch $branch)
    {
        if (session('active_role') !== 'owner') {
            abort(403, 'Hanya Owner yang dapat mengelola cabang.');
        }

        if ($branch->vendor_id !== session('active_vendor_id')) {
            abort(403, 'Cabang ini tidak dimiliki oleh vendor Anda.');
        }

        $vendorId = session('active_vendor_id');
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'code' => [
                'required',
                'string',
                'max:50',
                Rule::unique('branches', 'code')
                    ->ignore($branch->id),
            ],
            'phone' => ['nullable', 'string', 'max:20'],
            'address' => ['nullable', 'string', 'max:500'],
            'is_active' => ['required', 'boolean'],
        ], [
            'code.unique' => 'Kode cabang ini sudah digunakan oleh cabang lain.',
        ]);

        $branch->update($validated);

        return redirect()
            ->route('vendor.branches.index')
            ->with('success', 'Cabang berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Branch $branch)
    {
        if (session('active_role') !== 'owner') {
            abort(403, 'Hanya Owner yang dapat mengelola cabang.');
        }

        if ($branch->vendor_id !== session('active_vendor_id')) {
            abort(403, 'Cabang ini tidak dimiliki oleh vendor Anda.');
        }

        // Safety Check 1: Check for existing transactions
        $hasTransactions = \App\Models\Transaction::withoutGlobalScopes()
            ->where('branch_id', $branch->id)
            ->exists();

        if ($hasTransactions) {
            return redirect()
                ->route('vendor.branches.index')
                ->with('error', 'Cabang tidak bisa dihapus karena memiliki riwayat transaksi aktif.');
        }

        // Safety Check 2: Check for registered personnel
        $hasUsers = \App\Models\VendorUser::where('branch_id', $branch->id)->exists();
        if ($hasUsers) {
            return redirect()
                ->route('vendor.branches.index')
                ->with('error', 'Cabang tidak bisa dihapus karena masih memiliki tim/staf yang ditugaskan.');
        }

        $branch->delete();

        return redirect()
            ->route('vendor.branches.index')
            ->with('success', 'Cabang berhasil dihapus.');
    }
}
