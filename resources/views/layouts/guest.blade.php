<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'POS BarengSir') }} - {{ $title ?? 'Auth' }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-slate-50">
        <div class="min-h-screen flex">

            <!-- Left Panel - Branding -->
            <div class="hidden lg:flex lg:w-1/2 {{ $panelClass }} flex-col justify-between p-12 relative overflow-hidden">
                <!-- Decorative circles -->
                <div class="absolute top-0 right-0 w-64 h-64 bg-white/5 rounded-full -translate-y-1/2 translate-x-1/2"></div>
                <div class="absolute bottom-0 left-0 w-96 h-96 bg-white/5 rounded-full translate-y-1/2 -translate-x-1/2"></div>

                <!-- Logo & Brand -->
                <div class="relative z-10">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-white rounded-xl flex items-center justify-center shadow-lg">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                        </div>
                        <span class="text-white font-bold text-xl">POS BarengSir</span>
                    </div>
                </div>

                <!-- Hero Text -->
                <div class="relative z-10">
                    <h1 class="text-4xl font-bold text-white leading-tight mb-4">
                        {!! str_replace('&', '<br>', $heroTitle) !!}
                    </h1>
                    <p class="text-blue-100 text-lg leading-relaxed mb-8">
                        Sistem Point of Sale modern untuk usaha Anda. Catat transaksi, kelola stok, dan lihat laporan secara real-time.
                    </p>

                    <!-- Features -->
                    <div class="space-y-3">
                        @foreach([
                            ['icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2', 'text' => 'Manajemen Produk & Stok'],
                            ['icon' => 'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z', 'text' => 'Laporan Penjualan Real-time'],
                            ['icon' => 'M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z', 'text' => 'Multi-kasir & Multi-role'],
                        ] as $feature)
                            <div class="flex items-center space-x-3">
                                <div class="flex-shrink-0 w-8 h-8 bg-white/20 rounded-lg flex items-center justify-center">
                                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $feature['icon'] }}" />
                                    </svg>
                                </div>
                                <span class="text-blue-100 text-sm">{{ $feature['text'] }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Footer text -->
                <div class="relative z-10">
                    <p class="text-blue-200 text-sm">© {{ date('Y') }} POS BarengSir. All rights reserved.</p>
                </div>
            </div>

            <!-- Right Panel - Form -->
            <div class="flex-1 flex flex-col justify-center items-center p-6 sm:p-12">
                <!-- Mobile Logo -->
                <div class="lg:hidden mb-8 text-center">
                    <div class="inline-flex items-center space-x-2 mb-2">
                        <div class="w-10 h-10 bg-blue-600 rounded-xl flex items-center justify-center shadow-lg">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                        </div>
                        <span class="text-slate-800 font-bold text-xl">POS BarengSir</span>
                    </div>
                </div>

                <div class="w-full max-w-md">
                    {{ $slot }}
                </div>
            </div>
        </div>
    </body>
</html>
