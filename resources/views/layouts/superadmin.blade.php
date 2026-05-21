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
        <link href="https://fonts.googleapis.com/css2?family=Geist:wght@300;400;500;600;700;800&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.1/dist/cdn.min.js"></script>

        <style>
            * { font-family: 'Geist', ui-sans-serif, system-ui, sans-serif !important; }

            /* Slim scrollbar */
            ::-webkit-scrollbar { width: 5px; height: 5px; }
            ::-webkit-scrollbar-track { background: transparent; }
            ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 999px; }
            ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
        </style>
    </head>
    <body class="font-sans antialiased bg-slate-50 text-slate-900 h-screen overflow-hidden" x-data="{ sidebarOpen: false }">
        <div class="flex h-screen overflow-hidden">
            <!-- Sidebar Mobile Overlay -->
            <div id="sidebarOverlay" 
                 x-show="sidebarOpen" 
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 @click="sidebarOpen = false"
                 class="fixed inset-0 bg-black/60 backdrop-blur-sm z-20 md:hidden">
            </div>

            <!-- Sidebar -->
            @include('superadmin.layouts.sidebar')

            <!-- Main Content Area -->
            <div class="flex-1 flex flex-col min-w-0 h-screen overflow-hidden">
                <!-- Topbar -->
                <header class="bg-[#0f172a] text-white h-16 flex items-center justify-between px-6 shrink-0 border-b border-slate-800 sticky top-0 z-10 shadow-sm">
                    <div class="flex items-center space-x-4">
                        <!-- Hamburger Menu Button -->
                        <button @click="sidebarOpen = !sidebarOpen" 
                                class="p-2 rounded-lg text-slate-400 hover:text-white hover:bg-white/10 transition-colors focus:outline-none md:hidden">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                            </svg>
                        </button>
                        <h2 class="text-sm md:text-base font-bold text-white tracking-wide">@yield('title', 'Super Admin Panel')</h2>
                    </div>
                    <div class="flex items-center space-x-6">
                        <!-- User Profile Info -->
                        <div class="flex items-center space-x-3 pr-4 border-r border-slate-800">
                            <div class="text-right hidden sm:block">
                                <p class="text-xs font-bold text-white leading-none">{{ Auth::user()->name }}</p>
                                <p class="text-[10px] text-slate-400 font-semibold mt-1">Super Admin</p>
                            </div>
                            <div class="w-9 h-9 bg-gradient-to-tr from-blue-600 to-indigo-600 text-white rounded-lg flex items-center justify-center font-bold shadow-inner border border-blue-500/30 uppercase text-sm">
                                {{ substr(Auth::user()->name, 0, 1) }}
                            </div>
                        </div>

                        <!-- Logout -->
                        <form method="POST" action="{{ route('superadmin.logout') }}">
                            @csrf
                            <button type="submit" class="p-2 text-slate-400 hover:text-white transition-colors" title="Keluar">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                </svg>
                            </button>
                        </form>
                    </div>
                </header>

                <!-- Page Content -->
                <main class="flex-1 overflow-y-auto p-4 md:p-8 bg-slate-50">
                    @if (session('success'))
                        <div class="mb-6 p-4 bg-emerald-50/80 backdrop-blur-sm border border-emerald-200/50 text-emerald-800 rounded-2xl flex items-center shadow-sm shadow-emerald-100/50">
                            <div class="w-8 h-8 rounded-lg bg-emerald-500/10 text-emerald-600 flex items-center justify-center mr-3.5 shrink-0 border border-emerald-500/20">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                                </svg>
                            </div>
                            <span class="text-xs md:text-sm font-semibold tracking-tight">{{ session('success') }}</span>
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="mb-6 p-4 bg-rose-50/80 backdrop-blur-sm border border-rose-200/50 text-rose-800 rounded-2xl flex items-center shadow-sm shadow-rose-100/50">
                            <div class="w-8 h-8 rounded-lg bg-rose-500/10 text-rose-600 flex items-center justify-center mr-3.5 shrink-0 border border-rose-500/20">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <span class="text-xs md:text-sm font-semibold tracking-tight">{{ session('error') }}</span>
                        </div>
                    @endif

                    @yield('content')
                </main>
            </div>
        </div>
    </body>
</html>
