<aside id="sidebarComponent"
       :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
       class="fixed inset-y-0 left-0 z-30 md:relative md:translate-x-0 md:inset-y-auto md:z-auto
              w-64 h-screen flex flex-col shrink-0 bg-white border-r border-slate-200
              transition-transform duration-300 ease-in-out shadow-2xl md:shadow-none">
    
    <!-- Brand -->
    <div class="h-16 flex items-center justify-between px-6 border-b border-slate-200">
        <div class="flex items-center space-x-3">
            <div class="w-8 h-8 bg-gradient-to-tr from-blue-600 to-indigo-600 rounded-lg flex items-center justify-center shadow-lg border border-blue-500/20">
                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
            </div>
            <div>
                <p class="font-bold text-slate-800 text-sm leading-none tracking-tight">POS <span class="text-blue-600 italic">Central</span></p>
                <p class="text-[9px] text-slate-400 font-black mt-0.5 tracking-wider uppercase">Control Center</p>
            </div>
        </div>
        
        <!-- Mobile close button -->
        <button @click="sidebarOpen = false"
                class="md:hidden shrink-0 p-1.5 rounded-lg text-slate-500 hover:text-slate-800 hover:bg-slate-100 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
    </div>

    <!-- Quick Action Button -->
    <div class="shrink-0 p-5 pb-2">
        <a href="{{ route('superadmin.vendors.create') }}"
           class="flex items-center justify-center gap-2 w-full bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-500 hover:to-indigo-500 active:scale-[0.98] text-white text-xs font-bold py-2.5 px-4 rounded-xl transition-all duration-150 shadow-md shadow-blue-500/20 border border-blue-400/20">
            <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
            </svg>
            Daftarkan Vendor Baru
        </a>
    </div>

    <!-- Navigation -->
    <nav class="flex-1 px-3 py-4 space-y-1 overflow-y-auto">
        <p class="px-3 text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2 mt-2">Utama</p>
        
        <!-- Dashboard Global -->
        <a href="{{ route('superadmin.dashboard') }}" 
           class="group relative flex items-center px-3 py-2.5 rounded-xl transition-all duration-150
                  {{ request()->routeIs('superadmin.dashboard') ? 'bg-blue-50 text-blue-700 font-bold' : 'text-slate-600 hover:bg-slate-50 hover:text-blue-700' }}">
            @if(request()->routeIs('superadmin.dashboard'))
                <span class="absolute left-0 top-1/2 -translate-y-1/2 w-0.5 h-5 bg-blue-500 rounded-r-full shadow-md shadow-blue-500"></span>
            @endif
            <svg class="w-4 h-4 mr-3 {{ request()->routeIs('superadmin.dashboard') ? 'text-blue-600' : 'text-slate-400 group-hover:text-blue-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
            </svg>
            <span class="text-xs font-semibold">Dashboard Global</span>
        </a>

        <p class="px-3 text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2 mt-6">Manajemen Bisnis</p>

        <!-- Daftar Vendor -->
        <a href="{{ route('superadmin.vendors.index') }}" 
           class="group relative flex items-center px-3 py-2.5 rounded-xl transition-all duration-150
                  {{ request()->routeIs('superadmin.vendors.*') ? 'bg-blue-50 text-blue-700 font-bold' : 'text-slate-600 hover:bg-slate-50 hover:text-blue-700' }}">
            @if(request()->routeIs('superadmin.vendors.*'))
                <span class="absolute left-0 top-1/2 -translate-y-1/2 w-0.5 h-5 bg-blue-500 rounded-r-full shadow-md shadow-blue-500"></span>
            @endif
            <svg class="w-4 h-4 mr-3 {{ request()->routeIs('superadmin.vendors.*') ? 'text-blue-600' : 'text-slate-400 group-hover:text-blue-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
            </svg>
            <span class="text-xs font-semibold">Daftar Vendor</span>
        </a>

        <!-- Laporan Global -->
        <a href="{{ route('superadmin.reports') }}" 
           class="group relative flex items-center px-3 py-2.5 rounded-xl transition-all duration-150
                  {{ request()->routeIs('superadmin.reports') ? 'bg-blue-50 text-blue-700 font-bold' : 'text-slate-600 hover:bg-slate-50 hover:text-blue-700' }}">
            @if(request()->routeIs('superadmin.reports'))
                <span class="absolute left-0 top-1/2 -translate-y-1/2 w-0.5 h-5 bg-blue-500 rounded-r-full shadow-md shadow-blue-500"></span>
            @endif
            <svg class="w-4 h-4 mr-3 {{ request()->routeIs('superadmin.reports') ? 'text-blue-600' : 'text-slate-400 group-hover:text-blue-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
            </svg>
            <span class="text-xs font-semibold">Laporan Global</span>
        </a>

        <p class="px-3 text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2 mt-6">Pengaturan</p>

        <!-- Pengaturan Sistem -->
        <a href="{{ route('superadmin.settings') }}" 
           class="group relative flex items-center px-3 py-2.5 rounded-xl transition-all duration-150
                  {{ request()->routeIs('superadmin.settings') ? 'bg-blue-50 text-blue-700 font-bold' : 'text-slate-600 hover:bg-slate-50 hover:text-blue-700' }}">
            @if(request()->routeIs('superadmin.settings'))
                <span class="absolute left-0 top-1/2 -translate-y-1/2 w-0.5 h-5 bg-blue-500 rounded-r-full shadow-md shadow-blue-500"></span>
            @endif
            <svg class="w-4 h-4 mr-3 {{ request()->routeIs('superadmin.settings') ? 'text-blue-600' : 'text-slate-400 group-hover:text-blue-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
            </svg>
            <span class="text-xs font-semibold">Pengaturan Sistem</span>
        </a>
    </nav>

    <!-- Footer -->
    <div class="p-4 border-t border-slate-100 bg-slate-50">
        <p class="text-xs text-slate-500 font-medium">© 2026 POS BarengSir</p>
        <p class="text-[10px] text-slate-400 mt-0.5 uppercase tracking-wider font-bold">Control Center v1.0</p>
    </div>
</aside>
