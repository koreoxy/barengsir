<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'POS Dashboard') }}</title>

    {{-- Fonts: Geist (modern developer font, matches Neon's aesthetic) --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Geist:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.1/dist/cdn.min.js"></script>

    <style>
        * { font-family: 'Geist', ui-sans-serif, system-ui, sans-serif !important; }

        /* Slim scrollbar */
        ::-webkit-scrollbar { width: 4px; height: 4px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 999px; }
        ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }

        /* Dashboard layout — sidebar stays in column, only main content scrolls */
        @media (min-width: 768px) {
            #sidebarComponent {
                position: sticky !important;
                top: 0 !important;
                left: auto !important;
                transform: none !important;
                z-index: auto !important;
                inset: auto !important;
            }
        }

        /* Ensure proper flex height chain for scrollable content */
        .content-column {
            flex: 1 1 0%;
            min-width: 0;
            height: 100vh;
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }
        .content-main {
            flex: 1 1 0%;
            min-height: 0;
            overflow-y: auto;
        }

        /* Sidebar full-height with scrollable nav */
        #sidebarComponent {
            height: 100vh;
            display: flex;
            flex-direction: column;
        }
        #sidebarComponent nav {
            flex: 1 1 0%;
            min-height: 0;
            overflow-y: auto;
        }
    </style>
</head>

<body class="font-sans antialiased bg-slate-50 text-slate-900 h-screen overflow-hidden">

    <div class="flex h-screen overflow-hidden">

        {{-- ── Column 1: Sidebar ──────────────────────── --}}
        <x-sidebar />

        {{-- ── Column 2: Content ──────────────────────── --}}
        <div class="content-column">

            {{-- Mobile Topbar (only visible below md breakpoint) --}}
            <header class="md:hidden sticky top-0 z-10 shrink-0
                           h-14 px-4 flex items-center justify-between
                           bg-[#0f172a] border-b border-slate-700/40">
                <div class="flex items-center gap-3">
                    <button id="sidebarToggle"
                            class="p-2 rounded-lg text-slate-400 hover:text-white hover:bg-white/10
                                   transition-colors focus:outline-none">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                    </button>
                    <span class="font-semibold text-white text-sm tracking-tight">
                        {{ config('app.name', 'POS BarengSir') }}
                    </span>
                </div>
                <div class="w-7 h-7 rounded-lg bg-blue-500/20 border border-blue-500/30
                            flex items-center justify-center text-blue-400 text-xs font-semibold uppercase">
                    {{ strtoupper(substr(auth()->user()->name ?? 'K', 0, 1)) }}
                </div>
            </header>

            {{-- Page Content --}}
            <main class="content-main">
                @isset($header)
                    <div class="bg-white border-b border-slate-100 px-6 py-4">
                        {{ $header }}
                    </div>
                @endisset

                @hasSection('content')
                    @yield('content')
                @else
                    {{ $slot ?? '' }}
                @endif
            </main>

        </div>
    </div>

    {{-- Mobile Sidebar JS Controller --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const toggleBtn = document.getElementById('sidebarToggle');
            const sidebar   = document.getElementById('sidebarComponent');
            const overlay   = document.getElementById('sidebarOverlay');
            const closeBtn  = document.getElementById('closeSidebarMobile');

            function openSidebar() {
                sidebar?.classList.remove('-translate-x-full');
                overlay?.classList.remove('hidden');
                document.body.style.overflow = 'hidden'; // lock body scroll when drawer is open
            }

            function closeSidebar() {
                sidebar?.classList.add('-translate-x-full');
                overlay?.classList.add('hidden');
                document.body.style.overflow = ''; // restore body scroll
            }

            toggleBtn?.addEventListener('click', () =>
                sidebar?.classList.contains('-translate-x-full') ? openSidebar() : closeSidebar()
            );
            overlay?.addEventListener('click', closeSidebar);
            closeBtn?.addEventListener('click', closeSidebar);
        });
    </script>
</body>
</html>
