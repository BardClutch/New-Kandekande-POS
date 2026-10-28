@php
    $nu = Auth::user();
    $ico = [
        'home'     => 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6',
        'cart'     => 'M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z',
        'cash'     => 'M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z',
        'flame'    => 'M17.657 18.657A8 8 0 016.343 7.343S7 9 9 10c0-2 .5-5 2.986-7C14 5 16.09 5.777 17.656 7.343A7.975 7.975 0 0120 13a7.975 7.975 0 01-2.343 5.657z M9.879 16.121A3 3 0 1012.015 11L11 14H9c0 .768.293 1.536.879 2.121z',
        'box'      => 'M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4',
        'tag'      => 'M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z',
        'doc'      => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z',
        'percent'  => 'M7 7h.01M17 17h.01M7 17L17 7M5 3h14a2 2 0 012 2v14a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2z',
        'archive'  => 'M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10',
        'inbox'    => 'M8 4H6a2 2 0 00-2 2v12a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-2m-4-1v8m0 0l3-3m-3 3L9 8m-5 5h2.586a1 1 0 01.707.293l2.414 2.414a1 1 0 00.707.293h3.172a1 1 0 00.707-.293l2.414-2.414a1 1 0 01.707-.293H20',
        'chart'    => 'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z',
        'users'    => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z',
        'team'     => 'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z',
    ];

    // [grup, label, route, pola aktif, ikon, boleh?]
    $navAll = [
        ['', 'Dashboard', 'dashboard', 'dashboard', 'home', true],
        ['', 'Kasir', 'kasir.index', 'kasir.*', 'cart', $nu->hasPermission('kasir')],
        ['', 'Shift dan kas', 'shift.index', 'shift.*', 'cash', $nu->hasPermission('kasir')],
        ['', 'Dapur', 'dapur.index', 'dapur.*', 'flame', $nu->hasPermission('dapur')],
        ['Katalog', 'Produk', 'produk.index', 'produk.*', 'box', $nu->hasPermission('produk')],
        ['Katalog', 'Kategori', 'kategori.index', 'kategori.*', 'tag', $nu->hasPermission('produk')],
        ['Katalog', 'Resep', 'resep.index', 'resep.*', 'doc', $nu->hasPermission('resep')],
        ['Katalog', 'Promo', 'diskon.index', 'diskon.*', 'percent', $nu->hasPermission('diskon')],
        ['Gudang', 'Bahan baku', 'bahanbaku.index', 'bahanbaku.*', 'archive', $nu->hasPermission('bahanbaku')],
        ['Gudang', 'Restock', 'restock.index', 'restock.*', 'inbox', $nu->hasPermission('bahanbaku')],
        [$nu->isOwner() ? 'Analisis' : 'Transaksi', $nu->isOwner() ? 'Laporan' : 'Riwayat transaksi', 'laporan.index', 'laporan.*', 'chart', $nu->hasPermission('laporan')],
        [$nu->isOwner() ? 'Analisis' : 'Transaksi', 'Pelanggan', 'pelanggan.index', 'pelanggan.*', 'users', $nu->hasPermission('pelanggan') || $nu->hasPermission('kasir')],
        ['Tim', 'Karyawan', 'karyawan.index', 'karyawan.*', 'team', $nu->hasPermission('karyawan')],
    ];
    $navItems = array_values(array_filter($navAll, fn ($i) => $i[5]));
    $navGroups = [];
    foreach ($navItems as $i) { $navGroups[$i[0]][] = $i; }
@endphp

