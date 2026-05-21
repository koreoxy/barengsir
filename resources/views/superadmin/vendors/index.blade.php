@extends('layouts.superadmin')

@section('title', 'Daftar Vendor')

@section('content')
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200/60 overflow-hidden">
        <!-- Header & Search -->
        <div class="p-5 px-6 border-b border-slate-100 bg-slate-50/30 flex flex-col md:flex-row md:items-center justify-between gap-4">
            <form action="{{ route('superadmin.vendors.index') }}" method="GET" class="flex flex-1 max-w-lg gap-2">
                <div class="relative flex-1">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                        <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                    <input type="text" name="search" value="{{ request('search') }}" 
                           class="block w-full pl-10 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold text-slate-800 placeholder-slate-400 focus:bg-white focus:border-blue-500 focus:ring-1 focus:ring-blue-500 focus:outline-none transition-all" 
                           placeholder="Cari nama vendor atau email...">
                </div>
                <button type="submit" class="px-5 py-2.5 bg-slate-800 text-white text-xs font-bold rounded-xl hover:bg-slate-700 active:scale-[0.98] transition-all shadow-sm border border-slate-700/10">
                    Cari
                </button>
            </form>

            <a href="{{ route('superadmin.vendors.create') }}" class="inline-flex items-center justify-center gap-2 px-5 py-2.5 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-500 hover:to-indigo-500 active:scale-[0.98] text-white text-xs font-bold rounded-xl transition-all shadow-md shadow-blue-500/15 border border-blue-500/20">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                Tambah Vendor
            </a>
        </div>

        <!-- Table -->
        <div class="p-0 overflow-x-auto">
            <table class="w-full text-left border-collapse hidden md:table">
                <thead class="bg-slate-50/50 border-b border-slate-100">
                    <tr>
                        <th class="px-6 py-3.5 text-[10px] font-bold text-slate-400 uppercase tracking-widest text-left">Vendor</th>
                        <th class="px-6 py-3.5 text-[10px] font-bold text-slate-400 uppercase tracking-widest text-center">Cabang</th>
                        <th class="px-6 py-3.5 text-[10px] font-bold text-slate-400 uppercase tracking-widest text-center">User</th>
                        <th class="px-6 py-3.5 text-[10px] font-bold text-slate-400 uppercase tracking-widest text-center">Status</th>
                        <th class="px-6 py-3.5 text-[10px] font-bold text-slate-400 uppercase tracking-widest text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($vendors as $vendor)
                    <tr class="hover:bg-slate-50/50 transition-colors duration-150">
                        <td class="px-6 py-4">
                            <div class="flex items-center space-x-3.5">
                                <div class="w-10 h-10 bg-gradient-to-tr from-blue-500/10 to-indigo-500/10 text-blue-600 rounded-xl flex items-center justify-center font-bold text-base border border-blue-200/30 uppercase shadow-inner">
                                    {{ substr($vendor->name, 0, 1) }}
                                </div>
                                <div>
                                    <p class="text-sm font-extrabold text-slate-800 tracking-tight">{{ $vendor->name }}</p>
                                    <p class="text-xs text-slate-400 mt-0.5">{{ $vendor->email }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="bg-slate-50 text-slate-700 text-xs font-bold px-2.5 py-1 rounded-lg border border-slate-200/40">
                                {{ $vendor->branches_count }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="bg-slate-50 text-slate-700 text-xs font-bold px-2.5 py-1 rounded-lg border border-slate-200/40">
                                {{ $vendor->users_count }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            @if($vendor->is_active)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-extrabold uppercase tracking-wider bg-emerald-50 text-emerald-700 border border-emerald-100/50 shadow-sm shadow-emerald-50/50 animate-pulse">
                                    Active
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-extrabold uppercase tracking-wider bg-rose-50 text-rose-700 border border-rose-100/50 shadow-sm shadow-rose-50/50">
                                    Suspended
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end space-x-1.5">
                                <a href="{{ route('superadmin.vendors.show', $vendor) }}" class="p-1.5 text-slate-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-all" title="Detail">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                </a>
                                <a href="{{ route('superadmin.vendors.edit', $vendor) }}" class="p-1.5 text-slate-400 hover:text-amber-600 hover:bg-amber-50 rounded-lg transition-all" title="Edit">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                </a>
                                <form action="{{ route('superadmin.vendors.destroy', $vendor) }}" method="POST" class="inline" onsubmit="return confirm('Hapus vendor ini? Tindakan ini tidak bisa dibatalkan.')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-1.5 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition-all" title="Hapus">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-slate-400">
                            <svg class="w-12 h-12 mx-auto mb-4 text-slate-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                            Belum ada vendor terdaftar.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>

            <!-- Mobile Card Layout -->
            <div class="block md:hidden divide-y divide-slate-100">
                @forelse($vendors as $vendor)
                <div class="p-4 hover:bg-slate-50/50 transition-colors duration-150">
                    <div class="flex items-center justify-between mb-3">
                        <div class="flex items-center space-x-3">
                            <div class="w-8 h-8 bg-gradient-to-tr from-blue-500/10 to-indigo-500/10 text-blue-600 rounded-lg flex items-center justify-center font-bold text-sm border border-blue-200/30 uppercase shadow-inner">
                                {{ substr($vendor->name, 0, 1) }}
                            </div>
                            <div>
                                <p class="text-xs font-bold text-slate-800 tracking-tight">{{ $vendor->name }}</p>
                                <p class="text-[10px] text-slate-400">{{ $vendor->email }}</p>
                            </div>
                        </div>
                        @if($vendor->is_active)
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[9px] font-extrabold uppercase tracking-wider bg-emerald-50 text-emerald-700 border border-emerald-100/50 shadow-sm shadow-emerald-50/50">
                                Active
                            </span>
                        @else
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[9px] font-extrabold uppercase tracking-wider bg-rose-50 text-rose-700 border border-rose-100/50 shadow-sm shadow-rose-50/50">
                                Suspended
                            </span>
                        @endif
                    </div>
                    <div class="flex justify-between items-center text-[10px] text-slate-400 mt-2 py-1">
                        <div class="flex space-x-2">
                            <span class="bg-slate-50 text-slate-700 font-bold px-2 py-0.5 rounded border border-slate-200/40">
                                {{ $vendor->branches_count }} Cabang
                            </span>
                            <span class="bg-slate-50 text-slate-700 font-bold px-2 py-0.5 rounded border border-slate-200/40">
                                {{ $vendor->users_count }} User
                            </span>
                        </div>
                        <div class="flex items-center space-x-1">
                            <a href="{{ route('superadmin.vendors.show', $vendor) }}" class="p-1.5 text-slate-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-all" title="Detail">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            </a>
                            <a href="{{ route('superadmin.vendors.edit', $vendor) }}" class="p-1.5 text-slate-400 hover:text-amber-600 hover:bg-amber-50 rounded-lg transition-all" title="Edit">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            </a>
                            <form action="{{ route('superadmin.vendors.destroy', $vendor) }}" method="POST" class="inline" onsubmit="return confirm('Hapus vendor ini? Tindakan ini tidak bisa dibatalkan.')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-1.5 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition-all" title="Hapus">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                @empty
                <div class="p-6 text-center text-slate-400 text-xs font-medium">Belum ada vendor terdaftar.</div>
                @endforelse
            </div>
        </div>

        @if($vendors->hasPages())
        <div class="p-6 border-t border-slate-100 bg-slate-50/30">
            {{ $vendors->links() }}
        </div>
        @endif
    </div>
@endsection
