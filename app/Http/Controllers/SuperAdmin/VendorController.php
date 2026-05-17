<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class VendorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Vendor::withCount(['branches', 'users']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }

        $vendors = $query->latest()->paginate(15)->withQueryString();

        return view('superadmin.vendors.index', compact('vendors'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('superadmin.vendors.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:vendors',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
        ]);

        $validated['slug'] = Str::slug($validated['name']) . '-' . Str::random(4);
        $validated['is_active'] = true;

        Vendor::create($validated);

        return redirect()->route('superadmin.vendors.index')->with('success', 'Vendor berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Vendor $vendor)
    {
        $vendor->load(['branches', 'users']);
        
        // Statistik vendor
        $totalRevenue = \App\Models\Transaction::withoutGlobalScopes()
            ->whereIn('branch_id', $vendor->branches->pluck('id'))
            ->sum('total_amount');

        $totalTransactions = \App\Models\Transaction::withoutGlobalScopes()
            ->whereIn('branch_id', $vendor->branches->pluck('id'))
            ->count();

        return view('superadmin.vendors.show', compact('vendor', 'totalRevenue', 'totalTransactions'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Vendor $vendor)
    {
        return view('superadmin.vendors.edit', compact('vendor'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Vendor $vendor)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('vendors')->ignore($vendor->id)],
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'is_active' => 'required|boolean',
        ]);

        $vendor->update($validated);

        return redirect()->route('superadmin.vendors.index')->with('success', 'Data vendor berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Vendor $vendor)
    {
        // Cek jika vendor punya data transaksi (mencegah hapus data penting)
        $hasTransactions = \App\Models\Transaction::withoutGlobalScopes()
            ->whereIn('branch_id', $vendor->branches->pluck('id'))
            ->exists();

        if ($hasTransactions) {
            return redirect()->route('superadmin.vendors.index')->with('error', 'Vendor tidak bisa dihapus karena memiliki riwayat transaksi.');
        }

        $vendor->delete();

        return redirect()->route('superadmin.vendors.index')->with('success', 'Vendor berhasil dihapus.');
    }
}
