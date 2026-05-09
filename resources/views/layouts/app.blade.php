<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'POS Dashboard') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <!-- AlpineJS -->
        <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    </head>
    <body class="font-sans antialiased bg-slate-50 text-slate-900">
        <div class="min-h-screen flex bg-slate-50">
            <!-- Sidebar -->
            <x-sidebar />

            <!-- Main Wrapper -->
            <div class="flex-1 flex flex-col min-w-0 overflow-hidden">
                <!-- Topbar -->
                <header class="bg-blue-600 text-white shadow-md z-10 sticky top-0">
                    <div class="w-full px-4 sm:px-6 lg:px-8">
                        <div class="flex justify-between h-16 items-center">
                            <div class="flex items-center">
                                <!-- Mobile menu button -->
                                <button type="button" id="sidebarToggle" class="md:hidden inline-flex items-center justify-center p-2 rounded-md text-white hover:text-blue-100 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-white mr-2">
                                    <span class="sr-only">Buka menu sidebar</span>
                                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                                    </svg>
                                </button>
                                <div class="shrink-0 flex items-center font-bold text-xl">
                                    POS BarengSir
                                </div>
                            </div>
                            <div class="flex items-center space-x-4">
                                <span class="text-sm font-medium">{{ auth()->user()->name ?? 'Kasir' }}</span>
                                <div class="h-8 w-8 rounded-full bg-blue-400 border-2 border-white flex items-center justify-center text-xs font-bold uppercase">
                                    {{ strtoupper(substr(auth()->user()->name ?? 'K', 0, 1)) }}
                                </div>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="text-sm text-blue-100 hover:text-white transition-colors" title="Keluar">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </header>

                <!-- Page Content -->
                <main class="flex-1 overflow-y-auto">
                    @yield('content')
                </main>
            </div>
        </div>

        <!-- Mobile Sidebar Overlay script -->
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const sidebarToggle = document.getElementById('sidebarToggle');
                const sidebar = document.getElementById('sidebarComponent');
                const overlay = document.getElementById('sidebarOverlay');

                function toggleSidebar() {
                    sidebar.classList.toggle('-translate-x-full');
                    if (overlay) {
                        overlay.classList.toggle('hidden');
                    }
                }

                if (sidebarToggle && sidebar) {
                    sidebarToggle.addEventListener('click', toggleSidebar);
                }

                if (overlay) {
                    overlay.addEventListener('click', toggleSidebar);
                }
            });
        </script>
    </body>
</html>
