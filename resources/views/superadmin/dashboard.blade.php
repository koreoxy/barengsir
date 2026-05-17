@extends('layouts.superadmin')

@section('title', 'Dashboard Global')

@section('content')
    <!-- Page Title Header -->
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-900">Selamat datang kembali, {{ Auth::user()->name }}! 👋</h1>
        <p class="text-slate-500 mt-1 text-sm">Berikut ringkasan performa dan statistik seluruh ekosistem vendor hari ini.</p>
    </div>

    <!-- Statistics Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
        <!-- Total Vendors -->
        <div class="bg-white rounded-xl shadow-sm border border-slate-100 p-6 flex items-center justify-between group hover:shadow-md transition-all duration-300">
            <div>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Total Vendor</p>
                <h3 class="text-3xl font-black text-slate-800">{{ number_format($stats['total_vendors']) }}</h3>
                <p class="text-xs text-blue-600 font-semibold mt-2">
                    <span class="bg-blue-50 px-2 py-0.5 rounded">+{{ $stats['new_vendors_this_month'] }}</span> bulan ini
                </p>
            </div>
            <div class="w-14 h-14 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center group-hover:scale-110 group-hover:bg-blue-600 group-hover:text-white transition-all duration-300">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                </svg>
            </div>
        </div>

        <!-- Total Revenue -->
        <div class="bg-white rounded-xl shadow-sm border border-slate-100 p-6 flex items-center justify-between group hover:shadow-md transition-all duration-300">
            <div>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Total Revenue</p>
                <h3 class="text-2xl font-black text-slate-800">Rp {{ number_format($stats['total_revenue'], 0, ',', '.') }}</h3>
                <p class="text-xs text-emerald-600 font-semibold mt-2">Seluruh ekosistem</p>
            </div>
            <div class="w-14 h-14 bg-emerald-50 text-emerald-600 rounded-xl flex items-center justify-center group-hover:scale-110 group-hover:bg-emerald-600 group-hover:text-white transition-all duration-300">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
        </div>

        <!-- Total Transactions -->
        <div class="bg-white rounded-xl shadow-sm border border-slate-100 p-6 flex items-center justify-between group hover:shadow-md transition-all duration-300">
            <div>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Total Transaksi</p>
                <h3 class="text-3xl font-black text-slate-800">{{ number_format($stats['total_transactions']) }}</h3>
                <p class="text-xs text-blue-600 font-semibold mt-2">Order diproses</p>
            </div>
            <div class="w-14 h-14 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center group-hover:scale-110 group-hover:bg-blue-600 group-hover:text-white transition-all duration-300">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
            </div>
        </div>

        <!-- Total Branches -->
        <div class="bg-white rounded-xl shadow-sm border border-slate-100 p-6 flex items-center justify-between group hover:shadow-md transition-all duration-300">
            <div>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Total Cabang</p>
                <h3 class="text-3xl font-black text-slate-800">{{ number_format($stats['total_branches']) }}</h3>
                <p class="text-xs text-amber-600 font-semibold mt-2">Titik outlet</p>
            </div>
            <div class="w-14 h-14 bg-amber-50 text-amber-600 rounded-xl flex items-center justify-center group-hover:scale-110 group-hover:bg-amber-600 group-hover:text-white transition-all duration-300">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Recent Vendors -->
        <div class="bg-white rounded-xl shadow-sm border border-slate-100 overflow-hidden">
            <div class="p-6 border-b border-slate-100 bg-slate-50/30 flex justify-between items-center">
                <h3 class="text-base font-bold text-gray-800">Vendor Baru Terdaftar</h3>
                <a href="{{ route('superadmin.vendors.index') }}" class="text-xs font-semibold text-blue-600 hover:text-blue-800 transition-colors">Lihat Semua</a>
            </div>
            <div class="p-0 overflow-x-auto">
                <table class="w-full text-left">
                    <thead class="bg-blue-50/50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-bold text-blue-800 uppercase tracking-wider">Vendor</th>
                            <th class="px-6 py-3 text-center text-xs font-bold text-blue-800 uppercase tracking-wider">Cabang</th>
                            <th class="px-6 py-3 text-right text-xs font-bold text-blue-800 uppercase tracking-wider">Bergabung</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($recentVendors as $vendor)
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            <td class="px-6 py-4">
                                <div class="flex items-center space-x-3">
                                    <div class="w-10 h-10 bg-blue-50 rounded-lg flex items-center justify-center font-bold text-blue-600 border border-blue-100">
                                        {{ substr($vendor->name, 0, 1) }}
                                    </div>
                                    <div>
                                        <p class="text-sm font-bold text-slate-800">{{ $vendor->name }}</p>
                                        <p class="text-xs text-slate-500">{{ $vendor->email }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="bg-blue-50 text-blue-700 text-[10px] font-bold px-2.5 py-1 rounded uppercase">
                                    {{ $vendor->branches_count }} Cabang
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <p class="text-xs text-slate-400">{{ $vendor->created_at->diffForHumans() }}</p>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Top Performing Vendors -->
        <div class="bg-white rounded-xl shadow-sm border border-slate-100 overflow-hidden">
            <div class="p-6 border-b border-slate-100 bg-slate-50/30 flex justify-between items-center">
                <h3 class="text-base font-bold text-gray-800">Top Performer (Revenue)</h3>
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Berdasarkan Total Order</span>
            </div>
            <div class="p-0 overflow-x-auto">
                <table class="w-full text-left">
                    <thead class="bg-blue-50/50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-bold text-blue-800 uppercase tracking-wider">Vendor</th>
                            <th class="px-6 py-3 text-right text-xs font-bold text-blue-800 uppercase tracking-wider">Total Revenue</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($topVendors as $vendor)
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            <td class="px-6 py-4">
                                <div class="flex items-center space-x-3">
                                    <div class="w-8 h-8 bg-amber-50 text-amber-600 rounded-lg flex items-center justify-center font-bold text-xs border border-amber-100">
                                        {{ $loop->iteration }}
                                    </div>
                                    <p class="text-sm font-bold text-slate-800">{{ $vendor->name }}</p>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <p class="text-sm font-black text-slate-800">Rp {{ number_format($vendor->transactions_sum_total_amount ?? 0, 0, ',', '.') }}</p>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
