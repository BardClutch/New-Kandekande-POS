<!-- Desktop Collapsible Sidebar -->
<aside :class="sidebarCollapsed ? 'w-20' : 'w-64'" 
       @mouseenter="handleMouseEnter()"
       @mouseleave="handleMouseLeave()"
       class="hidden lg:flex flex-col justify-between bg-white dark:bg-gray-800 border-r border-gray-200 dark:border-gray-700 transition-all duration-300 ease-in-out z-30 select-none flex-shrink-0 h-screen shadow-sm">
    
    <div class="flex flex-col h-full overflow-hidden">
        <!-- Sidebar Header / Logo -->
        <div class="h-16 flex items-center border-b border-gray-100 dark:border-gray-700/80 px-4 transition-all duration-300"
             :class="sidebarCollapsed ? 'justify-center' : 'justify-between'">
            
            <a href="{{ route('dashboard') }}" class="flex items-center gap-2 overflow-hidden focus:outline-none">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-indigo-700 to-indigo-500 flex items-center justify-center text-white font-extrabold text-xl shadow-md flex-shrink-0">
                    K
                </div>
                <div x-show="!sidebarCollapsed" x-transition.opacity.duration.200ms class="flex flex-col overflow-hidden">
                    <span class="text-xl font-black text-indigo-600 dark:text-indigo-400 tracking-tight leading-none truncate">Kandekande</span>
                    <span class="text-[10px] uppercase font-bold text-gray-400 tracking-wider mt-0.5">Point of Sale</span>
                </div>
            </a>

            <!-- Pin / Unpin Button inside Sidebar -->
            <button @click="togglePin()" 
                    x-show="!sidebarCollapsed"
                    x-transition.opacity
                    class="p-1.5 rounded-lg text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors focus:outline-none"
                    :title="pinned ? 'Lepas Sematan (Ciutkan otomatis)' : 'Sematkan Sidebar (Tetap Terbuka)'">
                <svg class="w-5 h-5 transition-transform duration-300" :class="pinned ? '' : 'rotate-180'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 19l-7-7 7-7m8 14l-7-7 7-7"></path>
                </svg>
            </button>
        </div>

        <!-- Navigation Links Menu -->
        <nav class="flex-1 overflow-y-auto custom-scrollbar py-4 px-3 space-y-1.5">
            
            <!-- Dashboard -->
            <a href="{{ route('dashboard') }}" 
               class="flex items-center rounded-xl transition-all duration-200 {{ request()->routeIs('dashboard') ? 'bg-indigo-50 dark:bg-indigo-900/40 text-indigo-600 dark:text-indigo-400 font-bold shadow-sm' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700/60 hover:text-gray-900 dark:hover:text-gray-200 font-medium' }}"
               :class="sidebarCollapsed ? 'justify-center p-3' : 'px-3.5 py-2.5 gap-3'"
               title="Dashboard">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                </svg>
                <span x-show="!sidebarCollapsed" x-transition.opacity.duration.200ms class="truncate text-sm">Dashboard</span>
            </a>

            @if(Auth::user()->hasPermission('kasir'))
            <!-- Kasir (POS) -->
            <a href="{{ route('kasir.index') }}" 
               class="flex items-center rounded-xl transition-all duration-200 {{ request()->routeIs('kasir.*') ? 'bg-indigo-50 dark:bg-indigo-900/40 text-indigo-600 dark:text-indigo-400 font-bold shadow-sm' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700/60 hover:text-gray-900 dark:hover:text-gray-200 font-medium' }}"
               :class="sidebarCollapsed ? 'justify-center p-3' : 'px-3.5 py-2.5 gap-3'"
               title="Kasir (POS)">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
                <span x-show="!sidebarCollapsed" x-transition.opacity.duration.200ms class="truncate text-sm">Kasir (POS)</span>
            </a>

            <!-- Shift & Rekap Kasir -->
            <a href="{{ route('shift.index') }}" 
               class="flex items-center rounded-xl transition-all duration-200 {{ request()->routeIs('shift.*') ? 'bg-indigo-50 dark:bg-indigo-900/40 text-indigo-600 dark:text-indigo-400 font-bold shadow-sm' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700/60 hover:text-gray-900 dark:hover:text-gray-200 font-medium' }}"
               :class="sidebarCollapsed ? 'justify-center p-3' : 'px-3.5 py-2.5 gap-3'"
               title="Shift & Rekap Kasir">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <span x-show="!sidebarCollapsed" x-transition.opacity.duration.200ms class="truncate text-sm">Shift & Rekap Kas</span>
            </a>
            @endif

            @if(Auth::user()->hasPermission('dapur'))
            <!-- Dapur (Produksi) -->
            <a href="{{ route('dapur.index') }}" 
               class="flex items-center rounded-xl transition-all duration-200 {{ request()->routeIs('dapur.*') ? 'bg-indigo-50 dark:bg-indigo-900/40 text-indigo-600 dark:text-indigo-400 font-bold shadow-sm' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700/60 hover:text-gray-900 dark:hover:text-gray-200 font-medium' }}"
               :class="sidebarCollapsed ? 'justify-center p-3' : 'px-3.5 py-2.5 gap-3'"
               title="Dapur (Produksi)">
                <svg class="w-5 h-5 flex-shrink-0 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 18.657A8 8 0 016.343 7.343S7 9 9 10c0-2 .5-5 2.986-7C14 5 16.09 5.777 17.656 7.343A7.975 7.975 0 0120 13a7.975 7.975 0 01-2.343 5.657z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.879 16.121A3 3 0 1012.015 11L11 14H9c0 .768.293 1.536.879 2.121z"></path>
                </svg>
                <span x-show="!sidebarCollapsed" x-transition.opacity.duration.200ms class="truncate text-sm">Dapur</span>
            </a>
            @endif

            @if(Auth::user()->hasPermission('produk') || Auth::user()->hasPermission('resep') || Auth::user()->hasPermission('diskon'))
            <!-- Divider Section: Master Katalog & Produk -->
            <div x-show="!sidebarCollapsed" class="pt-3 pb-1 px-3 text-[11px] font-bold uppercase tracking-wider text-gray-400 dark:text-gray-500">
                Katalog & Menu
            </div>

            @if(Auth::user()->hasPermission('produk'))
            <!-- Katalog Produk -->
            <a href="{{ route('produk.index') }}" 
               class="flex items-center rounded-xl transition-all duration-200 {{ request()->routeIs('produk.*') ? 'bg-indigo-50 dark:bg-indigo-900/40 text-indigo-600 dark:text-indigo-400 font-bold shadow-sm' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700/60 hover:text-gray-900 dark:hover:text-gray-200 font-medium' }}"
               :class="sidebarCollapsed ? 'justify-center p-3' : 'px-3.5 py-2.5 gap-3'"
               title="Katalog Produk">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                </svg>
                <span x-show="!sidebarCollapsed" x-transition.opacity.duration.200ms class="truncate text-sm">Katalog Produk</span>
            </a>

            <!-- Kategori -->
            <a href="{{ route('kategori.index') }}" 
               class="flex items-center rounded-xl transition-all duration-200 {{ request()->routeIs('kategori.*') ? 'bg-indigo-50 dark:bg-indigo-900/40 text-indigo-600 dark:text-indigo-400 font-bold shadow-sm' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700/60 hover:text-gray-900 dark:hover:text-gray-200 font-medium' }}"
               :class="sidebarCollapsed ? 'justify-center p-3' : 'px-3.5 py-2.5 gap-3'"
               title="Kategori">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                </svg>
                <span x-show="!sidebarCollapsed" x-transition.opacity.duration.200ms class="truncate text-sm">Kategori</span>
            </a>
            @endif

            @if(Auth::user()->hasPermission('resep'))
            <!-- Resep -->
            <a href="{{ route('resep.index') }}" 
               class="flex items-center rounded-xl transition-all duration-200 {{ request()->routeIs('resep.*') ? 'bg-indigo-50 dark:bg-indigo-900/40 text-indigo-600 dark:text-indigo-400 font-bold shadow-sm' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700/60 hover:text-gray-900 dark:hover:text-gray-200 font-medium' }}"
               :class="sidebarCollapsed ? 'justify-center p-3' : 'px-3.5 py-2.5 gap-3'"
               title="Resep">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <span x-show="!sidebarCollapsed" x-transition.opacity.duration.200ms class="truncate text-sm">Resep</span>
            </a>
            @endif

            @if(Auth::user()->hasPermission('diskon'))
            <!-- Promo & Diskon -->
            <a href="{{ route('diskon.index') }}" 
               class="flex items-center rounded-xl transition-all duration-200 {{ request()->routeIs('diskon.*') ? 'bg-indigo-50 dark:bg-indigo-900/40 text-indigo-600 dark:text-indigo-400 font-bold shadow-sm' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700/60 hover:text-gray-900 dark:hover:text-gray-200 font-medium' }}"
               :class="sidebarCollapsed ? 'justify-center p-3' : 'px-3.5 py-2.5 gap-3'"
               title="Promo & Diskon">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M17 17h.01M7 17L17 7M5 3h14a2 2 0 012 2v14a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2z"></path>
                </svg>
                <span x-show="!sidebarCollapsed" x-transition.opacity.duration.200ms class="truncate text-sm">Promo</span>
            </a>
            @endif
            @endif

            @if(Auth::user()->hasPermission('bahanbaku'))
            <!-- Divider Section: Logistik & Gudang -->
            <div x-show="!sidebarCollapsed" class="pt-3 pb-1 px-3 text-[11px] font-bold uppercase tracking-wider text-gray-400 dark:text-gray-500">
                Logistik & Gudang
            </div>

            <!-- Bahan Baku -->
            <a href="{{ route('bahanbaku.index') }}" 
               class="flex items-center rounded-xl transition-all duration-200 {{ request()->routeIs('bahanbaku.*') ? 'bg-indigo-50 dark:bg-indigo-900/40 text-indigo-600 dark:text-indigo-400 font-bold shadow-sm' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700/60 hover:text-gray-900 dark:hover:text-gray-200 font-medium' }}"
               :class="sidebarCollapsed ? 'justify-center p-3' : 'px-3.5 py-2.5 gap-3'"
               title="Bahan Baku">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                </svg>
                <span x-show="!sidebarCollapsed" x-transition.opacity.duration.200ms class="truncate text-sm">Bahan Baku</span>
            </a>

            <!-- Riwayat Restock -->
            <a href="{{ route('restock.index') }}" 
               class="flex items-center rounded-xl transition-all duration-200 {{ request()->routeIs('restock.*') ? 'bg-indigo-50 dark:bg-indigo-900/40 text-indigo-600 dark:text-indigo-400 font-bold shadow-sm' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700/60 hover:text-gray-900 dark:hover:text-gray-200 font-medium' }}"
               :class="sidebarCollapsed ? 'justify-center p-3' : 'px-3.5 py-2.5 gap-3'"
               title="Riwayat Restock">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 4H6a2 2 0 00-2 2v12a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-2m-4-1v8m0 0l3-3m-3 3L9 8m-5 5h2.586a1 1 0 01.707.293l2.414 2.414a1 1 0 00.707.293h3.172a1 1 0 00.707-.293l2.414-2.414a1 1 0 01.707-.293H20"></path>
                </svg>
                <span x-show="!sidebarCollapsed" x-transition.opacity.duration.200ms class="truncate text-sm">Riwayat Restock</span>
            </a>
            @endif

            @if(Auth::user()->hasPermission('laporan') || Auth::user()->hasPermission('pelanggan') || Auth::user()->hasPermission('kasir'))
            <!-- Divider Section: Analitik / Riwayat -->
            <div x-show="!sidebarCollapsed" class="pt-3 pb-1 px-3 text-[11px] font-bold uppercase tracking-wider text-gray-400 dark:text-gray-500">
                {{ Auth::user()->isOwner() ? 'Analisis & Rekap' : 'Aktivitas Transaksi' }}
            </div>

            @if(Auth::user()->hasPermission('laporan'))
            <!-- Laporan Penjualan / Riwayat Transaksi -->
            <a href="{{ route('laporan.index') }}" 
               class="flex items-center rounded-xl transition-all duration-200 {{ request()->routeIs('laporan.*') ? 'bg-indigo-50 dark:bg-indigo-900/40 text-indigo-600 dark:text-indigo-400 font-bold shadow-sm' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700/60 hover:text-gray-900 dark:hover:text-gray-200 font-medium' }}"
               :class="sidebarCollapsed ? 'justify-center p-3' : 'px-3.5 py-2.5 gap-3'"
               title="{{ Auth::user()->isOwner() ? 'Laporan Penjualan' : 'Riwayat Transaksi' }}">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                </svg>
                <span x-show="!sidebarCollapsed" x-transition.opacity.duration.200ms class="truncate text-sm">
                    {{ Auth::user()->isOwner() ? 'Laporan' : 'Riwayat Transaksi' }}
                </span>
            </a>
            @endif

            @if(Auth::user()->hasPermission('pelanggan') || Auth::user()->hasPermission('kasir'))
            <!-- Daya Pelanggan (CRM) -->
            <a href="{{ route('pelanggan.index') }}" 
               class="flex items-center rounded-xl transition-all duration-200 {{ request()->routeIs('pelanggan.*') ? 'bg-indigo-50 dark:bg-indigo-900/40 text-indigo-600 dark:text-indigo-400 font-bold shadow-sm' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700/60 hover:text-gray-900 dark:hover:text-gray-200 font-medium' }}"
               :class="sidebarCollapsed ? 'justify-center p-3' : 'px-3.5 py-2.5 gap-3'"
               title="Daya Pelanggan">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
                <span x-show="!sidebarCollapsed" x-transition.opacity.duration.200ms class="truncate text-sm">
                    Daya Pelanggan
                </span>
            </a>
            @endif
            @endif

            @if(Auth::user()->hasPermission('karyawan'))
            <!-- Kelola Karyawan -->
            <a href="{{ route('karyawan.index') }}" 
               class="flex items-center rounded-xl transition-all duration-200 {{ request()->routeIs('karyawan.*') ? 'bg-indigo-50 dark:bg-indigo-900/40 text-indigo-600 dark:text-indigo-400 font-bold shadow-sm' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700/60 hover:text-gray-900 dark:hover:text-gray-200 font-medium' }}"
               :class="sidebarCollapsed ? 'justify-center p-3' : 'px-3.5 py-2.5 gap-3'"
               title="Kelola Karyawan">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                </svg>
                <span x-show="!sidebarCollapsed" x-transition.opacity.duration.200ms class="truncate text-sm">
                    Kelola Karyawan
                </span>
            </a>
            @endif

        </nav>

        <!-- Sidebar Footer / Dark Mode Toggle & User Profile & Logout -->
        <div class="border-t border-gray-100 dark:border-gray-700/80 p-3 bg-gray-50/50 dark:bg-gray-800/80 transition-all duration-300 space-y-2">
            <!-- Dark / Light Mode Toggle inside Sidebar -->
            <button @click="toggleTheme()" 
                    type="button"
                    class="w-full flex items-center rounded-xl p-2 text-gray-600 dark:text-gray-300 hover:bg-gray-200/60 dark:hover:bg-gray-700/60 transition-all duration-200 focus:outline-none"
                    :class="sidebarCollapsed ? 'justify-center' : 'justify-between px-2.5'"
                    :title="isDark ? 'Ganti ke Mode Terang (Light Mode)' : 'Ganti ke Mode Gelap (Dark Mode)'">
                
                <div class="flex items-center gap-2.5 overflow-hidden">
                    <!-- Sun Icon in dark mode -->
                    <div x-show="isDark" class="w-7 h-7 rounded-lg bg-amber-500/10 text-amber-500 flex items-center justify-center flex-shrink-0">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path>
                        </svg>
                    </div>
                    <!-- Moon Icon in light mode -->
                    <div x-show="!isDark" class="w-7 h-7 rounded-lg bg-indigo-50 dark:bg-indigo-900/30 text-indigo-600 flex items-center justify-center flex-shrink-0">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path>
                        </svg>
                    </div>
                    
                    <span x-show="!sidebarCollapsed" x-transition.opacity.duration.200ms class="text-xs font-semibold truncate">
                        <span x-show="isDark">Mode Gelap</span>
                        <span x-show="!isDark">Mode Terang</span>
                    </span>
                </div>

                <!-- Small Pill indicator when expanded -->
                <div x-show="!sidebarCollapsed" x-transition.opacity.duration.200ms class="w-8 h-4.5 flex items-center rounded-full p-0.5 transition-colors duration-300" :class="isDark ? 'bg-amber-500 justify-end' : 'bg-gray-300 justify-start'">
                    <div class="bg-white w-3.5 h-3.5 rounded-full shadow-md transform transition-transform duration-300"></div>
                </div>
            </button>

            <!-- User Profile & Logout Row -->
            <div class="flex items-center pt-1 border-t border-gray-100 dark:border-gray-700/60" :class="sidebarCollapsed ? 'justify-center' : 'justify-between'">
                <a href="{{ route('profile.edit') }}" class="flex items-center gap-2.5 group overflow-hidden focus:outline-none" :title="Auth::user()->name">
                    <div class="w-9 h-9 rounded-xl bg-indigo-100 dark:bg-indigo-900/60 text-indigo-700 dark:text-indigo-300 font-bold flex items-center justify-center text-sm border border-indigo-200 dark:border-indigo-700 flex-shrink-0 group-hover:ring-2 group-hover:ring-indigo-400 transition-all">
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                    </div>
                    <div x-show="!sidebarCollapsed" x-transition.opacity.duration.200ms class="flex flex-col overflow-hidden">
                        <span class="text-xs font-bold text-gray-800 dark:text-gray-200 truncate group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors">{{ Auth::user()->name }}</span>
                        <span class="text-[10px] text-gray-500 dark:text-gray-400 truncate">{{ Auth::user()->role_label }}</span>
                    </div>
                </a>

                <!-- Logout Button inside Sidebar -->
                <form method="POST" action="{{ route('logout') }}" x-show="!sidebarCollapsed" x-transition.opacity>
                    @csrf
                    <button type="submit" 
                            class="p-2 text-gray-400 hover:text-red-600 dark:hover:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/30 rounded-lg transition-colors"
                            title="Keluar">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                        </svg>
                    </button>
                </form>
            </div>
        </div>

    </div>
