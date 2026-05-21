<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h2 class="font-bold text-xl text-slate-800 leading-tight">
                    {{ __('Pengaturan Sistem') }}
                </h2>
                <p class="text-xs text-slate-500 mt-1">
                    {{ __('Kelola konfigurasi dasar kasir, profil akun, dan info operasional cabang.') }}
                </p>
            </div>
            
            {{-- Branch Status Badge --}}
            @if(session('active_branch_name'))
                <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-xl bg-blue-50 border border-blue-100 text-blue-700 text-xs font-semibold self-start md:self-auto">
                    <span class="w-2 h-2 rounded-full bg-blue-500 animate-pulse"></span>
                    <span>{{ session('active_branch_name') }}</span>
                </div>
            @endif
        </div>
    </x-slot>

    <div class="py-8" x-data="{ activeTab: 'store' }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            <!-- Flash Messages -->
            @if(session('success'))
                <div class="flex items-center gap-3 bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3.5 rounded-2xl shadow-sm animate-fade-in" role="alert">
                    <div class="shrink-0 w-6 h-6 rounded-full bg-emerald-100 flex items-center justify-center text-emerald-600">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                        </svg>
                    </div>
                    <div>
                        <strong class="font-semibold text-sm">Berhasil!</strong>
                        <span class="text-xs block mt-0.5">{{ session('success') }}</span>
                    </div>
                </div>
            @endif

            @if(session('error'))
                <div class="flex items-center gap-3 bg-red-50 border border-red-200 text-red-800 px-4 py-3.5 rounded-2xl shadow-sm animate-fade-in" role="alert">
                    <div class="shrink-0 w-6 h-6 rounded-full bg-red-100 flex items-center justify-center text-red-600">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </div>
                    <div>
                        <strong class="font-semibold text-sm">Error!</strong>
                        <span class="text-xs block mt-0.5">{{ session('error') }}</span>
                    </div>
                </div>
            @endif

            <!-- Navigation Tabs -->
            <div class="flex border-b border-slate-200 gap-6 overflow-x-auto pb-1.5 scrollbar-none">
                <button @click="activeTab = 'store'" 
                        :class="activeTab === 'store' ? 'border-blue-600 text-blue-600 font-bold' : 'border-transparent text-slate-500 hover:text-slate-700'"
                        class="py-3 px-1 border-b-2 text-sm whitespace-nowrap transition-all focus:outline-none flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                    <span>Profil Toko & Cabang</span>
                </button>
                <button @click="activeTab = 'system'" 
                        :class="activeTab === 'system' ? 'border-blue-600 text-blue-600 font-bold' : 'border-transparent text-slate-500 hover:text-slate-700'"
                        class="py-3 px-1 border-b-2 text-sm whitespace-nowrap transition-all focus:outline-none flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    <span>Pengaturan Sistem POS</span>
                </button>
                <button @click="activeTab = 'account'" 
                        :class="activeTab === 'account' ? 'border-purple-600 text-purple-600 font-bold' : 'border-transparent text-slate-500 hover:text-slate-700'"
                        class="py-3 px-1 border-b-2 text-sm whitespace-nowrap transition-all focus:outline-none flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.952 11.952 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                    </svg>
                    <span>Keamanan Akun</span>
                </button>
            </div>

            <!-- TAB 1: PROFIL TOKO & CABANG -->
            <div x-show="activeTab === 'store'" class="max-w-4xl mx-auto space-y-6 animate-fade-in">
                <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
                    <div class="p-6 sm:p-8 border-b border-slate-100 bg-slate-50/50">
                        <div class="flex items-center gap-3.5">
                            <div class="w-10 h-10 rounded-xl bg-blue-50 flex items-center justify-center text-blue-600 shrink-0">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-md font-bold text-slate-800">{{ __('Informasi Toko / Cabang') }}</h3>
                                <p class="text-xs text-slate-500 mt-0.5">{{ __('Perbarui informasi bisnis Anda untuk pencetakan struk dan laporan.') }}</p>
                            </div>
                        </div>
                    </div>

                    <form method="post" action="{{ route('setting.store') }}" class="p-6 sm:p-8 space-y-6">
                        @csrf
                        @method('put')

                        <div class="space-y-6">
                            <!-- Profil Toko -->
                            <div class="border-b border-slate-100 pb-5">
                                <div class="flex items-center gap-2 mb-4">
                                    <div class="h-1.5 w-6 rounded bg-blue-500"></div>
                                    <h4 class="text-xs font-bold text-blue-600 uppercase tracking-widest">A. Profil Toko (Pusat / Vendor)</h4>
                                </div>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label for="vendor_name" class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-2">Nama Toko</label>
                                        <input id="vendor_name" 
                                               name="vendor_name" 
                                               type="text" 
                                               class="block w-full px-4 py-3 rounded-xl border {{ $errors->has('vendor_name') ? 'border-red-400 bg-red-50 focus:ring-red-500/20 focus:border-red-500' : 'border-slate-200 bg-slate-50/50 focus:ring-blue-500/20 focus:border-blue-600' }} text-sm text-slate-800 focus:outline-none focus:ring-4 focus:bg-white transition-all" 
                                               value="{{ old('vendor_name', $vendor->name ?? '') }}" 
                                               required />
                                        @error('vendor_name')
                                            <p class="mt-2 text-xs text-red-500 font-medium flex items-center gap-1">
                                                {{ $message }}
                                            </p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label for="vendor_email" class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-2">Email Toko</label>
                                        <input id="vendor_email" 
                                               name="vendor_email" 
                                               type="email" 
                                               class="block w-full px-4 py-3 rounded-xl border {{ $errors->has('vendor_email') ? 'border-red-400 bg-red-50 focus:ring-red-500/20 focus:border-red-500' : 'border-slate-200 bg-slate-50/50 focus:ring-blue-500/20 focus:border-blue-600' }} text-sm text-slate-800 focus:outline-none focus:ring-4 focus:bg-white transition-all" 
                                               value="{{ old('vendor_email', $vendor->email ?? '') }}" 
                                               required />
                                        @error('vendor_email')
                                            <p class="mt-2 text-xs text-red-500 font-medium flex items-center gap-1">
                                                {{ $message }}
                                            </p>
                                        @enderror
                                    </div>

                                    <div class="md:col-span-2">
                                        <label for="vendor_phone" class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-2">No. Telepon Toko</label>
                                        <input id="vendor_phone" 
                                               name="vendor_phone" 
                                               type="text" 
                                               class="block w-full px-4 py-3 rounded-xl border {{ $errors->has('vendor_phone') ? 'border-red-400 bg-red-50 focus:ring-red-500/20 focus:border-red-500' : 'border-slate-200 bg-slate-50/50 focus:ring-blue-500/20 focus:border-blue-600' }} text-sm text-slate-800 focus:outline-none focus:ring-4 focus:bg-white transition-all" 
                                               value="{{ old('vendor_phone', $vendor->phone ?? '') }}" 
                                               required />
                                        @error('vendor_phone')
                                            <p class="mt-2 text-xs text-red-500 font-medium flex items-center gap-1">
                                                {{ $message }}
                                            </p>
                                        @enderror
                                    </div>

                                    <div class="md:col-span-2">
                                        <label for="vendor_address" class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-2">Alamat Kantor Toko (Pusat)</label>
                                        <textarea id="vendor_address" 
                                                  name="vendor_address" 
                                                  rows="2" 
                                                  class="block w-full px-4 py-3 rounded-xl border {{ $errors->has('vendor_address') ? 'border-red-400 bg-red-50 focus:ring-red-500/20 focus:border-red-500' : 'border-slate-200 bg-slate-50/50 focus:ring-blue-500/20 focus:border-blue-600' }} text-sm text-slate-800 focus:outline-none focus:ring-4 focus:bg-white transition-all resize-none" 
                                                  required>{{ old('vendor_address', $vendor->address ?? '') }}</textarea>
                                        @error('vendor_address')
                                            <p class="mt-2 text-xs text-red-500 font-medium flex items-center gap-1">
                                                {{ $message }}
                                            </p>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Detail Cabang Aktif -->
                            <div>
                                <div class="flex items-center gap-2 mb-4">
                                    <div class="h-1.5 w-6 rounded bg-purple-500"></div>
                                    <h4 class="text-xs font-bold text-purple-600 uppercase tracking-widest">B. Detail Cabang Aktif</h4>
                                </div>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label for="branch_name" class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-2">Nama Cabang</label>
                                        <input id="branch_name" 
                                               name="branch_name" 
                                               type="text" 
                                               class="block w-full px-4 py-3 rounded-xl border {{ $errors->has('branch_name') ? 'border-red-400 bg-red-50 focus:ring-red-500/20 focus:border-red-500' : 'border-slate-200 bg-slate-50/50 focus:ring-blue-500/20 focus:border-blue-600' }} text-sm text-slate-800 focus:outline-none focus:ring-4 focus:bg-white transition-all" 
                                               value="{{ old('branch_name', $branch->name ?? '') }}" 
                                               required />
                                        @error('branch_name')
                                            <p class="mt-2 text-xs text-red-500 font-medium flex items-center gap-1">
                                                {{ $message }}
                                            </p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label for="branch_phone" class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-2">No. Telepon Cabang</label>
                                        <input id="branch_phone" 
                                               name="branch_phone" 
                                               type="text" 
                                               class="block w-full px-4 py-3 rounded-xl border {{ $errors->has('branch_phone') ? 'border-red-400 bg-red-50 focus:ring-red-500/20 focus:border-red-500' : 'border-slate-200 bg-slate-50/50 focus:ring-blue-500/20 focus:border-blue-600' }} text-sm text-slate-800 focus:outline-none focus:ring-4 focus:bg-white transition-all" 
                                               value="{{ old('branch_phone', $branch->phone ?? '') }}" 
                                               required />
                                        @error('branch_phone')
                                            <p class="mt-2 text-xs text-red-500 font-medium flex items-center gap-1">
                                                {{ $message }}
                                            </p>
                                        @enderror
                                    </div>

                                    <div class="md:col-span-2">
                                        <label for="branch_address" class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-2">Alamat Cabang</label>
                                        <textarea id="branch_address" 
                                                  name="branch_address" 
                                                  rows="2" 
                                                  class="block w-full px-4 py-3 rounded-xl border {{ $errors->has('branch_address') ? 'border-red-400 bg-red-50 focus:ring-red-500/20 focus:border-red-500' : 'border-slate-200 bg-slate-50/50 focus:ring-blue-500/20 focus:border-blue-600' }} text-sm text-slate-800 focus:outline-none focus:ring-4 focus:bg-white transition-all resize-none" 
                                                  required>{{ old('branch_address', $branch->address ?? '') }}</textarea>
                                        @error('branch_address')
                                            <p class="mt-2 text-xs text-red-500 font-medium flex items-center gap-1">
                                                {{ $message }}
                                            </p>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="pt-6 border-t border-slate-100 flex justify-end">
                            <button type="submit" 
                                    class="inline-flex items-center justify-center px-5 py-3 bg-blue-600 hover:bg-blue-500 active:scale-[0.98] text-white text-xs font-bold uppercase tracking-wider rounded-xl shadow-lg shadow-blue-500/20 hover:shadow-blue-500/35 transition-all duration-150">
                                {{ __('Simpan Pengaturan Toko & Cabang') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- TAB 2: PENGATURAN SISTEM POS -->
            <div x-show="activeTab === 'system'" class="space-y-8 animate-fade-in">
                <form method="post" action="{{ route('setting.system') }}" enctype="multipart/form-data" class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    @csrf
                    @method('put')

                    <!-- KIRI: GENERAL SETTINGS -->
                    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 sm:p-8 space-y-6 flex flex-col justify-between">
                        <div>
                            <div class="flex items-center gap-3.5 mb-6">
                                <div class="w-10 h-10 rounded-xl bg-blue-50 flex items-center justify-center text-blue-600 shrink-0">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-md font-bold text-slate-800">General Settings</h3>
                                    <p class="text-xs text-slate-500 mt-0.5">Konfigurasi visual utama dan aturan penanggalan tokomu.</p>
                                </div>
                            </div>

                            <div class="space-y-5">
                                <!-- Upload Logo Toko dengan Live Preview (Alpine.js) -->
                                <div x-data="{ logoPreview: '{{ setting('store_logo') ? asset('storage/' . setting('store_logo')) : '' }}' }">
                                    <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-2">Logo Toko (Format Struk & Invoice)</label>
                                    <div class="flex items-center gap-5">
                                        <div class="w-20 h-20 rounded-2xl bg-slate-50 border border-dashed border-slate-200 overflow-hidden flex items-center justify-center shrink-0">
                                            <template x-if="logoPreview">
                                                <img :src="logoPreview" class="w-full h-full object-cover">
                                            </template>
                                            <template x-if="!logoPreview">
                                                <svg class="w-8 h-8 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                </svg>
                                            </template>
                                        </div>
                                        <div class="flex-1">
                                            <input type="file" 
                                                   id="store_logo" 
                                                   name="store_logo" 
                                                   class="hidden" 
                                                   accept="image/*"
                                                   @change="const file = $event.target.files[0]; if (file) { logoPreview = URL.createObjectURL(file); }">
                                            <label for="store_logo" 
                                                   class="inline-flex items-center gap-2 px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs font-bold uppercase tracking-wider cursor-pointer transition-colors active:scale-98">
                                                Pilih Gambar Logo
                                            </label>
                                            <p class="text-[10px] text-slate-400 mt-2">Mendukung PNG/JPG. Ukuran maksimal 2MB.</p>
                                        </div>
                                    </div>
                                    @error('store_logo')
                                        <p class="mt-2 text-xs text-red-500 font-medium">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Pilihan Mata Uang -->
                                <div>
                                    <label for="store_currency" class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-2">Mata Uang Default</label>
                                    <select id="store_currency" 
                                            name="store_currency" 
                                            class="block w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50/50 text-sm text-slate-800 focus:outline-none focus:ring-4 focus:ring-blue-500/20 focus:border-blue-600 focus:bg-white transition-all">
                                        <option value="IDR" {{ setting('store_currency', 'IDR') == 'IDR' ? 'selected' : '' }}>Rupiah (IDR)</option>
                                        <option value="USD" {{ setting('store_currency') == 'USD' ? 'selected' : '' }}>Dollar (USD)</option>
                                        <option value="SGD" {{ setting('store_currency') == 'SGD' ? 'selected' : '' }}>Singapore Dollar (SGD)</option>
                                        <option value="EUR" {{ setting('store_currency') == 'EUR' ? 'selected' : '' }}>Euro (EUR)</option>
                                    </select>
                                    @error('store_currency')
                                        <p class="mt-2 text-xs text-red-500 font-medium">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Pilihan Zona Waktu -->
                                <div>
                                    <label for="store_timezone" class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-2">Zona Waktu Sistem</label>
                                    <select id="store_timezone" 
                                            name="store_timezone" 
                                            class="block w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50/50 text-sm text-slate-800 focus:outline-none focus:ring-4 focus:ring-blue-500/20 focus:border-blue-600 focus:bg-white transition-all">
                                        <option value="Asia/Jakarta" {{ setting('store_timezone', 'Asia/Jakarta') == 'Asia/Jakarta' ? 'selected' : '' }}>WIB (Asia/Jakarta)</option>
                                        <option value="Asia/Makassar" {{ setting('store_timezone') == 'Asia/Makassar' ? 'selected' : '' }}>WITA (Asia/Makassar)</option>
                                        <option value="Asia/Jayapura" {{ setting('store_timezone') == 'Asia/Jayapura' ? 'selected' : '' }}>WIT (Asia/Jayapura)</option>
                                        <option value="UTC" {{ setting('store_timezone') == 'UTC' ? 'selected' : '' }}>UTC (Waktu Standar)</option>
                                    </select>
                                    @error('store_timezone')
                                        <p class="mt-2 text-xs text-red-500 font-medium">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- KANAN: POS SETTINGS -->
                    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 sm:p-8 space-y-6 flex flex-col justify-between">
                        <div>
                            <div class="flex items-center gap-3.5 mb-6">
                                <div class="w-10 h-10 rounded-xl bg-purple-50 flex items-center justify-center text-purple-600 shrink-0">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-md font-bold text-slate-800">POS & Kasir Settings</h3>
                                    <p class="text-xs text-slate-500 mt-0.5">Konfigurasi operasional kasir, printer, dan format struk belanja.</p>
                                </div>
                            </div>

                            <div class="space-y-5">
                                <!-- Pajak Default -->
                                <div>
                                    <label for="default_tax" class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-2">Pajak Transaksi Default (%)</label>
                                    <div class="relative">
                                        <input id="default_tax" 
                                               name="default_tax" 
                                               type="number" 
                                               step="0.01" 
                                               min="0" 
                                               max="100"
                                               class="block w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50/50 text-sm text-slate-800 focus:outline-none focus:ring-4 focus:ring-blue-500/20 focus:border-blue-600 focus:bg-white transition-all" 
                                               value="{{ old('default_tax', setting('default_tax', '0')) }}" 
                                               required />
                                        <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none text-slate-400 font-bold text-sm">
                                            %
                                        </div>
                                    </div>
                                    @error('default_tax')
                                        <p class="mt-2 text-xs text-red-500 font-medium">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Printer Default -->
                                <div>
                                    <label for="default_printer" class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-2">Nama / IP Printer Default</label>
                                    <input id="default_printer" 
                                           name="default_printer" 
                                           type="text" 
                                           placeholder="Contoh: POS-58 atau 192.168.1.200"
                                           class="block w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50/50 text-sm text-slate-800 focus:outline-none focus:ring-4 focus:ring-blue-500/20 focus:border-blue-600 focus:bg-white transition-all" 
                                           value="{{ old('default_printer', setting('default_printer', '')) }}" />
                                    @error('default_printer')
                                        <p class="mt-2 text-xs text-red-500 font-medium">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Format Struk -->
                                <div>
                                    <label for="receipt_format" class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-2">Format Ukuran Kertas Struk</label>
                                    <select id="receipt_format" 
                                            name="receipt_format" 
                                            class="block w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50/50 text-sm text-slate-800 focus:outline-none focus:ring-4 focus:ring-blue-500/20 focus:border-blue-600 focus:bg-white transition-all">
                                        <option value="58mm" {{ setting('receipt_format', '58mm') == '58mm' ? 'selected' : '' }}>Thermal 58mm</option>
                                        <option value="80mm" {{ setting('receipt_format') == '80mm' ? 'selected' : '' }}>Thermal 80mm</option>
                                        <option value="A4" {{ setting('receipt_format') == 'A4' ? 'selected' : '' }}>Faktur A4 (Standard Inkjet)</option>
                                    </select>
                                    @error('receipt_format')
                                        <p class="mt-2 text-xs text-red-500 font-medium">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="pt-6 border-t border-slate-100 flex justify-end">
                            <button type="submit" 
                                    class="inline-flex items-center justify-center px-5 py-3 bg-blue-600 hover:bg-blue-500 active:scale-[0.98] text-white text-xs font-bold uppercase tracking-wider rounded-xl shadow-lg shadow-blue-500/20 hover:shadow-blue-500/35 transition-all duration-150">
                                Simpan Pengaturan Sistem
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            <!-- TAB 3: KEAMANAN AKUN -->
            <div x-show="activeTab === 'account'" class="max-w-4xl mx-auto space-y-6 animate-fade-in" x-data="{ showCurrent: false, showNew: false, showConfirm: false }">
                <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
                    <div class="p-6 sm:p-8 border-b border-slate-100 bg-slate-50/50">
                        <div class="flex items-center gap-3.5">
                            <div class="w-10 h-10 rounded-xl bg-purple-50 flex items-center justify-center text-purple-600 shrink-0">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.952 11.952 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-md font-bold text-slate-800">{{ __('Pengaturan Akun & Keamanan') }}</h3>
                                <p class="text-xs text-slate-500 mt-0.5">{{ __('Kelola kredensial login kasir, profil email, dan ganti sandi.') }}</p>
                            </div>
                        </div>
                    </div>

                    <form method="post" action="{{ route('setting.account') }}" class="p-6 sm:p-8 space-y-6">
                        @csrf
                        @method('put')

                        <div class="space-y-5">
                            <div>
                                <label for="name" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Nama Lengkap</label>
                                <input id="name" 
                                       name="name" 
                                       type="text" 
                                       class="block w-full px-4 py-3 rounded-xl border {{ $errors->has('name') ? 'border-red-400 bg-red-50 focus:ring-red-500/20' : 'border-slate-200 bg-slate-50/50 focus:ring-blue-500/20 focus:border-blue-600' }} text-sm text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-4 focus:bg-white transition-all" 
                                       value="{{ old('name', auth()->user()->name) }}" 
                                       required />
                                @error('name')
                                    <p class="mt-2 text-xs text-red-500 font-medium flex items-center gap-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="email" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Email</label>
                                <input id="email" 
                                       name="email" 
                                       type="email" 
                                       class="block w-full px-4 py-3 rounded-xl border {{ $errors->has('email') ? 'border-red-400 bg-red-50 focus:ring-red-500/20' : 'border-slate-200 bg-slate-50/50 focus:ring-blue-500/20' }} text-sm text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-4 focus:bg-white transition-all" 
                                       value="{{ old('email', auth()->user()->email) }}" 
                                       required />
                                @error('email')
                                    <p class="mt-2 text-xs text-red-500 font-medium flex items-center gap-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="border-t border-slate-100 pt-6 mt-6">
                                <div class="flex items-center gap-2 mb-4">
                                    <div class="h-1 w-6 rounded bg-purple-500"></div>
                                    <h4 class="text-xs font-bold text-slate-800 uppercase tracking-wider">Ubah Kata Sandi (Opsional)</h4>
                                </div>
                                
                                <div class="space-y-4">
                                    <div>
                                        <label for="current_password" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Kata Sandi Saat Ini</label>
                                        <div class="relative">
                                            <input id="current_password" 
                                                   name="current_password" 
                                                   :type="showCurrent ? 'text' : 'password'" 
                                                   class="block w-full pl-4 pr-11 py-3 rounded-xl border {{ $errors->has('current_password') ? 'border-red-400 bg-red-50 focus:ring-red-500/20' : 'border-slate-200 bg-slate-50/50 focus:ring-blue-500/20' }} text-sm text-slate-800 focus:outline-none focus:ring-4 focus:bg-white transition-all" />
                                            <button type="button" 
                                                    @click="showCurrent = !showCurrent" 
                                                    class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-400 hover:text-slate-600 transition-colors">
                                                <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" x-show="!showCurrent">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                </svg>
                                                <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" x-show="showCurrent" x-cloak>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18" />
                                                </svg>
                                            </button>
                                        </div>
                                        @error('current_password')
                                            <p class="mt-2 text-xs text-red-500 font-medium flex items-center gap-1">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label for="password" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Kata Sandi Baru</label>
                                        <div class="relative">
                                            <input id="password" 
                                                   name="password" 
                                                   :type="showNew ? 'text' : 'password'" 
                                                   class="block w-full pl-4 pr-11 py-3 rounded-xl border {{ $errors->has('password') ? 'border-red-400 bg-red-50 focus:ring-red-500/20' : 'border-slate-200 bg-slate-50/50 focus:ring-blue-500/20' }} text-sm text-slate-800 focus:outline-none focus:ring-4 focus:bg-white transition-all" />
                                            <button type="button" 
                                                    @click="showNew = !showNew" 
                                                    class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-400 hover:text-slate-600 transition-colors">
                                                <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" x-show="!showNew">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                </svg>
                                                <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" x-show="showNew" x-cloak>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18" />
                                                </svg>
                                            </button>
                                        </div>
                                        @error('password')
                                            <p class="mt-2 text-xs text-red-500 font-medium flex items-center gap-1">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label for="password_confirmation" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Konfirmasi Kata Sandi Baru</label>
                                        <div class="relative">
                                            <input id="password_confirmation" 
                                                   name="password_confirmation" 
                                                   :type="showConfirm ? 'text' : 'password'" 
                                                   class="block w-full pl-4 pr-11 py-3 rounded-xl border {{ $errors->has('password_confirmation') ? 'border-red-400 bg-red-50 focus:ring-red-500/20' : 'border-slate-200 bg-slate-50/50 focus:ring-blue-500/20' }} text-sm text-slate-800 focus:outline-none focus:ring-4 focus:bg-white transition-all" />
                                            <button type="button" 
                                                    @click="showConfirm = !showConfirm" 
                                                    class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-400 hover:text-slate-600 transition-colors">
                                                <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" x-show="!showConfirm">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                </svg>
                                                <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" x-show="showConfirm" x-cloak>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18" />
                                                </svg>
                                            </button>
                                        </div>
                                        @error('password_confirmation')
                                            <p class="mt-2 text-xs text-red-500 font-medium flex items-center gap-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="pt-6 border-t border-slate-100 flex justify-end">
                            <button type="submit" 
                                    class="inline-flex items-center justify-center px-5 py-3 bg-purple-600 hover:bg-purple-500 active:scale-[0.98] text-white text-xs font-bold uppercase tracking-wider rounded-xl shadow-lg shadow-purple-500/20 hover:shadow-purple-500/35 transition-all duration-150">
                                {{ __('Simpan Pengaturan Akun') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
