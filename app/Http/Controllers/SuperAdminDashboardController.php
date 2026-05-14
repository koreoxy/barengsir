<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SuperAdminDashboardController extends Controller
{
    public function index()
    {
        $totalVendors = \App\Models\Vendor::count();
        $totalBranches = \App\Models\Branch::count();
        $totalUsers = \App\Models\User::count();
        $totalTransactions = \App\Models\Transaction::withoutGlobalScopes()->count();

        return view('superadmin.dashboard', compact('totalVendors', 'totalBranches', 'totalUsers', 'totalTransactions'));
    }
}
