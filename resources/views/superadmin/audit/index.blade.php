@extends('layouts.superadmin')

@section('title', 'Audit & Keamanan Sistem')

@section('content')
<div class="space-y-6" x-data="{ currentTab: '{{ $tab }}' }">
    <!-- Header Page -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-xl md:text-2xl font-black text-slate-800 tracking-tight">Audit Trail & Keamanan</h1>
            <p class="text-xs md:text-sm text-slate-500 font-medium mt-1">Lacak log aktivitas operasional pengguna serta perubahan database secara real-time.</p>
        </div>
    </div>

    <!-- Tab Navigation -->
    <div class="flex border-b border-slate-200 gap-6 overflow-x-auto pb-1.5">
        <button @click="currentTab = 'activity'; const url = new URL(window.location); url.searchParams.set('tab', 'activity'); window.history.pushState({}, '', url);"
                :class="currentTab === 'activity' ? 'border-blue-600 text-blue-600 font-bold' : 'border-transparent text-slate-500 hover:text-slate-700'"
                class="py-3 px-1 border-b-2 text-sm whitespace-nowrap transition-all focus:outline-none flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 11c0 3.517-1.009 6.799-2.753 9.571m-3.44-2.04l.054-.09A13.916 13.916 0 009 11.5a13.96 13.96 0 00-2.293-8.174m1.035 3.206A13.952 13.952 0 0012 10.5c1.213-3.834 3.857-7.311 7.4-8.525a7.488 7.488 0 011.083 4.717m0 0A12.017 12.017 0 0121 12a12.017 12.017 0 01-1.083 4.717m0 0c-2.94-3.46-7.1-5.585-11.75-5.585" />
            </svg>
            Log Aktivitas User
        </button>
        <button @click="currentTab = 'mutation'; const url = new URL(window.location); url.searchParams.set('tab', 'mutation'); window.history.pushState({}, '', url);"
                :class="currentTab === 'mutation' ? 'border-blue-600 text-blue-600 font-bold' : 'border-transparent text-slate-500 hover:text-slate-700'"
                class="py-3 px-1 border-b-2 text-sm whitespace-nowrap transition-all focus:outline-none flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
            </svg>
            Riwayat Perubahan Data (Audit Trail)
        </button>
    </div>

    <!-- Filters Section -->
    <div class="bg-white rounded-2xl border border-slate-100 p-5 shadow-sm">
        <form method="GET" action="{{ route('superadmin.audit.index') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 items-end">
            <input type="hidden" name="tab" :value="currentTab">

            <!-- Filter User -->
            <div>
                <label for="user_id" class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-2">Pengguna</label>
                <select id="user_id" name="user_id" 
                        class="block w-full px-3 py-2.5 rounded-xl border border-slate-200 bg-slate-50 text-xs text-slate-700 focus:outline-none focus:ring-4 focus:ring-blue-500/20 focus:border-blue-600 focus:bg-white transition-all">
                    <option value="">Semua Pengguna</option>
                    @foreach($users as $u)
                        <option value="{{ $u->id }}" {{ request('user_id') == $u->id ? 'selected' : '' }}>
                            {{ $u->name }} ({{ $u->branch ? $u->branch->name : 'Super Admin' }})
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Filter Date -->
            <div>
                <label for="date" class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-2">Tanggal</label>
                <input type="date" id="date" name="date" value="{{ request('date') }}"
                       class="block w-full px-3 py-2 rounded-xl border border-slate-200 bg-slate-50 text-xs text-slate-700 focus:outline-none focus:ring-4 focus:ring-blue-500/20 focus:border-blue-600 focus:bg-white transition-all">
            </div>

            <!-- Tab Specific Filters -->
            <div x-show="currentTab === 'activity'">
                <label for="search" class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-2">Pencarian Aktivitas</label>
                <input type="text" id="search" name="search" placeholder="Contoh: login, checkout" value="{{ request('search') }}"
                       class="block w-full px-3 py-2 rounded-xl border border-slate-200 bg-slate-50 text-xs text-slate-700 focus:outline-none focus:ring-4 focus:ring-blue-500/20 focus:border-blue-600 focus:bg-white transition-all">
            </div>

            <div x-show="currentTab === 'mutation'">
                <label for="event" class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-2">Aksi Perubahan</label>
                <select id="event" name="event" 
                        class="block w-full px-3 py-2.5 rounded-xl border border-slate-200 bg-slate-50 text-xs text-slate-700 focus:outline-none focus:ring-4 focus:ring-blue-500/20 focus:border-blue-600 focus:bg-white transition-all">
                    <option value="">Semua Aksi</option>
                    <option value="created" {{ request('event') == 'created' ? 'selected' : '' }}>Created (Tambah)</option>
                    <option value="updated" {{ request('event') == 'updated' ? 'selected' : '' }}>Updated (Ubah)</option>
                    <option value="deleted" {{ request('event') == 'deleted' ? 'selected' : '' }}>Deleted (Hapus)</option>
                </select>
            </div>

            <!-- Filter Buttons -->
            <div class="flex gap-2">
                <button type="submit" 
                        class="flex-1 inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-blue-600 hover:bg-blue-500 text-white text-xs font-bold uppercase tracking-wider rounded-xl shadow-md transition-all active:scale-98">
                    Filter
                </button>
                <a href="{{ route('superadmin.audit.index') }}" 
                   class="inline-flex items-center justify-center px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-600 text-xs font-bold uppercase tracking-wider rounded-xl transition-all active:scale-98">
                    Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Content Sections -->
    <!-- 1. Activity Logs Tab -->
    <div x-show="currentTab === 'activity'" class="space-y-4">
        <div class="bg-white rounded-2xl border border-slate-100 overflow-hidden shadow-sm">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50/75 border-b border-slate-100 text-[10px] font-bold uppercase text-slate-400 tracking-wider">
                            <th class="px-6 py-4">Waktu</th>
                            <th class="px-6 py-4">Pengguna</th>
                            <th class="px-6 py-4">Cabang</th>
                            <th class="px-6 py-4">Aktivitas</th>
                            <th class="px-6 py-4">Deskripsi</th>
                            <th class="px-6 py-4">IP Address</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-xs text-slate-700">
                        @forelse($activities as $act)
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="px-6 py-4 font-medium text-slate-500 whitespace-nowrap">
                                    {{ $act->created_at->format('Y-m-d H:i:s') }}
                                </td>
                                <td class="px-6 py-4 font-bold text-slate-800 whitespace-nowrap">
                                    {{ $act->user ? $act->user->name : 'System / Guest' }}
                                </td>
                                <td class="px-6 py-4 text-slate-500 whitespace-nowrap">
                                    {{ $act->user && $act->user->branch ? $act->user->branch->name : 'Super Admin' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wide
                                        {{ in_array($act->activity, ['login', 'logout']) ? 'bg-blue-50 text-blue-700' : 'bg-slate-100 text-slate-600' }}">
                                        {{ $act->activity }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 font-medium">
                                    {{ $act->description }}
                                </td>
                                <td class="px-6 py-4 text-slate-400 font-mono whitespace-nowrap">
                                    {{ $act->ip_address ?? '-' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-8 text-center text-slate-400 font-medium">
                                    Tidak ada data log aktivitas ditemukan.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($activities->hasPages())
                <div class="px-6 py-4 border-t border-slate-100 bg-slate-50/30">
                    {{ $activities->links() }}
                </div>
            @endif
        </div>
    </div>

    <!-- 2. Audit Trails Tab -->
    <div x-show="currentTab === 'mutation'" class="space-y-4">
        <div class="bg-white rounded-2xl border border-slate-100 overflow-hidden shadow-sm" x-data="{ expandedId: null }">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50/75 border-b border-slate-100 text-[10px] font-bold uppercase text-slate-400 tracking-wider">
                            <th class="px-6 py-4">Waktu</th>
                            <th class="px-6 py-4">Pengguna</th>
                            <th class="px-6 py-4">Model Data</th>
                            <th class="px-6 py-4">ID</th>
                            <th class="px-6 py-4">Aksi</th>
                            <th class="px-6 py-4 text-right">Detail Nilai</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-xs text-slate-700">
                        @forelse($audits as $audit)
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="px-6 py-4 font-medium text-slate-500 whitespace-nowrap">
                                    {{ $audit->created_at->format('Y-m-d H:i:s') }}
                                </td>
                                <td class="px-6 py-4 font-bold text-slate-800 whitespace-nowrap">
                                    {{ $audit->user ? $audit->user->name : 'System / Seeder' }}
                                </td>
                                <td class="px-6 py-4 font-medium text-slate-600 whitespace-nowrap">
                                    {{ class_basename($audit->auditable_type) }}
                                </td>
                                <td class="px-6 py-4 font-mono text-slate-400 whitespace-nowrap">
                                    #{{ $audit->auditable_id }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wide
                                        {{ $audit->event === 'created' ? 'bg-emerald-50 text-emerald-700 border border-emerald-100' : '' }}
                                        {{ $audit->event === 'updated' ? 'bg-amber-50 text-amber-700 border border-amber-100' : '' }}
                                        {{ $audit->event === 'deleted' ? 'bg-rose-50 text-rose-700 border border-rose-100' : '' }}
                                    ">
                                        {{ $audit->event }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right whitespace-nowrap">
                                    <button @click="expandedId = (expandedId === {{ $audit->id }} ? null : {{ $audit->id }})" 
                                            class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-lg font-bold text-[10px] uppercase tracking-wider transition-all">
                                        <span x-text="expandedId === {{ $audit->id }} ? 'Sembunyikan' : 'Lihat Delta'"></span>
                                        <svg class="w-3 h-3 transition-transform" :class="expandedId === {{ $audit->id }} ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                        </svg>
                                    </button>
                                </td>
                            </tr>
                            <!-- Collapsible JSON Delta View -->
                            <tr x-show="expandedId === {{ $audit->id }}" x-transition class="bg-slate-50/50">
                                <td colspan="6" class="px-8 py-5 border-t border-slate-100">
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <!-- Old Values -->
                                        <div class="bg-white p-4 rounded-xl border border-slate-200/60 shadow-sm">
                                            <div class="flex items-center gap-2 mb-2">
                                                <span class="w-2 h-2 rounded-full bg-rose-500"></span>
                                                <h4 class="text-[10px] font-bold text-slate-500 uppercase tracking-wider">Nilai Sebelum (Old Data)</h4>
                                            </div>
                                            @if(!empty($audit->old_values))
                                                <pre class="bg-slate-900 text-slate-300 font-mono text-[10px] p-4 rounded-lg overflow-x-auto max-h-60 leading-normal">{{ json_encode($audit->old_values, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) }}</pre>
                                            @else
                                                <p class="text-xs text-slate-400 font-medium italic mt-2">Tidak ada data sebelumnya (Event Created).</p>
                                            @endif
                                        </div>

                                        <!-- New Values -->
                                        <div class="bg-white p-4 rounded-xl border border-slate-200/60 shadow-sm">
                                            <div class="flex items-center gap-2 mb-2">
                                                <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                                                <h4 class="text-[10px] font-bold text-slate-500 uppercase tracking-wider">Nilai Sesudah (New Data)</h4>
                                            </div>
                                            @if(!empty($audit->new_values))
                                                <pre class="bg-slate-900 text-slate-300 font-mono text-[10px] p-4 rounded-lg overflow-x-auto max-h-60 leading-normal">{{ json_encode($audit->new_values, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) }}</pre>
                                            @else
                                                <p class="text-xs text-slate-400 font-medium italic mt-2">Tidak ada data baru (Event Deleted).</p>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="mt-4 flex justify-between items-center text-[10px] text-slate-400 font-medium">
                                        <span>User Agent: {{ $audit->user_agent }}</span>
                                        <span>IP Address: {{ $audit->ip_address }}</span>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-8 text-center text-slate-400 font-medium">
                                    Tidak ada data riwayat perubahan (audit trail) ditemukan.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($audits->hasPages())
                <div class="px-6 py-4 border-t border-slate-100 bg-slate-50/30">
                    {{ $audits->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
