<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
        $topVendors = Vendor::withSum('transactions', 'total_amount')
            ->orderByDesc('transactions_sum_total_amount')
            ->take(5)
            ->get();

        return view('superadmin.dashboard', compact('stats', 'recentVendors', 'topVendors'));
    }
}
