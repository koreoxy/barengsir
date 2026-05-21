@extends('layouts.superadmin')

@section('title', 'Database Backups')

@section('content')
<div class="space-y-6" x-data="{ 
    showRestoreModal: false, 
    confirmText: '', 
    targetRestoreFile: '',
    restoring: false,
    formatBytes(bytes, decimals = 2) {
        if (bytes === 0) return '0 Bytes';
        const k = 1024;
        const dm = decimals < 0 ? 0 : decimals;
        const sizes = ['Bytes', 'KB', 'MB', 'GB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return parseFloat((bytes / Math.pow(k, i)).toFixed(dm)) + ' ' + sizes[i];
    }
}">

    <!-- Restoring Fullscreen Overlay Loader -->
    <div x-show="restoring" 
         class="fixed inset-0 z-50 bg-slate-900/80 backdrop-blur-md flex flex-col items-center justify-center text-white"
         x-cloak>
        <div class="w-16 h-16 border-4 border-slate-700 border-t-blue-500 rounded-full animate-spin mb-6"></div>
        <h3 class="text-lg font-black tracking-tight">Sedang Memulihkan Database...</h3>
        <p class="text-xs text-slate-400 font-medium mt-2 max-w-sm text-center px-6 leading-relaxed">Sistem sedang memuat snapshot data cadangan. Harap tidak menutup atau menyegarkan halaman browser ini.</p>
    </div>

    <!-- Header Page -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-xl md:text-2xl font-black text-slate-800 tracking-tight">Database Backups & Recovery</h1>
            <p class="text-xs md:text-sm text-slate-500 font-medium mt-1">Kelola arsip snapshot database, buat cadangan baru, atau kembalikan sistem ke status sebelumnya.</p>
        </div>
        
        <!-- Action Trigger Backup -->
        <form method="POST" action="{{ route('superadmin.backups.store') }}" @submit="restoring = true">
            @csrf
            <button type="submit" 
                    class="inline-flex items-center gap-2 px-5 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-500 hover:to-indigo-500 text-white text-xs font-bold uppercase tracking-wider rounded-xl shadow-lg shadow-blue-500/20 active:scale-98 transition-all">
                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                Buat Backup Baru
            </button>
        </form>
    </div>

    <!-- Database Info & Guidelines -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="bg-blue-50/50 rounded-2xl border border-blue-100 p-5 flex gap-4 lg:col-span-2">
            <div class="w-10 h-10 rounded-xl bg-blue-500/10 text-blue-600 flex items-center justify-center shrink-0 border border-blue-500/20">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div>
                <h4 class="text-xs font-bold text-blue-800 uppercase tracking-wider">Panduan Disaster Recovery</h4>
                <p class="text-xs text-blue-700/80 leading-relaxed mt-1">Setiap file backup berformat `.sql` berisi skema dan seluruh baris data transaksi, inventaris, cabang, dan user. Melakukan restore database akan **mengganti seluruh database aktif saat ini**. Aplikasi otomatis dinonaktifkan sementara (maintenance mode) selama restore agar data tetap terjaga utuh.</p>
            </div>
        </div>

        <div class="bg-white rounded-2xl border border-slate-100 p-5 shadow-sm space-y-4">
            <div class="flex items-center justify-between border-b border-slate-100 pb-2">
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Koneksi Database</span>
                <span class="px-2 py-0.5 rounded bg-emerald-50 text-emerald-700 font-mono text-[9px] font-black uppercase tracking-wider border border-emerald-100">Aktif</span>
            </div>
            <div class="grid grid-cols-2 gap-2 text-xs font-medium">
                <div>
                    <span class="text-slate-400 block text-[10px] uppercase">Driver</span>
                    <span class="text-slate-700 font-bold font-mono">{{ config('database.default') }}</span>
                </div>
                <div>
                    <span class="text-slate-400 block text-[10px] uppercase">Nama Database</span>
                    <span class="text-slate-700 font-bold font-mono">{{ config('database.connections.' . config('database.default') . '.database') }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Backups List Table -->
    <div class="bg-white rounded-2xl border border-slate-100 overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/75 border-b border-slate-100 text-[10px] font-bold uppercase text-slate-400 tracking-wider">
                        <th class="px-6 py-4">Nama File Backup</th>
                        <th class="px-6 py-4">Waktu Pembuatan</th>
                        <th class="px-6 py-4">Ukuran File</th>
                        <th class="px-6 py-4 text-right">Aksi Manajemen</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-xs text-slate-700">
                    @forelse($backups as $backup)
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="px-6 py-4 font-bold font-mono text-slate-800">
                                {{ $backup['filename'] }}
                            </td>
                            <td class="px-6 py-4 font-medium text-slate-500">
                                {{ $backup['created_at'] }}
                            </td>
                            <td class="px-6 py-4 font-bold text-slate-600 whitespace-nowrap" x-text="formatBytes({{ $backup['size'] }})">
                                {{ $backup['size'] }} bytes
                            </td>
                            <td class="px-6 py-4 text-right space-x-2 whitespace-nowrap">
                                <!-- Secure Download Link -->
                                <a href="{{ route('superadmin.backups.download', $backup['filename']) }}" 
                                   class="inline-flex items-center gap-1.5 px-3 py-2 bg-blue-50 hover:bg-blue-100 text-blue-700 rounded-xl font-bold text-[10px] uppercase tracking-wider transition-all">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                    </svg>
                                    Unduh
                                </a>

                                <!-- Double Confirmation Restore Trigger -->
                                <button type="button"
                                        @click="targetRestoreFile = '{{ $backup['filename'] }}'; confirmText = ''; showRestoreModal = true"
                                        class="inline-flex items-center gap-1.5 px-3 py-2 bg-amber-50 hover:bg-amber-100 text-amber-700 rounded-xl font-bold text-[10px] uppercase tracking-wider transition-all">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 1121.21 8H18.2" />
                                    </svg>
                                    Restore
                                </button>

                                <!-- Delete Form -->
                                <form method="POST" action="{{ route('superadmin.backups.destroy', $backup['filename']) }}" class="inline-block"
                                      onsubmit="return confirm('Apakah Anda yakin ingin menghapus file backup cadangan ini secara permanen dari server?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="inline-flex items-center gap-1.5 px-3 py-2 bg-rose-50 hover:bg-rose-100 text-rose-700 rounded-xl font-bold text-[10px] uppercase tracking-wider transition-all">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                        Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-8 text-center text-slate-400 font-medium">
                                Belum ada file database backup cadangan (.sql) di server.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Double Confirmation Restore Modal -->
    <div x-show="showRestoreModal" 
         class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4"
         x-transition
         x-cloak>
        <div class="bg-white rounded-3xl max-w-md w-full shadow-2xl border border-slate-100 p-6 md:p-8 space-y-6"
             @click.away="showRestoreModal = false">
            
            <div class="flex items-center gap-4 text-rose-600">
                <div class="w-12 h-12 rounded-2xl bg-rose-50 flex items-center justify-center text-rose-600 shrink-0 border border-rose-100">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>
                <div>
                    <h3 class="text-md font-black uppercase tracking-wider text-slate-800 leading-tight">Double Confirmation</h3>
                    <p class="text-[11px] text-slate-400 font-semibold mt-0.5">Tindakan ini sangat berbahaya & permanen!</p>
                </div>
            </div>

            <div class="space-y-3 bg-slate-50 p-4 rounded-2xl border border-slate-100 text-slate-600 leading-relaxed">
                <p class="text-xs">Anda akan memulihkan database menggunakan file cadangan:</p>
                <p class="text-xs font-bold font-mono text-slate-800" x-text="targetRestoreFile"></p>
                <p class="text-xs text-rose-600 font-bold">Semua transaksi, data kasir, dan penyesuaian baru setelah waktu backup di atas akan terhapus secara permanen.</p>
            </div>

            <div class="space-y-3">
                <label for="confirm_text" class="block text-[10px] font-black text-slate-400 uppercase tracking-widest leading-none">Ketik kata kunci "RESTORE DATA" untuk konfirmasi:</label>
                <input type="text" 
                       id="confirm_text" 
                       x-model="confirmText" 
                       placeholder="Ketik RESTORE DATA di sini"
                       class="block w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50 text-sm text-slate-800 focus:outline-none focus:ring-4 focus:ring-rose-500/10 focus:border-rose-500 focus:bg-white transition-all uppercase" />
            </div>

            <div class="flex gap-3 pt-2">
                <button type="button" 
                        @click="showRestoreModal = false"
                        class="flex-1 inline-flex items-center justify-center px-5 py-3 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs font-bold uppercase tracking-wider transition-all">
                    Batal
                </button>
                
                <!-- Action Restore Form -->
                <form :action="'{{ route('superadmin.backups.restore', '__filename__') }}'.replace('__filename__', targetRestoreFile)" 
                      method="POST" 
                      class="flex-1"
                      @submit="showRestoreModal = false; restoring = true;">
                    @csrf
                    <button type="submit" 
                            :disabled="confirmText !== 'RESTORE DATA'"
                            :class="confirmText === 'RESTORE DATA' ? 'bg-rose-600 hover:bg-rose-500 text-white shadow-lg shadow-rose-500/20 active:scale-98' : 'bg-slate-100 text-slate-300 cursor-not-allowed'"
                            class="w-full inline-flex items-center justify-center px-5 py-3 rounded-xl text-xs font-bold uppercase tracking-wider transition-all">
                        Ya, Restore Data
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
