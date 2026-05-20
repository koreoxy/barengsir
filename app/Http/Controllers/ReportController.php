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
        $filterType = $request->input('filter_type', 'date_range');
        $startDate = Carbon::today()->startOfDay();
        $endDate = Carbon::today()->endOfDay();
        
        $selectedMonth = $request->input('month', Carbon::today()->format('Y-m'));
        $selectedYear = $request->input('year', Carbon::today()->format('Y'));

        if ($filterType === 'monthly') {
            $parsed = Carbon::parse($selectedMonth . '-01');
            $startDate = $parsed->copy()->startOfMonth();
            $endDate = $parsed->copy()->endOfMonth();
        } elseif ($filterType === 'yearly') {
            $startDate = Carbon::create($selectedYear, 1, 1)->startOfYear();
            $endDate = Carbon::create($selectedYear, 12, 31)->endOfYear();
        } else { // date_range
            $startDate = $request->input('start_date') ? Carbon::parse($request->input('start_date'))->startOfDay() : Carbon::today();
            $endDate = $request->input('end_date') ? Carbon::parse($request->input('end_date'))->endOfDay() : Carbon::today()->endOfDay();
        }

        // Transactions list with eager loading
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

        // Fetch chart data (database agnostic SQLite vs MySQL)
        $isSqlite = DB::connection()->getDriverName() === 'sqlite';
        
        if ($filterType === 'yearly') {
            // Group by month
            $groupByRaw = $isSqlite ? 'strftime("%Y-%m", created_at)' : 'DATE_FORMAT(created_at, "%Y-%m")';
        } else {
            // Group by date
            $groupByRaw = $isSqlite ? 'strftime("%Y-%m-%d", created_at)' : 'DATE(created_at)';
        }

        $chartRawData = Transaction::select(DB::raw("$groupByRaw as label"), DB::raw('SUM(total_amount) as value'))
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('label')
            ->orderBy('label', 'asc')
            ->get();

        // Prepare labels and values for Chart.js
        $chartLabels = [];
        $chartValues = [];
        
        foreach ($chartRawData as $data) {
            if ($filterType === 'yearly') {
                // E.g. "2026-05" to "Mei" or "May"
                $dateLabel = Carbon::parse($data->label . '-01')->translatedFormat('F');
            } else {
                // E.g. "2026-05-20" to "20 Mei"
                $dateLabel = Carbon::parse($data->label)->translatedFormat('d M');
            }
            $chartLabels[] = $dateLabel;
            $chartValues[] = (float) $data->value;
        }

        return view('report.index', compact(
            'transactions', 
            'totalRevenue', 
            'totalTransactions', 
            'totalItemsSold',
            'startDate',
            'endDate',
            'filterType',
            'selectedMonth',
            'selectedYear',
            'chartLabels',
            'chartValues'
        ));
    }
}
