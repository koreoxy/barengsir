<x-app-layout>

<div class="relative border-b border-slate-700/40 overflow-hidden"
     style="background: url('{{ asset('banner-pos.png') }}') center center / cover no-repeat;">

    {{-- Dark overlay so text remains readable --}}
    <div class="absolute inset-0" style="background: linear-gradient(135deg, rgba(15,23,42,0.82) 0%, rgba(15,23,42,0.50) 60%, rgba(15,23,42,0.20) 100%);"></div>

    {{-- Content --}}
    <div class="relative px-6 py-12 flex items-center justify-between gap-4">
        <div>
            {{-- Status indicator --}}
            <div class="flex items-center gap-2 mb-2">
                <span class="relative flex h-2 w-2">
                    <span class="animate-ping absolute inline-flex h-full w-full
                                 rounded-full bg-emerald-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                </span>
                <span class="text-[11px] font-medium text-slate-300">System Online</span>
            </div>

            <p class="text-slate-300 text-sm mb-1">
                Hi, <span class="text-blue-300 font-semibold">{{ session('active_branch_name') ?? Auth::user()->name }}</span>
            </p>
            <h1 class="text-2xl font-bold text-white tracking-tight leading-none">
                Welcome back 👋
            </h1>
            <p class="text-slate-400 text-xs mt-2">
                {{ now()->isoFormat('dddd, D MMMM Y') }}
            </p>
        </div>
    </div>
</div>


{{-- ══════════════════════════════════════════════════════
     CONTENT AREA
     ══════════════════════════════════════════════════════ --}}
