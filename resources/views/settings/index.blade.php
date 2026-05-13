<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Pengaturan') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- Flash Messages -->
            @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                <strong class="font-bold">Berhasil!</strong>
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
            @endif

            <!-- App Settings -->
            <div class="bg-white p-4 sm:p-8 shadow sm:rounded-lg border border-gray-100">
                <div class="max-w-xl">
                    <section>
                        <header>
                            <h2 class="text-lg font-medium text-gray-900">
                                {{ __('Pengaturan Toko') }}
                            </h2>
                            <p class="mt-1 text-sm text-gray-600">
                                {{ __('Perbarui informasi dasar toko Anda yang akan digunakan di seluruh aplikasi POS.') }}
                            </p>
                        </header>

                        <form method="post" action="{{ route('setting.store') }}" class="mt-6 space-y-6">
                            @csrf
                            @method('put')

                            <div>
                                <label for="store_name" class="block text-sm font-medium text-gray-700">Nama Toko</label>
                                <input id="store_name" name="store_name" type="text" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm" value="{{ old('store_name', $settings['store_name'] ?? '') }}" required autofocus />
                                @error('store_name')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="store_address" class="block text-sm font-medium text-gray-700">Alamat Toko</label>
                                <textarea id="store_address" name="store_address" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm" required>{{ old('store_address', $settings['store_address'] ?? '') }}</textarea>
                                @error('store_address')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="store_phone" class="block text-sm font-medium text-gray-700">Nomor Telepon</label>
                                <input id="store_phone" name="store_phone" type="text" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm" value="{{ old('store_phone', $settings['store_phone'] ?? '') }}" required />
                                @error('store_phone')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="flex items-center gap-4">
                                <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                    {{ __('Simpan') }}
                                </button>
                            </div>
                        </form>
                    </section>
                </div>
            </div>

            <!-- Account Settings -->
            <div class="bg-white p-4 sm:p-8 shadow sm:rounded-lg border border-gray-100">
                <div class="max-w-xl">
                    <section>
                        <header>
                            <h2 class="text-lg font-medium text-gray-900">
                                {{ __('Pengaturan Akun') }}
                            </h2>
                            <p class="mt-1 text-sm text-gray-600">
                                {{ __('Perbarui informasi profil dan kata sandi akun Anda.') }}
                            </p>
                        </header>

                        <form method="post" action="{{ route('setting.account') }}" class="mt-6 space-y-6">
                            @csrf
                            @method('put')

                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700">Nama Lengkap</label>
                                <input id="name" name="name" type="text" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm" value="{{ old('name', auth()->user()->name) }}" required />
                                @error('name')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                                <input id="email" name="email" type="email" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm" value="{{ old('email', auth()->user()->email) }}" required />
                                @error('email')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="border-t border-gray-100 pt-6 mt-6">
                                <h3 class="text-md font-medium text-gray-900 mb-4">Ubah Kata Sandi (Opsional)</h3>
                                
                                <div class="space-y-6">
                                    <div>
                                        <label for="current_password" class="block text-sm font-medium text-gray-700">Kata Sandi Saat Ini</label>
                                        <input id="current_password" name="current_password" type="password" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm" />
                                        @error('current_password')
                                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label for="password" class="block text-sm font-medium text-gray-700">Kata Sandi Baru</label>
                                        <input id="password" name="password" type="password" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm" />
                                        @error('password')
                                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Konfirmasi Kata Sandi Baru</label>
                                        <input id="password_confirmation" name="password_confirmation" type="password" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm" />
                                        @error('password_confirmation')
                                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="flex items-center gap-4">
                                <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                    {{ __('Simpan') }}
                                </button>
                            </div>
                        </form>
                    </section>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
