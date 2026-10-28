<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <!-- Tailwind CDN Fallback -->
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

            // Tampilan awal sebelum login: ikuti tema sistem perangkat
            const guestTheme = localStorage.getItem('theme_guest') || localStorage.getItem('theme');
            if (guestTheme === 'dark') {
                document.documentElement.classList.add('dark');
            } else if (guestTheme === 'light') {
                document.documentElement.classList.remove('dark');
            } else {
                // Default pertama kali buka: ikuti preferensi sistem perangkat (OS/Browser)
                const systemPrefersDark = window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches;
                if (systemPrefersDark) {
                    document.documentElement.classList.add('dark');
                } else {
                    document.documentElement.classList.remove('dark');
                }
            }

            if (!guestTheme && window.matchMedia) {
                window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', (e) => {
                    if (!localStorage.getItem('theme_guest')) {
                        if (e.matches) {
                            document.documentElement.classList.add('dark');
                        } else {
                            document.documentElement.classList.remove('dark');
                        }
                        if (typeof updateGuestThemeUI === 'function') {
                            updateGuestThemeUI();
                        }
                    }
                });
            }
        </script>
    </head>
    <body class="font-sans text-gray-900 dark:text-gray-100 antialiased bg-gray-100 dark:bg-gray-900 transition-colors duration-300 relative">
        <!-- Floating Theme Toggle Button -->
        <div class="absolute top-6 right-6 z-20">
            <button onclick="toggleGuestTheme()" 
                    type="button"
                    class="p-2.5 rounded-xl bg-white dark:bg-gray-800 text-gray-600 dark:text-gray-300 hover:text-amber-500 dark:hover:text-amber-400 shadow-md border border-gray-200 dark:border-gray-700 transition-all focus:outline-none flex items-center justify-center gap-2 text-xs font-bold"
                    id="guest-theme-btn"
                    title="Ganti Tema">
                <svg id="guest-theme-icon" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"></svg>
                <span id="guest-theme-text" class="hidden sm:inline">Tema</span>
            </button>
        </div>

        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100 dark:bg-gray-900 transition-colors duration-300">
            <div>
                <a href="/">
                    <x-application-logo class="w-20 h-20 fill-current text-indigo-600 dark:text-indigo-400" />
                </a>
            </div>

            <div class="w-full sm:max-w-xl mt-6 px-6 py-8 bg-white dark:bg-gray-800 shadow-xl overflow-hidden sm:rounded-3xl border border-gray-100 dark:border-gray-700 transition-colors duration-300">
                {{ $slot }}
            </div>
        </div>

        <script>
            function updateGuestThemeUI() {
                const isDark = document.documentElement.classList.contains('dark');
                const icon = document.getElementById('guest-theme-icon');
                const text = document.getElementById('guest-theme-text');
                if (!icon) return;
                if (isDark) {
                    icon.className = 'w-4 h-4 text-amber-400';
                    icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path>';
                    if (text) text.innerText = 'Mode Gelap';
                } else {
                    icon.className = 'w-4 h-4 text-gray-600';
                    icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path>';
                    if (text) text.innerText = 'Mode Terang';
                }
            }

            function toggleGuestTheme() {
                const willBeDark = !document.documentElement.classList.contains('dark');
                if (willBeDark) {
                    document.documentElement.classList.add('dark');
                    localStorage.setItem('theme_guest', 'dark');
                    localStorage.setItem('theme', 'dark');
                } else {
                    document.documentElement.classList.remove('dark');
                    localStorage.setItem('theme_guest', 'light');
                    localStorage.setItem('theme', 'light');
                }
                updateGuestThemeUI();
            }

            updateGuestThemeUI();
        </script>
    </body>
</html>
