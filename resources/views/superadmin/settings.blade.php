@extends('layouts.superadmin')

@section('title', 'Pengaturan Sistem')

@section('content')
    <!-- Page Title Header -->
    <div class="mb-8">
        <h1 class="text-2xl md:text-3xl font-black text-slate-800 tracking-tight">Pengaturan Sistem Global</h1>
        <p class="text-slate-500 mt-1.5 text-xs md:text-sm">Konfigurasi preferensi global dan kebijakan operasional platform POS Central.</p>
    </div>

    <div class="max-w-3xl">
        <div class="bg-white rounded-2xl border border-slate-200/60 shadow-sm shadow-slate-100/30 overflow-hidden">
            <div class="p-5 px-6 border-b border-slate-100 bg-slate-50/30 flex items-center justify-between">
                <h3 class="text-sm font-extrabold text-slate-800 tracking-tight uppercase">Preferensi Control Center</h3>
                <span class="text-[9px] font-black text-slate-400 bg-slate-100 px-2 py-0.5 rounded-md uppercase tracking-widest">Global Config</span>
            </div>

            <form action="{{ route('superadmin.settings.update') }}" method="POST" class="p-6 md:p-8 space-y-6">
                @csrf

                <!-- Platform Application Name -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-2 md:gap-6 items-start">
                    <label for="system_name" class="block text-xs font-bold text-slate-500 uppercase tracking-widest mt-2.5">Nama Aplikasi POS</label>
                    <div class="md:col-span-2 space-y-1.5">
                        <input type="text" 
                               name="system_name" 
                               id="system_name"
                               value="{{ old('system_name', $settings['system_name']) }}"
                               class="w-full px-4 py-2.5 bg-slate-50 border @error('system_name') border-rose-500 bg-rose-50/10 focus:border-rose-500 focus:ring-rose-500 @else border-slate-200 focus:border-blue-500 focus:ring-blue-500 @enderror rounded-xl transition-all text-xs font-semibold text-slate-800 focus:bg-white focus:ring-1 focus:outline-none"
                               placeholder="Contoh: POS BarengSir">
                        <p class="text-[11px] text-slate-400 font-medium">Nama brand sistem utama yang ditampilkan pada title bar, header, dan dashboard.</p>
                        @error('system_name')
                            <span class="text-xs font-bold text-rose-600 flex items-center gap-1 mt-1.5">
                                <svg class="w-3.5 h-3.5 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                                {{ $message }}
                            </span>
                        @enderror
                    </div>
                </div>

                <hr class="border-slate-100">

                <!-- Default Maximum Branches -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-2 md:gap-6 items-start">
                    <label for="default_max_branches" class="block text-xs font-bold text-slate-500 uppercase tracking-widest mt-2.5">Batas Maksimum Cabang</label>
                    <div class="md:col-span-2 space-y-1.5">
                        <div class="relative w-32">
                            <input type="number" 
                                   name="default_max_branches" 
                                   id="default_max_branches"
                                   min="1"
                                   max="999"
                                   value="{{ old('default_max_branches', $settings['default_max_branches']) }}"
                                   class="w-full pl-4 pr-10 py-2.5 bg-slate-50 border @error('default_max_branches') border-rose-500 bg-rose-50/10 focus:border-rose-500 focus:ring-rose-500 @else border-slate-200 focus:border-blue-500 focus:ring-blue-500 @enderror rounded-xl transition-all text-xs font-semibold text-slate-800 focus:bg-white focus:ring-1 focus:outline-none">
                            <span class="absolute right-3.5 top-1/2 -translate-y-1/2 text-xs font-bold text-slate-400 pointer-events-none uppercase">Outlet</span>
                        </div>
                        <p class="text-[11px] text-slate-400 font-medium">Batas default kuota cabang yang diizinkan untuk setiap vendor baru saat pertama kali didaftarkan.</p>
                        @error('default_max_branches')
                            <span class="text-xs font-bold text-rose-600 flex items-center gap-1 mt-1.5">
                                <svg class="w-3.5 h-3.5 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                                {{ $message }}
                            </span>
                        @enderror
                    </div>
                </div>

                <hr class="border-slate-100">

                <!-- New Vendor Registration Status -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-2 md:gap-6 items-start">
                    <label for="vendor_registration_status" class="block text-xs font-bold text-slate-500 uppercase tracking-widest mt-2.5">Registrasi Vendor</label>
                    <div class="md:col-span-2 space-y-1.5">
                        <select name="vendor_registration_status" 
                                id="vendor_registration_status"
                                class="w-48 px-4 py-2.5 bg-slate-50 border @error('vendor_registration_status') border-rose-500 bg-rose-50/10 focus:border-rose-500 focus:ring-rose-500 @else border-slate-200 focus:border-blue-500 focus:ring-blue-500 @enderror rounded-xl transition-all text-xs font-semibold text-slate-800 focus:bg-white focus:ring-1 focus:outline-none">
                            <option value="open" {{ old('vendor_registration_status', $settings['vendor_registration_status']) == 'open' ? 'selected' : '' }}>BUKA (Bebas Daftar)</option>
                            <option value="closed" {{ old('vendor_registration_status', $settings['vendor_registration_status']) == 'closed' ? 'selected' : '' }}>TUTUP (Melalui Admin)</option>
                        </select>
                        <p class="text-[11px] text-slate-400 font-medium">Status kebijakan pendaftaran vendor baru pada landing page atau portal online.</p>
                        @error('vendor_registration_status')
                            <span class="text-xs font-bold text-rose-600 flex items-center gap-1 mt-1.5">
                                <svg class="w-3.5 h-3.5 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                                {{ $message }}
                            </span>
                        @enderror
                    </div>
                </div>

                <div class="pt-6 border-t border-slate-100 flex items-center justify-end space-x-3">
                    <a href="{{ route('superadmin.dashboard') }}" 
                       class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200/80 border border-slate-200/60 rounded-xl transition-all text-xs font-bold text-slate-700 uppercase tracking-widest active:scale-[0.98]">
                        Batal
                    </a>
                    <button type="submit" 
                            class="px-6 py-2.5 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-500 hover:to-indigo-500 active:scale-[0.98] text-white rounded-xl transition-all duration-150 shadow-md shadow-blue-500/20 border border-blue-400/20 text-xs font-bold uppercase tracking-widest">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