<!-- Sidebar desktop -->
<aside :class="collapsed ? 'w-[68px]' : 'w-60'"
       class="hidden lg:flex flex-col bg-gray-50 dark:bg-gray-900 border-r border-gray-200 dark:border-gray-800 transition-[width] duration-200 ease-out z-30 select-none flex-shrink-0 h-screen overflow-hidden">

    <!-- Merek -->
    <div class="h-14 flex items-center border-b border-gray-200 dark:border-gray-800 flex-shrink-0 px-3.5">
        <a href="{{ route('dashboard') }}" class="flex items-center gap-2.5 min-w-0">
            <span class="w-9 h-9 rounded-lg bg-indigo-600 text-white flex items-center justify-center flex-shrink-0">
                <svg class="w-5 h-5" viewBox="0 0 34 34" fill="none"><path d="M6.5 20.5C6.5 14.5 11 11 17 11s10.5 3.5 10.5 9.5c0 1.4-4.7 2.5-10.5 2.5S6.5 21.9 6.5 20.5Z" fill="#FFE3A0"/><path d="M12 17.8l2.2-2.6M16.6 17.8l2.2-2.6M21.2 17.8l2-2.4" stroke="#D4501E" stroke-width="1.9" stroke-linecap="round"/></svg>
            </span>
            <span x-show="!collapsed" x-cloak class="font-display text-lg font-extrabold tracking-tight text-gray-900 dark:text-gray-100 truncate">Kandekande</span>
        </a>
    </div>

    <!-- Menu -->
    <nav class="flex-1 overflow-y-auto custom-scrollbar py-3 px-2.5">
        @foreach($navGroups as $gTitle => $rows)
            @if($gTitle !== '')
                <p x-show="!collapsed" x-cloak class="mt-5 mb-1.5 px-2.5 text-xs font-semibold text-gray-400 dark:text-gray-500">{{ $gTitle }}</p>
                <div x-show="collapsed" class="mt-3 mb-2 mx-3 border-t border-gray-200 dark:border-gray-800"></div>
            @endif
            <ul class="space-y-0.5">
                @foreach($rows as $r)
                    @php $on = request()->routeIs($r[3]); @endphp
                    <li>
                        <a href="{{ route($r[2]) }}" title="{{ $r[1] }}"
                           class="relative flex items-center gap-3 rounded-lg h-10 text-sm transition-colors {{ $on ? 'bg-gray-200/70 dark:bg-gray-800 text-gray-900 dark:text-gray-50 font-semibold' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-200/50 dark:hover:bg-gray-800/60 hover:text-gray-900 dark:hover:text-gray-100 font-medium' }}"
                           :class="collapsed ? 'justify-center' : 'px-2.5'">
                            @if($on)<span class="absolute left-0 top-2 bottom-2 w-[3px] rounded-r bg-indigo-600 dark:bg-indigo-400"></span>@endif
                            <svg class="w-[18px] h-[18px] flex-shrink-0 {{ $on ? 'text-indigo-600 dark:text-indigo-400' : '' }}" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                @foreach(explode(' M', ltrim($ico[$r[4]], 'M')) as $seg)<path stroke-linecap="round" stroke-linejoin="round" d="M{{ $seg }}"/>@endforeach
                            </svg>
                            <span x-show="!collapsed" x-cloak class="truncate">{{ $r[1] }}</span>
                        </a>
                    </li>
                @endforeach
            </ul>
        @endforeach
    </nav>

    <!-- Pengguna -->
    <div class="border-t border-gray-200 dark:border-gray-800 p-2.5 flex-shrink-0">
        <div class="flex items-center gap-2.5" :class="collapsed ? 'flex-col' : ''">
            <a href="{{ route('profile.edit') }}" class="flex items-center gap-2.5 min-w-0 flex-1 rounded-lg p-1.5 hover:bg-gray-200/50 dark:hover:bg-gray-800/60 transition-colors" title="{{ $nu->name }}">
                <span class="w-8 h-8 rounded-full bg-gray-900 dark:bg-gray-100 text-gray-50 dark:text-gray-900 text-sm font-bold flex items-center justify-center flex-shrink-0">{{ strtoupper(substr($nu->name, 0, 1)) }}</span>
                <span x-show="!collapsed" x-cloak class="min-w-0 leading-tight">
                    <span class="block text-sm font-semibold text-gray-900 dark:text-gray-100 truncate">{{ $nu->name }}</span>
                    <span class="block text-xs text-gray-500 dark:text-gray-400 truncate">{{ $nu->role_label }}</span>
                </span>
            </a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" title="Keluar" class="p-2 rounded-lg text-gray-400 hover:text-red-600 dark:hover:text-red-400 hover:bg-gray-200/50 dark:hover:bg-gray-800/60 transition-colors">
                    <svg class="w-[18px] h-[18px]" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                </button>
            </form>
        </div>
    </div>
