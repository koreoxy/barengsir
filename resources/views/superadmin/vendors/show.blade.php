@extends('layouts.superadmin')

@section('title', 'Detail Vendor')

@section('content')
    <div class="mb-8">
        <a href="{{ route('superadmin.vendors.index') }}" class="text-sm font-semibold text-blue-600 hover:text-blue-800 flex items-center transition-colors">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Kembali ke Daftar
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Sidebar: Info Profil -->
        <div class="space-y-6">
            <div class="bg-white rounded-xl shadow-sm border border-slate-100 p-6">
                <div class="flex flex-col items-center text-center mb-6">
                    <div class="w-20 h-20 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center font-black text-2xl border border-blue-100 mb-4 shadow-inner">
                        {{ substr($vendor->name, 0, 1) }}
                    </div>
                    <h3 class="text-base font-bold text-slate-800 uppercase tracking-tight">{{ $vendor->name }}</h3>
                    <p class="text-sm text-slate-400 mt-1">{{ $vendor->email }}</p>
                    <div class="mt-4">
                        @if($vendor->is_active)
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-600 border border-emerald-100">
                                <span class="w-2 h-2 bg-emerald-500 rounded-full mr-2 animate-pulse"></span>
                                Terverifikasi & Aktif
                            </span>
                        @else
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-rose-50 text-rose-600 border border-rose-100">
                                <span class="w-2 h-2 bg-rose-500 rounded-full mr-2"></span>
                                Suspended / Nonaktif
                            </span>
                        @endif
                    </div>
                </div>

                <div class="space-y-4 pt-6 border-t border-slate-100">
                    <div>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Telepon</p>
                        <p class="text-sm font-bold text-slate-700">{{ $vendor->phone ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Alamat</p>
                        <p class="text-sm text-slate-600 leading-relaxed">{{ $vendor->address ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Terdaftar Sejak</p>
                        <p class="text-sm font-bold text-slate-700">{{ $vendor->created_at->format('d M Y') }}</p>
                    </div>
                </div>

                <div class="mt-8">
                    <a href="{{ route('superadmin.vendors.edit', $vendor) }}" class="flex items-center justify-center w-full py-2.5 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-500 hover:to-indigo-500 active:scale-[0.98] text-white text-xs font-bold rounded-xl transition-all shadow-md shadow-blue-500/15 border border-blue-500/20">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                        Edit Profil Vendor
                    </a>
                </div>
            </div>
        </div>

        <!-- Main Content: Statistik & Data -->
        <div class="lg:col-span-2 space-y-8">
            <!-- Stats Summary -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="bg-blue-600 rounded-xl p-6 text-white shadow-lg shadow-blue-100">
                    <div class="flex justify-between items-start mb-4">
                        <p class="text-xs font-bold text-blue-200 uppercase tracking-widest">Total Revenue Vendor</p>
                        <div class="bg-blue-500/50 p-2 rounded-lg text-blue-100">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                    </div>
                    <h4 class="text-3xl font-black italic">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</h4>
                    <p class="text-xs text-blue-200 mt-2 font-medium">Dari {{ number_format($totalTransactions) }} transaksi sukses</p>
                </div>

                <div class="bg-white rounded-xl shadow-sm border border-slate-100 p-6 flex items-center justify-between">
                    <div>
                        <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-1">Cabang Aktif</p>
                        <h4 class="text-3xl font-black text-slate-800">{{ $vendor->branches->count() }}</h4>
                        <p class="text-xs text-slate-400 mt-1 font-medium">Titik operasional vendor</p>
                    </div>
                    <div class="w-12 h-12 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                    </div>
                </div>
            </div>

            <!-- Cabang List -->
            <div class="bg-white rounded-xl shadow-sm border border-slate-100 overflow-hidden">
                <div class="p-6 border-b border-slate-100 bg-slate-50/30">
                    <h3 class="font-bold text-slate-800">Daftar Cabang</h3>
                </div>
                <div class="p-0 overflow-x-auto">
                    <table class="w-full text-left border-collapse hidden md:table">
                        <thead class="bg-slate-50/50 border-b border-slate-100">
                            <tr>
                                <th class="px-6 py-3.5 text-[10px] font-bold text-slate-400 uppercase tracking-widest text-left">Nama Cabang</th>
                                <th class="px-6 py-3.5 text-[10px] font-bold text-slate-400 uppercase tracking-widest text-left">Kode</th>
                                <th class="px-6 py-3.5 text-[10px] font-bold text-slate-400 uppercase tracking-widest text-left">Kontak</th>
                                <th class="px-6 py-3.5 text-[10px] font-bold text-slate-400 uppercase tracking-widest text-center">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($vendor->branches as $branch)
                            <tr class="hover:bg-slate-50/50 transition-colors duration-150">
                                <td class="px-6 py-4">
                                    <p class="text-sm font-bold text-slate-800">{{ $branch->name }}</p>
                                    <p class="text-xs text-slate-400 line-clamp-1 mt-0.5">{{ $branch->address }}</p>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="text-xs font-black bg-slate-50 text-slate-600 px-2.5 py-1 rounded-lg border border-slate-200/40 font-mono">{{ $branch->code }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    <p class="text-xs font-bold text-slate-700">{{ $branch->phone ?? '-' }}</p>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    @if($branch->is_active)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-extrabold uppercase tracking-wider bg-emerald-50 text-emerald-700 border border-emerald-100/50 shadow-sm shadow-emerald-50/50 animate-pulse">
                                            Active
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-extrabold uppercase tracking-wider bg-rose-50 text-rose-700 border border-rose-100/50 shadow-sm shadow-rose-50/50">
                                            Suspended
                                        </span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="px-6 py-8 text-center text-slate-400 text-xs font-medium">Belum ada cabang.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>

                    <!-- Mobile Card Layout -->
                    <div class="block md:hidden divide-y divide-slate-100">
                        @forelse($vendor->branches as $branch)
                        <div class="p-4 hover:bg-slate-50/50 transition-colors duration-150">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-xs font-bold text-slate-800">{{ $branch->name }}</span>
                                @if($branch->is_active)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[9px] font-extrabold uppercase tracking-wider bg-emerald-50 text-emerald-700 border border-emerald-100/50 animate-pulse">
                                        Active
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[9px] font-extrabold uppercase tracking-wider bg-rose-50 text-rose-700 border border-rose-100/50">
                                        Suspended
                                    </span>
                                @endif
                            </div>
                            <p class="text-[10px] text-slate-400 line-clamp-2">{{ $branch->address }}</p>
                            <div class="flex justify-between items-center text-[10px] text-slate-400 mt-3 pt-2 border-t border-slate-100/50">
                                <span class="bg-slate-50 text-slate-700 font-bold px-2 py-0.5 rounded border border-slate-200/40 font-mono">{{ $branch->code }}</span>
                                <span class="font-semibold text-slate-600">{{ $branch->phone ?? '-' }}</span>
                            </div>
                        </div>
                        @empty
                        <div class="p-6 text-center text-slate-400 text-xs font-medium">Belum ada cabang.</div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Team / Users List -->
            <div class="bg-white rounded-xl shadow-sm border border-slate-100 overflow-hidden">
                <div class="p-6 border-b border-slate-100 bg-slate-50/30 flex justify-between items-center">
                    <h3 class="font-bold text-slate-800">Tim & Pengguna</h3>
                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">{{ $vendor->users->count() }} Orang Terdaftar</span>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach($vendor->users as $user)
                        <div class="flex items-center p-3 bg-slate-50 rounded-lg border border-slate-200/60 shadow-sm">
                            <div class="w-8 h-8 rounded-md bg-blue-50 border border-blue-100 flex items-center justify-center font-bold text-blue-600 text-xs mr-3 shadow-inner shrink-0">
                                {{ substr($user->name, 0, 1) }}
                            </div>
                            <div class="min-w-0">
                                <p class="text-sm font-bold text-slate-800 truncate">{{ $user->name }}</p>
                                <p class="text-[10px] text-slate-400 uppercase tracking-wider font-bold truncate">{{ $user->pivot->role ?? 'Staff' }}</p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
