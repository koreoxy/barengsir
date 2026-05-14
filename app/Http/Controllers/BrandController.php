<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class BrandController extends Controller
{
    public function index()
    {
        $brands = Brand::withCount('products')->latest()->paginate(10);
        return view('brand.index', compact('brands'));
    }

    public function create()
    {
        return view('brand.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => [
                'required', 
                'string', 
                'max:255', 
                Rule::unique('brands')->where(fn ($query) => $query->where('branch_id', session('active_branch_id')))
            ],
        ]);

        $validated['slug'] = Str::slug($validated['name']);

        Brand::create($validated);

        return redirect()->route('brand.index')->with('success', 'Brand berhasil ditambahkan.');
    }

    public function edit(Brand $brand)
    {
        return view('brand.edit', compact('brand'));
    }

    public function update(Request $request, Brand $brand)
    {
        $validated = $request->validate([
            'name' => [
                'required', 
                'string', 
                'max:255', 
                Rule::unique('brands')->ignore($brand->id)->where(fn ($query) => $query->where('branch_id', session('active_branch_id')))
            ],
        ]);

        $validated['slug'] = Str::slug($validated['name']);

        $brand->update($validated);

        return redirect()->route('brand.index')->with('success', 'Brand berhasil diperbarui.');
    }

    public function destroy(Brand $brand)
    {
        // Check if brand has products
        if ($brand->products()->count() > 0) {
            return redirect()->route('brand.index')->with('error', 'Brand tidak dapat dihapus karena masih memiliki produk terkait.');
        }

        $brand->delete();

        return redirect()->route('brand.index')->with('success', 'Brand berhasil dihapus.');
    }
}