</aside>

<!-- Drawer mobile -->
<div x-show="sidebarOpen" @click="sidebarOpen = false"
     x-transition:enter="transition-opacity ease-linear duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
     x-transition:leave="transition-opacity ease-linear duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
     class="fixed inset-0 bg-black/50 z-40 lg:hidden" x-cloak></div>

<div x-show="sidebarOpen"
     x-transition:enter="transition ease-out duration-200 transform" x-transition:enter-start="-translate-x-full" x-transition:enter-end="translate-x-0"
     x-transition:leave="transition ease-in duration-150 transform" x-transition:leave-start="translate-x-0" x-transition:leave-end="-translate-x-full"
     class="fixed inset-y-0 left-0 z-50 w-72 bg-gray-50 dark:bg-gray-900 border-r border-gray-200 dark:border-gray-800 flex flex-col lg:hidden" x-cloak>
    <div class="h-14 flex items-center justify-between px-4 border-b border-gray-200 dark:border-gray-800 flex-shrink-0">
        <span class="font-display text-lg font-extrabold tracking-tight text-gray-900 dark:text-gray-100">Kandekande</span>
        <button @click="sidebarOpen = false" class="p-2 -mr-2 text-gray-500 hover:text-gray-900 dark:hover:text-gray-100" aria-label="Tutup menu">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>
    </div>
    <nav class="flex-1 overflow-y-auto custom-scrollbar py-3 px-3">
        @foreach($navGroups as $gTitle => $rows)
            @if($gTitle !== '')<p class="mt-5 mb-1.5 px-2.5 text-xs font-semibold text-gray-400">{{ $gTitle }}</p>@endif
            <ul class="space-y-0.5">
                @foreach($rows as $r)
                    @php $on = request()->routeIs($r[3]); @endphp
                    <li>
                        <a href="{{ route($r[2]) }}" class="flex items-center gap-3 rounded-lg px-2.5 h-11 text-sm {{ $on ? 'bg-gray-200/70 dark:bg-gray-800 text-gray-900 dark:text-gray-50 font-semibold' : 'text-gray-600 dark:text-gray-400 font-medium' }}">
                            <svg class="w-[18px] h-[18px] {{ $on ? 'text-indigo-600 dark:text-indigo-400' : '' }}" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                @foreach(explode(' M', ltrim($ico[$r[4]], 'M')) as $seg)<path stroke-linecap="round" stroke-linejoin="round" d="M{{ $seg }}"/>@endforeach
                            </svg>
                            {{ $r[1] }}
                        </a>
                    </li>
                @endforeach
            </ul>
        @endforeach
    </nav>
    <div class="border-t border-gray-200 dark:border-gray-800 p-3 flex items-center justify-between gap-3 flex-shrink-0">
        <a href="{{ route('profile.edit') }}" class="flex items-center gap-2.5 min-w-0">
            <span class="w-9 h-9 rounded-full bg-gray-900 dark:bg-gray-100 text-gray-50 dark:text-gray-900 text-sm font-bold flex items-center justify-center flex-shrink-0">{{ strtoupper(substr($nu->name, 0, 1)) }}</span>
            <span class="min-w-0 leading-tight"><span class="block text-sm font-semibold text-gray-900 dark:text-gray-100 truncate">{{ $nu->name }}</span><span class="block text-xs text-gray-500 truncate">{{ $nu->role_label }}</span></span>
        </a>
        <form method="POST" action="{{ route('logout') }}">@csrf<button type="submit" class="text-sm font-semibold text-red-600 dark:text-red-400">Keluar</button></form>
    </div>
</div>
