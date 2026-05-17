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
                        <label for="name" class="text-xs font-semibold text-slate-600 uppercase tracking-wider">Nama Bisnis / Vendor</label>
                        <input type="text" name="name" id="name" value="{{ old('name', $vendor->name) }}" required
                               class="block w-full px-4 py-2.5 border border-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                        @error('name') <p class="text-xs text-rose-500 font-bold mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="space-y-2">
                        <label for="email" class="text-xs font-semibold text-slate-600 uppercase tracking-wider">Email Bisnis</label>
                        <input type="email" name="email" id="email" value="{{ old('email', $vendor->email) }}" required
                               class="block w-full px-4 py-2.5 border border-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                        @error('email') <p class="text-xs text-rose-500 font-bold mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="space-y-2">
                        <label for="phone" class="text-xs font-semibold text-slate-600 uppercase tracking-wider">Nomor Telepon</label>
                        <input type="text" name="phone" id="phone" value="{{ old('phone', $vendor->phone) }}"
                               class="block w-full px-4 py-2.5 border border-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                        @error('phone') <p class="text-xs text-rose-500 font-bold mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="space-y-2">
                        <label for="is_active" class="text-xs font-semibold text-slate-600 uppercase tracking-wider">Status Akun</label>
                        <select name="is_active" id="is_active" 
                                class="block w-full px-4 py-2.5 border border-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                            <option value="1" {{ old('is_active', $vendor->is_active) ? 'selected' : '' }}>Active (Bisa Transaksi)</option>
                            <option value="0" {{ !old('is_active', $vendor->is_active) ? 'selected' : '' }}>Suspended (Blokir Akses)</option>
                        </select>
                        @error('is_active') <p class="text-xs text-rose-500 font-bold mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="space-y-2">
                    <label for="address" class="text-xs font-semibold text-slate-600 uppercase tracking-wider">Alamat Pusat</label>
                    <textarea name="address" id="address" rows="3" 
                              class="block w-full px-4 py-2.5 border border-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">{{ old('address', $vendor->address) }}</textarea>
                    @error('address') <p class="text-xs text-rose-500 font-bold mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="pt-6 border-t border-slate-100 flex justify-end">
                    <button type="submit" class="px-6 py-2.5 bg-blue-600 text-white text-sm font-semibold rounded-lg hover:bg-blue-700 transition-colors shadow-sm">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
