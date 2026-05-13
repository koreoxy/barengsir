<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Transaction;
use App\Models\TransactionItem;
use App\Models\StockMovement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class TransactionController extends Controller
{
    public function index()
    {
        // Get all products that have stock > 0
        $products = Product::where('stock', '>', 0)->orderBy('name')->get();
        return view('transaction.index', compact('products'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'paid_amount' => 'required|numeric|min:0',
        ]);

        try {
            DB::beginTransaction();

            $totalAmount = 0;
            $itemsData = [];

            // 1. Validate stock and calculate total
            foreach ($validated['items'] as $item) {
                $product = Product::findOrFail($item['product_id']);
                
                if ($product->stock < $item['quantity']) {
                    throw new \Exception("Stok untuk produk {$product->name} tidak mencukupi. Sisa stok: {$product->stock}");
                }

                $subtotal = $product->selling_price * $item['quantity'];
                $totalAmount += $subtotal;

                $itemsData[] = [
                    'product' => $product,
                    'quantity' => $item['quantity'],
                    'price' => $product->selling_price,
                    'subtotal' => $subtotal
                ];
            }

            // 2. Validate payment
            if ($validated['paid_amount'] < $totalAmount) {
                throw new \Exception("Uang pembayaran kurang dari total tagihan.");
            }

            // 3. Create Transaction Header
            $invoiceNumber = 'INV-' . date('Ymd') . '-' . strtoupper(Str::random(5));
            $transaction = Transaction::create([
                'invoice_number' => $invoiceNumber,
                'total_amount' => $totalAmount,
                'paid_amount' => $validated['paid_amount'],
                'user_id' => auth()->id()
            ]);

            // 4. Create Items and Deduct Stock
            foreach ($itemsData as $data) {
                $product = $data['product'];

                // Create transaction item
                TransactionItem::create([
                    'transaction_id' => $transaction->id,
                    'product_id' => $product->id,
                    'quantity' => $data['quantity'],
                    'price' => $data['price'],
                    'subtotal' => $data['subtotal']
                ]);

                // Deduct stock
                $product->decrement('stock', $data['quantity']);

                // Record stock movement
                StockMovement::create([
                    'product_id' => $product->id,
                    'type' => 'out',
                    'quantity' => $data['quantity'],
                    'note' => "Penjualan POS ({$invoiceNumber})"
                ]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Transaksi berhasil diproses.',
                'invoice' => $invoiceNumber,
                'change' => $validated['paid_amount'] - $totalAmount
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 422);
        }
    }
}
