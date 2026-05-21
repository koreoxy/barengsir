@extends('layouts.superadmin')

@section('title', 'Dashboard Global')

@section('content')
    <!-- Page Title Header -->
    <div class="mb-8">
        <h1 class="text-2xl md:text-3xl font-black text-slate-800 tracking-tight">Selamat datang kembali, {{ Auth::user()->name }}! 👋</h1>
        <p class="text-slate-500 mt-1.5 text-xs md:text-sm">Berikut ringkasan performa dan statistik seluruh ekosistem vendor hari ini.</p>
    </div>

    <!-- Statistics Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Total Vendors -->
        <div class="bg-white rounded-2xl border border-slate-200/60 p-6 flex items-center justify-between group hover:scale-[1.02] hover:-translate-y-0.5 hover:shadow-lg hover:shadow-slate-100/80 transition-all duration-300 ease-out shadow-sm shadow-slate-100/40">
            <div>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">Total Vendor</p>
                <h3 class="text-3xl font-black text-slate-800 tracking-tight">{{ number_format($stats['total_vendors']) }}</h3>
                <p class="text-xs text-blue-600 font-semibold mt-2.5 flex items-center gap-1.5">
                    <span class="bg-blue-50 px-2 py-0.5 rounded-lg border border-blue-100/30">+{{ $stats['new_vendors_this_month'] }}</span>
                    <span class="text-slate-400 font-medium">bulan ini</span>
                </p>
            </div>
            <div class="w-12 h-12 bg-blue-50/80 text-blue-600 rounded-xl flex items-center justify-center border border-blue-100/50 shadow-sm group-hover:scale-110 group-hover:bg-gradient-to-tr group-hover:from-blue-600 group-hover:to-indigo-600 group-hover:text-white group-hover:border-transparent transition-all duration-300 ease-out">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                </svg>
            </div>
        </div>

        <!-- Total Revenue -->
        <div class="bg-white rounded-2xl border border-slate-200/60 p-6 flex items-center justify-between group hover:scale-[1.02] hover:-translate-y-0.5 hover:shadow-lg hover:shadow-slate-100/80 transition-all duration-300 ease-out shadow-sm shadow-slate-100/40">
            <div>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">Total Revenue</p>
                <h3 class="text-xl md:text-2xl font-black text-slate-800 tracking-tight font-mono">Rp{{ number_format($stats['total_revenue'], 0, ',', '.') }}</h3>
                <p class="text-xs text-emerald-600 font-semibold mt-2.5">
                    <span class="bg-emerald-50 px-2.5 py-0.5 rounded-lg border border-emerald-100/30">Seluruh ekosistem</span>
                </p>
            </div>
            <div class="w-12 h-12 bg-emerald-50/80 text-emerald-600 rounded-xl flex items-center justify-center border border-emerald-100/50 shadow-sm group-hover:scale-110 group-hover:bg-gradient-to-tr group-hover:from-emerald-600 group-hover:to-teal-500 group-hover:text-white group-hover:border-transparent transition-all duration-300 ease-out">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
        </div>

        <!-- Total Transactions -->
        <div class="bg-white rounded-2xl border border-slate-200/60 p-6 flex items-center justify-between group hover:scale-[1.02] hover:-translate-y-0.5 hover:shadow-lg hover:shadow-slate-100/80 transition-all duration-300 ease-out shadow-sm shadow-slate-100/40">
            <div>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">Total Transaksi</p>
                <h3 class="text-3xl font-black text-slate-800 tracking-tight">{{ number_format($stats['total_transactions']) }}</h3>
                <p class="text-xs text-indigo-600 font-semibold mt-2.5">
                    <span class="bg-indigo-50 px-2.5 py-0.5 rounded-lg border border-indigo-100/30">Order diproses</span>
                </p>
            </div>
            <div class="w-12 h-12 bg-indigo-50/80 text-indigo-600 rounded-xl flex items-center justify-center border border-indigo-100/50 shadow-sm group-hover:scale-110 group-hover:bg-gradient-to-tr group-hover:from-indigo-600 group-hover:to-violet-500 group-hover:text-white group-hover:border-transparent transition-all duration-300 ease-out">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
            </div>
        </div>

        <!-- Total Branches -->
        <div class="bg-white rounded-2xl border border-slate-200/60 p-6 flex items-center justify-between group hover:scale-[1.02] hover:-translate-y-0.5 hover:shadow-lg hover:shadow-slate-100/80 transition-all duration-300 ease-out shadow-sm shadow-slate-100/40">
            <div>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">Total Cabang</p>
                <h3 class="text-3xl font-black text-slate-800 tracking-tight">{{ number_format($stats['total_branches']) }}</h3>
                <p class="text-xs text-amber-600 font-semibold mt-2.5">
                    <span class="bg-amber-50 px-2.5 py-0.5 rounded-lg border border-amber-100/30">Titik outlet</span>
                </p>
            </div>
            <div class="w-12 h-12 bg-amber-50/80 text-amber-600 rounded-xl flex items-center justify-center border border-amber-100/50 shadow-sm group-hover:scale-110 group-hover:bg-gradient-to-tr group-hover:from-amber-500 group-hover:to-orange-500 group-hover:text-white group-hover:border-transparent transition-all duration-300 ease-out">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Recent Vendors -->
        <div class="bg-white rounded-2xl border border-slate-200/60 shadow-sm shadow-slate-100/30 overflow-hidden flex flex-col">
            <div class="p-5 px-6 border-b border-slate-100 bg-slate-50/30 flex justify-between items-center">
                <h3 class="text-sm font-extrabold text-slate-800 tracking-tight uppercase">Vendor Baru Terdaftar</h3>
                <a href="{{ route('superadmin.vendors.index') }}" class="text-xs font-bold text-blue-600 hover:text-blue-800 transition-colors flex items-center gap-1">
                    <span>Lihat Semua</span>
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse hidden md:table">
                    <thead>
                        <tr class="bg-slate-50/50 border-b border-slate-100">
                            <th class="px-6 py-3.5 text-[10px] font-bold text-slate-400 uppercase tracking-wider">Vendor</th>
                            <th class="px-6 py-3.5 text-center text-[10px] font-bold text-slate-400 uppercase tracking-wider">Cabang</th>
                            <th class="px-6 py-3.5 text-right text-[10px] font-bold text-slate-400 uppercase tracking-wider">Bergabung</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($recentVendors as $vendor)
                        <tr class="hover:bg-slate-50/50 transition-colors duration-150">
                            <td class="px-6 py-4">
                                <div class="flex items-center space-x-3.5">
                                    <div class="w-10 h-10 bg-gradient-to-tr from-blue-500/10 to-indigo-500/10 text-blue-600 rounded-xl flex items-center justify-center font-bold text-base border border-blue-200/30 uppercase">
                                        {{ substr($vendor->name, 0, 1) }}
                                    </div>
                                    <div>
                                        <p class="text-sm font-extrabold text-slate-800 tracking-tight">{{ $vendor->name }}</p>
                                        <p class="text-xs text-slate-400">{{ $vendor->email }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="bg-blue-50/80 text-blue-700 text-[10px] font-extrabold px-2.5 py-1 rounded-full uppercase tracking-wider border border-blue-100/30">
                                    {{ $vendor->branches_count }} Cabang
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <p class="text-xs font-semibold text-slate-400">{{ $vendor->created_at->diffForHumans() }}</p>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="px-6 py-8 text-center text-slate-400 text-xs font-medium">Belum ada vendor terdaftar.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>

                <!-- Mobile Card Layout -->
                <div class="block md:hidden divide-y divide-slate-100">
                    @forelse($recentVendors as $vendor)
                    <div class="p-4 hover:bg-slate-50/50 transition-colors duration-150">
                        <div class="flex items-center justify-between mb-2">
                            <div class="flex items-center space-x-3">
                                <div class="w-8 h-8 bg-gradient-to-tr from-blue-500/10 to-indigo-500/10 text-blue-600 rounded-lg flex items-center justify-center font-bold text-sm border border-blue-200/30 uppercase shadow-inner">
                                    {{ substr($vendor->name, 0, 1) }}
                                </div>
                                <div>
                                    <p class="text-xs font-bold text-slate-800 tracking-tight">{{ $vendor->name }}</p>
                                    <p class="text-[10px] text-slate-400">{{ $vendor->email }}</p>
                                </div>
                            </div>
                            <span class="bg-blue-50/80 text-blue-700 text-[9px] font-extrabold px-2 py-0.5 rounded-full uppercase tracking-wider border border-blue-100/30">
                                {{ $vendor->branches_count }} Cabang
                            </span>
                        </div>
                        <div class="flex justify-between items-center text-[10px] text-slate-400 mt-2 pt-2 border-t border-slate-100/50">
                            <span>Bergabung</span>
                            <span class="font-semibold text-slate-600">{{ $vendor->created_at->diffForHumans() }}</span>
                        </div>
                    </div>
                    @empty
                    <div class="p-6 text-center text-slate-400 text-xs font-medium">Belum ada vendor terdaftar.</div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Top Performing Vendors -->
        <div class="bg-white rounded-2xl border border-slate-200/60 shadow-sm shadow-slate-100/30 overflow-hidden flex flex-col">
            <div class="p-5 px-6 border-b border-slate-100 bg-slate-50/30 flex justify-between items-center">
                <h3 class="text-sm font-extrabold text-slate-800 tracking-tight uppercase">Top Performer (Revenue)</h3>
                <span class="text-[9px] font-black text-slate-400 bg-slate-100 px-2 py-0.5 rounded-md uppercase tracking-widest">Berdasarkan Omset</span>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse hidden md:table">
                    <thead>
                        <tr class="bg-slate-50/50 border-b border-slate-100">
                            <th class="px-6 py-3.5 text-[10px] font-bold text-slate-400 uppercase tracking-wider w-16">Peringkat</th>
                            <th class="px-6 py-3.5 text-left text-[10px] font-bold text-slate-400 uppercase tracking-wider">Vendor</th>
                            <th class="px-6 py-3.5 text-right text-[10px] font-bold text-slate-400 uppercase tracking-wider">Total Revenue</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($topVendors as $vendor)
                        <tr class="hover:bg-slate-50/50 transition-colors duration-150">
                            <td class="px-6 py-4">
                                <div class="w-8 h-8 rounded-lg flex items-center justify-center font-extrabold text-xs
                                    {{ $loop->iteration == 1 ? 'bg-amber-100 text-amber-800 border border-amber-200/30 shadow-sm shadow-amber-100/40' : '' }}
                                    {{ $loop->iteration == 2 ? 'bg-slate-100 text-slate-700 border border-slate-200/30 shadow-sm shadow-slate-100/40' : '' }}
                                    {{ $loop->iteration == 3 ? 'bg-orange-50 text-orange-800 border border-orange-200/30 shadow-sm shadow-orange-50/40' : '' }}
                                    {{ $loop->iteration > 3 ? 'bg-slate-50 text-slate-500' : '' }}">
                                    {{ $loop->iteration }}
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <p class="text-sm font-extrabold text-slate-800 tracking-tight">{{ $vendor->name }}</p>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <p class="text-sm font-black text-slate-800 font-mono">Rp{{ number_format($vendor->transactions_sum_total_amount ?? 0, 0, ',', '.') }}</p>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="px-6 py-8 text-center text-slate-400 text-xs font-medium">Belum ada data transaksi.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>

                <!-- Mobile Card Layout -->
                <div class="block md:hidden divide-y divide-slate-100">
                    @forelse($topVendors as $vendor)
                    <div class="p-4 hover:bg-slate-50/50 transition-colors duration-150 flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <div class="w-6 h-6 rounded flex items-center justify-center font-extrabold text-[10px]
                                {{ $loop->iteration == 1 ? 'bg-amber-100 text-amber-800 border border-amber-200/30' : '' }}
                                {{ $loop->iteration == 2 ? 'bg-slate-100 text-slate-700 border border-slate-200/30' : '' }}
                                {{ $loop->iteration == 3 ? 'bg-orange-50 text-orange-800 border border-orange-200/30' : '' }}
                                {{ $loop->iteration > 3 ? 'bg-slate-50 text-slate-500' : '' }}">
                                {{ $loop->iteration }}
                            </div>
                            <p class="text-xs font-bold text-slate-800 tracking-tight">{{ $vendor->name }}</p>
                        </div>
                        <p class="text-xs font-black text-slate-800 font-mono">Rp{{ number_format($vendor->transactions_sum_total_amount ?? 0, 0, ',', '.') }}</p>
                    </div>
                    @empty
                    <div class="p-6 text-center text-slate-400 text-xs font-medium">Belum ada data transaksi.</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
@endsection
