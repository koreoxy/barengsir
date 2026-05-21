<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Expense;
use App\Services\ReportService;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    protected $reportService;

    /**
     * Inject ReportService.
     */
    public function __construct(ReportService $reportService)
    {
        $this->reportService = $reportService;
    }

    /**
     * Display reporting dashboard.
     */
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

        $branchId = session('active_branch_id');

        // Fetch paginated transactions (for Sales Tab Table)
        $transactions = Transaction::with(['user', 'items'])
            ->whereBetween('created_at', [$startDate, $endDate]);

        if ($branchId) {
            $transactions->where('branch_id', $branchId);
        }

        $transactions = $transactions->orderBy('created_at', 'desc')
            ->paginate(15)
            ->withQueryString();

        // 1. Fetch sales metrics
        $metrics = $this->reportService->getSalesMetrics($startDate, $endDate, $branchId);

        // 2. Fetch product analysis metrics (terlaris, slow-moving, profit)
        $products = $this->reportService->getProductPerformance($startDate, $endDate, $branchId);

        // 3. Fetch financial profit & loss report
        $finance = $this->reportService->getFinancialReport($startDate, $endDate, $branchId);

        // 4. Fetch dynamic Chart.js data
        $isSqlite = DB::connection()->getDriverName() === 'sqlite';
        
        if ($filterType === 'yearly') {
            $groupByRaw = $isSqlite ? 'strftime("%Y-%m", created_at)' : 'DATE_FORMAT(created_at, "%Y-%m")';
        } else {
            $groupByRaw = $isSqlite ? 'strftime("%Y-%m-%d", created_at)' : 'DATE(created_at)';
        }

        $chartQuery = Transaction::select(DB::raw("$groupByRaw as label"), DB::raw('SUM(total_amount) as value'))
            ->whereBetween('created_at', [$startDate, $endDate]);

        if ($branchId) {
            $chartQuery->where('branch_id', $branchId);
        }

        $chartRawData = $chartQuery->groupBy('label')
            ->orderBy('label', 'asc')
            ->get();

        $chartLabels = [];
        $chartValues = [];
        
        foreach ($chartRawData as $data) {
            if ($filterType === 'yearly') {
                $dateLabel = Carbon::parse($data->label . '-01')->translatedFormat('F');
            } else {
                $dateLabel = Carbon::parse($data->label)->translatedFormat('d M');
            }
            $chartLabels[] = $dateLabel;
            $chartValues[] = (float) $data->value;
        }

        // Fetch recent operational expenses for the Expense list tab
        $expensesQuery = Expense::orderBy('expense_date', 'desc');
        if ($branchId) {
            $expensesQuery->where('branch_id', $branchId);
        }
        $recentExpenses = $expensesQuery->take(10)->get();

        return view('report.index', compact(
            'transactions', 
            'metrics',
            'products',
            'finance',
            'startDate',
            'endDate',
            'filterType',
            'selectedMonth',
            'selectedYear',
            'chartLabels',
            'chartValues',
            'recentExpenses'
        ));
    }

    /**
     * Export report data as Excel-compatible CSV.
     */
    public function exportExcel(Request $request)
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
        } else {
            $startDate = $request->input('start_date') ? Carbon::parse($request->input('start_date'))->startOfDay() : Carbon::today();
            $endDate = $request->input('end_date') ? Carbon::parse($request->input('end_date'))->endOfDay() : Carbon::today()->endOfDay();
        }

        $branchId = session('active_branch_id');
        
        $metrics = $this->reportService->getSalesMetrics($startDate, $endDate, $branchId);
        $products = $this->reportService->getProductPerformance($startDate, $endDate, $branchId);
        $finance = $this->reportService->getFinancialReport($startDate, $endDate, $branchId);

        $filename = "Laporan_Vendor_" . $startDate->format('Ymd') . "_to_" . $endDate->format('Ymd') . ".csv";

        $headers = [
            "Content-type"        => "text/csv; charset=UTF-8",
            "Content-Disposition" => "attachment; filename=$filename",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $callback = function() use ($startDate, $endDate, $metrics, $finance, $products) {
            $file = fopen('php://output', 'w');
            
            // UTF-8 BOM
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));

            fputcsv($file, ['LAPORAN KINERJA VENDOR & CABANG']);
            fputcsv($file, ['Periode:', $startDate->translatedFormat('d M Y') . ' s/d ' . $endDate->translatedFormat('d M Y')]);
            fputcsv($file, []);

            // Sales Summary
            fputcsv($file, ['=== LAPORAN RINGKASAN PENJUALAN ===']);
            fputcsv($file, ['Metrik', 'Nilai']);
            fputcsv($file, ['Total Pendapatan Kotor', 'Rp ' . number_format($finance['gross_revenue'], 2, ',', '.')]);
            fputcsv($file, ['Total Transaksi', $metrics['total_transactions'] . ' trx']);
            fputcsv($file, ['Total Item Terjual', $metrics['total_items_sold'] . ' item']);
            fputcsv($file, []);

            // Profit & Loss
            fputcsv($file, ['=== LAPORAN LABA RUGI ===']);
            fputcsv($file, ['Komponen', 'Jumlah']);
            fputcsv($file, ['1. Pendapatan Penjualan (Gross Revenue)', 'Rp ' . number_format($finance['gross_revenue'], 2, ',', '.')]);
            fputcsv($file, ['2. Harga Pokok Penjualan (COGS / HPP)', 'Rp ' . number_format($finance['cogs'], 2, ',', '.')]);
            fputcsv($file, ['Laba Kotor (Gross Profit)', 'Rp ' . number_format($finance['gross_profit'], 2, ',', '.')]);
            fputcsv($file, ['3. Pengeluaran Operasional (OPEX)', 'Rp ' . number_format($finance['opex'], 2, ',', '.')]);
            fputcsv($file, ['Laba Bersih (Net Profit)', 'Rp ' . number_format($finance['net_profit'], 2, ',', '.')]);
            fputcsv($file, []);

            // Cashflow
            fputcsv($file, ['=== LAPORAN ARUS KAS (CASHFLOW) ===']);
            fputcsv($file, ['Mutasi Kas', 'Jumlah']);
            fputcsv($file, ['Kas Masuk (Cash In)', 'Rp ' . number_format($finance['cashflow']['cash_in'], 2, ',', '.')]);
            fputcsv($file, ['Kas Keluar (Cash Out)', 'Rp ' . number_format($finance['cashflow']['cash_out'], 2, ',', '.')]);
            fputcsv($file, ['Arus Kas Bersih (Net Flow)', 'Rp ' . number_format($finance['cashflow']['net_flow'], 2, ',', '.')]);
            fputcsv($file, []);

            // Products
            fputcsv($file, ['=== LAPORAN ANALISIS PRODUK TERLARIS ===']);
            fputcsv($file, ['SKU', 'Nama Produk', 'Terjual (Qty)', 'Pendapatan Kotor', 'Laba Bersih']);
            foreach ($products['performance'] as $prod) {
                fputcsv($file, [
                    $prod->sku,
                    $prod->name,
                    (int) $prod->total_sold,
                    'Rp ' . number_format($prod->gross_sales, 2, ',', '.'),
                    'Rp ' . number_format($prod->total_profit, 2, ',', '.')
                ]);
            }
            fputcsv($file, []);

            // Slow Moving
            fputcsv($file, ['=== LAPORAN PRODUK TIDAK LAKU / SLOW MOVING ===']);
            fputcsv($file, ['SKU', 'Nama Produk', 'Stok Saat Ini', 'Harga Beli/Modal']);
            foreach ($products['slow_moving'] as $slow) {
                fputcsv($file, [
                    $slow->sku,
                    $slow->name,
                    $slow->stock,
                    'Rp ' . number_format($slow->purchase_price, 2, ',', '.')
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Export standalone printable PDF layout.
     */
    public function exportPdf(Request $request)
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
        } else {
            $startDate = $request->input('start_date') ? Carbon::parse($request->input('start_date'))->startOfDay() : Carbon::today();
            $endDate = $request->input('end_date') ? Carbon::parse($request->input('end_date'))->endOfDay() : Carbon::today()->endOfDay();
        }

        $branchId = session('active_branch_id');
        
        $metrics = $this->reportService->getSalesMetrics($startDate, $endDate, $branchId);
        $products = $this->reportService->getProductPerformance($startDate, $endDate, $branchId);
        $finance = $this->reportService->getFinancialReport($startDate, $endDate, $branchId);

        // Fetch all transactions for this period (for full print attachment)
        $transactionsQuery = Transaction::with(['user', 'items'])
            ->whereBetween('created_at', [$startDate, $endDate]);

        if ($branchId) {
            $transactionsQuery->where('branch_id', $branchId);
        }

        $transactions = $transactionsQuery->orderBy('created_at', 'desc')->get();

        return view('report.pdf', compact(
            'transactions',
            'metrics',
            'products',
            'finance',
            'startDate',
            'endDate',
            'filterType'
        ));
    }
}
