<div class="md:shrink-0">

    {{-- ── Mobile Overlay ──────────────────────────────────────── --}}
    <div id="sidebarOverlay"
         class="fixed inset-0 bg-black/60 backdrop-blur-sm z-40 hidden md:hidden">
    </div>

    <aside id="sidebarComponent"
           class="fixed inset-y-0 left-0 z-50 -translate-x-full
                  md:relative md:translate-x-0 md:inset-y-auto md:z-auto
                  w-60 h-screen flex flex-col shrink-0
                  bg-white border-r border-slate-200
                  transition-transform duration-300 ease-in-out
                  shadow-2xl md:shadow-none">


        {{-- ── Logo / Brand ─────────────────────────────────────── --}}
        <div class="shrink-0 h-14 flex items-center justify-between px-4
                    border-b border-slate-200">
            <div class="flex items-center gap-2.5 min-w-0">
                {{-- App Icon --}}
                <div class="shrink-0 w-7 h-7 bg-blue-600 rounded-lg flex items-center justify-center">
                    <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4z
                                 M3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6z
                                 M14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z"/>
                    </svg>
                </div>
                <div class="min-w-0">
                    <p class="font-semibold text-slate-800 text-sm leading-none tracking-tight truncate">
                        {{ session('active_vendor_name') ?? config('app.name', 'POS BarengSir') }}
                    </p>
                    @if(session('active_branch_name'))
                        <p class="text-[10px] text-slate-500 truncate mt-0.5">
                            Cabang: {{ session('active_branch_name') }}
                        </p>
                    @endif
                </div>
            </div>

            {{-- Mobile close button --}}
            <button id="closeSidebarMobile"
                    class="md:hidden shrink-0 p-1.5 rounded-md
                           text-slate-500 hover:text-slate-800 hover:bg-slate-100
                           transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        {{-- ── Quick Action Button ──────────────────────────────── --}}
        <div class="shrink-0 p-5 pb-2">
            <a href="{{ route('transaction.index') }}"
               class="flex items-center justify-center gap-1.5 w-full
                      bg-blue-600 hover:bg-blue-500 active:scale-[0.98]
                      text-white text-xs font-semibold
                      py-2 px-3 rounded-xl
                      transition-all duration-150
                      shadow-lg shadow-blue-900/30">
                <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                </svg>
                New Transaction
            </a>
        </div>

        {{-- ── Navigation (scrollable) ──────────────────────────── --}}
        <nav class="flex-1 overflow-y-auto px-2 py-2">
            <p class="text-[10px] font-semibold text-slate-600 uppercase tracking-widest px-3 mb-1.5">
                Main Menu
            </p>
            <ul class="space-y-0.5">
                @foreach($menus as $menu)
                    @php
                        $hasSubmenus = isset($menu['submenus']);
                        $isActive    = request()->routeIs($menu['route']);
                        if ($hasSubmenus && !$isActive) {
                            foreach ($menu['submenus'] as $sub) {
                                if (request()->routeIs($sub['route'] . '*')) {
                                    $isActive = true;
                                    break;
                                }
                            }
                        }
                    @endphp

                    {{-- ── Menu item with submenus ──────────────── --}}
                    @if($hasSubmenus)
                        <li x-data="{ open: {{ $isActive ? 'true' : 'false' }} }">
                            <button @click="open = !open"
                                    class="w-full flex items-center justify-between
                                           px-3 py-2 rounded-lg text-xs font-medium
                                           transition-colors duration-150
                                           {{ $isActive
                                               ? 'bg-blue-50 text-blue-600'
                                               : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50' }}">
                                <div class="flex items-center gap-2.5">
                                    <svg class="w-4 h-4 shrink-0
                                                {{ $isActive ? 'text-blue-600' : 'text-slate-400' }}"
                                         fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        {!! $menu['icon'] !!}
                                    </svg>
                                    {{ $menu['name'] }}
                                </div>
                                <svg class="w-3.5 h-3.5 shrink-0 text-slate-600 transition-transform duration-200"
                                     :class="{ 'rotate-180': open }"
                                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M19 9l-7 7-7-7"/>
                                </svg>
                            </button>

                            {{-- Submenu tree --}}
                            <ul x-show="open" x-collapse
                                class="mt-0.5 ml-5 pl-3 space-y-0.5 border-l border-slate-200">
                                @foreach($menu['submenus'] as $submenu)
                                    @php $isSubActive = request()->routeIs($submenu['route'] . '*'); @endphp
                                    <li>
                                        <a href="{{ $submenu['url'] }}"
                                           class="block px-2 py-1.5 rounded-md text-xs
                                                  transition-colors duration-150
                                                  {{ $isSubActive
                                                      ? 'text-blue-600 bg-blue-50 font-medium'
                                                      : 'text-slate-500 hover:text-slate-900 hover:bg-slate-50' }}">
                                            {{ $submenu['name'] }}
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </li>

                    {{-- ── Regular menu item ────────────────────── --}}
                    @else
                        <li>
                            <a href="{{ $menu['url'] }}"
                               class="group relative flex items-center gap-2.5
                                      px-3 py-2 rounded-lg text-xs font-medium
                                      transition-colors duration-150
                                      {{ $isActive
                                          ? 'bg-blue-50 text-blue-600'
                                          : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50' }}">

                                {{-- Active accent bar --}}
                                @if($isActive)
                                    <span class="absolute left-0 top-1/2 -translate-y-1/2
                                                 w-0.5 h-5 bg-blue-500 rounded-r-full"></span>
                                @endif

                                <svg class="w-4 h-4 shrink-0 transition-colors
                                            {{ $isActive ? 'text-blue-600' : 'text-slate-400 group-hover:text-slate-600' }}"
                                     fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    {!! $menu['icon'] !!}
                                </svg>
                                {{ $menu['name'] }}
                            </a>
                        </li>
                    @endif
                @endforeach
            </ul>
        </nav>

        {{-- ── User Profile (always pinned at bottom) ───────────── --}}
        <div class="shrink-0 border-t border-slate-200 px-3 py-3">
            <div class="flex items-center gap-2.5">
                {{-- Avatar --}}
                <div class="shrink-0 w-8 h-8 rounded-lg
                            bg-blue-50 border border-blue-100
                            flex items-center justify-center
                            text-blue-600 font-semibold text-xs uppercase">
                    {{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 1)) }}
                </div>

                {{-- Name & Role --}}
                <div class="flex-1 min-w-0">
                    <p class="text-xs font-semibold text-slate-800 truncate leading-none mb-0.5">
                        {{ auth()->user()->name ?? 'User' }}
                    </p>
                    <p class="text-[10px] text-slate-500 truncate capitalize">
                        {{ session('active_role', auth()->user()->role ?? 'kasir') }}
                    </p>
                </div>

                {{-- Logout --}}
                <form method="POST" action="{{ route('logout') }}" class="shrink-0">
                    @csrf
                    <button type="submit"
                            title="Keluar"
                            class="p-1.5 text-slate-600 rounded-md
                                   hover:text-red-400 hover:bg-red-500/10
                                   transition-colors">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                        </svg>
                    </button>
                </form>
            </div>
        </div>

    </aside>
</div>