@extends('layouts.superadmin')

@section('title', 'Tambah Pengguna Vendor')

@section('content')
    <div class="max-w-3xl">
        <div class="mb-8">
            <a href="{{ route('superadmin.vendors.show', $vendor) }}" class="text-sm font-semibold text-blue-600 hover:text-blue-800 flex items-center transition-colors">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Kembali ke Detail Vendor
            </a>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-slate-100 overflow-hidden">
            <div class="p-6 border-b border-slate-100 bg-slate-50/30">
                <h3 class="font-bold text-slate-800">Form Pengguna Baru</h3>
                <p class="text-xs text-slate-500 mt-1">Buat akun kredensial masuk baru untuk vendor <span class="font-bold text-slate-700 uppercase">{{ $vendor->name }}</span></p>
            </div>

            <form action="{{ route('superadmin.vendors.users.store', $vendor) }}" method="POST" class="p-8 space-y-6" x-data="{ role: '{{ old('role', 'cashier') }}' }">
                @csrf

                @if(session('error'))
                    <div class="p-4 bg-rose-50 border border-rose-100 rounded-xl text-xs font-bold text-rose-600 flex items-center gap-2">
                        <svg class="w-4 h-4 shrink-0 text-rose-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                        {{ session('error') }}
                    </div>
                @endif

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Nama Lengkap -->
                    <div class="md:col-span-2 space-y-2">
                        <label for="name" class="text-xs font-bold text-slate-500 uppercase tracking-widest">Nama Lengkap</label>
                        <input type="text" name="name" id="name" value="{{ old('name') }}" required
                               class="block w-full px-4 py-2.5 bg-slate-50 border @error('name') border-rose-500 bg-rose-50/10 focus:border-rose-500 focus:ring-rose-500 @else border-slate-200 focus:border-blue-500 focus:ring-blue-500 @enderror rounded-xl text-xs font-semibold text-slate-800 placeholder-slate-400 focus:bg-white focus:ring-1 focus:outline-none transition-all duration-150"
                               placeholder="Contoh: Ahmad Subardjo">
                        @error('name')
                            <p class="text-xs text-rose-600 font-bold mt-1.5 flex items-center gap-1">
                                <svg class="w-3.5 h-3.5 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div class="space-y-2">
                        <label for="email" class="text-xs font-bold text-slate-500 uppercase tracking-widest">Alamat Email</label>
                        <input type="email" name="email" id="email" value="{{ old('email') }}" required
                               class="block w-full px-4 py-2.5 bg-slate-50 border @error('email') border-rose-500 bg-rose-50/10 focus:border-rose-500 focus:ring-rose-500 @else border-slate-200 focus:border-blue-500 focus:ring-blue-500 @enderror rounded-xl text-xs font-semibold text-slate-800 placeholder-slate-400 focus:bg-white focus:ring-1 focus:outline-none transition-all duration-150"
                               placeholder="subardjo@email.com">
                        @error('email')
                            <p class="text-xs text-rose-600 font-bold mt-1.5 flex items-center gap-1">
                                <svg class="w-3.5 h-3.5 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div class="space-y-2">
                        <label for="password" class="text-xs font-bold text-slate-500 uppercase tracking-widest">Kata Sandi (Password)</label>
                        <input type="password" name="password" id="password" required
                               class="block w-full px-4 py-2.5 bg-slate-50 border @error('password') border-rose-500 bg-rose-50/10 focus:border-rose-500 focus:ring-rose-500 @else border-slate-200 focus:border-blue-500 focus:ring-blue-500 @enderror rounded-xl text-xs font-semibold text-slate-800 placeholder-slate-400 focus:bg-white focus:ring-1 focus:outline-none transition-all duration-150"
                               placeholder="Minimal 8 karakter">
                        @error('password')
                            <p class="text-xs text-rose-600 font-bold mt-1.5 flex items-center gap-1">
                                <svg class="w-3.5 h-3.5 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Role Vendor -->
                    <div class="space-y-2">
                        <label for="role" class="text-xs font-bold text-slate-500 uppercase tracking-widest">Hak Akses Vendor</label>
                        <select name="role" id="role" x-model="role" required
                                class="block w-full px-4 py-2.5 bg-slate-50 border border-slate-200 focus:border-blue-500 focus:ring-blue-500 rounded-xl text-xs font-semibold text-slate-800 focus:bg-white focus:ring-1 focus:outline-none transition-all duration-150">
                            <option value="owner">Owner (Pemilik Vendor)</option>
                            <option value="admin">Admin (Pengelola Toko)</option>
                            <option value="cashier">Cashier (Petugas Kasir)</option>
                        </select>
                        @error('role')
                            <p class="text-xs text-rose-600 font-bold mt-1.5 flex items-center gap-1">
                                <svg class="w-3.5 h-3.5 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Cabang (Dinamis / Alpine.js) -->
                    <div class="space-y-2" x-show="role !== 'owner'" 
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 -translate-y-1"
                         x-transition:enter-end="opacity-100 translate-y-0">
                        <label for="branch_id" class="text-xs font-bold text-slate-500 uppercase tracking-widest">
                            Tugaskan ke Cabang 
                            <span class="text-rose-500 text-[10px]" x-show="role === 'cashier'">*Wajib</span>
                        </label>
                        <select name="branch_id" id="branch_id" :required="role === 'cashier'"
                                class="block w-full px-4 py-2.5 bg-slate-50 border border-slate-200 focus:border-blue-500 focus:ring-blue-500 rounded-xl text-xs font-semibold text-slate-800 focus:bg-white focus:ring-1 focus:outline-none transition-all duration-150">
                            <option value="">-- Pilih Cabang --</option>
                            @foreach($branches as $branch)
                                <option value="{{ $branch->id }}" {{ old('branch_id') == $branch->id ? 'selected' : '' }}>
                                    {{ $branch->name }} ({{ $branch->code }})
                                </option>
                            @endforeach
                        </select>
                        @error('branch_id')
                            <p class="text-xs text-rose-600 font-bold mt-1.5 flex items-center gap-1">
                                <svg class="w-3.5 h-3.5 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Status Aktif -->
                    <div class="md:col-span-2 space-y-3">
                        <label class="text-xs font-bold text-slate-500 uppercase tracking-widest">Status Akun</label>
                        <div class="flex items-center gap-6">
                            <label class="inline-flex items-center cursor-pointer">
                                <input type="radio" name="is_active" value="1" {{ old('is_active', '1') == '1' ? 'checked' : '' }} class="w-4 h-4 text-blue-600 focus:ring-blue-500 border-slate-300">
                                <span class="ml-2.5 text-xs text-slate-700 font-bold">Aktif / Berizin Masuk</span>
                            </label>
                            <label class="inline-flex items-center cursor-pointer">
                                <input type="radio" name="is_active" value="0" {{ old('is_active') == '0' ? 'checked' : '' }} class="w-4 h-4 text-blue-600 focus:ring-blue-500 border-slate-300">
                                <span class="ml-2.5 text-xs text-slate-700 font-bold">Ditangguhkan (Suspended)</span>
                            </label>
                        </div>
                        @error('is_active')
                            <p class="text-xs text-rose-600 font-bold mt-1.5 flex items-center gap-1">
                                <svg class="w-3.5 h-3.5 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>
                </div>

                <div class="pt-6 border-t border-slate-100 flex justify-end items-center">
                    <a href="{{ route('superadmin.vendors.show', $vendor) }}" class="px-6 py-2.5 bg-slate-100 hover:bg-slate-200/80 border border-slate-200/60 active:scale-[0.98] text-slate-700 text-xs font-bold rounded-xl transition-all mr-3">
                        Batal
                    </a>
                    <button type="submit" class="px-6 py-2.5 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-500 hover:to-indigo-500 active:scale-[0.98] text-white text-xs font-bold rounded-xl transition-all shadow-md shadow-blue-500/15 border border-blue-500/20">
                        Simpan Akun
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
