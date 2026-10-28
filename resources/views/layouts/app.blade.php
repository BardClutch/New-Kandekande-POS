<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=bricolage-grotesque:600,700,800|albert-sans:400,500,600,700,800&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        <!-- Tailwind CDN (Fallback for development without Node.js) -->
        <script src="https://cdn.tailwindcss.com"></script>
        <script>
            tailwind.config = {
                darkMode: 'class',
                theme: {
                    extend: {
                        fontFamily: {
                            sans: ['Albert Sans', 'ui-sans-serif', 'system-ui', 'sans-serif'],
                            display: ['Bricolage Grotesque', 'ui-sans-serif', 'system-ui', 'sans-serif'],
                        },
                        borderRadius: { xl: '0.5rem', '2xl': '0.625rem', '3xl': '0.75rem' },
                        boxShadow: {
                            sm: '0 1px 0 rgba(27,22,18,.04)',
                            DEFAULT: '0 1px 2px rgba(27,22,18,.06)',
                            md: '0 2px 8px -2px rgba(27,22,18,.08)',
                            lg: '0 6px 18px -6px rgba(27,22,18,.12)',
                            xl: '0 10px 28px -10px rgba(27,22,18,.16)',
                            '2xl': '0 14px 36px -12px rgba(27,22,18,.2)',
                        },
                        colors: {
                            gray: { 50: '#FAF9F6', 100: '#F3F1EC', 200: '#E6E2DA', 300: '#D3CEC4', 400: '#A8A097', 500: '#7D756C', 600: '#5F5851', 700: '#433D38', 800: '#2A2521', 900: '#1B1612', 950: '#14110F' },
                            indigo: { 50: '#FFF3EC', 100: '#FFE3D2', 200: '#FFC7A6', 300: '#FBA474', 400: '#F08A57', 500: '#E36A33', 600: '#D4501E', 700: '#B03F16', 800: '#8A3314', 900: '#6B2B14', 950: '#3F1809' },
                            purple: { 50: '#FDF1EE', 100: '#FBE0D9', 200: '#F6C2B5', 300: '#EE9C88', 400: '#E27560', 500: '#CC5440', 600: '#B03F2E', 700: '#8F3225', 800: '#72281F', 900: '#5A211A', 950: '#33100C' },
                            blue: { 50: '#FFF3EC', 100: '#FFE3D2', 200: '#FFC7A6', 300: '#FBA474', 400: '#F08A57', 500: '#E36A33', 600: '#D4501E', 700: '#B03F16', 800: '#8A3314', 900: '#6B2B14', 950: '#3F1809' },
                            violet: { 50: '#FDF1EE', 100: '#FBE0D9', 200: '#F6C2B5', 300: '#EE9C88', 400: '#E27560', 500: '#CC5440', 600: '#B03F2E', 700: '#8F3225', 800: '#72281F', 900: '#5A211A', 950: '#33100C' },
                            brand: { 50: '#FFF3EC', 100: '#FFE3D2', 200: '#FFC7A6', 300: '#FBA474', 400: '#F08A57', 500: '#E36A33', 600: '#D4501E', 700: '#B03F16', 800: '#8A3314', 900: '#6B2B14' },
                        },
                    },
                },
            }

            // Inisialisasi tema per-akun pengguna
            const currentUserId = {{ Auth::id() }};
            const dbUserTheme = @json(Auth::user()->theme ?? null);
            const localUserTheme = localStorage.getItem('theme_user_' + currentUserId);

            let activeTheme = null;

            if (localUserTheme) {
                // Gunakan preferensi manual yang pernah disimpan di browser untuk akun ini
                activeTheme = localUserTheme;
            } else if (dbUserTheme) {
                // Gunakan preferensi yang tersimpan di akun database
                activeTheme = dbUserTheme;
                localStorage.setItem('theme_user_' + currentUserId, dbUserTheme);
            } else {
                // TAMPILAN AWAL PERTAMA KALI AKUN LOGIN:
                // Mengikuti tampilan sistem yang sedang digunakan di perangkat (OS / Browser)
                const systemPrefersDark = window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches;
                activeTheme = systemPrefersDark ? 'dark' : 'light';
            }

            if (activeTheme === 'dark') {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }

            // Sinkronisasi otomatis jika tema OS berubah & akun belum pernah memilih manual
            if (!localUserTheme && !dbUserTheme && window.matchMedia) {
                window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', (e) => {
                    if (!localStorage.getItem('theme_user_' + currentUserId)) {
                        if (e.matches) {
                            document.documentElement.classList.add('dark');
                        } else {
                            document.documentElement.classList.remove('dark');
                        }
                        window.dispatchEvent(new CustomEvent('theme-changed', { detail: { isDark: e.matches } }));
                    }
                });
            }
        </script>
        
        <!-- Alpine.js CDN fallback -->
        <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

        <style>
            [x-cloak] { display: none !important; }
            :root { color-scheme: light; }
            .dark { color-scheme: dark; }
            h1, h2, h3 { font-family: 'Bricolage Grotesque', ui-sans-serif, system-ui, sans-serif; letter-spacing: -.02em; }
            .font-black { font-weight: 800; }
            .text-2xl.font-black, .text-3xl.font-black, .text-4xl.font-black { font-family: 'Bricolage Grotesque', ui-sans-serif, system-ui, sans-serif; font-variant-numeric: tabular-nums; }
            /* ratakan gradien jadi warna solid, buang huruf kapital berjarak dan blur kaca */
            [class*="bg-gradient-to"] { background-image: none !important; background-color: var(--tw-gradient-from, transparent); }
            .uppercase { text-transform: none; }
            [class*="tracking-wid"] { letter-spacing: 0; }
            [class*="backdrop-blur"] { -webkit-backdrop-filter: none; backdrop-filter: none; }
            .animate-pulse, .animate-bounce { animation: none; }
            .text-\[10px\] { font-size: 11px; }
            .text-\[11px\] { font-size: 12px; }
            :focus-visible { outline: 2px solid #D4501E; outline-offset: 2px; }
            /* tabel dan formulir bersama */
            .tbl { width: 100%; font-size: .875rem; border-collapse: collapse; }
            .tbl th { text-align: left; font-weight: 500; padding: .65rem 1rem; color: #7D756C; border-bottom: 1px solid #E6E2DA; white-space: nowrap; }
            .tbl td { padding: .8rem 1rem; border-bottom: 1px solid #E6E2DA; vertical-align: middle; }
            .tbl tbody tr:last-child td { border-bottom: 0; }
            .tbl tbody tr:hover td { background: #FAF9F6; }
            .tbl th:first-child, .tbl td:first-child { padding-left: 1.5rem; }
            .tbl th:last-child, .tbl td:last-child { padding-right: 1.5rem; }
            .tbl .r { text-align: right; } .tbl .c { text-align: center; } .tbl .num { font-variant-numeric: tabular-nums; }
            /* kolom pertama menyerap sisa lebar, kolom lain selebar isinya supaya rapat dan sejajar */
            .tbl th:not(:first-child), .tbl td:not(:first-child) { width: 1%; white-space: nowrap; }
            .tbl td:first-child, .tbl th:first-child { min-width: 12rem; }
            .tbl td.wrap, .tbl th.wrap { width: auto; white-space: normal; }
            .tbl th, .tbl td { padding-left: 1rem; padding-right: 1rem; }
            .tbl th:first-child, .tbl td:first-child { padding-left: 1.5rem; }
            .tbl th:last-child, .tbl td:last-child { padding-right: 1.5rem; }
            @media (max-width: 1279px) {
                .tbl { font-size: .8125rem; }
                .tbl th, .tbl td { padding-left: .625rem; padding-right: .625rem; }
                .tbl th:first-child, .tbl td:first-child { padding-left: 1rem; min-width: 9rem; }
                .tbl th:last-child, .tbl td:last-child { padding-right: 1rem; }
            }
            .dark .tbl th { color: #A39A91; border-color: #3A332D; }
            .dark .tbl td { border-color: #3A332D; }
            .dark .tbl tbody tr:hover td { background: rgba(255,255,255,.03); }
            .fld > label, .fld > .lbl { display: block; font-size: .875rem; font-weight: 600; margin-bottom: .375rem; color: #433D38; }
            .dark .fld > label, .dark .fld > .lbl { color: #E6E2DA; }
            .fld .hint { margin-top: .35rem; font-size: .8125rem; color: #7D756C; }
            .inp { display: block; width: 100%; height: 2.625rem; font: inherit; line-height: 1.375rem; font-size: .9375rem; color: #1B1612; background: #fff; border: 1.5px solid #D3CEC4; border-radius: .5rem; padding: 0 .85rem; outline: none; transition: border-color .15s, box-shadow .15s; }
            textarea.inp { height: auto; min-height: 2.625rem; padding: .625rem .85rem; }
            .inp:hover { border-color: #A8A097; }
            .inp:focus { border-color: #D4501E; box-shadow: 0 0 0 3px rgba(212,80,30,.18); }
            .dark .inp { color: #F3EEE7; background: #1B1612; border-color: #4A423B; }
            .dark .inp:hover { border-color: #7D756C; }
            .dark .inp:focus { border-color: #F08A57; box-shadow: 0 0 0 3px rgba(240,138,87,.2); }
            .inp::placeholder { color: #A8A097; }
            .mode-switch { background: none; border: 0; padding: 2px; cursor: pointer; border-radius: 999px; }
            .mode-track { position: relative; display: block; width: 52px; height: 28px; border-radius: 999px; overflow: hidden; background: linear-gradient(#A9DAF2, #D7EEFA); box-shadow: inset 0 0 0 1.5px rgba(0,0,0,.12); transition: background .6s; }
            .mode-track::before { content: ''; position: absolute; left: 26px; top: 14px; width: 17px; height: 7px; border-radius: 7px; background: #fff; box-shadow: -5px -3px 0 -1px #fff; transition: transform .6s, opacity .5s; }
            .mode-track::after { content: ''; position: absolute; inset: 0; opacity: 0; transition: opacity .6s; background: radial-gradient(1.3px 1.3px at 12px 8px, #fff 50%, transparent 52%), radial-gradient(1px 1px at 22px 18px, #fff 50%, transparent 52%), radial-gradient(1px 1px at 8px 19px, #fff 50%, transparent 52%); }
            .mode-thumb { position: absolute; top: 3px; left: 3px; width: 22px; height: 22px; border-radius: 50%; background: #FFD54A; box-shadow: 0 0 0 4px rgba(255,213,74,.35); transition: transform .55s cubic-bezier(.5,-.3,.3,1.3), background .5s, box-shadow .5s; }
            .dark .mode-track { background: linear-gradient(#0F1534, #2A2450); }
            .dark .mode-track::before { transform: translateX(22px); opacity: 0; }
            .dark .mode-track::after { opacity: 1; }
            .dark .mode-thumb { transform: translateX(24px); background: #F3EEE7; box-shadow: 0 0 0 4px rgba(243,238,231,.15); }
            .dark :focus-visible { outline-color: #F08A57; }
            input[type="text"], input[type="email"], input[type="password"], input[type="number"], input[type="search"], input[type="date"], input[type="tel"], select, textarea {
                border-radius: .5rem;
            }
            .custom-scrollbar::-webkit-scrollbar {
                width: 6px;
                height: 6px;
            }
            .custom-scrollbar::-webkit-scrollbar-track {
                background: transparent;
            }
            .custom-scrollbar::-webkit-scrollbar-thumb {
                background-color: rgba(156, 163, 175, 0.4);
                border-radius: 20px;
            }
            @media (prefers-color-scheme: dark) {
                .custom-scrollbar::-webkit-scrollbar-thumb {
                    background-color: rgba(75, 85, 99, 0.4);
                }
            }
        </style>
    </head>
    <body class="font-sans antialiased bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-gray-100 transition-colors duration-300"
          x-data="{ 
              sidebarOpen: false, 
              collapsed: localStorage.getItem('sb_collapsed') === '1',
              isDark: document.documentElement.classList.contains('dark'),
              init() {
                  window.addEventListener('theme-changed', (e) => {
                      this.isDark = e.detail.isDark;
                  });
              },
              toggleTheme() {
                  const willBeDark = !document.documentElement.classList.contains('dark');
                  if (willBeDark) {
                      document.documentElement.classList.add('dark');
                      this.isDark = true;
                  } else {
                      document.documentElement.classList.remove('dark');
                      this.isDark = false;
                  }
                  const newTheme = willBeDark ? 'dark' : 'light';
                  window.dispatchEvent(new CustomEvent('theme-changed', { detail: { isDark: willBeDark } }));
                  const userId = {{ Auth::id() }};
                  localStorage.setItem('theme_user_' + userId, newTheme);
                  localStorage.setItem('theme', newTheme);

                  // Simpan ke database user profile
                  fetch('{{ route('profile.theme') }}', {
                      method: 'POST',
                      headers: {
                          'Content-Type': 'application/json',
                          'X-CSRF-TOKEN': '{{ csrf_token() }}'
                      },
                      body: JSON.stringify({ theme: newTheme })
                  }).catch(() => {});
              },
              toggleSidebar() {
                  this.collapsed = !this.collapsed;
                  localStorage.setItem('sb_collapsed', this.collapsed ? '1' : '0');
              }
          }">
        
        <div class="flex h-screen overflow-hidden">
            <!-- Collapsible Sidebar (Left) -->
            @include('layouts.navigation')

            <!-- Main Content Area (Right) -->
            <div class="flex-1 flex flex-col min-w-0 h-screen overflow-hidden">
                <!-- Topbar Header -->
                <header class="relative z-30 bg-gray-50 dark:bg-gray-900 border-b border-gray-200 dark:border-gray-800 h-14 flex items-center justify-between px-4 sm:px-6 lg:px-8 transition-colors duration-300 flex-shrink-0">
                    <div class="flex items-center gap-1 sm:gap-3 min-w-0">
                        <!-- Mobile Hamburger Button -->
                        <button @click="sidebarOpen = true" class="lg:hidden p-2 rounded-xl text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700 focus:outline-none transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                            </svg>
                        </button>

                        <!-- Desktop Sidebar Pin / Toggle Button -->
                        <button @click="toggleSidebar()" type="button"
                                class="hidden lg:flex p-2 rounded-lg text-gray-500 hover:text-gray-900 dark:text-gray-400 dark:hover:text-gray-100 hover:bg-gray-200/60 dark:hover:bg-gray-800 focus:outline-none transition-colors"
                                :title="collapsed ? 'Lebarkan menu' : 'Ciutkan menu'" aria-label="Ciutkan atau lebarkan menu">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                <rect x="3" y="4" width="18" height="16" rx="2"/><path stroke-linecap="round" d="M9 4v16"/>
                            </svg>
                        </button>

                        <!-- Page Title Header -->
                        <div class="truncate">
                            @isset($header)
                                {{ $header }}
                            @else
                                @php
                                    $secMap = ['dashboard' => 'Dashboard', 'kasir' => 'Kasir', 'shift' => 'Shift dan kas', 'dapur' => 'Dapur', 'produk' => 'Produk', 'kategori' => 'Kategori', 'resep' => 'Resep', 'diskon' => 'Promo', 'bahanbaku' => 'Bahan baku', 'restock' => 'Restock', 'laporan' => 'Laporan', 'pelanggan' => 'Pelanggan', 'karyawan' => 'Karyawan', 'profile' => 'Profil'];
                                    $secKey = \Illuminate\Support\Str::before(Route::currentRouteName() ?? '', '.');
                                @endphp
                                <p class="text-sm text-gray-500 dark:text-gray-400 truncate">
                                    <span class="hidden sm:inline">Kandekande <span class="mx-1 text-gray-300 dark:text-gray-600">/</span></span>
                                    <span class="font-semibold text-gray-900 dark:text-gray-100">{{ $secMap[$secKey] ?? 'Beranda' }}</span>
                                </p>
                            @endisset
                        </div>
                    </div>

                    <!-- Topbar Right Info -->
                    <div class="flex items-center gap-1.5 sm:gap-3 flex-shrink-0">
                        <!-- Dark / Light Mode Toggle Button -->
                        <button @click="toggleTheme()" type="button" class="mode-switch" role="switch" :aria-checked="isDark.toString()"
                                :title="isDark ? 'Ganti ke siang' : 'Ganti ke malam'" aria-label="Mode malam">
                            <span class="mode-track"><span class="mode-thumb"></span></span>
                        </button>

                        <!-- Low Stock Notification Bell Dropdown -->
                        <div x-data="{ notifOpen: false }" class="relative z-50" @click.outside="notifOpen = false">
                            <button @click="notifOpen = !notifOpen" 
                                    type="button"
                                    class="relative p-2.5 rounded-xl text-gray-500 hover:text-indigo-600 dark:text-gray-400 dark:hover:text-indigo-400 hover:bg-gray-100 dark:hover:bg-gray-700/60 focus:outline-none transition-all duration-200 border border-transparent hover:border-gray-200 dark:hover:border-gray-600 shadow-sm"
                                    title="Pemberitahuan & Peringatan Stok">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                                </svg>

                                @if(($globalLowStockCount ?? 0) > 0)
                                <span class="absolute top-1.5 right-1.5 flex h-4 w-4">
                                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                                    <span class="relative inline-flex rounded-full h-4 w-4 bg-red-600 text-[10px] font-black text-white items-center justify-center">
                                        {{ ($globalLowStockCount ?? 0) > 9 ? '9+' : ($globalLowStockCount ?? 0) }}
                                    </span>
                                </span>
                                @endif
                            </button>

                            <!-- Notification Dropdown Panel -->
                            <div x-show="notifOpen" 
                                 x-transition:enter="transition ease-out duration-150"
                                 x-transition:enter-start="opacity-0 scale-95 -translate-y-1"
                                 x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                                 x-transition:leave="transition ease-in duration-100"
                                 x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                                 x-transition:leave-end="opacity-0 scale-95 -translate-y-1"
                                 class="absolute right-0 mt-2 w-80 sm:w-96 bg-white dark:bg-gray-800 rounded-2xl shadow-2xl border border-gray-100 dark:border-gray-700 overflow-hidden z-50 transition-colors"
                                 x-cloak>
                                <div class="p-4 border-b border-gray-100 dark:border-gray-700/80 flex items-center justify-between bg-gray-50/70 dark:bg-gray-800/80">
                                    <div class="flex items-center gap-2">
                                        <span class="text-sm font-extrabold text-gray-800 dark:text-gray-100">Pemberitahuan Sistem</span>
                                        @if(($globalLowStockCount ?? 0) > 0)
                                        <span class="px-2 py-0.5 rounded-full text-[10px] font-black bg-red-100 dark:bg-red-900/40 text-red-600 dark:text-red-300">
                                            {{ $globalLowStockCount }} Perhatian
                                        </span>
                                        @endif
                                    </div>
                                    <span class="text-[11px] text-gray-400">Peringatan Stok</span>
                                </div>

                                <div class="max-h-80 overflow-y-auto custom-scrollbar divide-y divide-gray-100 dark:divide-gray-700/60">
                                    @if(($globalLowStockCount ?? 0) > 0)
                                        @foreach(($globalLowStockBahan ?? []) as $b)
                                        <div class="p-3.5 hover:bg-gray-50 dark:hover:bg-gray-700/40 transition-colors flex items-start gap-3">
                                            <div class="w-8 h-8 rounded-lg bg-red-100 dark:bg-red-900/40 text-red-600 dark:text-red-400 flex items-center justify-center font-bold text-xs flex-shrink-0 mt-0.5">
                                                Bahan
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <div class="flex items-center justify-between gap-1">
                                                    <p class="text-xs font-bold text-gray-900 dark:text-gray-100 truncate">{{ $b->nama_bahan }}</p>
                                                    <span class="text-[10px] font-bold text-red-600 dark:text-red-400 whitespace-nowrap">Stok Menipis</span>
                                                </div>
                                                <p class="text-[11px] text-gray-500 dark:text-gray-400 mt-0.5">
                                                    Sisa: <strong class="text-red-600 dark:text-red-400">{{ $b->jumlah_stok }} {{ $b->satuan }}</strong> (Min: {{ $b->stok_minimum }})
                                                </p>
                                                <a href="{{ route('restock.index') }}" class="inline-flex items-center gap-1 text-[11px] font-bold text-indigo-600 dark:text-indigo-400 hover:underline mt-1.5">
                                                    + Restock Bahan Ini &rarr;
                                                </a>
                                            </div>
                                        </div>
                                        @endforeach

                                        @foreach(($globalLowStockProduk ?? []) as $p)
                                        <div class="p-3.5 hover:bg-gray-50 dark:hover:bg-gray-700/40 transition-colors flex items-start gap-3">
                                            <div class="w-8 h-8 rounded-lg bg-amber-100 dark:bg-amber-900/40 text-amber-600 dark:text-amber-400 flex items-center justify-center font-bold text-xs flex-shrink-0 mt-0.5">
                                                Roti
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <div class="flex items-center justify-between gap-1">
                                                    <p class="text-xs font-bold text-gray-900 dark:text-gray-100 truncate">{{ $p->nama_produk }}</p>
                                                    <span class="text-[10px] font-bold text-amber-600 dark:text-amber-400 whitespace-nowrap">Etalase Menipis</span>
                                                </div>
                                                <p class="text-[11px] text-gray-500 dark:text-gray-400 mt-0.5">
                                                    Sisa etalase: <strong class="text-amber-600 dark:text-amber-400">{{ $p->stok_etalase }} pcs</strong> (Min: {{ $p->stok_minimum }})
                                                </p>
                                                @if(Auth::user()->isOwner() || Auth::user()->isDapur())
                                                <a href="{{ route('dapur.index') }}" class="inline-flex items-center gap-1 text-[11px] font-bold text-orange-600 dark:text-orange-400 hover:underline mt-1.5">
                                                    Produksi Roti Ini &rarr;
                                                </a>
                                                @endif
                                            </div>
                                        </div>
                                        @endforeach
                                    @else
                                        <div class="p-6 text-center">
                                            
                                            <p class="text-xs font-bold text-gray-800 dark:text-gray-200">Semua Stok Aman</p>
                                            <p class="text-[11px] text-gray-400 dark:text-gray-500 mt-0.5">Tidak ada bahan baku atau produk di bawah batas minimum.</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- User Status & Role -->
                        <div class="hidden sm:flex flex-col text-right">
                            <span class="text-sm font-semibold text-gray-800 dark:text-gray-200 leading-tight">{{ Auth::user()->name }}</span>
                            <span class="text-xs text-gray-500 dark:text-gray-400">{{ Auth::user()->role_label }}</span>
                        </div>

                        <!-- User Profile Dropdown -->
                        <x-dropdown align="right" width="48">
                            <x-slot name="trigger">
                                <button class="flex items-center justify-center w-9 h-9 rounded-full bg-gray-900 dark:bg-gray-100 text-gray-50 dark:text-gray-900 text-sm font-bold hover:ring-2 hover:ring-indigo-500 hover:ring-offset-2 hover:ring-offset-gray-50 dark:hover:ring-offset-gray-900 transition-all focus:outline-none">
                                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                </button>
                            </x-slot>

                            <x-slot name="content">
                                <div class="px-4 py-2 border-b border-gray-100 dark:border-gray-700 text-xs text-gray-500 dark:text-gray-400">
                                    Masuk sebagai <strong class="text-gray-700 dark:text-gray-200">{{ Auth::user()->name }}</strong>
                                </div>
                                <x-dropdown-link :href="route('profile.edit')">
                                    {{ __('Edit Profil') }}
                                </x-dropdown-link>
                                
                                @if(Auth::user()->isOwner() || Auth::user()->isKasir())
                                <x-dropdown-link :href="route('kasir.index')">
                                    {{ __('Buka Kasir (POS)') }}
                                </x-dropdown-link>
                                @endif

                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <x-dropdown-link :href="route('logout')"
                                            onclick="event.preventDefault();
                                                        this.closest('form').submit();"
                                            class="text-red-600 dark:text-red-400 font-semibold">
                                        {{ __('Keluar (Log Out)') }}
                                    </x-dropdown-link>
                                </form>
                            </x-slot>
                        </x-dropdown>
                    </div>
                </header>

                <!-- Page Content Area -->
                <main class="flex-1 overflow-y-auto custom-scrollbar relative z-0">
                    {{ $slot }}
                </main>
            </div>
        </div>
    </body>
</html>
