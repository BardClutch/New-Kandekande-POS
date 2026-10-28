<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        <!-- Tailwind CDN (Fallback for development without Node.js) -->
        <script src="https://cdn.tailwindcss.com"></script>
        <script>
            tailwind.config = {
                darkMode: 'class',
                theme: {
                    extend: {
                        colors: {
                            brand: {
                                50: '#FAF5F5',
                                100: '#F5EBEB',
                                200: '#EBD4D4',
                                300: '#DCB5B5',
                                400: '#D19D9D',
                                500: '#C48686',
                                600: '#B06E6E',
                                700: '#955454',
                                800: '#753F3F',
                                900: '#5A2E2E',
                            },
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
    <body class="font-sans antialiased bg-gray-100 dark:bg-gray-900 text-gray-900 dark:text-gray-100 transition-colors duration-300"
          x-data="{ 
              sidebarOpen: false, 
              sidebarCollapsed: true,
              pinned: false,
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
              handleMouseEnter() {
                  this.sidebarCollapsed = false;
              },
              handleMouseLeave() {
                  if (!this.pinned) {
                      this.sidebarCollapsed = true;
                  }
              },
              togglePin() {
                  this.pinned = !this.pinned;
                  this.sidebarCollapsed = !this.pinned;
              }
          }">
        
        <div class="flex h-screen overflow-hidden">
            <!-- Collapsible Sidebar (Left) -->
            @include('layouts.navigation')

            <!-- Main Content Area (Right) -->
            <div class="flex-1 flex flex-col min-w-0 h-screen overflow-hidden">
                <!-- Topbar Header -->
                <header class="relative z-30 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 h-16 flex items-center justify-between px-4 sm:px-6 lg:px-8 transition-colors duration-300 flex-shrink-0">
                    <div class="flex items-center gap-3">
                        <!-- Mobile Hamburger Button -->
                        <button @click="sidebarOpen = true" class="lg:hidden p-2 rounded-xl text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700 focus:outline-none transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                            </svg>
                        </button>

                        <!-- Desktop Sidebar Pin / Toggle Button -->
                        <button @click="togglePin()" 
                                class="hidden lg:flex p-2 rounded-xl text-gray-500 hover:text-indigo-600 dark:text-gray-400 dark:hover:text-indigo-400 hover:bg-indigo-50 dark:hover:bg-gray-700/60 focus:outline-none transition-colors" 
                                :title="pinned ? 'Lepas Sematan (Ciutkan otomatis saat mouse keluar)' : 'Sematkan Sidebar (Tetap Terbuka)'">
                            <svg class="w-5 h-5 transition-transform duration-300" :class="pinned ? '' : 'rotate-180'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 19l-7-7 7-7m8 14l-7-7 7-7"></path>
                            </svg>
                        </button>

                        <!-- Page Title Header -->
                        <div class="truncate">
                            @isset($header)
                                {{ $header }}
                            @else
                                <h2 class="font-bold text-lg text-gray-800 dark:text-gray-200">Kandekande POS</h2>
                            @endisset
                        </div>
                    </div>

                    <!-- Topbar Right Info -->
                    <div class="flex items-center gap-3">
                        <!-- Dark / Light Mode Toggle Button -->
                        <button @click="toggleTheme()" 
                                type="button"
                                class="p-2.5 rounded-xl text-gray-500 hover:text-amber-500 dark:text-gray-400 dark:hover:text-amber-400 hover:bg-gray-100 dark:hover:bg-gray-700/60 focus:outline-none transition-all duration-200 border border-transparent hover:border-gray-200 dark:hover:border-gray-600 shadow-sm"
                                :title="isDark ? 'Ganti ke Mode Terang (Light Mode)' : 'Ganti ke Mode Gelap (Dark Mode)'">
                            <!-- Sun icon (shown in dark mode) -->
                            <svg x-show="isDark" class="w-5 h-5 text-amber-400 transform transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path>
                            </svg>
                            <!-- Moon icon (shown in light mode) -->
                            <svg x-show="!isDark" class="w-5 h-5 text-gray-600 dark:text-gray-300 transform transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path>
                            </svg>
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
                                                    🔥 Produksi Roti Ini &rarr;
                                                </a>
                                                @endif
                                            </div>
                                        </div>
                                        @endforeach
                                    @else
                                        <div class="p-6 text-center">
                                            <div class="w-10 h-10 rounded-full bg-emerald-50 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400 flex items-center justify-center mx-auto mb-2">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                            </div>
                                            <p class="text-xs font-bold text-gray-800 dark:text-gray-200">Semua Stok Aman</p>
                                            <p class="text-[11px] text-gray-400 dark:text-gray-500 mt-0.5">Tidak ada bahan baku atau produk di bawah batas minimum.</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- User Status & Role -->
                        <div class="hidden sm:flex flex-col text-right">
                            <span class="text-sm font-bold text-gray-800 dark:text-gray-200 leading-tight">{{ Auth::user()->name }}</span>
                            <span class="text-xs text-indigo-600 dark:text-indigo-400 font-semibold">{{ Auth::user()->role_label }}</span>
                        </div>

                        <!-- User Profile Dropdown -->
                        <x-dropdown align="right" width="48">
                            <x-slot name="trigger">
                                <button class="flex items-center justify-center w-10 h-10 rounded-xl bg-indigo-50 dark:bg-indigo-900/40 text-indigo-700 dark:text-indigo-300 font-bold border border-indigo-200 dark:border-indigo-700 hover:ring-2 hover:ring-indigo-400 transition-all focus:outline-none shadow-sm">
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
