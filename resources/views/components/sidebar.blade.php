<div>
    <!-- Mobile Sidebar Overlay -->
    <div id="sidebarOverlay" class="fixed inset-0 bg-slate-900/50 z-20 hidden md:hidden transition-opacity"></div>

    <!-- Sidebar Component -->
    <aside id="sidebarComponent" class="fixed inset-y-0 left-0 w-64 bg-white border-r border-slate-200 z-30 transform -translate-x-full md:translate-x-0 transition-transform duration-300 ease-in-out md:static flex flex-col h-full shadow-sm">
        
        <!-- Sidebar Header (Mobile Only) -->
        <div class="h-16 flex items-center justify-between px-4 bg-blue-600 text-white md:hidden">
            <span class="font-bold text-xl">Menu POS</span>
            <button id="closeSidebarMobile" class="p-1 rounded-md hover:bg-blue-700 focus:outline-none" onclick="document.getElementById('sidebarToggle').click()">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        <!-- User Info / Shop Info (Optional) -->
        <div class="p-6 border-b border-slate-100 hidden md:block">
            <h2 class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Navigasi Utama</h2>
        </div>

        <!-- Navigation Links -->
        <nav class="flex-1 px-4 py-4 space-y-1 overflow-y-auto">
            @foreach($menus as $menu)
                @php
                    $hasSubmenus = isset($menu['submenus']);
                    $isActive = request()->routeIs($menu['route']);
                    $activeClass = 'bg-blue-50 text-blue-700 font-medium';
                    $inactiveClass = 'text-slate-600 hover:bg-slate-50 hover:text-blue-600';
                @endphp

                @if($hasSubmenus)
                    <div x-data="{ open: {{ $isActive ? 'true' : 'false' }} }">
                        <button @click="open = !open" 
                           class="w-full group flex items-center justify-between px-3 py-2.5 text-sm rounded-lg transition-colors duration-150 ease-in-out {{ $isActive ? $activeClass : $inactiveClass }}">
                            <div class="flex items-center">
                                <svg class="flex-shrink-0 w-5 h-5 mr-3 {{ $isActive ? 'text-blue-600' : 'text-slate-400 group-hover:text-blue-500' }}" 
                                     fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    {!! $menu['icon'] !!}
                                </svg>
                                {{ $menu['name'] }}
                            </div>
                            <svg class="w-4 h-4 transition-transform duration-200" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </button>
                        
                        <div x-show="open" x-collapse class="mt-1 space-y-1 pl-11 pr-3">
                            @foreach($menu['submenus'] as $submenu)
                                @php
                                    $isSubActive = request()->routeIs($submenu['route']);
                                @endphp
                                <a href="{{ $submenu['url'] }}" 
                                   class="block px-3 py-2 text-sm rounded-lg transition-colors duration-150 {{ $isSubActive ? 'text-blue-700 bg-blue-50/50 font-medium' : 'text-slate-500 hover:text-blue-600 hover:bg-slate-50' }}">
                                    {{ $submenu['name'] }}
                                </a>
                            @endforeach
                        </div>
                    </div>
                @else
                    <a href="{{ $menu['url'] }}" 
                       class="group flex items-center px-3 py-2.5 text-sm rounded-lg transition-colors duration-150 ease-in-out {{ $isActive ? $activeClass : $inactiveClass }}">
                        <svg class="flex-shrink-0 w-5 h-5 mr-3 {{ $isActive ? 'text-blue-600' : 'text-slate-400 group-hover:text-blue-500' }}" 
                             fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            {!! $menu['icon'] !!}
                        </svg>
                        {{ $menu['name'] }}
                    </a>
                @endif
            @endforeach
        </nav>

        <!-- Sidebar Footer -->
        <div class="p-4 border-t border-slate-100">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full flex items-center px-3 py-2.5 text-sm font-medium text-slate-600 rounded-lg hover:bg-red-50 hover:text-red-600 transition-colors duration-150 group">
                    <svg class="flex-shrink-0 w-5 h-5 mr-3 text-slate-400 group-hover:text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                    </svg>
                    Keluar
                </button>
            </form>
        </div>
    </aside>
</div>