<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Laporan Penjualan') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Form Filter -->
            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 mb-6">
                <form action="{{ route('report.index') }}" method="GET" class="flex flex-col sm:flex-row items-end gap-4">
                    <div>
                        <x-input-label for="start_date" :value="__('Tanggal Mulai')" />
                        <x-text-input id="start_date" name="start_date" type="date" class="mt-1 block w-full" :value="request('start_date', $startDate->format('Y-m-d'))" required />
                    </div>
                    <div>
                        <x-input-label for="end_date" :value="__('Tanggal Akhir')" />
                        <x-text-input id="end_date" name="end_date" type="date" class="mt-1 block w-full" :value="request('end_date', $endDate->format('Y-m-d'))" required />
                    </div>
                    <div class="flex-1">
                        <x-primary-button class="w-full sm:w-auto bg-blue-600 hover:bg-blue-700 py-3 mt-1 justify-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path></svg>
                            Terapkan Filter
                        </x-primary-button>
                    </div>
                </form>
            </div>

            <!-- Area Metrik Ringkasan (Cards) -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <!-- Pendapatan Card -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 flex items-center justify-between group hover:shadow-md transition-shadow">
                    <div>
                        <p class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-1">Total Pendapatan</p>
                        <h3 class="text-2xl font-bold text-gray-900">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</h3>
                    </div>
                    <div class="w-14 h-14 bg-blue-50 text-blue-600 rounded-full flex items-center justify-center group-hover:scale-110 transition-transform">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                </div>

                <!-- Transaksi Card -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 flex items-center justify-between group hover:shadow-md transition-shadow">
                    <div>
                        <p class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-1">Total Transaksi</p>
                        <h3 class="text-2xl font-bold text-gray-900">{{ number_format($totalTransactions, 0, ',', '.') }} <span class="text-sm font-medium text-gray-500 lowercase">trx</span></h3>
                    </div>
                    <div class="w-14 h-14 bg-blue-50 text-blue-600 rounded-full flex items-center justify-center group-hover:scale-110 transition-transform">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                    </div>
                </div>

                <!-- Barang Terjual Card -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 flex items-center justify-between group hover:shadow-md transition-shadow">
                    <div>
                        <p class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-1">Barang Terjual</p>
                        <h3 class="text-2xl font-bold text-gray-900">{{ number_format($totalItemsSold, 0, ',', '.') }} <span class="text-sm font-medium text-gray-500 lowercase">item</span></h3>
                    </div>
                    <div class="w-14 h-14 bg-blue-50 text-blue-600 rounded-full flex items-center justify-center group-hover:scale-110 transition-transform">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    </div>
                </div>
            </div>

            <!-- Tabel Riwayat Transaksi -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl border border-gray-100">
                <div class="p-6 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
                    <h3 class="text-lg font-bold text-gray-800">Riwayat Transaksi</h3>
                    <div class="text-sm text-gray-500">
                        Menampilkan data dari <span class="font-bold text-gray-700">{{ $startDate->format('d M Y') }}</span> s/d <span class="font-bold text-gray-700">{{ $endDate->format('d M Y') }}</span>
                    </div>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-blue-50/50">
                            <tr>
                                <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-blue-800 uppercase tracking-wider">No. Invoice</th>
                                <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-blue-800 uppercase tracking-wider">Tanggal & Waktu</th>
                                <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-blue-800 uppercase tracking-wider">Kasir</th>
                                <th scope="col" class="px-6 py-4 text-right text-xs font-bold text-blue-800 uppercase tracking-wider">Total Tagihan</th>
                                <th scope="col" class="px-6 py-4 text-right text-xs font-bold text-blue-800 uppercase tracking-wider">Dibayar</th>
                                <th scope="col" class="px-6 py-4 text-right text-xs font-bold text-blue-800 uppercase tracking-wider">Kembalian</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-100">
                            @forelse($transactions as $transaction)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="font-bold text-blue-600">{{ $transaction->invoice_number }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                    {{ $transaction->created_at->format('d M Y, H:i') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-medium">
                                    {{ $transaction->user->name ?? 'Kasir Default' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900 text-right">
                                    Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 text-right">
                                    Rp {{ number_format($transaction->paid_amount, 0, ',', '.') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-green-600 font-bold text-right">
                                    Rp {{ number_format($transaction->paid_amount - $transaction->total_amount, 0, ',', '.') }}
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="px-6 py-8 whitespace-nowrap text-center">
                                    <div class="flex flex-col items-center justify-center text-gray-400">
                                        <svg class="w-16 h-16 mb-4 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                                        <p class="text-lg">Tidak ada data transaksi pada rentang tanggal ini.</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($transactions->hasPages())
                <div class="px-6 py-4 border-t border-gray-100">
                    {{ $transactions->links() }}
                </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
