<?php

namespace App\Services;

use App\Models\Transaction;
use App\Models\Product;
use App\Models\Expense;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ReportService
{
    /**
     * Get aggregate sales metrics for a given date range and branch.
     */
    public function getSalesMetrics($startDate, $endDate, $branchId = null)
    {
        $query = Transaction::whereBetween('created_at', [$startDate, $endDate]);

        if ($branchId) {
            $query->where('branch_id', $branchId);
        }

        $totalRevenue = $query->sum('total_amount');
        $totalTransactions = $query->count();

        // Total items sold
        $itemsQuery = DB::table('transaction_items')
            ->join('transactions', 'transaction_items.transaction_id', '=', 'transactions.id')
            ->whereBetween('transactions.created_at', [$startDate, $endDate]);

        if ($branchId) {
            $itemsQuery->where('transactions.branch_id', $branchId);
        }

        $totalItemsSold = $itemsQuery->sum('transaction_items.quantity');

        return [
            'total_revenue' => (float) $totalRevenue,
            'total_transactions' => $totalTransactions,
            'total_items_sold' => (int) $totalItemsSold,
        ];
    }

    /**
     * Get product performance metrics (best sellers, slow moving, and profitability).
     */
    public function getProductPerformance($startDate, $endDate, $branchId = null)
    {
        // 1. Best Sellers & Profitability per product
        $bestSellersQuery = DB::table('transaction_items')
            ->join('transactions', 'transaction_items.transaction_id', '=', 'transactions.id')
            ->join('products', 'transaction_items.product_id', '=', 'products.id')
            ->select(
                'products.id',
                'products.name',
                'products.sku',
                'products.purchase_price',
                DB::raw('SUM(transaction_items.quantity) as total_sold'),
                DB::raw('SUM(transaction_items.quantity * transaction_items.price) as gross_sales'),
                DB::raw('SUM(transaction_items.quantity * (transaction_items.price - products.purchase_price)) as total_profit')
            )
            ->whereBetween('transactions.created_at', [$startDate, $endDate]);

        if ($branchId) {
            $bestSellersQuery->where('transactions.branch_id', $branchId);
        }

        $productsPerformance = $bestSellersQuery
            ->groupBy('products.id', 'products.name', 'products.sku', 'products.purchase_price')
            ->orderBy('total_sold', 'desc')
            ->get();

        // 2. Slow Moving / Non-selling products
        // Products that are active but sold less than 5 items (or 0) in the period
        $soldProductIds = DB::table('transaction_items')
            ->join('transactions', 'transaction_items.transaction_id', '=', 'transactions.id')
            ->whereBetween('transactions.created_at', [$startDate, $endDate]);

        if ($branchId) {
            $soldProductIds->where('transactions.branch_id', $branchId);
        }

        $soldProductIds = $soldProductIds->pluck('product_id')->unique()->toArray();

        $slowMovingQuery = Product::whereNotIn('id', $soldProductIds);
        if ($branchId) {
            $slowMovingQuery->where('branch_id', $branchId);
        }

        $slowMoving = $slowMovingQuery->orderBy('stock', 'desc')->get();

        return [
            'performance' => $productsPerformance,
            'slow_moving' => $slowMoving,
        ];
    }

    /**
     * Get financial statement (Profit & Loss and Cashflow).
     */
    public function getFinancialReport($startDate, $endDate, $branchId = null)
    {
        // 1. Revenue & HPP (COGS)
        $financeQuery = DB::table('transaction_items')
            ->join('transactions', 'transaction_items.transaction_id', '=', 'transactions.id')
            ->join('products', 'transaction_items.product_id', '=', 'products.id')
            ->select(
                DB::raw('SUM(transaction_items.quantity * transaction_items.price) as gross_revenue'),
                DB::raw('SUM(transaction_items.quantity * products.purchase_price) as cogs')
            )
            ->whereBetween('transactions.created_at', [$startDate, $endDate]);

        if ($branchId) {
            $financeQuery->where('transactions.branch_id', $branchId);
        }

        $financeResult = $financeQuery->first();

        $grossRevenue = $financeResult ? (float) $financeResult->gross_revenue : 0.0;
        $cogs = $financeResult ? (float) $financeResult->cogs : 0.0;
        $grossProfit = $grossRevenue - $cogs;

        // 2. Operational Expenses (OPEX)
        $expensesQuery = Expense::whereBetween('expense_date', [$startDate->format('Y-m-d'), $endDate->format('Y-m-d')]);

        if ($branchId) {
            $expensesQuery->where('branch_id', $branchId);
        }

        $totalExpenses = $expensesQuery->sum('amount');
        $netProfit = $grossProfit - $totalExpenses;

        // 3. Cashflow detail (Cash In from transactions vs Cash Out from expenses)
        $cashflow = [
            'cash_in' => $grossRevenue,
            'cash_out' => (float) $totalExpenses,
            'net_flow' => $grossRevenue - $totalExpenses,
        ];

        return [
            'gross_revenue' => $grossRevenue,
            'cogs' => $cogs,
            'gross_profit' => $grossProfit,
            'opex' => (float) $totalExpenses,
            'net_profit' => $netProfit,
            'cashflow' => $cashflow,
        ];
    }
}
