<x-guest-layout>
    <x-slot name="title">Daftar</x-slot>

    <!-- Page Title -->
    <div class="mb-8">
        <h2 class="text-3xl font-bold text-slate-800">Buat Akun Baru</h2>
        <p class="text-slate-500 mt-2">Isi formulir berikut untuk mendaftarkan akun kasir.</p>
    </div>

    <form method="POST" action="{{ route('register') }}" class="space-y-5">
        @csrf

        <!-- Name -->
        <div>
            <label for="name" class="block text-sm font-medium text-slate-700 mb-1.5">
                Nama Lengkap
            </label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                </div>
                <input
                    id="name"
                    type="text"
                    name="name"
                    value="{{ old('name') }}"
                    required
                    autofocus
                    autocomplete="name"
                    placeholder="Nama Kasir"
                    class="block w-full pl-10 pr-4 py-3 border {{ $errors->has('name') ? 'border-red-400 bg-red-50' : 'border-slate-300 bg-white' }} rounded-xl text-sm text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                />
            </div>
            @error('name')
                <p class="mt-1.5 text-xs text-red-600 flex items-center gap-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    {{ $message }}
                </p>
            @enderror
        </div>

        <!-- Email -->
        <div>
            <label for="email" class="block text-sm font-medium text-slate-700 mb-1.5">
                Alamat Email
            </label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" />
                    </svg>
                </div>
                <input
                    id="email"
                    type="email"
                    name="email"
                    value="{{ old('email') }}"
                    required
                    autocomplete="username"
                    placeholder="kasir@gmail.com"
                    class="block w-full pl-10 pr-4 py-3 border {{ $errors->has('email') ? 'border-red-400 bg-red-50' : 'border-slate-300 bg-white' }} rounded-xl text-sm text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                />
            </div>
            @error('email')
                <p class="mt-1.5 text-xs text-red-600 flex items-center gap-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    {{ $message }}
                </p>
            @enderror
        </div>

        <!-- Password -->
        <div>
            <label for="password" class="block text-sm font-medium text-slate-700 mb-1.5">
                Password
            </label>
            <div class="relative" x-data="{ show: false }">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                    </svg>
                </div>
                <input
                    id="password"
                    :type="show ? 'text' : 'password'"
                    name="password"
                    required
                    autocomplete="new-password"
                    placeholder="Min. 8 karakter"
                    class="block w-full pl-10 pr-10 py-3 border {{ $errors->has('password') ? 'border-red-400 bg-red-50' : 'border-slate-300 bg-white' }} rounded-xl text-sm text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                />
                <button
                    type="button"
                    @click="show = !show"
                    class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-blue-600 transition-colors"
                >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" x-show="!show">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" x-show="show" x-cloak>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18" />
                    </svg>
                </button>
            </div>
            @error('password')
                <p class="mt-1.5 text-xs text-red-600 flex items-center gap-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    {{ $message }}
                </p>
            @enderror
        </div>

        <!-- Confirm Password -->
        <div>
            <label for="password_confirmation" class="block text-sm font-medium text-slate-700 mb-1.5">
                Konfirmasi Password
            </label>
            <div class="relative" x-data="{ show: false }">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                    </svg>
                </div>
                <input
                    id="password_confirmation"
                    :type="show ? 'text' : 'password'"
                    name="password_confirmation"
                    required
                    autocomplete="new-password"
                    placeholder="Ulangi password"
                    class="block w-full pl-10 pr-10 py-3 border {{ $errors->has('password_confirmation') ? 'border-red-400 bg-red-50' : 'border-slate-300 bg-white' }} rounded-xl text-sm text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                />
                <button
                    type="button"
                    @click="show = !show"
                    class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-blue-600 transition-colors"
                >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" x-show="!show">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" x-show="show" x-cloak>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18" />
                    </svg>
                </button>
            </div>
            @error('password_confirmation')
                <p class="mt-1.5 text-xs text-red-600 flex items-center gap-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    {{ $message }}
                </p>
            @enderror
        </div>

        <!-- Divider -->
        <div class="relative my-6">
            <div class="absolute inset-0 flex items-center">
                <div class="w-full border-t border-slate-200"></div>
            </div>
            <div class="relative flex justify-center">
                <span class="bg-white px-4 text-xs font-semibold text-slate-400 uppercase tracking-wider">
                    Informasi Bisnis Anda
                </span>
            </div>
        </div>

        <!-- Business Name -->
        <div>
            <label for="business_name" class="block text-sm font-medium text-slate-700 mb-1.5">
                Nama Bisnis / Toko <span class="text-red-500">*</span>
            </label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                </div>
                <input
                    id="business_name"
                    type="text"
                    name="business_name"
                    value="{{ old('business_name') }}"
                    required
                    placeholder="contoh: Toko Serba Ada"
                    class="block w-full pl-10 pr-4 py-3 border {{ $errors->has('business_name') ? 'border-red-400 bg-red-50' : 'border-slate-300 bg-white' }} rounded-xl text-sm text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                />
            </div>
            @error('business_name')
                <p class="mt-1.5 text-xs text-red-600 flex items-center gap-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    {{ $message }}
                </p>
            @enderror
        </div>

        <!-- Business Phone -->
        <div>
            <label for="business_phone" class="block text-sm font-medium text-slate-700 mb-1.5">
                Nomor Telepon Bisnis
            </label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                    </svg>
                </div>
                <input
                    id="business_phone"
                    type="text"
                    name="business_phone"
                    value="{{ old('business_phone') }}"
                    placeholder="0812..."
                    class="block w-full pl-10 pr-4 py-3 border {{ $errors->has('business_phone') ? 'border-red-400 bg-red-50' : 'border-slate-300 bg-white' }} rounded-xl text-sm text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                />
            </div>
            @error('business_phone')
                <p class="mt-1.5 text-xs text-red-600 flex items-center gap-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    {{ $message }}
                </p>
            @enderror
        </div>

        <!-- Business Address -->
        <div>
            <label for="business_address" class="block text-sm font-medium text-slate-700 mb-1.5">
                Alamat Bisnis
            </label>
            <div class="relative">
                <div class="absolute top-3 left-3 pointer-events-none">
                    <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                </div>
                <textarea
                    id="business_address"
                    name="business_address"
                    rows="2"
                    placeholder="Jl. Merdeka No. 1..."
                    class="block w-full pl-10 pr-4 py-3 border {{ $errors->has('business_address') ? 'border-red-400 bg-red-50' : 'border-slate-300 bg-white' }} rounded-xl text-sm text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                >{{ old('business_address') }}</textarea>
            </div>
            @error('business_address')
                <p class="mt-1.5 text-xs text-red-600 flex items-center gap-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    {{ $message }}
                </p>
            @enderror
        </div>

        <!-- Divider -->
        <div class="relative my-6">
            <div class="absolute inset-0 flex items-center">
                <div class="w-full border-t border-slate-200"></div>
            </div>
            <div class="relative flex justify-center">
                <span class="bg-white px-4 text-xs font-semibold text-slate-400 uppercase tracking-wider">
                    Cabang Pertama
                </span>
            </div>
        </div>

        <!-- Branch Name -->
        <div>
            <label for="branch_name" class="block text-sm font-medium text-slate-700 mb-1.5">
                Nama Cabang <span class="text-red-500">*</span>
            </label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                </div>
                <input
                    id="branch_name"
                    type="text"
                    name="branch_name"
                    value="{{ old('branch_name') }}"
                    required
                    placeholder="contoh: Cabang Pusat"
                    class="block w-full pl-10 pr-4 py-3 border {{ $errors->has('branch_name') ? 'border-red-400 bg-red-50' : 'border-slate-300 bg-white' }} rounded-xl text-sm text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                />
            </div>
            @error('branch_name')
                <p class="mt-1.5 text-xs text-red-600 flex items-center gap-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    {{ $message }}
                </p>
            @enderror
        </div>

        <!-- Submit -->
        <button
            type="submit"
            class="w-full flex items-center justify-center px-4 py-3 bg-blue-600 border border-transparent rounded-xl shadow-sm text-sm font-semibold text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-150 mt-2"
        >
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
            </svg>
            Daftar Sekarang
        </button>

        <!-- Login Link -->
        <p class="text-center text-sm text-slate-500 mt-4">
            Sudah punya akun?
            <a href="{{ route('login') }}" class="font-semibold text-blue-600 hover:text-blue-700 transition-colors">
                Masuk di sini
            </a>
        </p>
    </form>
</x-guest-layout>