</aside>

<!-- Mobile Off-Canvas Drawer Backdrop -->
<div x-show="sidebarOpen" 
     @click="sidebarOpen = false" 
     x-transition:enter="transition-opacity ease-linear duration-300"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="transition-opacity ease-linear duration-300"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0"
     class="fixed inset-0 bg-black/60 z-40 lg:hidden backdrop-blur-sm"
     x-cloak></div>

<!-- Mobile Off-Canvas Drawer -->
<div x-show="sidebarOpen" 
     x-transition:enter="transition ease-in-out duration-300 transform"
     x-transition:enter-start="-translate-x-full"
     x-transition:enter-end="translate-x-0"
     x-transition:leave="transition ease-in-out duration-300 transform"
     x-transition:leave-start="translate-x-0"
     x-transition:leave-end="-translate-x-full"
     class="fixed inset-y-0 left-0 z-50 w-72 bg-white dark:bg-gray-800 border-r border-gray-200 dark:border-gray-700 flex flex-col justify-between shadow-2xl lg:hidden"
     x-cloak>
    
    <!-- Header -->
    <div class="h-16 flex items-center justify-between px-5 border-b border-gray-100 dark:border-gray-700">
        <div class="flex items-center gap-2">
            <div class="w-9 h-9 rounded-xl bg-indigo-600 flex items-center justify-center text-white font-extrabold text-lg shadow">
                K
            </div>
            <span class="text-xl font-black text-indigo-600 dark:text-indigo-400 tracking-tight">Kandekande</span>
        </div>
        <button @click="sidebarOpen = false" class="p-2 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 rounded-lg">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>
    </div>

    <!-- Mobile Nav Links -->
    <nav class="flex-1 overflow-y-auto custom-scrollbar py-4 px-4 space-y-1">
        <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl {{ request()->routeIs('dashboard') ? 'bg-indigo-50 dark:bg-indigo-900/40 text-indigo-600 dark:text-indigo-400 font-bold' : 'text-gray-600 dark:text-gray-400 font-medium' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
            Dashboard
        </a>
        @if(Auth::user()->hasPermission('kasir'))
        <a href="{{ route('kasir.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl {{ request()->routeIs('kasir.*') ? 'bg-indigo-50 dark:bg-indigo-900/40 text-indigo-600 dark:text-indigo-400 font-bold' : 'text-gray-600 dark:text-gray-400 font-medium' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
            Kasir (POS)
        </a>
        <a href="{{ route('shift.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl {{ request()->routeIs('shift.*') ? 'bg-indigo-50 dark:bg-indigo-900/40 text-indigo-600 dark:text-indigo-400 font-bold' : 'text-gray-600 dark:text-gray-400 font-medium' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            Shift & Rekap Kas
        </a>
        @endif

        @if(Auth::user()->hasPermission('dapur'))
        <a href="{{ route('dapur.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl {{ request()->routeIs('dapur.*') ? 'bg-indigo-50 dark:bg-indigo-900/40 text-indigo-600 dark:text-indigo-400 font-bold' : 'text-gray-600 dark:text-gray-400 font-medium' }}">
            <svg class="w-5 h-5 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 18.657A8 8 0 016.343 7.343S7 9 9 10c0-2 .5-5 2.986-7C14 5 16.09 5.777 17.656 7.343A7.975 7.975 0 0120 13a7.975 7.975 0 01-2.343 5.657z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.879 16.121A3 3 0 1012.015 11L11 14H9c0 .768.293 1.536.879 2.121z"></path></svg>
            Dapur
        </a>
        @endif

        @if(Auth::user()->hasAnyPermission(['produk', 'resep', 'diskon']))
        <div class="pt-3 pb-1 px-4 text-[11px] font-bold uppercase tracking-wider text-gray-400">Katalog & Menu</div>
        @if(Auth::user()->hasPermission('produk'))
        <a href="{{ route('produk.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl {{ request()->routeIs('produk.*') ? 'bg-indigo-50 dark:bg-indigo-900/40 text-indigo-600 dark:text-indigo-400 font-bold' : 'text-gray-600 dark:text-gray-400 font-medium' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
            Katalog Produk
        </a>
        <a href="{{ route('kategori.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl {{ request()->routeIs('kategori.*') ? 'bg-indigo-50 dark:bg-indigo-900/40 text-indigo-600 dark:text-indigo-400 font-bold' : 'text-gray-600 dark:text-gray-400 font-medium' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path></svg>
            Kategori
        </a>
        @endif
        @if(Auth::user()->hasPermission('resep'))
        <a href="{{ route('resep.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl {{ request()->routeIs('resep.*') ? 'bg-indigo-50 dark:bg-indigo-900/40 text-indigo-600 dark:text-indigo-400 font-bold' : 'text-gray-600 dark:text-gray-400 font-medium' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
            Resep
        </a>
        @endif
        @if(Auth::user()->hasPermission('diskon'))
        <a href="{{ route('diskon.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl {{ request()->routeIs('diskon.*') ? 'bg-indigo-50 dark:bg-indigo-900/40 text-indigo-600 dark:text-indigo-400 font-bold' : 'text-gray-600 dark:text-gray-400 font-medium' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M17 17h.01M7 17L17 7M5 3h14a2 2 0 012 2v14a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2z"></path></svg>
            Promo & Diskon
        </a>
        @endif
        @endif

        @if(Auth::user()->hasPermission('bahanbaku'))
        <div class="pt-3 pb-1 px-4 text-[11px] font-bold uppercase tracking-wider text-gray-400">Logistik & Gudang</div>
        <a href="{{ route('bahanbaku.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl {{ request()->routeIs('bahanbaku.*') ? 'bg-indigo-50 dark:bg-indigo-900/40 text-indigo-600 dark:text-indigo-400 font-bold' : 'text-gray-600 dark:text-gray-400 font-medium' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
            Bahan Baku
        </a>
        <a href="{{ route('restock.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl {{ request()->routeIs('restock.*') ? 'bg-indigo-50 dark:bg-indigo-900/40 text-indigo-600 dark:text-indigo-400 font-bold' : 'text-gray-600 dark:text-gray-400 font-medium' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 4H6a2 2 0 00-2 2v12a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-2m-4-1v8m0 0l3-3m-3 3L9 8m-5 5h2.586a1 1 0 01.707.293l2.414 2.414a1 1 0 00.707.293h3.172a1 1 0 00.707-.293l2.414-2.414a1 1 0 01.707-.293H20"></path></svg>
            Riwayat Restock
        </a>
        @endif

        @if(Auth::user()->hasAnyPermission(['laporan', 'pelanggan']))
        <div class="pt-3 pb-1 px-4 text-[11px] font-bold uppercase tracking-wider text-gray-400">
            {{ Auth::user()->isOwner() ? 'Analisis & Rekap' : 'Aktivitas Transaksi' }}
        </div>
        @if(Auth::user()->hasPermission('laporan'))
        <a href="{{ route('laporan.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl {{ request()->routeIs('laporan.*') ? 'bg-indigo-50 dark:bg-indigo-900/40 text-indigo-600 dark:text-indigo-400 font-bold' : 'text-gray-600 dark:text-gray-400 font-medium' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
            {{ Auth::user()->isOwner() ? 'Laporan' : 'Riwayat Transaksi' }}
        </a>
        @endif
        @if(Auth::user()->hasPermission('pelanggan'))
        <a href="{{ route('pelanggan.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl {{ request()->routeIs('pelanggan.*') ? 'bg-indigo-50 dark:bg-indigo-900/40 text-indigo-600 dark:text-indigo-400 font-bold' : 'text-gray-600 dark:text-gray-400 font-medium' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
            Daya Pelanggan
        </a>
        @endif
        @endif

        @if(Auth::user()->hasPermission('karyawan'))
        <a href="{{ route('karyawan.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl {{ request()->routeIs('karyawan.*') ? 'bg-indigo-50 dark:bg-indigo-900/40 text-indigo-600 dark:text-indigo-400 font-bold' : 'text-gray-600 dark:text-gray-400 font-medium' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
            Kelola Karyawan
        </a>
        @endif
    </nav>

    <!-- Mobile Footer -->
    <div class="p-4 border-t border-gray-100 dark:border-gray-700 bg-gray-50 dark:bg-gray-800/90 space-y-3">
        <!-- Theme Toggle in Mobile Drawer -->
        <button @click="toggleTheme()" 
                type="button" 
                class="w-full flex items-center justify-between p-2.5 rounded-xl bg-white dark:bg-gray-700/80 border border-gray-200 dark:border-gray-600 text-gray-700 dark:text-gray-200 shadow-sm transition-colors focus:outline-none">
            <div class="flex items-center gap-2.5">
                <svg x-show="isDark" class="w-4 h-4 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                <svg x-show="!isDark" class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path></svg>
                <span class="text-xs font-bold" x-text="isDark ? 'Tema: Mode Gelap' : 'Tema: Mode Terang'"></span>
            </div>
            <div class="w-8 h-4.5 flex items-center rounded-full p-0.5 transition-colors" :class="isDark ? 'bg-amber-500 justify-end' : 'bg-gray-300 justify-start'">
                <div class="bg-white w-3.5 h-3.5 rounded-full shadow"></div>
            </div>
        </button>

        <div class="flex items-center justify-between pt-1">
            <div class="flex items-center gap-2">
                <div class="w-8 h-8 rounded-lg bg-indigo-600 text-white font-bold flex items-center justify-center text-xs">
                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                </div>
                <div>
                    <div class="text-sm font-bold text-gray-800 dark:text-gray-200">{{ Auth::user()->name }}</div>
                    <div class="text-xs text-indigo-600 dark:text-indigo-400 font-medium">{{ Auth::user()->role_label }}</div>
                </div>
            </div>
            <a href="{{ route('profile.edit') }}" class="text-xs font-semibold text-gray-500 dark:text-gray-400 hover:text-indigo-600 dark:hover:text-indigo-400">Profil</a>
        </div>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="w-full bg-red-500 hover:bg-red-600 text-white font-bold py-2 px-3 rounded-lg text-xs transition-colors shadow-sm">
                Keluar (Log Out)
            </button>
        </form>
    </div>
</div>
