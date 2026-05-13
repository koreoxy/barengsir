<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Welcome Message -->
            <div class="mb-8">
                <h1 class="text-2xl font-bold text-gray-900">Selamat datang kembali, {{ Auth::user()->name }}! 👋</h1>
                <p class="text-gray-500 mt-1">Berikut adalah ringkasan performa bisnis Anda hari ini.</p>
            </div>

            <!-- Summary Cards Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <!-- Sales Card -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 flex items-center justify-between group hover:shadow-md transition-shadow">
                    <div>
                        <p class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-1">Penjualan Hari Ini</p>
                        <h3 class="text-2xl font-bold text-gray-900">Rp {{ number_format($todaySales, 0, ',', '.') }}</h3>
                    </div>
                    <div class="w-14 h-14 bg-blue-50 text-blue-600 rounded-full flex items-center justify-center group-hover:scale-110 transition-transform">
                        <!-- Money Icon -->
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                </div>

                <!-- Transactions Card -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 flex items-center justify-between group hover:shadow-md transition-shadow">
                    <div>
                        <p class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-1">Transaksi Hari Ini</p>
                        <h3 class="text-2xl font-bold text-gray-900">{{ number_format($todayTransactions, 0, ',', '.') }} <span class="text-sm font-medium text-gray-500 lowercase">trx</span></h3>
                    </div>
                    <div class="w-14 h-14 bg-blue-50 text-blue-600 rounded-full flex items-center justify-center group-hover:scale-110 transition-transform">
                        <!-- Shopping Cart Icon -->
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    </div>
                </div>

                <!-- Active Products Card -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 flex items-center justify-between group hover:shadow-md transition-shadow">
                    <div>
                        <p class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-1">Total Produk Aktif</p>
                        <h3 class="text-2xl font-bold text-gray-900">{{ number_format($activeProducts, 0, ',', '.') }} <span class="text-sm font-medium text-gray-500 lowercase">item</span></h3>
                    </div>
                    <div class="w-14 h-14 bg-blue-50 text-blue-600 rounded-full flex items-center justify-center group-hover:scale-110 transition-transform">
                        <!-- Box/Product Icon -->
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                    </div>
                </div>

                <!-- Low Stock Card -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 flex items-center justify-between group hover:shadow-md transition-shadow">
                    <div>
                        <p class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-1">Peringatan Stok</p>
                        <h3 class="text-2xl font-bold text-red-600">{{ number_format($lowStockCount, 0, ',', '.') }} <span class="text-sm font-medium text-gray-500 lowercase">item menipis</span></h3>
                    </div>
                    <div class="w-14 h-14 bg-red-50 text-red-600 rounded-full flex items-center justify-center group-hover:scale-110 transition-transform">
                        <!-- Alert Icon -->
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                    </div>
                </div>
            </div>

            <!-- Content Section (Split Layout) -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                
                <!-- Recent Transactions (Left / Larger) -->
                <div class="lg:col-span-2 bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="p-6 border-b border-gray-100 bg-gray-50/50 flex justify-between items-center">
                        <h3 class="text-lg font-bold text-gray-800">Transaksi Terakhir</h3>
                        <a href="{{ route('report.index') }}" class="text-sm font-medium text-blue-600 hover:text-blue-800 transition-colors">Lihat Semua &rarr;</a>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-blue-50/50">
                                <tr>
                                    <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-blue-800 uppercase tracking-wider">Invoice</th>
                                    <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-blue-800 uppercase tracking-wider">Waktu</th>
                                    <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-blue-800 uppercase tracking-wider">Kasir</th>
                                    <th scope="col" class="px-6 py-4 text-right text-xs font-bold text-blue-800 uppercase tracking-wider">Total Tagihan</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-100">
                                @forelse($recentTransactions as $trx)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="font-bold text-blue-600">{{ $trx->invoice_number }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                        {{ $trx->created_at->diffForHumans() }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $trx->user->name ?? 'Unknown' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900 text-right">
                                        Rp {{ number_format($trx->total_amount, 0, ',', '.') }}
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-8 whitespace-nowrap text-center">
                                        <div class="text-gray-400">Belum ada transaksi hari ini.</div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Top Selling Products (Right / Smaller) -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="p-6 border-b border-gray-100 bg-gray-50/50">
                        <h3 class="text-lg font-bold text-gray-800">Produk Terlaris</h3>
                    </div>
                    <div class="p-4">
                        <div class="space-y-4">
                            @forelse($topProducts as $item)
                            <div class="flex items-center justify-between pb-4 border-b border-gray-100 last:border-0 last:pb-0">
                                <div class="flex items-center gap-3">
                                    <div class="w-12 h-12 bg-blue-50 rounded-lg flex items-center justify-center shrink-0">
                                        @if($item->product->image)
                                            <img src="{{ asset('storage/' . $item->product->image) }}" alt="{{ $item->product->name }}" class="w-12 h-12 rounded-lg object-cover">
                                        @else
                                            <svg class="w-6 h-6 text-blue-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                        @endif
                                    </div>
                                    <div>
                                        <p class="font-bold text-gray-900 line-clamp-1">{{ $item->product->name }}</p>
                                        <p class="text-xs text-gray-500">{{ $item->product->sku ?? 'No SKU' }}</p>
                                    </div>
                                </div>
                                <div class="text-right pl-4">
                                    <p class="font-bold text-blue-600">{{ $item->total_sold }}</p>
                                    <p class="text-xs text-gray-500">terjual</p>
                                </div>
                            </div>
                            @empty
                            <div class="py-8 text-center text-gray-400">
                                Belum ada data penjualan.
                            </div>
                            @endforelse
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </div>
</x-app-layout>