<div class="px-6 py-6 space-y-5 bg-slate-50">

    {{-- ── OVERVIEW STATS ──────────────────────────────── --}}
    <section>
        <h2 class="text-xs font-semibold text-slate-400 uppercase tracking-widest mb-3">Overview</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">

            {{-- Card: Penjualan Hari Ini --}}
            <div class="bg-white rounded-2xl border border-slate-200 p-5 group
                        hover:border-emerald-300 hover:shadow-lg hover:shadow-emerald-50
                        transition-all duration-200">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-10 h-10 rounded-xl flex items-center justify-center shrink-0
                                bg-emerald-50 text-emerald-600 border border-emerald-100
                                group-hover:bg-emerald-600 group-hover:text-white group-hover:border-emerald-600
                                transition-all duration-200">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                  d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <span class="text-xs font-semibold text-emerald-600 bg-emerald-50 border border-emerald-100 px-2.5 py-1 rounded-full">
                        Revenue
                    </span>
                </div>
                <p class="text-xs font-medium text-slate-400 mb-1.5">Penjualan Hari Ini</p>
                <p class="text-2xl font-bold text-slate-800 tracking-tight">
                    Rp {{ number_format($todaySales, 0, ',', '.') }}
                </p>
            </div>

            {{-- Card: Transaksi Hari Ini --}}
            <div class="bg-white rounded-2xl border border-slate-200 p-5 group
                        hover:border-blue-300 hover:shadow-lg hover:shadow-blue-50
                        transition-all duration-200">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-10 h-10 rounded-xl flex items-center justify-center shrink-0
                                bg-blue-50 text-blue-600 border border-blue-100
                                group-hover:bg-blue-600 group-hover:text-white group-hover:border-blue-600
                                transition-all duration-200">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                  d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                    </div>
                    <a href="{{ route('report.index') }}"
                       class="text-xs font-semibold text-blue-500 hover:text-blue-700
                              bg-blue-50 hover:bg-blue-100 border border-blue-100
                              px-2.5 py-1 rounded-full transition-colors">
                        See All →
                    </a>
                </div>
                <p class="text-xs font-medium text-slate-400 mb-1.5">Transaksi Hari Ini</p>
                <p class="text-2xl font-bold text-slate-800 tracking-tight">
                    {{ number_format($todayTransactions) }}
                    <span class="text-base font-normal text-slate-400">trx</span>
                </p>
            </div>

            {{-- Card: Total Produk Aktif --}}
            <div class="bg-white rounded-2xl border border-slate-200 p-5 group
                        hover:border-orange-300 hover:shadow-lg hover:shadow-orange-50
                        transition-all duration-200">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-10 h-10 rounded-xl flex items-center justify-center shrink-0
                                bg-orange-50 text-orange-600 border border-orange-100
                                group-hover:bg-orange-600 group-hover:text-white group-hover:border-orange-600
                                transition-all duration-200">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                  d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                        </svg>
                    </div>
                    <a href="{{ route('product.index') }}"
                       class="text-xs font-semibold text-orange-500 hover:text-orange-700
                              bg-orange-50 hover:bg-orange-100 border border-orange-100
                              px-2.5 py-1 rounded-full transition-colors">
                        See All →
                    </a>
                </div>
                <p class="text-xs font-medium text-slate-400 mb-1.5">Total Produk Aktif</p>
                <p class="text-2xl font-bold text-slate-800 tracking-tight">
                    {{ number_format($activeProducts) }}
                    <span class="text-base font-normal text-slate-400">item</span>
                </p>
            </div>

            {{-- Card: Stok Menipis --}}
            <div class="bg-white rounded-2xl border border-slate-200 p-5 group
                        hover:border-rose-300 hover:shadow-lg hover:shadow-rose-50
                        transition-all duration-200">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-10 h-10 rounded-xl flex items-center justify-center shrink-0
                                bg-rose-50 text-rose-600 border border-rose-100
                                group-hover:bg-rose-600 group-hover:text-white group-hover:border-rose-600
                                transition-all duration-200">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                  d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                    </div>
                    <a href="{{ route('product.stock') }}"
                       class="text-xs font-semibold text-rose-500 hover:text-rose-700
                              bg-rose-50 hover:bg-rose-100 border border-rose-100
                              px-2.5 py-1 rounded-full transition-colors">
                        Check →
                    </a>
                </div>
                <p class="text-xs font-medium text-slate-400 mb-1.5">Stok Menipis</p>
                <p class="text-2xl font-bold text-rose-600 tracking-tight">
                    {{ number_format($lowStockCount) }}
                    <span class="text-base font-normal text-slate-400">item</span>
                </p>
            </div>

        </div>
    </section>

    {{-- ── RECENT ORDERS + RIGHT PANEL ─────────────────── --}}
    <div class="grid grid-cols-1 xl:grid-cols-3 gap-5">

        {{-- Recent Orders Table --}}
        <section class="xl:col-span-2 bg-white rounded-2xl border border-slate-200 overflow-hidden">
            <div class="px-5 py-4 border-b border-slate-100 flex items-center justify-between">
                <div>
                    <h2 class="text-sm font-semibold text-slate-800">Recent Orders</h2>
                    <p class="text-xs text-slate-400 mt-0.5">Transaksi terbaru hari ini</p>
                </div>
                <a href="{{ route('report.index') }}"
                   class="text-xs font-semibold text-slate-600 hover:text-slate-900
                          bg-slate-50 hover:bg-slate-100 border border-slate-200
                          rounded-lg px-3 py-1.5 transition-colors">
                    View all
                </a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b border-slate-100 bg-slate-50/50">
                            <th class="px-5 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wide whitespace-nowrap">Kasir</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wide whitespace-nowrap">Waktu</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wide whitespace-nowrap">Invoice</th>
                            <th class="px-4 py-3 text-right text-xs font-semibold text-slate-500 uppercase tracking-wide whitespace-nowrap">Total</th>
                            <th class="px-5 py-3 text-center text-xs font-semibold text-slate-500 uppercase tracking-wide whitespace-nowrap">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @forelse ($recentTransactions as $trx)
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="px-5 py-3.5">
                                <div class="flex items-center gap-2.5">
                                    <div class="shrink-0 w-8 h-8 rounded-lg
                                                bg-blue-50 border border-blue-100
                                                flex items-center justify-center
                                                text-blue-600 font-semibold text-xs uppercase">
                                        {{ substr($trx->user->name ?? 'K', 0, 1) }}
                                    </div>
                                    <span class="text-sm font-medium text-slate-700 whitespace-nowrap">
                                        {{ $trx->user->name ?? 'Kasir' }}
                                    </span>
                                </div>
                            </td>
                            <td class="px-4 py-3.5 text-sm text-slate-400 whitespace-nowrap">
                                {{ $trx->created_at->diffForHumans() }}
                            </td>
                            <td class="px-4 py-3.5">
                                <span class="text-sm font-mono font-medium text-blue-600 whitespace-nowrap">
                                    {{ $trx->invoice_number }}
                                </span>
                            </td>
                            <td class="px-4 py-3.5 text-right text-sm font-semibold text-slate-800 whitespace-nowrap">
                                Rp {{ number_format($trx->total_amount, 0, ',', '.') }}
                            </td>
                            <td class="px-5 py-3.5 text-center">
                                <span class="inline-flex items-center gap-1.5
                                             px-2.5 py-1 rounded-full
                                             text-xs font-semibold
                                             bg-emerald-50 text-emerald-700 border border-emerald-100">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 shrink-0"></span>
                                    Completed
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-5 py-14 text-center">
                                <p class="text-3xl mb-3">📋</p>
                                <p class="text-sm font-medium text-slate-400">Belum ada transaksi hari ini</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>

        {{-- Right Panel --}}
        <div class="xl:col-span-1 space-y-4">

            {{-- Top Selling Products --}}
            <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden">
                <div class="px-5 py-4 border-b border-slate-100">
                    <h2 class="text-sm font-semibold text-slate-800">🏆 Produk Terlaris</h2>
                    <p class="text-xs text-slate-400 mt-0.5">Berdasarkan kuantitas terjual</p>
                </div>
                <div class="p-4 space-y-3">
                    @forelse($topProducts as $index => $item)
                    <div class="flex items-center gap-3">
                        <span class="shrink-0 w-6 h-6 rounded-md flex items-center justify-center
                                     text-xs font-bold
                                     {{ $index === 0 ? 'bg-yellow-50 text-yellow-600 border border-yellow-200'
                                      : ($index === 1 ? 'bg-slate-100 text-slate-500 border border-slate-200'
                                      : ($index === 2 ? 'bg-orange-50 text-orange-500 border border-orange-200'
                                      : 'bg-slate-50 text-slate-400 border border-slate-100')) }}">
                            {{ $index + 1 }}
                        </span>
                        <div class="shrink-0 w-9 h-9 rounded-xl bg-slate-50 border border-slate-100
                                    flex items-center justify-center overflow-hidden">
                            @if($item->product && $item->product->image)
                                <img src="{{ asset('storage/' . $item->product->image) }}"
                                     alt="{{ $item->product->name }}"
                                     class="w-full h-full object-cover">
                            @else
                                <svg class="w-4 h-4 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                            @endif
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-slate-700 truncate">
                                {{ $item->product?->name ?? 'Produk Tidak Ditemukan' }}
                            </p>
                            <p class="text-xs text-slate-400">{{ $item->product?->sku ?? 'No SKU' }}</p>
                        </div>
                        <div class="shrink-0 text-right">
                            <p class="text-sm font-bold text-blue-600">{{ $item->total_sold }}</p>
                            <p class="text-xs text-slate-400">sold</p>
                        </div>
                    </div>
                    @empty
                    <p class="text-sm text-slate-400 text-center py-8">Belum ada data penjualan.</p>
                    @endforelse
                </div>
            </div>

            {{-- Today's Summary --}}
            <div class="bg-white rounded-2xl border border-slate-200 p-5">
                <h3 class="text-sm font-semibold text-slate-800 mb-4">Today's Summary</h3>
                <div class="space-y-4">
                    <div>
                        <div class="flex justify-between items-center mb-2">
                            <span class="text-xs text-slate-500">Total Penjualan</span>
                            <span class="text-xs font-semibold text-slate-700">
                                Rp {{ number_format($todaySales, 0, ',', '.') }}
                            </span>
                        </div>
                        <div class="h-1.5 bg-slate-100 rounded-full overflow-hidden">
                            <div class="h-full bg-emerald-500 rounded-full" style="width:70%"></div>
                        </div>
                    </div>
                    <div>
                        <div class="flex justify-between items-center mb-2">
                            <span class="text-xs text-slate-500">Transaksi Berhasil</span>
                            <span class="text-xs font-semibold text-slate-700">{{ $todayTransactions }} trx</span>
                        </div>
                        <div class="h-1.5 bg-slate-100 rounded-full overflow-hidden">
                            <div class="h-full bg-blue-500 rounded-full" style="width:55%"></div>
                        </div>
                    </div>
                    <div>
                        <div class="flex justify-between items-center mb-2">
                            <span class="text-xs text-slate-500">Produk Aktif</span>
                            <span class="text-xs font-semibold text-slate-700">{{ $activeProducts }} item</span>
                        </div>
                        <div class="h-1.5 bg-slate-100 rounded-full overflow-hidden">
                            <div class="h-full bg-orange-400 rounded-full" style="width:80%"></div>
                        </div>
                    </div>
                </div>
            </div>

        </div>{{-- end right panel --}}

    </div>{{-- end grid --}}


</div>{{-- end content area --}}

</x-app-layout>
