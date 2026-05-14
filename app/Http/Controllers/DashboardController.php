<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $today = \Carbon\Carbon::today();

        $todaySales = \App\Models\Transaction::whereDate('created_at', $today)->sum('total_amount');
        $todayTransactions = \App\Models\Transaction::whereDate('created_at', $today)->count();
        $activeProducts = \App\Models\Product::count();
        $lowStockCount = \App\Models\Product::where('stock', '<=', 5)->count();

        $recentTransactions = \App\Models\Transaction::with('user')
                                ->orderBy('created_at', 'desc')
                                ->take(5)
                                ->get();

        $topProducts = \App\Models\TransactionItem::with('product')
                            ->selectRaw('product_id, SUM(quantity) as total_sold')
                            ->groupBy('product_id')
                            ->orderByDesc('total_sold')
                            ->take(5)
                            ->get()
                            ->filter(function($item) {
                                return $item->product !== null;
                            });

        $data = [
            'todaySales' => $todaySales,
            'todayTransactions' => $todayTransactions,
            'activeProducts' => $activeProducts,
            'lowStockCount' => $lowStockCount,
            'recentTransactions' => $recentTransactions,
            'topProducts' => $topProducts,
        ];

        return view('dashboard', $data);
    }
}
