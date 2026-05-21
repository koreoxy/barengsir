@extends('layouts.superadmin')

@section('title', 'Edit Vendor')

@section('content')
    <div class="max-w-3xl">
        <div class="mb-8">
            <a href="{{ route('superadmin.vendors.index') }}" class="text-sm font-semibold text-blue-600 hover:text-blue-800 flex items-center transition-colors">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Kembali ke Daftar
            </a>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-slate-100 overflow-hidden">
            <div class="p-6 border-b border-slate-100 bg-slate-50/30">
                <h3 class="font-bold text-slate-800">Edit Data: {{ $vendor->name }}</h3>
                <p class="text-xs text-slate-500 mt-1">Perbarui informasi bisnis atau ubah status aktivasi vendor.</p>
            </div>
            
            <form action="{{ route('superadmin.vendors.update', $vendor) }}" method="POST" class="p-8 space-y-6">
                @csrf
                @method('PUT')
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label for="name" class="text-xs font-bold text-slate-500 uppercase tracking-widest">Nama Bisnis / Vendor</label>
                        <input type="text" name="name" id="name" value="{{ old('name', $vendor->name) }}" required
                               class="block w-full px-4 py-2.5 bg-slate-50 border @error('name') border-rose-500 bg-rose-50/10 focus:border-rose-500 focus:ring-rose-500 @else border-slate-200 focus:border-blue-500 focus:ring-blue-500 @enderror rounded-xl text-xs font-semibold text-slate-800 placeholder-slate-400 focus:bg-white focus:ring-1 focus:outline-none transition-all duration-150">
                        @error('name') <p class="text-xs text-rose-600 font-bold mt-1.5 flex items-center gap-1">
                            <svg class="w-3.5 h-3.5 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                            {{ $message }}
                        </p> @enderror
                    </div>

                    <div class="space-y-2">
                        <label for="email" class="text-xs font-bold text-slate-500 uppercase tracking-widest">Email Bisnis</label>
                        <input type="email" name="email" id="email" value="{{ old('email', $vendor->email) }}" required
                               class="block w-full px-4 py-2.5 bg-slate-50 border @error('email') border-rose-500 bg-rose-50/10 focus:border-rose-500 focus:ring-rose-500 @else border-slate-200 focus:border-blue-500 focus:ring-blue-500 @enderror rounded-xl text-xs font-semibold text-slate-800 placeholder-slate-400 focus:bg-white focus:ring-1 focus:outline-none transition-all duration-150">
                        @error('email') <p class="text-xs text-rose-600 font-bold mt-1.5 flex items-center gap-1">
                            <svg class="w-3.5 h-3.5 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                            {{ $message }}
                        </p> @enderror
                    </div>

                    <div class="space-y-2">
                        <label for="phone" class="text-xs font-bold text-slate-500 uppercase tracking-widest">Nomor Telepon</label>
                        <input type="text" name="phone" id="phone" value="{{ old('phone', $vendor->phone) }}"
                               class="block w-full px-4 py-2.5 bg-slate-50 border @error('phone') border-rose-500 bg-rose-50/10 focus:border-rose-500 focus:ring-rose-500 @else border-slate-200 focus:border-blue-500 focus:ring-blue-500 @enderror rounded-xl text-xs font-semibold text-slate-800 placeholder-slate-400 focus:bg-white focus:ring-1 focus:outline-none transition-all duration-150">
                        @error('phone') <p class="text-xs text-rose-600 font-bold mt-1.5 flex items-center gap-1">
                            <svg class="w-3.5 h-3.5 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                            {{ $message }}
                        </p> @enderror
                    </div>

                    <div class="space-y-2">
                        <label for="is_active" class="text-xs font-bold text-slate-500 uppercase tracking-widest">Status Akun</label>
                        <select name="is_active" id="is_active" 
                                class="block w-full px-4 py-2.5 bg-slate-50 border @error('is_active') border-rose-500 bg-rose-50/10 focus:border-rose-500 focus:ring-rose-500 @else border-slate-200 focus:border-blue-500 focus:ring-blue-500 @enderror rounded-xl text-xs font-semibold text-slate-800 focus:bg-white focus:ring-1 focus:outline-none transition-all duration-150">
                            <option value="1" {{ old('is_active', $vendor->is_active) ? 'selected' : '' }}>Active (Bisa Transaksi)</option>
                            <option value="0" {{ !old('is_active', $vendor->is_active) ? 'selected' : '' }}>Suspended (Blokir Akses)</option>
                        </select>
                        @error('is_active') <p class="text-xs text-rose-600 font-bold mt-1.5 flex items-center gap-1">
                            <svg class="w-3.5 h-3.5 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                            {{ $message }}
                        </p> @enderror
                    </div>
                </div>

                <div class="space-y-2">
                    <label for="address" class="text-xs font-bold text-slate-500 uppercase tracking-widest">Alamat Pusat</label>
                    <textarea name="address" id="address" rows="3" 
                              class="block w-full px-4 py-2.5 bg-slate-50 border @error('address') border-rose-500 bg-rose-50/10 focus:border-rose-500 focus:ring-rose-500 @else border-slate-200 focus:border-blue-500 focus:ring-blue-500 @enderror rounded-xl text-xs font-semibold text-slate-800 placeholder-slate-400 focus:bg-white focus:ring-1 focus:outline-none transition-all duration-150">{{ old('address', $vendor->address) }}</textarea>
                    @error('address') <p class="text-xs text-rose-600 font-bold mt-1.5 flex items-center gap-1">
                        <svg class="w-3.5 h-3.5 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                        {{ $message }}
                    </p> @enderror
                </div>

                <div class="pt-6 border-t border-slate-100 flex justify-end items-center">
                    <a href="{{ route('superadmin.vendors.index') }}" class="px-6 py-2.5 bg-slate-100 hover:bg-slate-200/80 border border-slate-200/60 active:scale-[0.98] text-slate-700 text-xs font-bold rounded-xl transition-all mr-3">
                        Batal
                    </a>
                    <button type="submit" class="px-6 py-2.5 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-500 hover:to-indigo-500 active:scale-[0.98] text-white text-xs font-bold rounded-xl transition-all shadow-md shadow-blue-500/15 border border-blue-500/20">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
