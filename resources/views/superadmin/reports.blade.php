@extends('layouts.superadmin')

@section('title', 'Laporan Global')

@section('content')
    <!-- Page Title Header -->
    <div class="mb-8">
        <h1 class="text-2xl md:text-3xl font-black text-slate-800 tracking-tight">Laporan Global Platform</h1>
        <p class="text-slate-500 mt-1.5 text-xs md:text-sm">Ringkasan performa finansial dan audit log transaksi dari seluruh ekosistem vendor POS.</p>
    </div>

    <!-- Statistics Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 mb-8">
        <!-- Aggregate Revenue -->
        <div class="bg-white rounded-2xl border border-slate-200/60 p-6 flex items-center justify-between group hover:scale-[1.02] hover:-translate-y-0.5 hover:shadow-lg hover:shadow-slate-100/80 transition-all duration-300 ease-out shadow-sm shadow-slate-100/40">
            <div>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">Omset Platform</p>
                <h3 class="text-xl md:text-2xl font-black text-slate-800 tracking-tight font-mono">Rp{{ number_format($revenue, 0, ',', '.') }}</h3>
                <p class="text-xs text-emerald-600 font-semibold mt-2.5">
                    <span class="bg-emerald-50 px-2.5 py-0.5 rounded-lg border border-emerald-100/30">Total Pendapatan</span>
                </p>
            </div>
            <div class="w-12 h-12 bg-emerald-50/80 text-emerald-600 rounded-xl flex items-center justify-center border border-emerald-100/50 shadow-sm transition-all duration-300">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
        </div>

        <!-- Total Volume -->
        <div class="bg-white rounded-2xl border border-slate-200/60 p-6 flex items-center justify-between group hover:scale-[1.02] hover:-translate-y-0.5 hover:shadow-lg hover:shadow-slate-100/80 transition-all duration-300 ease-out shadow-sm shadow-slate-100/40">
            <div>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">Volume Transaksi</p>
                <h3 class="text-3xl font-black text-slate-800 tracking-tight">{{ number_format($transactionCount) }}</h3>
                <p class="text-xs text-blue-600 font-semibold mt-2.5">
                    <span class="bg-blue-50 px-2.5 py-0.5 rounded-lg border border-blue-100/30">Order Diproses</span>
                </p>
            </div>
            <div class="w-12 h-12 bg-blue-50/80 text-blue-600 rounded-xl flex items-center justify-center border border-blue-100/50 shadow-sm transition-all duration-300">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                </svg>
            </div>
        </div>

        <!-- Average Order Value -->
        <div class="bg-white rounded-2xl border border-slate-200/60 p-6 flex items-center justify-between group hover:scale-[1.02] hover:-translate-y-0.5 hover:shadow-lg hover:shadow-slate-100/80 transition-all duration-300 ease-out shadow-sm shadow-slate-100/40">
            <div>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">Rata-Rata Transaksi</p>
                <h3 class="text-xl md:text-2xl font-black text-slate-800 tracking-tight font-mono">Rp{{ number_format($averageOrderValue, 0, ',', '.') }}</h3>
                <p class="text-xs text-indigo-600 font-semibold mt-2.5">
                    <span class="bg-indigo-50 px-2.5 py-0.5 rounded-lg border border-indigo-100/30">Nilai Keranjang</span>
                </p>
            </div>
            <div class="w-12 h-12 bg-indigo-50/80 text-indigo-600 rounded-xl flex items-center justify-center border border-indigo-100/50 shadow-sm transition-all duration-300">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 8v8m-4-5v5m-4-2v2m-2 4h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
        <!-- Payment Methods Breakdown -->
        <div class="bg-white rounded-2xl border border-slate-200/60 shadow-sm shadow-slate-100/30 p-6 lg:col-span-1">
            <h3 class="text-sm font-extrabold text-slate-800 tracking-tight uppercase mb-4">Metode Pembayaran</h3>
            <div class="space-y-4">
                @forelse($paymentMethods as $method)
                    <div class="flex items-center justify-between p-3.5 bg-slate-50/60 border border-slate-100 rounded-xl hover:bg-slate-50 transition-colors">
                        <div class="flex items-center space-x-3">
                            <span class="w-2.5 h-2.5 rounded-full 
                                {{ strtolower($method->payment_method) == 'cash' || strtolower($method->payment_method) == 'tunai' ? 'bg-emerald-500' : '' }}
                                {{ strtolower($method->payment_method) == 'qris' ? 'bg-blue-500' : '' }}
                                {{ strtolower($method->payment_method) == 'transfer' ? 'bg-violet-500' : '' }}
                                {{ !in_array(strtolower($method->payment_method), ['cash', 'tunai', 'qris', 'transfer']) ? 'bg-amber-500' : '' }}
                            "></span>
                            <span class="text-xs font-bold text-slate-700 capitalize">{{ $method->payment_method }}</span>
                        </div>
                        <div class="text-right">
                            <p class="text-xs font-black text-slate-800 font-mono">Rp{{ number_format($method->total, 0, ',', '.') }}</p>
                            <p class="text-[10px] text-slate-400 mt-0.5">{{ $method->count }} Transaksi</p>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-8 text-slate-400 text-xs font-medium">Belum ada metode pembayaran yang tercatat.</div>
                @endforelse
            </div>
        </div>

        <!-- Global Transactions Audit Log -->
        <div class="bg-white rounded-2xl border border-slate-200/60 shadow-sm shadow-slate-100/30 overflow-hidden flex flex-col lg:col-span-2">
            <div class="p-5 px-6 border-b border-slate-100 bg-slate-50/30 flex justify-between items-center">
                <h3 class="text-sm font-extrabold text-slate-800 tracking-tight uppercase">Audit Transaksi Sistem</h3>
                <span class="text-[9px] font-black text-slate-400 bg-slate-100 px-2 py-0.5 rounded-md uppercase tracking-widest">Real-time Log</span>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse hidden md:table">
                    <thead>
                        <tr class="bg-slate-50/50 border-b border-slate-100">
                            <th class="px-6 py-3.5 text-[10px] font-bold text-slate-400 uppercase tracking-wider">Invoice</th>
                            <th class="px-6 py-3.5 text-left text-[10px] font-bold text-slate-400 uppercase tracking-wider">Vendor & Cabang</th>
                            <th class="px-6 py-3.5 text-center text-[10px] font-bold text-slate-400 uppercase tracking-wider">Metode</th>
                            <th class="px-6 py-3.5 text-right text-[10px] font-bold text-slate-400 uppercase tracking-wider">Jumlah</th>
                            <th class="px-6 py-3.5 text-right text-[10px] font-bold text-slate-400 uppercase tracking-wider">Waktu</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($transactions as $transaction)
                        <tr class="hover:bg-slate-50/50 transition-colors duration-150">
                            <td class="px-6 py-4">
                                <span class="text-xs font-black text-slate-800 font-mono">{{ $transaction->invoice_number }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <p class="text-xs font-extrabold text-slate-800 tracking-tight">
                                    {{ $transaction->branch->vendor->name ?? 'Vendor Unknown' }}
                                </p>
                                <p class="text-[10px] text-slate-400 mt-0.5">
                                    {{ $transaction->branch->name ?? 'Cabang Unknown' }}
                                </p>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="text-[10px] font-bold px-2 py-0.5 rounded-full uppercase tracking-wider border
                                    {{ strtolower($transaction->payment_method) == 'cash' || strtolower($transaction->payment_method) == 'tunai' ? 'bg-emerald-50 text-emerald-700 border-emerald-100/50' : '' }}
                                    {{ strtolower($transaction->payment_method) == 'qris' ? 'bg-blue-50 text-blue-700 border-blue-100/50' : '' }}
                                    {{ strtolower($transaction->payment_method) == 'transfer' ? 'bg-violet-50 text-violet-700 border-violet-100/50' : '' }}
                                    {{ !in_array(strtolower($transaction->payment_method), ['cash', 'tunai', 'qris', 'transfer']) ? 'bg-amber-50 text-amber-700 border-amber-100/50' : '' }}
                                ">
                                    {{ $transaction->payment_method }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <p class="text-xs font-black text-slate-800 font-mono">Rp{{ number_format($transaction->total_amount, 0, ',', '.') }}</p>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <p class="text-[10px] text-slate-400 font-bold" title="{{ $transaction->created_at }}">{{ $transaction->created_at->diffForHumans() }}</p>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-6 py-8 text-center text-slate-400 text-xs font-medium">Belum ada data transaksi yang tercatat.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>

                <!-- Mobile Card Layout -->
                <div class="block md:hidden divide-y divide-slate-100">
                    @forelse($transactions as $transaction)
                    <div class="p-4 hover:bg-slate-50/50 transition-colors duration-150">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-xs font-black text-slate-800 font-mono">{{ $transaction->invoice_number }}</span>
                            <span class="text-[10px] font-bold px-2 py-0.5 rounded-full uppercase tracking-wider border
                                {{ strtolower($transaction->payment_method) == 'cash' || strtolower($transaction->payment_method) == 'tunai' ? 'bg-emerald-50 text-emerald-700 border-emerald-100/50' : '' }}
                                {{ strtolower($transaction->payment_method) == 'qris' ? 'bg-blue-50 text-blue-700 border-blue-100/50' : '' }}
                                {{ strtolower($transaction->payment_method) == 'transfer' ? 'bg-violet-50 text-violet-700 border-violet-100/50' : '' }}
                                {{ !in_array(strtolower($transaction->payment_method), ['cash', 'tunai', 'qris', 'transfer']) ? 'bg-amber-50 text-amber-700 border-amber-100/50' : '' }}
                            ">
                                {{ $transaction->payment_method }}
                            </span>
                        </div>
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-xs font-extrabold text-slate-800 tracking-tight">
                                    {{ $transaction->branch->vendor->name ?? 'Vendor Unknown' }}
                                </p>
                                <p class="text-[10px] text-slate-400">
                                    {{ $transaction->branch->name ?? 'Cabang Unknown' }}
                                </p>
                            </div>
                            <div class="text-right">
                                <p class="text-xs font-black text-slate-800 font-mono">Rp{{ number_format($transaction->total_amount, 0, ',', '.') }}</p>
                                <p class="text-[9px] text-slate-400 font-medium" title="{{ $transaction->created_at }}">{{ $transaction->created_at->diffForHumans() }}</p>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="p-6 text-center text-slate-400 text-xs font-medium">Belum ada data transaksi yang tercatat.</div>
                    @endforelse
                </div>
            </div>
            @if($transactions->hasPages())
                <div class="p-4 border-t border-slate-100 bg-slate-50/50">
                    {{ $transactions->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
