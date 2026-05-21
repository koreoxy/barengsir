<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Transaction;
use App\Models\TransactionItem;
use App\Models\StockMovement;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class TransactionController extends Controller
{
    public function index()
    {
        // Load all active products with category relation (stok 0 tetap ditampilkan tapi disabled)
        $products = Product::with('category')->orderBy('name')->get();

        // Unique categories from available products
        $categories = $products
            ->pluck('category')
            ->filter()
            ->unique('id')
            ->sortBy('name')
            ->values();

        // Store settings and active branch for receipt
        $branch = \App\Models\Branch::find(session('active_branch_id'));
        $settings = \App\Services\SettingService::all(session('active_branch_id'));

        return view('transaction.index', compact('products', 'categories', 'settings', 'branch'));
    }



    public function store(Request $request)
    {
        $validated = $request->validate([
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'payment_method' => 'required|in:cash,qris',
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

            // Calculate and add branch default tax
            $taxPercent = floatval(setting('default_tax', 0));
            if ($taxPercent > 0) {
                $taxAmount = $totalAmount * ($taxPercent / 100);
                $totalAmount = round($totalAmount + $taxAmount, 2);
            }

            // 2. Validate and adjust payment
            $paymentMethod = $validated['payment_method'];
            $paidAmount = floatval($validated['paid_amount']);
            $changeAmount = 0;

            if ($paymentMethod === 'cash') {
                if ($paidAmount < $totalAmount) {
                    throw new \Exception("Uang pembayaran kurang dari total tagihan.");
                }
                $changeAmount = $paidAmount - $totalAmount;
            } else { // qris
                $paidAmount = $totalAmount; // automatic fit
                $changeAmount = 0;
            }

            // 3. Create Transaction Header
            $invoiceNumber = 'INV-' . date('Ymd') . '-' . strtoupper(Str::random(5));
            $transaction = Transaction::create([
                'invoice_number' => $invoiceNumber,
                'total_amount' => $totalAmount,
                'paid_amount' => $paidAmount,
                'payment_method' => $paymentMethod,
                'change_amount' => $changeAmount,
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
                'payment_method' => $paymentMethod,
                'paid_amount' => $paidAmount,
                'change' => $changeAmount
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
