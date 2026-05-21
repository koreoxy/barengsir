<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Models\Setting;

class SuperAdminDashboardController extends Controller
{
    public function index()
    {
        // Statistik Agregat (tanpa BranchScope karena Super Admin)
        $stats = [
            'total_vendors' => Vendor::count(),
            'total_branches' => Branch::count(),
            'total_users' => User::count(),
            'total_transactions' => Transaction::withoutGlobalScopes()->count(),
            'total_revenue' => Transaction::withoutGlobalScopes()->sum('total_amount'),
            'new_vendors_this_month' => Vendor::whereMonth('created_at', now()->month)->count(),
        ];

        // Vendor Terbaru
        $recentVendors = Vendor::withCount('branches')->latest()->take(5)->get();

        // Vendor Performa Terbaik (berdasarkan revenue)
        $topVendors = Vendor::withCount('branches')
            ->withSum('transactions', 'total_amount')
            ->orderByDesc('transactions_sum_total_amount')
            ->take(5)
            ->get();

        return view('superadmin.dashboard', compact('stats', 'recentVendors', 'topVendors'));
    }

    /**
     * Display consolidated platform transactions report.
     */
    public function reports()
    {
        // Financial aggregated performance metrics
        $revenue = Transaction::withoutGlobalScopes()->sum('total_amount');
        $transactionCount = Transaction::withoutGlobalScopes()->count();
        $averageOrderValue = $transactionCount > 0 ? ($revenue / $transactionCount) : 0;

        // Payment methods aggregations
        $paymentMethods = Transaction::withoutGlobalScopes()
            ->select('payment_method', DB::raw('count(*) as count'), DB::raw('sum(total_amount) as total'))
            ->groupBy('payment_method')
            ->get();

        // Consolidated system-wide transactions with details
        $transactions = Transaction::withoutGlobalScopes()
            ->with(['branch.vendor', 'user'])
            ->latest()
            ->paginate(15);

        return view('superadmin.reports', compact(
            'revenue',
            'transactionCount',
            'averageOrderValue',
            'paymentMethods',
            'transactions'
        ));
    }

    /**
     * Show global platform settings.
     */
    public function settings()
    {
        $settings = [
            'system_name' => Setting::withoutGlobalScopes()->whereNull('branch_id')->where('key', 'system_name')->value('value') ?? 'POS BarengSir',
            'default_max_branches' => Setting::withoutGlobalScopes()->whereNull('branch_id')->where('key', 'default_max_branches')->value('value') ?? '5',
            'vendor_registration_status' => Setting::withoutGlobalScopes()->whereNull('branch_id')->where('key', 'vendor_registration_status')->value('value') ?? 'open',
        ];

        return view('superadmin.settings', compact('settings'));
    }

    /**
     * Update global platform settings.
     */
    public function updateSettings(Request $request)
    {
        $validated = $request->validate([
            'system_name' => 'required|string|max:255',
            'default_max_branches' => 'required|integer|min:1|max:999',
            'vendor_registration_status' => 'required|string|in:open,closed',
        ]);

        foreach ($validated as $key => $value) {
            Setting::withoutGlobalScopes()->updateOrCreate(
                ['key' => $key, 'branch_id' => null],
                ['value' => $value]
            );
        }

        return redirect()->route('superadmin.settings')->with('success', 'Pengaturan sistem berhasil diperbarui.');
    }
}
