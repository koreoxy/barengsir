<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::withCount('products')->latest()->paginate(10);
        return view('category.index', compact('categories'));
    }

    public function create()
    {
        return view('category.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => [
                'required', 
                'string', 
                'max:255', 
                Rule::unique('categories')->where(fn ($query) => $query->where('branch_id', session('active_branch_id')))
            ],
        ]);

        $validated['slug'] = Str::slug($validated['name']);

        Category::create($validated);

        return redirect()->route('category.index')->with('success', 'Kategori berhasil ditambahkan.');
    }

    public function edit(Category $category)
    {
        return view('category.edit', compact('category'));
    }

    public function update(Request $request, Category $category)
    {
        $validated = $request->validate([
            'name' => [
                'required', 
                'string', 
                'max:255', 
                Rule::unique('categories')->ignore($category->id)->where(fn ($query) => $query->where('branch_id', session('active_branch_id')))
            ],
        ]);

        $validated['slug'] = Str::slug($validated['name']);

        $category->update($validated);

        return redirect()->route('category.index')->with('success', 'Kategori berhasil diperbarui.');
    }

    public function destroy(Category $category)
    {
        // Check if category has products
        if ($category->products()->count() > 0) {
            return redirect()->route('category.index')->with('error', 'Kategori tidak dapat dihapus karena masih memiliki produk terkait.');
        }

        $category->delete();

        return redirect()->route('category.index')->with('success', 'Kategori berhasil dihapus.');
    }
}
