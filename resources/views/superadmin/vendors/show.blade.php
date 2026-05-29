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
                <div class="p-6 border-b border-slate-100 bg-slate-50/30 flex justify-between items-center flex-wrap gap-4">
                    <div>
                        <h3 class="font-bold text-slate-800">Daftar Cabang</h3>
                        <p class="text-xs text-slate-400 mt-0.5">Kelola titik operasional dan cabang milik vendor</p>
                    </div>
                    <div class="flex items-center gap-3">
                        <span class="text-[10px] font-bold bg-slate-100 text-slate-500 px-2.5 py-1 rounded-lg border border-slate-200/40">{{ $vendor->branches->count() }} Cabang</span>
                        <a href="{{ route('superadmin.vendors.branches.create', $vendor) }}" class="inline-flex items-center justify-center px-4 py-2 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-500 hover:to-indigo-500 active:scale-[0.98] text-white text-xs font-bold rounded-xl transition-all shadow-md shadow-blue-500/15 border border-blue-500/20">
                            <svg class="w-3.5 h-3.5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                            Tambah Cabang
                        </a>
                    </div>
                </div>
                <div class="p-0 overflow-x-auto">
                    <table class="w-full text-left border-collapse hidden md:table">
                        <thead class="bg-slate-50/50 border-b border-slate-100">
                            <tr>
                                <th class="px-6 py-3.5 text-[10px] font-bold text-slate-400 uppercase tracking-widest text-left">Nama Cabang</th>
                                <th class="px-6 py-3.5 text-[10px] font-bold text-slate-400 uppercase tracking-widest text-left">Kode</th>
                                <th class="px-6 py-3.5 text-[10px] font-bold text-slate-400 uppercase tracking-widest text-left">Kontak</th>
                                <th class="px-6 py-3.5 text-[10px] font-bold text-slate-400 uppercase tracking-widest text-center">Status</th>
                                <th class="px-6 py-3.5 text-[10px] font-bold text-slate-400 uppercase tracking-widest text-center">Aksi</th>
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
                                <td class="px-6 py-4 text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <a href="{{ route('superadmin.vendors.branches.edit', [$vendor, $branch]) }}" class="p-1 text-slate-400 hover:text-blue-600 rounded hover:bg-blue-50 transition-colors" title="Edit Cabang">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                        </a>
                                        <form action="{{ route('superadmin.vendors.branches.destroy', [$vendor, $branch]) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus cabang ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-1 text-slate-400 hover:text-rose-600 rounded hover:bg-rose-50 transition-colors" title="Hapus Cabang">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="px-6 py-8 text-center text-slate-400 text-xs font-medium">Belum ada cabang.</td>
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
                                <div class="flex items-center gap-3">
                                    <a href="{{ route('superadmin.vendors.branches.edit', [$vendor, $branch]) }}" class="text-blue-600 font-bold hover:underline">Edit</a>
                                    <form action="{{ route('superadmin.vendors.branches.destroy', [$vendor, $branch]) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus cabang ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-rose-600 font-bold hover:underline">Hapus</button>
                                    </form>
                                </div>
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
                <div class="p-6 border-b border-slate-100 bg-slate-50/30 flex justify-between items-center flex-wrap gap-4">
                    <div>
                        <h3 class="font-bold text-slate-800">Tim & Pengguna</h3>
                        <p class="text-xs text-slate-400 mt-0.5">Kelola akun dan hak akses personil vendor</p>
                    </div>
                    <div class="flex items-center gap-3">
                        <span class="text-[10px] font-bold bg-slate-100 text-slate-500 px-2.5 py-1 rounded-lg border border-slate-200/40">{{ $vendor->users->count() }} Orang Terdaftar</span>
                        <a href="{{ route('superadmin.vendors.users.create', $vendor) }}" class="inline-flex items-center justify-center px-4 py-2 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-500 hover:to-indigo-500 active:scale-[0.98] text-white text-xs font-bold rounded-xl transition-all shadow-md shadow-blue-500/15 border border-blue-500/20">
                            <svg class="w-3.5 h-3.5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                            Tambah Pengguna
                        </a>
                    </div>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach($vendor->users as $user)
                        @php
                            $branch = $user->pivot->branch_id ? $vendor->branches->firstWhere('id', $user->pivot->branch_id) : null;
                        @endphp
                        <div class="flex items-center justify-between p-3.5 bg-slate-50 rounded-lg border border-slate-200/60 shadow-sm hover:border-slate-300 transition-colors">
                            <div class="flex items-center min-w-0">
                                <div class="w-9 h-9 rounded-md bg-blue-50 border border-blue-100 flex items-center justify-center font-bold text-blue-600 text-sm mr-3 shadow-inner shrink-0">
                                    {{ substr($user->name, 0, 1) }}
                                </div>
                                <div class="min-w-0">
                                    <p class="text-sm font-bold text-slate-800 truncate" title="{{ $user->name }}">{{ $user->name }}</p>
                                    <div class="flex items-center gap-1.5 mt-0.5 flex-wrap">
                                        <span class="text-[9px] text-blue-600 bg-blue-50 px-1.5 py-0.5 rounded font-extrabold uppercase tracking-wider">{{ $user->pivot->role ?? 'Staff' }}</span>
                                        @if($branch)
                                            <span class="text-[9px] text-slate-500 bg-slate-100 px-1.5 py-0.5 rounded font-semibold">{{ $branch->name }}</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <form action="{{ route('superadmin.vendors.users.destroy', [$vendor, $user]) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus akses pengguna ini dari vendor?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-1.5 text-slate-400 hover:text-rose-600 rounded-lg hover:bg-rose-50 transition-colors duration-150" title="Hapus Akses">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                            </form>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
