<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use App\Models\StockMovement;
use Illuminate\Validation\Rule;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with(['category', 'brand'])->latest()->paginate(10);
        return view('product.index', compact('products'));
    }

    public function create()
    {
        $categories = Category::all();
        $brands = Brand::all();
        return view('product.create', compact('categories', 'brands'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'nullable|exists:categories,id',
            'brand_id' => 'nullable|exists:brands,id',
            'sku' => [
                'nullable', 
                'string', 
                'max:255', 
                Rule::unique('products')->where(fn ($query) => $query->where('branch_id', session('active_branch_id')))
            ],
            'description' => 'nullable|string',
            'purchase_price' => 'required|numeric|min:0',
            'selling_price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('products', 'public');
            $validated['image'] = $imagePath;
        }

        Product::create($validated);

        return redirect()->route('product.index')->with('success', 'Produk berhasil ditambahkan.');
    }

    public function edit(Product $product)
    {
        $categories = Category::all();
        $brands = Brand::all();
        return view('product.edit', compact('product', 'categories', 'brands'));
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'nullable|exists:categories,id',
            'brand_id' => 'nullable|exists:brands,id',
            'sku' => [
                'nullable', 
                'string', 
                'max:255', 
                Rule::unique('products')->ignore($product->id)->where(fn ($query) => $query->where('branch_id', session('active_branch_id')))
            ],
            'description' => 'nullable|string',
            'purchase_price' => 'required|numeric|min:0',
            'selling_price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('image')) {
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            $imagePath = $request->file('image')->store('products', 'public');
            $validated['image'] = $imagePath;
        }

        $product->update($validated);

        return redirect()->route('product.index')->with('success', 'Produk berhasil diperbarui.');
    }

    public function destroy(Product $product)
    {
        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }
        
        $product->delete();

        return redirect()->route('product.index')->with('success', 'Produk berhasil dihapus.');
    }

    public function stock()
    {
        // View to list products focused on stock, perhaps with recent movements
        $products = Product::with('category')->orderBy('stock', 'asc')->paginate(15);
        return view('product.stock', compact('products'));
    }

    public function stockOpname()
    {
        $products = Product::orderBy('name')->get();
        $recentMovements = StockMovement::with('product')->latest()->take(10)->get();
        return view('product.stock_opname', compact('products', 'recentMovements'));
    }

    public function storeStockOpname(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'type' => 'required|in:in,out,adjustment',
            'quantity' => 'required|integer|min:1',
            'note' => 'nullable|string|max:255',
        ]);

        DB::transaction(function () use ($validated) {
            $product = Product::findOrFail($validated['product_id']);
            
            // Create movement record
            StockMovement::create($validated);

            // Update product stock
            if ($validated['type'] === 'in') {
                $product->increment('stock', $validated['quantity']);
            } elseif ($validated['type'] === 'out') {
                $product->decrement('stock', $validated['quantity']);
            } elseif ($validated['type'] === 'adjustment') {
                // Adjustment here usually means setting absolute value or diff.
                // Let's assume adjustment sets the stock to exactly the quantity provided.
                // Wait, if quantity is just absolute value, then:
                $product->stock = $validated['quantity'];
                $product->save();
            }
        });

        return redirect()->route('product.stock_opname')->with('success', 'Stock berhasil diperbarui.');
    }

    public function barcode()
    {
        // Only show products that have a SKU
        $products = Product::whereNotNull('sku')->where('sku', '!=', '')->orderBy('name')->get();
        return view('product.barcode', compact('products'));
    }
}
