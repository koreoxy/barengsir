<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'POS BarengSir') }} - Super Admin</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-slate-50" style="font-family: 'Inter', sans-serif;">
        <div class="min-h-screen flex">
            <!-- Sidebar -->
            @include('superadmin.layouts.sidebar')

            <!-- Main Content -->
            <div class="flex-1 flex flex-col min-w-0 overflow-hidden">
                <!-- Topbar -->
                <header class="bg-blue-600 text-white h-16 flex items-center justify-between px-6 shrink-0 shadow-md sticky top-0 z-10">
                    <div class="flex items-center space-x-4">
                        <h2 class="text-lg font-bold text-white">@yield('title', 'Super Admin Panel')</h2>
                    </div>
                    <div class="flex items-center space-x-6">
                        <!-- User Profile Info -->
                        <div class="flex items-center space-x-3 pr-6 border-r border-blue-500">
                            <div class="text-right">
                                <p class="text-sm font-bold text-white leading-none">{{ Auth::user()->name }}</p>
                                <p class="text-xs text-blue-200 font-semibold mt-1">Super Admin</p>
                            </div>
                            <div class="w-9 h-9 bg-blue-500 text-white rounded-full flex items-center justify-center font-bold shadow-inner border border-blue-400">
                                {{ substr(Auth::user()->name, 0, 1) }}
                            </div>
                        </div>

                        <!-- Logout -->
                        <form method="POST" action="{{ route('superadmin.logout') }}">
                            @csrf
                            <button type="submit" class="p-2 text-blue-200 hover:text-white transition-colors" title="Keluar">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                </svg>
                            </button>
                        </form>
                    </div>
                </header>

                <!-- Page Content -->
                <main class="flex-1 overflow-y-auto p-8">
                    @if (session('success'))
                        <div class="mb-6 p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-xl flex items-center">
                            <svg class="w-5 h-5 mr-3 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            {{ session('success') }}
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="mb-6 p-4 bg-rose-50 border border-rose-200 text-rose-700 rounded-xl flex items-center">
                            <svg class="w-5 h-5 mr-3 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            {{ session('error') }}
                        </div>
                    @endif

                    @yield('content')
                </main>
            </div>
        </div>
    </body>
</html>
