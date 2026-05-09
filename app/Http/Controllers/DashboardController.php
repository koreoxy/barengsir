<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $data = [
            'total_sales' => 'Rp 15.430.000',
            'total_transactions' => 142,
            'revenue' => 'Rp 8.250.000',
            'active_products' => 320,
            'recent_transactions' => [
                ['id' => 'TRX-001', 'date' => '2023-10-27', 'amount' => 'Rp 150.000', 'status' => 'Completed'],
                ['id' => 'TRX-002', 'date' => '2023-10-27', 'amount' => 'Rp 50.000', 'status' => 'Completed'],
                ['id' => 'TRX-003', 'date' => '2023-10-26', 'amount' => 'Rp 200.000', 'status' => 'Pending'],
                ['id' => 'TRX-004', 'date' => '2023-10-26', 'amount' => 'Rp 75.000', 'status' => 'Completed'],
                ['id' => 'TRX-005', 'date' => '2023-10-25', 'amount' => 'Rp 300.000', 'status' => 'Completed'],
            ]
        ];

        return view('dashboard', $data);
    }
}
