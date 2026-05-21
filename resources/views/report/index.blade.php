<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Laporan Vendor & Cabang') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-slate-50/50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div x-data="{ activeTab: 'sales' }" class="space-y-6">
                
                <!-- Filter & Export Controls Modern Panel -->
                <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm">
                    <form action="{{ route('report.index') }}" method="GET" class="space-y-6">
                        <!-- Tab Picker & Filter Selection -->
                        <div class="flex flex-wrap items-center justify-between gap-4 border-b border-slate-100 pb-5">
                            <div class="flex flex-wrap items-center gap-2">
                                <button type="button" 
                                        @click="activeTab = 'sales'" 
                                        :class="activeTab === 'sales' ? 'bg-blue-600 text-white shadow-md shadow-blue-500/20' : 'bg-slate-50 hover:bg-slate-100 text-slate-600'" 
                                        class="px-4 py-2.5 rounded-xl text-xs font-bold uppercase tracking-wider transition-all duration-200">
                                    📈 Penjualan
                                </button>
                                <button type="button" 
                                        @click="activeTab = 'products'" 
                                        :class="activeTab === 'products' ? 'bg-blue-600 text-white shadow-md shadow-blue-500/20' : 'bg-slate-50 hover:bg-slate-100 text-slate-600'" 
                                        class="px-4 py-2.5 rounded-xl text-xs font-bold uppercase tracking-wider transition-all duration-200">
                                    📦 Analisis Produk
                                </button>
                                <button type="button" 
                                        @click="activeTab = 'finance'" 
                                        :class="activeTab === 'finance' ? 'bg-blue-600 text-white shadow-md shadow-blue-500/20' : 'bg-slate-50 hover:bg-slate-100 text-slate-600'" 
                                        class="px-4 py-2.5 rounded-xl text-xs font-bold uppercase tracking-wider transition-all duration-200">
                                    💰 Laba Rugi
                                </button>
                                <button type="button" 
                                        @click="activeTab = 'expenses'" 
                                        :class="activeTab === 'expenses' ? 'bg-blue-600 text-white shadow-md shadow-blue-500/20' : 'bg-slate-50 hover:bg-slate-100 text-slate-600'" 
                                        class="px-4 py-2.5 rounded-xl text-xs font-bold uppercase tracking-wider transition-all duration-200">
                                    💸 Pengeluaran
                                </button>
                            </div>

                            <!-- Export buttons -->
                            <div class="flex items-center gap-2">
                                <a href="{{ route('report.export.excel', request()->query()) }}" class="inline-flex items-center justify-center px-4 py-2 bg-emerald-600 hover:bg-emerald-500 text-white text-xs font-bold uppercase tracking-wider rounded-xl shadow-xs transition-colors">
                                    Excel
                                </a>
                                <a href="{{ route('report.export.pdf', request()->query()) }}" target="_blank" class="inline-flex items-center justify-center px-4 py-2 bg-rose-600 hover:bg-rose-500 text-white text-xs font-bold uppercase tracking-wider rounded-xl shadow-xs transition-colors">
                                    PDF/Print
                                </a>
                            </div>
                        </div>

                        <!-- Date / Period filters -->
                        <div x-data="{ filterType: '{{ $filterType }}' }" class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
                            <div>
                                <label for="filter_type" class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-2">Tipe Periode</label>
                                <select id="filter_type" name="filter_type" x-model="filterType" class="block w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50/50 focus:ring-4 focus:ring-blue-500/20 focus:border-blue-600 focus:bg-white text-xs text-slate-800 focus:outline-none transition-all font-semibold">
                                    <option value="date_range">Rentang Tanggal</option>
                                    <option value="monthly">Laporan Bulanan</option>
                                    <option value="yearly">Laporan Tahunan</option>
                                </select>
                            </div>

                            <!-- Dynamic Filter Fields -->
                            <div x-show="filterType === 'date_range'" class="md:col-span-2 grid grid-cols-1 sm:grid-cols-2 gap-4" x-transition>
                                <div>
                                    <label for="start_date" class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-2">Tanggal Mulai</label>
                                    <input id="start_date" name="start_date" type="date" class="block w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50/50 focus:ring-4 focus:ring-blue-500/20 focus:border-blue-600 focus:bg-white text-xs text-slate-800 focus:outline-none transition-all font-semibold" value="{{ $startDate->format('Y-m-d') }}" />
                                </div>
                                <div>
                                    <label for="end_date" class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-2">Tanggal Akhir</label>
                                    <input id="end_date" name="end_date" type="date" class="block w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50/50 focus:ring-4 focus:ring-blue-500/20 focus:border-blue-600 focus:bg-white text-xs text-slate-800 focus:outline-none transition-all font-semibold" value="{{ $endDate->format('Y-m-d') }}" />
                                </div>
                            </div>

                            <div x-show="filterType === 'monthly'" class="md:col-span-2" x-transition>
                                <label for="month" class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-2">Pilih Bulan & Tahun</label>
                                <input id="month" name="month" type="month" class="block w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50/50 focus:ring-4 focus:ring-blue-500/20 focus:border-blue-600 focus:bg-white text-xs text-slate-800 focus:outline-none transition-all font-semibold" value="{{ $selectedMonth }}" />
                            </div>

                            <div x-show="filterType === 'yearly'" class="md:col-span-2" x-transition>
                                <label for="year" class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-2">Pilih Tahun</label>
                                <select id="year" name="year" class="block w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50/50 focus:ring-4 focus:ring-blue-500/20 focus:border-blue-600 focus:bg-white text-xs text-slate-800 focus:outline-none transition-all font-semibold">
                                    @php
                                        $currentYear = date('Y');
                                    @endphp
                                    @for ($y = $currentYear - 5; $y <= $currentYear; $y++)
                                        <option value="{{ $y }}" {{ $selectedYear == $y ? 'selected' : '' }}>{{ $y }}</option>
                                    @endfor
                                </select>
                            </div>

                            <div>
                                <button type="submit" class="w-full inline-flex items-center justify-center px-5 py-3 bg-blue-600 hover:bg-blue-500 active:scale-[0.98] text-white text-xs font-bold uppercase tracking-wider rounded-xl shadow-lg shadow-blue-500/20 hover:shadow-blue-500/35 transition-all duration-150">
                                    Terapkan Filter
                                </button>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Active Tab Contents -->

                <!-- 1. SALES TAB -->
                <div x-show="activeTab === 'sales'" class="space-y-6" x-transition>
                    <!-- Charts -->
                    <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm">
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 mb-6">
                            <div>
                                <h3 class="text-sm font-bold text-slate-800 uppercase tracking-wider">Grafik Penjualan</h3>
                                <p class="text-xs text-slate-400">Tren penjualan kotor pada periode terfilter</p>
                            </div>
                        </div>
                        <div class="h-80 relative w-full">
                            <canvas id="salesChart"></canvas>
                        </div>
                    </div>

                    <!-- Cards -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="bg-white rounded-2xl border border-slate-100 p-6 flex items-center justify-between group hover:shadow-sm transition-all">
                            <div>
                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-2">Total Pendapatan</p>
                                <h3 class="text-2xl font-black text-slate-800 tracking-tight">Rp {{ number_format($finance['gross_revenue'], 0, ',', '.') }}</h3>
                            </div>
                            <div class="w-12 h-12 bg-emerald-50 text-emerald-600 rounded-2xl flex items-center justify-center transition-all">
                                💰
                            </div>
                        </div>

                        <div class="bg-white rounded-2xl border border-slate-100 p-6 flex items-center justify-between group hover:shadow-sm transition-all">
                            <div>
                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-2">Total Transaksi</p>
                                <h3 class="text-2xl font-black text-slate-800 tracking-tight">{{ number_format($metrics['total_transactions'], 0, ',', '.') }} trx</h3>
                            </div>
                            <div class="w-12 h-12 bg-blue-50 text-blue-600 rounded-2xl flex items-center justify-center transition-all">
                                📄
                            </div>
                        </div>

                        <div class="bg-white rounded-2xl border border-slate-100 p-6 flex items-center justify-between group hover:shadow-sm transition-all">
                            <div>
                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-2">Item Terjual</p>
                                <h3 class="text-2xl font-black text-slate-800 tracking-tight">{{ number_format($metrics['total_items_sold'], 0, ',', '.') }} item</h3>
                            </div>
                            <div class="w-12 h-12 bg-amber-50 text-amber-600 rounded-2xl flex items-center justify-center transition-all">
                                🛍️
                            </div>
                        </div>
                    </div>

                    <!-- Transaction List -->
                    <div class="bg-white overflow-hidden shadow-sm rounded-2xl border border-slate-100">
                        <div class="p-6 border-b border-slate-100 bg-slate-50/50">
                            <h3 class="text-sm font-bold text-slate-800 uppercase tracking-wider">Riwayat Transaksi Terperinci</h3>
                        </div>
                        
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-slate-100">
                                <thead class="bg-slate-50/70">
                                    <tr>
                                        <th scope="col" class="px-6 py-4 text-left text-[10px] font-bold text-slate-500 uppercase tracking-wider">Invoice</th>
                                        <th scope="col" class="px-6 py-4 text-left text-[10px] font-bold text-slate-500 uppercase tracking-wider">Waktu</th>
                                        <th scope="col" class="px-6 py-4 text-left text-[10px] font-bold text-slate-500 uppercase tracking-wider">Kasir</th>
                                        <th scope="col" class="px-6 py-4 text-right text-[10px] font-bold text-slate-500 uppercase tracking-wider">Total</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-slate-100">
                                    @forelse($transactions as $trx)
                                    <tr class="hover:bg-slate-50/50 transition-colors">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="font-mono text-xs font-bold text-blue-600 bg-blue-50 px-2 py-1 rounded-lg border border-blue-100">
                                                {{ $trx->invoice_number }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-xs text-slate-500 font-medium">
                                            {{ $trx->created_at->translatedFormat('d M Y, H:i') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-xs text-slate-700 font-bold">
                                            {{ $trx->user->name ?? 'Kasir Default' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-xs font-bold text-slate-800 text-right">
                                            Rp {{ number_format($trx->total_amount, 0, ',', '.') }}
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="4" class="px-6 py-12 text-center text-xs text-slate-400">
                                            Tidak ada transaksi tercatat dalam periode ini.
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        
                        @if($transactions->hasPages())
                        <div class="px-6 py-4 border-t border-slate-100 bg-slate-50/50">
                            {{ $transactions->links() }}
                        </div>
                        @endif
                    </div>
                </div>

                <!-- 2. PRODUCTS ANALYSIS TAB -->
                <div x-show="activeTab === 'products'" class="space-y-6" x-transition>
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        
                        <!-- Best Sellers -->
                        <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm">
                            <h3 class="text-sm font-bold text-slate-800 uppercase tracking-wider mb-4">📦 Produk Terlaris & Margin Laba</h3>
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-slate-100">
                                    <thead>
                                        <tr>
                                            <th class="pb-3 text-left text-[9px] font-bold text-slate-400 uppercase tracking-wider">Nama Produk</th>
                                            <th class="pb-3 text-right text-[9px] font-bold text-slate-400 uppercase tracking-wider">Terjual</th>
                                            <th class="pb-3 text-right text-[9px] font-bold text-slate-400 uppercase tracking-wider">Omset</th>
                                            <th class="pb-3 text-right text-[9px] font-bold text-slate-400 uppercase tracking-wider">Profit</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-slate-100 text-xs">
                                        @forelse($products['performance'] as $prod)
                                        <tr>
                                            <td class="py-3 font-bold text-slate-800">
                                                {{ $prod->name }}
                                                <p class="text-[9px] text-slate-400 font-normal mt-0.5">{{ $prod->sku }}</p>
                                            </td>
                                            <td class="py-3 text-right text-slate-600 font-semibold">{{ number_format($prod->total_sold) }} pcs</td>
                                            <td class="py-3 text-right font-medium text-slate-800">Rp {{ number_format($prod->gross_sales, 0, ',', '.') }}</td>
                                            <td class="py-3 text-right font-bold text-emerald-600">Rp {{ number_format($prod->total_profit, 0, ',', '.') }}</td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="4" class="py-8 text-center text-slate-400">Belum ada produk terjual dalam periode ini.</td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Slow Moving -->
                        <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm">
                            <h3 class="text-sm font-bold text-slate-800 uppercase tracking-wider mb-4">⚠️ Produk Kurang Laku (Slow-Moving)</h3>
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-slate-100">
                                    <thead>
                                        <tr>
                                            <th class="pb-3 text-left text-[9px] font-bold text-slate-400 uppercase tracking-wider">Nama Produk</th>
                                            <th class="pb-3 text-right text-[9px] font-bold text-slate-400 uppercase tracking-wider">Stok Gudang</th>
                                            <th class="pb-3 text-right text-[9px] font-bold text-slate-400 uppercase tracking-wider">Modal Item</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-slate-100 text-xs">
                                        @forelse($products['slow_moving'] as $slow)
                                        <tr>
                                            <td class="py-3 font-bold text-slate-800">
                                                {{ $slow->name }}
                                                <p class="text-[9px] text-slate-400 font-normal mt-0.5">{{ $slow->sku }}</p>
                                            </td>
                                            <td class="py-3 text-right font-semibold text-slate-600">{{ number_format($slow->stock) }}</td>
                                            <td class="py-3 text-right font-medium text-slate-800">Rp {{ number_format($slow->purchase_price, 0, ',', '.') }}</td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="3" class="py-8 text-center text-slate-400">Seluruh produk memiliki riwayat penjualan aktif!</td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>

                    </div>
                </div>

                <!-- 3. LABA RUGI & CASHFLOW TAB -->
                <div x-show="activeTab === 'finance'" class="space-y-6" x-transition>
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                        
                        <!-- Statement P&L -->
                        <div class="bg-white p-8 rounded-2xl border border-slate-100 shadow-sm lg:col-span-2">
                            <h3 class="text-sm font-bold text-slate-800 uppercase tracking-wider mb-6">📉 Laporan Keuangan Laba Rugi</h3>
                            
                            <div class="space-y-4">
                                <div class="flex justify-between items-center py-3 border-b border-slate-100">
                                    <span class="text-xs font-semibold text-slate-600">1. Pendapatan Penjualan (Gross Revenue)</span>
                                    <span class="text-sm font-black text-slate-800">Rp {{ number_format($finance['gross_revenue'], 0, ',', '.') }}</span>
                                </div>
                                <div class="flex justify-between items-center py-3 border-b border-slate-100">
                                    <span class="text-xs font-semibold text-slate-600">2. Harga Pokok Penjualan (HPP / COGS)</span>
                                    <span class="text-sm font-bold text-rose-600">- Rp {{ number_format($finance['cogs'], 0, ',', '.') }}</span>
                                </div>
                                <div class="flex justify-between items-center py-3 bg-slate-50 px-4 rounded-xl">
                                    <span class="text-xs font-bold text-slate-800">Laba Kotor (Gross Profit)</span>
                                    <span class="text-sm font-black text-blue-600">Rp {{ number_format($finance['gross_profit'], 0, ',', '.') }}</span>
                                </div>
                                <div class="flex justify-between items-center py-3 border-b border-slate-100">
                                    <span class="text-xs font-semibold text-slate-600">3. Pengeluaran Operasional (OPEX)</span>
                                    <span class="text-sm font-bold text-rose-600">- Rp {{ number_format($finance['opex'], 0, ',', '.') }}</span>
                                </div>
                                <div class="flex justify-between items-center py-4 bg-blue-50 px-4 rounded-xl border border-blue-100">
                                    <span class="text-xs font-extrabold text-blue-900">Laba Bersih (Net Profit)</span>
                                    <span class="text-base font-black text-emerald-600">Rp {{ number_format($finance['net_profit'], 0, ',', '.') }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Cashflow -->
                        <div class="bg-white p-8 rounded-2xl border border-slate-100 shadow-sm">
                            <h3 class="text-sm font-bold text-slate-800 uppercase tracking-wider mb-6">💸 Laporan Arus Kas</h3>
                            <div class="space-y-6">
                                <div class="p-4 bg-emerald-50 border border-emerald-100 rounded-xl">
                                    <p class="text-[10px] font-bold text-emerald-800 uppercase tracking-wider mb-1">Kas Masuk (Cash In)</p>
                                    <h4 class="text-lg font-black text-emerald-950">Rp {{ number_format($finance['cashflow']['cash_in'], 0, ',', '.') }}</h4>
                                </div>

                                <div class="p-4 bg-rose-50 border border-rose-100 rounded-xl">
                                    <p class="text-[10px] font-bold text-rose-800 uppercase tracking-wider mb-1">Kas Keluar (Cash Out)</p>
                                    <h4 class="text-lg font-black text-rose-950">Rp {{ number_format($finance['cashflow']['cash_out'], 0, ',', '.') }}</h4>
                                </div>

                                <div class="p-4 bg-slate-50 border border-slate-200/50 rounded-xl">
                                    <p class="text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">Mutasi Bersih (Net Cashflow)</p>
                                    <h4 class="text-lg font-black text-slate-800">Rp {{ number_format($finance['cashflow']['net_flow'], 0, ',', '.') }}</h4>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                <!-- 4. EXPENSES MANAGEMENTS TAB -->
                <div x-show="activeTab === 'expenses'" class="space-y-6" x-transition>
                    <div class="bg-white overflow-hidden shadow-sm rounded-2xl border border-slate-100">
                        <div class="p-6 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
                            <div>
                                <h3 class="text-sm font-bold text-slate-800 uppercase tracking-wider">Catatan Pengeluaran Operasional</h3>
                                <p class="text-xs text-slate-400">Total Pengeluaran Periode Ini: <span class="font-bold text-rose-600">Rp {{ number_format($finance['opex'], 0, ',', '.') }}</span></p>
                            </div>
                            <a href="{{ route('expense.index') }}" class="inline-flex items-center justify-center px-4 py-2 bg-blue-600 hover:bg-blue-500 active:scale-[0.98] text-white text-xs font-bold uppercase tracking-wider rounded-xl shadow-xs transition-all">
                                Kelola Pengeluaran
                            </a>
                        </div>
                        
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-slate-100">
                                <thead class="bg-slate-50/70">
                                    <tr>
                                        <th scope="col" class="px-6 py-4 text-left text-[10px] font-bold text-slate-500 uppercase tracking-wider">Tanggal</th>
                                        <th scope="col" class="px-6 py-4 text-left text-[10px] font-bold text-slate-500 uppercase tracking-wider">Deskripsi/Judul</th>
                                        <th scope="col" class="px-6 py-4 text-left text-[10px] font-bold text-slate-500 uppercase tracking-wider">Kategori</th>
                                        <th scope="col" class="px-6 py-4 text-right text-[10px] font-bold text-slate-500 uppercase tracking-wider">Jumlah Biaya</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-slate-100">
                                    @forelse($recentExpenses as $exp)
                                    <tr class="hover:bg-slate-50/50 transition-colors">
                                        <td class="px-6 py-4 whitespace-nowrap text-xs text-slate-500 font-medium">
                                            {{ $exp->expense_date->translatedFormat('d M Y') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-xs text-slate-800 font-bold">
                                            {{ $exp->title }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-xs">
                                            <span class="px-2 py-0.5 rounded bg-slate-100 text-[10px] font-bold uppercase tracking-wide border border-slate-200">
                                                {{ $exp->category }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-xs font-black text-rose-600 text-right">
                                            Rp {{ number_format($exp->amount, 0, ',', '.') }}
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="4" class="px-6 py-8 text-center text-xs text-slate-400">
                                            Belum ada pengeluaran dicatat. Silakan klik "Kelola Pengeluaran" untuk menambah data.
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- Script Block for line charts -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const ctx = document.getElementById('salesChart').getContext('2d');
            
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
                        label: 'Pendapatan Penjualan',
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
                        pointHoverRadius: 6
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: '#1e293b',
                            padding: 12,
                            cornerRadius: 12,
                            callbacks: {
                                label: function(context) {
                                    return ' Pendapatan: Rp ' + new Intl.NumberFormat('id-ID').format(context.parsed.y);
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            grid: { color: '#f1f5f9' },
                            ticks: {
                                color: '#94a3b8',
                                font: { size: 10, family: 'monospace' },
                                callback: function(value) {
                                    return 'Rp ' + new Intl.NumberFormat('id-ID').format(value);
                                }
                            }
                        },
                        x: {
                            grid: { display: false },
                            ticks: { color: '#94a3b8', font: { size: 10, weight: 'bold' } }
                        }
                    }
                }
            });
        });
    </script>
</x-app-layout>
