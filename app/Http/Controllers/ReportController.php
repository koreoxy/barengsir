<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        // Default to today if no dates provided
        $startDate = $request->input('start_date') ? Carbon::parse($request->input('start_date'))->startOfDay() : Carbon::today();
        $endDate = $request->input('end_date') ? Carbon::parse($request->input('end_date'))->endOfDay() : Carbon::today()->endOfDay();

        $transactions = Transaction::with(['user', 'items'])
            ->whereBetween('created_at', [$startDate, $endDate])
            ->orderBy('created_at', 'desc')
            ->paginate(15)
            ->withQueryString();

        // Calculate metrics
        $metricsQuery = Transaction::whereBetween('created_at', [$startDate, $endDate]);
        
        $totalRevenue = $metricsQuery->sum('total_amount');
        $totalTransactions = $metricsQuery->count();
        
        // Total items sold in this period
        $totalItemsSold = DB::table('transaction_items')
            ->join('transactions', 'transaction_items.transaction_id', '=', 'transactions.id')
            ->whereBetween('transactions.created_at', [$startDate, $endDate])
            ->sum('transaction_items.quantity');

        return view('report.index', compact(
            'transactions', 
            'totalRevenue', 
            'totalTransactions', 
            'totalItemsSold',
            'startDate',
            'endDate'
        ));
    }
}
