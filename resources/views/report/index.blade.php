<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Laporan Penjualan') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-slate-50/50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div x-data="{ filterType: '{{ $filterType }}' }" class="space-y-6">
                <!-- Form Filter Modern -->
                <form action="{{ route('report.index') }}" method="GET" class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm">
                    <!-- Selector Filter Type Tabs -->
                    <div class="flex flex-wrap items-center gap-2 mb-6 border-b border-slate-100 pb-5">
                        <button type="button" 
                                @click="filterType = 'date_range'" 
                                :class="filterType === 'date_range' ? 'bg-blue-600 text-white shadow-md shadow-blue-500/20' : 'bg-slate-50 hover:bg-slate-100 text-slate-600'" 
                                class="px-4 py-2.5 rounded-xl text-xs font-bold uppercase tracking-wider transition-all duration-200">
                            📅 Rentang Tanggal
                        </button>
                        <button type="button" 
                                @click="filterType = 'monthly'" 
                                :class="filterType === 'monthly' ? 'bg-blue-600 text-white shadow-md shadow-blue-500/20' : 'bg-slate-50 hover:bg-slate-100 text-slate-600'" 
                                class="px-4 py-2.5 rounded-xl text-xs font-bold uppercase tracking-wider transition-all duration-200">
                            🗓️ Laporan Bulanan
                        </button>
                        <button type="button" 
                                @click="filterType = 'yearly'" 
                                :class="filterType === 'yearly' ? 'bg-blue-600 text-white shadow-md shadow-blue-500/20' : 'bg-slate-50 hover:bg-slate-100 text-slate-600'" 
                                class="px-4 py-2.5 rounded-xl text-xs font-bold uppercase tracking-wider transition-all duration-200">
                            📊 Laporan Tahunan
                        </button>
                        <input type="hidden" name="filter_type" :value="filterType">
                    </div>

                    <!-- Input Fields Dinamis -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end">
                        <!-- Date Range Inputs -->
                        <div x-show="filterType === 'date_range'" class="md:col-span-2 grid grid-cols-1 sm:grid-cols-2 gap-4" x-transition>
                            <div>
                                <label for="start_date" class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-2">Tanggal Mulai</label>
                                <input id="start_date" name="start_date" type="date" class="block w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50/50 focus:ring-4 focus:ring-blue-500/20 focus:border-blue-600 focus:bg-white text-sm text-slate-800 focus:outline-none transition-all" value="{{ $startDate->format('Y-m-d') }}" />
                            </div>
                            <div>
                                <label for="end_date" class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-2">Tanggal Akhir</label>
                                <input id="end_date" name="end_date" type="date" class="block w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50/50 focus:ring-4 focus:ring-blue-500/20 focus:border-blue-600 focus:bg-white text-sm text-slate-800 focus:outline-none transition-all" value="{{ $endDate->format('Y-m-d') }}" />
                            </div>
                        </div>

                        <!-- Monthly Inputs -->
                        <div x-show="filterType === 'monthly'" class="md:col-span-2" x-transition>
                            <label for="month" class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-2">Pilih Bulan & Tahun</label>
                            <input id="month" name="month" type="month" class="block w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50/50 focus:ring-4 focus:ring-blue-500/20 focus:border-blue-600 focus:bg-white text-sm text-slate-800 focus:outline-none transition-all" value="{{ $selectedMonth }}" />
                        </div>

                        <!-- Yearly Inputs -->
                        <div x-show="filterType === 'yearly'" class="md:col-span-2" x-transition>
                            <label for="year" class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-2">Pilih Tahun</label>
                            <select id="year" name="year" class="block w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50/50 focus:ring-4 focus:ring-blue-500/20 focus:border-blue-600 focus:bg-white text-sm text-slate-800 focus:outline-none transition-all">
                                @php
                                    $currentYear = date('Y');
                                @endphp
                                @for ($y = $currentYear - 5; $y <= $currentYear; $y++)
                                    <option value="{{ $y }}" {{ $selectedYear == $y ? 'selected' : '' }}>{{ $y }}</option>
                                @endfor
                            </select>
                        </div>

                        <!-- Submit Button -->
                        <div>
                            <button type="submit" class="w-full inline-flex items-center justify-center px-5 py-3 bg-blue-600 hover:bg-blue-500 active:scale-[0.98] text-white text-xs font-bold uppercase tracking-wider rounded-xl shadow-lg shadow-blue-500/20 hover:shadow-blue-500/35 transition-all duration-150">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                                </svg>
                                Terapkan Filter
                            </button>
                        </div>
                    </div>
                </form>

                <!-- Tren Grafik Penjualan (Chart) -->
                <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 mb-6">
                        <div>
                            <h3 class="text-sm font-bold text-slate-800 uppercase tracking-wider">Grafik Penjualan</h3>
                            <p class="text-xs text-slate-400">Visualisasi tren pendapatan operasional berdasarkan periode terfilter</p>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="h-2.5 w-2.5 rounded-full bg-blue-500 shadow-sm shadow-blue-500/30"></span>
                            <span class="text-[10px] font-bold text-slate-500 uppercase tracking-widest">Total Pendapatan</span>
                        </div>
                    </div>
                    <div class="h-80 relative w-full">
                        <canvas id="salesChart"></canvas>
                    </div>
                </div>

                <!-- Area Metrik Ringkasan (Cards) -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <!-- Pendapatan Card -->
                    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 flex items-center justify-between group hover:shadow-md hover:border-blue-100 transition-all duration-200">
                        <div>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-2">Total Pendapatan</p>
                            <h3 class="text-2xl font-black text-slate-800 tracking-tight">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</h3>
                        </div>
                        <div class="w-12 h-12 bg-emerald-50 text-emerald-600 rounded-2xl flex items-center justify-center group-hover:scale-110 group-hover:rotate-3 transition-all duration-200">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    </div>

                    <!-- Transaksi Card -->
                    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 flex items-center justify-between group hover:shadow-md hover:border-blue-100 transition-all duration-200">
                        <div>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-2">Total Transaksi</p>
                            <h3 class="text-2xl font-black text-slate-800 tracking-tight">{{ number_format($totalTransactions, 0, ',', '.') }} <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider ml-0.5">trx</span></h3>
                        </div>
                        <div class="w-12 h-12 bg-blue-50 text-blue-600 rounded-2xl flex items-center justify-center group-hover:scale-110 group-hover:-rotate-3 transition-all duration-200">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                            </svg>
                        </div>
                    </div>

                    <!-- Barang Terjual Card -->
                    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 flex items-center justify-between group hover:shadow-md hover:border-blue-100 transition-all duration-200">
                        <div>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-2">Barang Terjual</p>
                            <h3 class="text-2xl font-black text-slate-800 tracking-tight">{{ number_format($totalItemsSold, 0, ',', '.') }} <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider ml-0.5">item</span></h3>
                        </div>
                        <div class="w-12 h-12 bg-amber-50 text-amber-600 rounded-2xl flex items-center justify-center group-hover:scale-110 group-hover:rotate-3 transition-all duration-200">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Tabel Riwayat Transaksi -->
                <div class="bg-white overflow-hidden shadow-sm rounded-2xl border border-slate-100">
                    <div class="p-6 border-b border-slate-100 flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4 bg-slate-50/50">
                        <div>
                            <h3 class="text-sm font-bold text-slate-800 uppercase tracking-wider">Riwayat Transaksi</h3>
                            <p class="text-xs text-slate-400">Daftar transaksi operasional terperinci</p>
                        </div>
                        <div class="inline-flex items-center gap-2 px-3 py-1.5 bg-slate-100/80 rounded-xl text-xs font-bold text-slate-600 border border-slate-200/50 self-start sm:self-auto">
                            <span class="h-2 w-2 rounded-full bg-emerald-500 animate-pulse"></span>
                            <span>Periode:</span>
                            <span class="text-blue-600 bg-white px-2 py-0.5 rounded-md border border-slate-200/40 shadow-xs">{{ $startDate->translatedFormat('d M Y') }}</span>
                            <span class="text-slate-400 font-medium">s/d</span>
                            <span class="text-blue-600 bg-white px-2 py-0.5 rounded-md border border-slate-200/40 shadow-xs">{{ $endDate->translatedFormat('d M Y') }}</span>
                        </div>
                    </div>
                    
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-slate-100">
                            <thead class="bg-slate-50/70">
                                <tr>
                                    <th scope="col" class="px-6 py-4 text-left text-[10px] font-bold text-slate-500 uppercase tracking-wider">No. Invoice</th>
                                    <th scope="col" class="px-6 py-4 text-left text-[10px] font-bold text-slate-500 uppercase tracking-wider">Tanggal & Waktu</th>
                                    <th scope="col" class="px-6 py-4 text-left text-[10px] font-bold text-slate-500 uppercase tracking-wider">Kasir</th>
                                    <th scope="col" class="px-6 py-4 text-right text-[10px] font-bold text-slate-500 uppercase tracking-wider">Total Tagihan</th>
                                    <th scope="col" class="px-6 py-4 text-right text-[10px] font-bold text-slate-500 uppercase tracking-wider">Dibayar</th>
                                    <th scope="col" class="px-6 py-4 text-right text-[10px] font-bold text-slate-500 uppercase tracking-wider">Kembalian</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-slate-100">
                                @forelse($transactions as $transaction)
                                <tr class="hover:bg-slate-50/50 transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="font-mono text-xs font-bold text-blue-600 bg-blue-50 px-2.5 py-1.5 rounded-lg border border-blue-100">
                                            {{ $transaction->invoice_number }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-xs text-slate-500 font-medium">
                                        {{ $transaction->created_at->translatedFormat('d M Y, H:i') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-xs text-slate-700 font-bold">
                                        {{ $transaction->user->name ?? 'Kasir Default' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-xs font-bold text-slate-800 text-right">
                                        Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-xs text-slate-500 text-right font-medium">
                                        Rp {{ number_format($transaction->paid_amount, 0, ',', '.') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-xs font-bold text-right">
                                        @if($transaction->paid_amount - $transaction->total_amount > 0)
                                            <span class="text-emerald-600 bg-emerald-50 px-2 py-1 rounded-md border border-emerald-100">
                                                Rp {{ number_format($transaction->paid_amount - $transaction->total_amount, 0, ',', '.') }}
                                            </span>
                                        @else
                                            <span class="text-slate-400">Rp 0</span>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-12 whitespace-nowrap text-center">
                                        <div class="flex flex-col items-center justify-center text-slate-400">
                                            <div class="w-16 h-16 bg-slate-50 rounded-2xl flex items-center justify-center mb-4 border border-slate-100 text-slate-300">
                                                <svg class="w-8 h-8" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                                </svg>
                                            </div>
                                            <p class="text-sm font-bold text-slate-600">Tidak ada data transaksi</p>
                                            <p class="text-xs text-slate-400 mt-1">Coba sesuaikan filter pencarian atau buat transaksi baru</p>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if($transactions->hasPages())
                    <div class="px-6 py-4 border-t border-slate-100 bg-slate-50/50 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                        <div class="text-xs text-slate-500 font-medium">
                            Menampilkan <span class="font-bold text-slate-700">{{ $transactions->firstItem() }}</span> sampai <span class="font-bold text-slate-700">{{ $transactions->lastItem() }}</span> dari <span class="font-bold text-slate-700">{{ $transactions->total() }}</span> transaksi
                        </div>
                        <div class="flex items-center gap-1.5">
                            @if ($transactions->onFirstPage())
                                <span class="cursor-not-allowed inline-flex items-center justify-center px-4 py-2 bg-slate-100 text-slate-400 text-xs font-bold uppercase tracking-wider rounded-xl border border-slate-200/40">
                                    Sebelumnya
                                </span>
                            @else
                                <a href="{{ $transactions->previousPageUrl() }}" class="inline-flex items-center justify-center px-4 py-2 bg-white hover:bg-slate-50 active:scale-95 text-slate-700 text-xs font-bold uppercase tracking-wider rounded-xl border border-slate-200 shadow-sm transition-all duration-150">
                                    Sebelumnya
                                </a>
                            @endif

                            @if ($transactions->hasMorePages())
                                <a href="{{ $transactions->nextPageUrl() }}" class="inline-flex items-center justify-center px-4 py-2 bg-white hover:bg-slate-50 active:scale-95 text-slate-700 text-xs font-bold uppercase tracking-wider rounded-xl border border-slate-200 shadow-sm transition-all duration-150">
                                    Selanjutnya
                                </a>
                            @else
                                <span class="cursor-not-allowed inline-flex items-center justify-center px-4 py-2 bg-slate-100 text-slate-400 text-xs font-bold uppercase tracking-wider rounded-xl border border-slate-200/40">
                                    Selanjutnya
                                </span>
                            @endif
                        </div>
                    </div>
                    @endif
                </div>
            </div>
            
        </div>
    </div>

    <!-- Script Block -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const ctx = document.getElementById('salesChart').getContext('2d');
            
            // Dynamic gradient for premium smooth styling
            const gradient = ctx.createLinearGradient(0, 0, 0, ctx.canvas.clientHeight || 250);
            gradient.addColorStop(0, 'rgba(59, 130, 246, 0.35)');
            gradient.addColorStop(1, 'rgba(59, 130, 246, 0.00)');

            const labels = {!! json_encode($chartLabels) !!};
            const values = {!! json_encode($chartValues) !!};

            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Total Pendapatan',
                        data: values,
                        borderColor: '#2563eb',
                        borderWidth: 3,
                        backgroundColor: gradient,
                        fill: true,
                        tension: 0.35,
                        pointBackgroundColor: '#2563eb',
                        pointBorderColor: '#ffffff',
                        pointBorderWidth: 2,
                        pointRadius: 4,
                        pointHoverRadius: 6,
                        pointHoverBorderWidth: 3
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            backgroundColor: '#1e293b',
                            titleColor: '#ffffff',
                            bodyColor: '#ffffff',
                            padding: 12,
                            cornerRadius: 12,
                            displayColors: false,
                            callbacks: {
                                label: function(context) {
                                    let value = context.parsed.y;
                                    return ' Pendapatan: Rp ' + new Intl.NumberFormat('id-ID').format(value);
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            grid: {
                                color: '#f1f5f9',
                                drawTicks: false
                            },
                            border: {
                                dash: [5, 5]
                            },
                            ticks: {
                                color: '#94a3b8',
                                font: {
                                    size: 10,
                                    family: 'monospace'
                                },
                                callback: function(value) {
                                    return 'Rp ' + new Intl.NumberFormat('id-ID').format(value);
                                }
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            },
                            ticks: {
                                color: '#94a3b8',
                                font: {
                                    size: 10,
                                    weight: 'bold'
                                }
                            }
                        }
                    }
                }
            });
        });
    </script>
</x-app-layout>
