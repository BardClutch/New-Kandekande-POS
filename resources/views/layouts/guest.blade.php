<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ $attributes->get('heading') ? $attributes->get('heading') . ' · ' : '' }}{{ config('app.name', 'Kandekande POS') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=bricolage-grotesque:600,700,800|albert-sans:400,500,600,700&display=swap" rel="stylesheet" />

        <script>
            // Tema awal: pilihan tersimpan, kalau belum ada ikuti perangkat
            (function () {
                var saved = localStorage.getItem('theme_guest') || localStorage.getItem('theme');
                var dark = saved ? saved === 'dark' : (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches);
                document.documentElement.classList.toggle('dark', dark);
            })();
        </script>

        @include('partials.storefront-style')
        <style>
            :root {
                --bg: #FAF9F6;
                --ink: #1B1612;
                --muted: #78706A;
                --line: #DEDAD3;
                --field: #FFFFFF;
                --btn: #1B1612;
                --on-btn: #FAF9F6;
                --accent: #D4501E;
                --err: #B3261E;
                --ok: #1F6B3A;
                --display: 'Bricolage Grotesque', system-ui, sans-serif;
                --body: 'Albert Sans', system-ui, sans-serif;

            }
            .dark {
                --bg: #14110F;
                --ink: #F3EEE7;
                --muted: #A39A91;
                --line: #35302B;
                --field: #1D1916;
                --btn: #F3EEE7;
                --on-btn: #14110F;
                --accent: #F08A57;
                --err: #FF9C93;
                --ok: #8FD6A4;

            }

            *, *::before, *::after { box-sizing: border-box; }
            html { -webkit-text-size-adjust: 100%; }
            body {
                margin: 0;
                background: var(--bg);
                color: var(--ink);
                font-family: var(--body);
                font-size: 15px;
                line-height: 1.5;
                -webkit-font-smoothing: antialiased;
                transition: background-color .6s, color .6s;
            }
            a { color: inherit; }

            .shell { min-height: 100vh; display: grid; grid-template-columns: minmax(440px, 5fr) minmax(0, 8fr); }

            /* kolom form */
            .pane { display: flex; flex-direction: column; min-height: 100vh; padding: 32px clamp(24px, 4vw, 64px); }
            .bar { width: 100%; max-width: 460px; margin: 0 auto; display: flex; align-items: center; justify-content: space-between; gap: 16px; }
            .brand {
                display: inline-flex; align-items: center; gap: 9px;
                text-decoration: none;
                font-family: var(--display); font-weight: 800; font-size: 22px; letter-spacing: -.02em; line-height: 1;
            }
            .brand::before { content: ''; width: 11px; height: 11px; border-radius: 50% 50% 4px 4px; background: var(--accent); }

            /* saklar siang / malam */
            .mode {
                display: inline-flex; align-items: center; gap: 10px;
                font: inherit; font-size: 13px; font-weight: 600; color: var(--muted);
                background: none; border: 0; padding: 2px; cursor: pointer;
            }
            .mode:hover { color: var(--ink); }
            .mode:focus-visible { outline: 2px solid var(--accent); outline-offset: 4px; border-radius: 999px; }
            .mode-track {
                position: relative; width: 62px; height: 32px; border-radius: 999px; overflow: hidden;
                background: linear-gradient(#A9DAF2, #D7EEFA);
                box-shadow: inset 0 0 0 1.5px rgba(0,0,0,.12);
                transition: background .6s;
            }
            .mode-track::before { /* awan kecil */
                content: ''; position: absolute; left: 30px; top: 16px; width: 20px; height: 8px; border-radius: 8px; background: #fff;
                box-shadow: -6px -4px 0 -1px #fff; transition: transform .6s, opacity .5s;
            }
            .mode-track::after { /* bintang */
                content: ''; position: absolute; inset: 0; opacity: 0; transition: opacity .6s;
                background:
                    radial-gradient(1.4px 1.4px at 14px 9px, #fff 50%, transparent 52%),
                    radial-gradient(1px 1px at 26px 20px, #fff 50%, transparent 52%),
                    radial-gradient(1.2px 1.2px at 20px 7px, #fff 50%, transparent 52%),
                    radial-gradient(1px 1px at 8px 21px, #fff 50%, transparent 52%);
            }
            .mode-thumb {
                position: absolute; top: 4px; left: 4px; width: 24px; height: 24px; border-radius: 50%;
                background: #FFD54A; box-shadow: 0 0 0 4px rgba(255, 213, 74, .35);
                transition: transform .55s cubic-bezier(.5, -.3, .3, 1.3), background .5s, box-shadow .5s;
            }
            .mode-thumb::after { /* kawah bulan */
                content: ''; position: absolute; left: 12px; top: 5px; width: 6px; height: 6px; border-radius: 50%;
                background: #D9D5C4; box-shadow: -7px 9px 0 -1px #D9D5C4; opacity: 0; transition: opacity .5s;
            }
            .dark .mode-track { background: linear-gradient(#0F1534, #2A2450); }
            .dark .mode-track::before { transform: translateX(26px); opacity: 0; }
            .dark .mode-track::after { opacity: 1; }
            .dark .mode-thumb { transform: translateX(30px); background: #F3EEE7; box-shadow: 0 0 0 4px rgba(243, 238, 231, .15); }
            .dark .mode-thumb::after { opacity: 1; }

            .main { flex: 1; width: 100%; max-width: 460px; margin: 0 auto; padding: 48px 0; display: flex; flex-direction: column; justify-content: center; }
            .heading { font-family: var(--display); font-weight: 800; font-size: clamp(46px, min(7.6vw, 12vh), 100px); line-height: .92; letter-spacing: -.045em; margin: 0 0 20px -3px; }
            .lede { margin: 0 0 40px; color: var(--muted); font-size: 17px; max-width: 30ch; }
            .foot { width: 100%; max-width: 460px; margin: 0 auto; display: flex; align-items: center; justify-content: space-between; gap: 16px; padding-top: 18px; border-top: 1px solid var(--line); font-size: 13px; color: var(--muted); }
            .open { display: inline-flex; align-items: center; gap: 8px; }
            .open::before { content: ''; width: 8px; height: 8px; border-radius: 50%; background: var(--rest, #9A9189); }
            .open.is-open { --rest: #2FA55A; }
            .open.is-open::before { box-shadow: 0 0 0 0 rgba(47, 165, 90, .5); animation: ping 2.2s ease-out infinite; }
            @keyframes ping { to { box-shadow: 0 0 0 8px rgba(47, 165, 90, 0); } }

            /* form */
            .field { margin-bottom: 26px; }
            .field label { display: block; font-size: 13px; font-weight: 600; color: var(--muted); margin-bottom: 2px; transition: color .2s; }
            .field:focus-within label { color: var(--accent); }
            .field-row { display: flex; justify-content: space-between; align-items: baseline; gap: 12px; }
            .field-row a { font-size: 13px; color: var(--muted); }
            .field-row a:hover { color: var(--accent); }
            .input {
                display: block; width: 100%;
                font: inherit; font-size: 20px; font-weight: 500; color: var(--ink);
                background: transparent;
                border: 0; border-bottom: 2px solid var(--line); border-radius: 0;
                padding: 8px 0 10px; outline: none;
                transition: border-color .2s;
            }
            .input::placeholder { color: color-mix(in srgb, var(--muted) 45%, transparent); font-weight: 400; }
            .input:hover { border-bottom-color: color-mix(in srgb, var(--ink) 40%, var(--line)); }
            .input:focus { border-bottom-color: var(--accent); }
            .input:-webkit-autofill { -webkit-text-fill-color: var(--ink); box-shadow: 0 0 0 40px var(--bg) inset; }
            .input[aria-invalid="true"] { border-color: var(--err); }
            .error { margin: 7px 0 0; padding: 0; list-style: none; font-size: 13px; color: var(--err); }
            .status { margin: 0 0 22px; padding: 11px 14px; border-radius: 12px; background: color-mix(in srgb, var(--ok) 12%, var(--bg)); color: var(--ok); font-size: 14px; }

            .check { display: inline-flex; align-items: center; gap: 9px; font-size: 14px; cursor: pointer; margin: 0 0 26px; }
            .check input { width: 17px; height: 17px; accent-color: var(--accent); margin: 0; }

            .btn {
                display: flex; align-items: center; justify-content: space-between;
                width: 100%;
                font: inherit; font-weight: 700; font-size: 17px;
                color: var(--on-btn); background: var(--btn);
                border: 0; border-radius: 999px;
                padding: 8px 8px 8px 28px; cursor: pointer;
                transition: background .2s, color .2s, transform .1s;
            }
            .btn span {
                display: grid; place-items: center; width: 46px; height: 46px; border-radius: 50%;
                background: var(--accent); color: #fff; font-size: 20px;
                transition: transform .3s cubic-bezier(.3, .9, .3, 1.2);
            }
            .btn:hover span { transform: rotate(-45deg); }
            .btn:active { transform: scale(.985); }
            .btn:focus-visible, .debug button:focus-visible { outline: 2px solid var(--accent); outline-offset: 3px; }
            .btn-quiet { display: inline-block; width: auto; background: none; color: var(--muted); padding: 6px 0; font-weight: 500; font-size: 14px; text-decoration: underline; text-underline-offset: 4px; }
            .btn-quiet:hover { background: none; color: var(--accent); }
            .alt { margin: 22px 0 0; font-size: 14px; color: var(--muted); }
            .alt a { color: var(--ink); font-weight: 700; }
            .split { margin-top: 14px; }

            /* login cepat (debug) */
            .debug { display: flex; flex-wrap: wrap; align-items: baseline; gap: 4px 16px; margin-bottom: 32px; font-size: 13px; }
            .debug-title { color: var(--muted); }
            .debug button { font: inherit; font-weight: 600; color: var(--ink); background: none; border: 0; border-bottom: 1.5px solid transparent; padding: 1px 0; cursor: pointer; transition: color .15s, border-color .15s; }
            .debug button:hover { border-bottom-color: var(--ink); }
            .debug button.is-active { color: var(--accent); border-bottom-color: var(--accent); }
            .debug-info { margin: -22px 0 32px; font-size: 12px; color: var(--muted); }

            /* kolom ilustrasi */
            .visual { margin: 0; padding: 16px 16px 16px 0; position: sticky; top: 0; height: 100vh; align-self: start; }
            .visual-frame { position: relative; height: 100%; border-radius: 24px; overflow: hidden; background: var(--sky-a); }
            .scene { position: absolute; inset: 0; width: 100%; height: 100%; display: block; }



            /* satu gerakan saat halaman dibuka: ilustrasi turun tenang, isi form naik berurutan */
            @keyframes settle { from { transform: scale(1.07); } to { transform: scale(1); } }
            @keyframes lift { from { opacity: 0; transform: translateY(12px); } to { opacity: 1; transform: none; } }
            .scene { animation: settle 2.2s cubic-bezier(.2, .7, .2, 1) both; transform-origin: 50% 100%; }
            .main > * { animation: lift .7s cubic-bezier(.2, .7, .2, 1) both; }
            .main > :nth-child(1) { animation-delay: .05s; }
            .main > :nth-child(2) { animation-delay: .12s; }
            .main > :nth-child(3) { animation-delay: .19s; }
            .main > :nth-child(4) { animation-delay: .26s; }
            .main > :nth-child(5) { animation-delay: .33s; }
            .main > :nth-child(6) { animation-delay: .4s; }

            @media (max-width: 860px) {
                .shell { grid-template-columns: 1fr; }
                .visual { order: -1; position: static; height: auto; padding: 12px 12px 0; }
                .visual-frame { height: 230px; border-radius: 20px; }
                .scene { inset: auto; left: 50%; bottom: -55px; width: 440px; height: 550px; transform: translateX(-50%); animation: none; }
                .pane { min-height: 0; padding-top: 20px; }
                .main { padding: 32px 0 40px; justify-content: flex-start; }
            }
            
            @media (min-width: 861px) and (max-height: 820px) {
                .pane { padding-top: 22px; padding-bottom: 16px; }
                .main { padding: 20px 0; }
                .lede { margin-bottom: 24px; font-size: 16px; }
                .field { margin-bottom: 16px; }
                .input { font-size: 18px; padding: 6px 0 8px; }
                .check { margin-bottom: 18px; }
                .debug { margin-bottom: 22px; }
                .btn span { width: 42px; height: 42px; }
                .alt { margin-top: 14px; }
                .foot { padding-top: 12px; }
            }
            @media (prefers-reduced-motion: reduce) {
                *, *::before, *::after { animation: none !important; }
                .puff { opacity: 0 !important; }
            }
        </style>
    </head>
    <body>
        <div class="shell">
            <div class="pane">
                <div class="bar">
                    <a href="/" class="brand">Kandekande</a>
                    <button type="button" class="mode" id="mode-btn" role="switch" aria-checked="false" aria-label="Mode malam" onclick="toggleGuestTheme()">
                        <span class="mode-track"><span class="mode-thumb"></span></span>
                        <span id="mode-label">Siang</span>
                    </button>
                </div>

                <main class="main">
                    @if ($attributes->get('heading'))
                        <h1 class="heading">{{ $attributes->get('heading') }}</h1>
                    @endif
                    @if ($attributes->get('lede'))
                        <p class="lede">{{ $attributes->get('lede') }}</p>
                    @endif

                    {{ $slot }}
                </main>

                <p class="foot">
                    <span id="open-status" class="open">Toko tutup</span>
                    <span>Buka setiap hari 06.00 – 21.00</span>
                </p>
            </div>

            <figure class="visual" aria-hidden="true">
                <div class="visual-frame">
                    @include('partials.storefront-scene')
                </div>
            </figure>
        </div>

        <script>
            function updateGuestThemeUI() {
                var dark = document.documentElement.classList.contains('dark');
                var btn = document.getElementById('mode-btn');
                btn.setAttribute('aria-checked', dark ? 'true' : 'false');
                document.getElementById('mode-label').textContent = dark ? 'Malam' : 'Siang';
            }
            function toggleGuestTheme() {
                var dark = !document.documentElement.classList.contains('dark');
                document.documentElement.classList.toggle('dark', dark);
                localStorage.setItem('theme_guest', dark ? 'dark' : 'light');
                localStorage.setItem('theme', dark ? 'dark' : 'light');
                updateGuestThemeUI();
            }
            updateGuestThemeUI();

            // status buka/tutup mengikuti jam perangkat
            (function () {
                var el = document.getElementById('open-status');
                function tick() {
                    var h = new Date().getHours();
                    var open = h >= 6 && h < 21;
                    el.classList.toggle('is-open', open);
                    el.textContent = open ? 'Toko sedang buka' : 'Toko sedang tutup';
                }
                tick(); setInterval(tick, 60000);
            })();
        </script>
    </body>
</html>
