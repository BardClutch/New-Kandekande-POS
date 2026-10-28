<!DOCTYPE html>
<html lang="id">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ config('app.name', 'Kandekande') }} · Roti dan kue</title>
        <meta name="description" content="Toko roti dan kue Kandekande. Roti yang masih hangat setiap pagi dan kue ulang tahun sesuai pesananmu.">

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=bricolage-grotesque:600,700,800|albert-sans:400,500,600,700&display=swap" rel="stylesheet" />

        <script>
            (function () {
                document.documentElement.classList.add('js');
                var saved = null;
                try { saved = localStorage.getItem('theme_guest') || localStorage.getItem('theme'); } catch (e) {}
                var dark = saved ? saved === 'dark' : (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches);
                document.documentElement.classList.toggle('dark', dark);
            })();
        </script>

        @include('partials.storefront-style')

        @php
            use Illuminate\Support\Str;

            $semua = $menu->flatten();
            $hargaMin = $semua->min('harga_dasar');
            $hargaKustom = $semua->filter(fn ($p) => $p->isKustom())->min('harga_dasar');
            $rp = fn ($n) => 'Rp ' . number_format($n, 0, ',', '.');
            $foto = function ($p) {
                $nama = Str::lower($p->nama_produk);
                return match (true) {
                    str_contains($nama, 'cheesecake') => 'cheesecake',
                    str_contains($nama, 'donat') => 'donat',
                    str_contains($nama, 'sobek') => 'roti-sobek',
                    $p->isKustom(), str_contains($nama, 'ulang tahun') => 'kue-ulang-tahun',
                    default => 'oven',
                };
            };
            $img = fn ($f) => asset('images/landing/' . $f . '.jpg');
            $kategoriAda = $menu->keys()->map(fn ($id) => ['id' => $id, 'nama' => $kategori[$id] ?? 'Lainnya']);
        @endphp

        <style>
            :root {
                --bg: #FAF6EF;
                --ink: #1B1612;
                --muted: #6F665E;
                --line: #E8DFD2;
                --card: #FFFFFF;
                --accent: #D4501E;
                --btn: #1B1612;
                --on-btn: #FAF6EF;
                --peach: #FBE4D8;
                --mint: #D3EAD9;
                --sun: #F7DC9B;
                --rose: #F8D9D2;
                --sand: #EEE8DE;
                --ok: #1F6B3A;
                --ann-bg: #1B1612; --ann-ink: #F3EEE7;
                --mq-bg: #D4501E; --mq-ink: #FFFFFF;
                --band: #1B1612; --band-ink: #F3EEE7; --band-muted: #B5ABA1; --band-line: #3A332D; --band-accent: #F08A57; --band-num: #3A2418; --band-btn-ink: #14110F;
                --hours-text: #4A4036;
                --cta-bg: #D4501E; --cta-ink: #FFFFFF; --cta-btn-bg: #FFFFFF; --cta-btn-ink: #1B1612; --cta-line: #FFFFFF;
                --foot: #1B1612; --foot-ink: #F3EEE7; --foot-muted: #A39A91; --foot-accent: #F08A57; --foot-line: #3A332D; --word: #2A2420;
                --display: 'Bricolage Grotesque', system-ui, sans-serif;
                --body: 'Albert Sans', system-ui, sans-serif;
                --pad: clamp(20px, 5.6vw, 80px);
            }
            .dark {
                --bg: #14110F;
                --ink: #F3EEE7;
                --muted: #A39A91;
                --line: #35302B;
                --card: #1F1B18;
                --accent: #F08A57;
                --btn: #F3EEE7;
                --on-btn: #14110F;
                --peach: #3A2418;
                --mint: #1F3A2B;
                --sun: #4A3F22;
                --rose: #40262A;
                --sand: #2A2521;
                --ok: #8FD6A4;
                --ann-bg: #D4501E; --ann-ink: #14110F;
                --mq-bg: #F08A57; --mq-ink: #14110F;
                --band: #2A2420;
                --hours-text: #D9CFA6;
                --cta-bg: #F08A57; --cta-ink: #14110F; --cta-btn-bg: #14110F; --cta-btn-ink: #F3EEE7; --cta-line: #14110F;
                --foot: #0E0C0A; --word: #1C1815;
            }

            *, *::before, *::after { box-sizing: border-box; }
            html { -webkit-text-size-adjust: 100%; scroll-behavior: smooth; scroll-padding-top: 96px; }
            body { margin: 0; background: var(--bg); color: var(--ink); font-family: var(--body); font-size: 16px; line-height: 1.55; -webkit-font-smoothing: antialiased; transition: background-color .6s, color .6s; overflow-x: hidden; }
            a { color: inherit; }
            h1, h2, h3, h4, p, ul, ol, figure { margin: 0; padding: 0; }
            ul, ol { list-style: none; }
            img { display: block; max-width: 100%; }
            button { font: inherit; color: inherit; }
            .wrap { width: 100%; max-width: 1440px; margin: 0 auto; padding-left: var(--pad); padding-right: var(--pad); }
            :focus-visible { outline: 2px solid var(--accent); outline-offset: 3px; border-radius: 8px; }

            .h2 { font-family: var(--display); font-weight: 800; font-size: clamp(40px, 4.7vw, 68px); line-height: .97; letter-spacing: -.04em; }
            .sec-head { display: flex; align-items: flex-end; justify-content: space-between; gap: 24px 48px; margin-bottom: clamp(32px, 4vw, 56px); }
            .sec-note { max-width: 34ch; color: var(--muted); font-size: 16px; line-height: 1.55; text-align: right; }
            .sec { padding-bottom: clamp(64px, 8vw, 112px); }

            /* pengumuman */
            .ann { background: var(--ann-bg); color: var(--ann-ink); transition: background-color .6s; }
            .ann-in { display: flex; align-items: center; justify-content: center; gap: 10px; padding: 10px var(--pad); font-size: 14px; font-weight: 500; text-align: center; }
            .ann svg { flex: none; }

            /* header */
            .top { position: sticky; top: 0; z-index: 30; background: var(--bg); border-bottom: 1px solid var(--line); transition: background-color .6s, border-color .6s; }
            .top-in { display: grid; grid-template-columns: 1fr auto 1fr; align-items: center; gap: 24px; height: 80px; }
            .brand { display: inline-flex; align-items: center; gap: 12px; text-decoration: none; font-family: var(--display); font-weight: 800; font-size: 24px; letter-spacing: -.03em; line-height: 1; justify-self: start; }
            .mark { position: relative; width: 28px; height: 28px; border-radius: 9px; background: var(--accent); flex: none; }
            .mark::before { content: ''; position: absolute; inset: 6px; border-radius: 50%; background: #fff; }
            .mark::after { content: ''; position: absolute; inset: 11px; border-radius: 50%; background: #1B1612; }
            .nav { display: flex; align-items: center; gap: 36px; font-size: 15px; font-weight: 500; }
            .nav a { text-decoration: none; color: var(--muted); transition: color .15s; }
            .nav a:hover { color: var(--ink); }
            .top-end { display: flex; align-items: center; gap: 16px; justify-self: end; }
            .pill { display: inline-flex; align-items: center; height: 40px; padding: 0 22px; border-radius: 999px; border: 1.5px solid var(--ink); font-size: 14px; font-weight: 700; text-decoration: none; white-space: nowrap; transition: background .2s, color .2s; }
            .pill:hover { background: var(--ink); color: var(--bg); }

            .mode { display: inline-flex; align-items: center; background: none; border: 0; padding: 2px; cursor: pointer; }
            .mode-track { position: relative; width: 62px; height: 32px; border-radius: 16px; overflow: hidden; background: #BFE3F5; transition: background .6s; }
            .mode-track::after { content: ''; position: absolute; inset: 0; opacity: 0; transition: opacity .6s; background: radial-gradient(1.4px 1.4px at 14px 9px, #fff 50%, transparent 52%), radial-gradient(1px 1px at 26px 20px, #fff 50%, transparent 52%), radial-gradient(1.2px 1.2px at 20px 7px, #fff 50%, transparent 52%), radial-gradient(1px 1px at 8px 21px, #fff 50%, transparent 52%); }
            .mode-thumb { position: absolute; top: 4px; left: 4px; width: 24px; height: 24px; border-radius: 50%; background: #FFD54A; transition: transform .55s cubic-bezier(.5, -.3, .3, 1.3), background .5s; }
            .mode-thumb::after { content: ''; position: absolute; left: 12px; top: 5px; width: 6px; height: 6px; border-radius: 50%; background: #D9D5C4; box-shadow: -7px 9px 0 -1px #D9D5C4; opacity: 0; transition: opacity .5s; }
            .dark .mode-track { background: #1B2048; }
            .dark .mode-track::after { opacity: 1; }
            .dark .mode-thumb { transform: translateX(30px); background: #F3EEE7; }
            .dark .mode-thumb::after { opacity: 1; }

            /* tombol dan status */
            .open { display: inline-flex; align-items: center; gap: 10px; height: 35px; padding: 0 16px; border-radius: 999px; background: var(--card); border: 1px solid var(--line); font-size: 14px; font-weight: 600; transition: background-color .6s, border-color .6s; }
            .open::before { content: ''; width: 9px; height: 9px; border-radius: 50%; background: var(--rest, #9A9189); }
            .open.is-open::before { --rest: #2FA55A; box-shadow: 0 0 0 0 rgba(47, 165, 90, .5); animation: ping 2.2s ease-out infinite; }
            @keyframes ping { to { box-shadow: 0 0 0 9px rgba(47, 165, 90, 0); } }
            .btn { display: inline-flex; align-items: center; gap: 24px; font-weight: 700; font-size: 17px; text-decoration: none; color: var(--on-btn); background: var(--btn); border-radius: 999px; padding: 8px 8px 8px 28px; transition: transform .1s; }
            .btn span { display: grid; place-items: center; width: 48px; height: 48px; border-radius: 50%; background: var(--accent); color: #fff; transition: transform .3s cubic-bezier(.3, .9, .3, 1.2); }
            .dark .btn span { color: #14110F; }
            .btn:hover span { transform: rotate(-45deg); }
            .btn:active { transform: scale(.985); }
            .link { font-weight: 600; font-size: 16px; text-decoration: underline; text-underline-offset: 5px; text-decoration-color: color-mix(in srgb, currentColor 35%, transparent); transition: color .2s, text-decoration-color .2s; }
            .link:hover { color: var(--accent); text-decoration-color: var(--accent); }

            /* hero */
            .hero { display: grid; grid-template-columns: minmax(0, 652fr) minmax(0, 543fr); gap: clamp(32px, 6vw, 86px); align-items: center; padding-top: 56px; padding-bottom: clamp(56px, 7vw, 96px); }
            .hero h1 { font-family: var(--display); font-weight: 800; font-size: clamp(52px, 6.7vw, 96px); line-height: .94; letter-spacing: -.045em; margin: 28px 0 0 -3px; }
            .hero h1 em { font-style: normal; color: var(--accent); }
            .lede { margin-top: 28px; max-width: 30em; font-size: clamp(17px, 1.4vw, 20px); line-height: 1.5; color: var(--muted); }
            .cta { display: flex; flex-wrap: wrap; align-items: center; gap: 16px 28px; margin-top: 32px; }
            .stats { display: grid; grid-template-columns: repeat(3, auto); justify-content: start; margin-top: 36px; padding-top: 28px; border-top: 1px solid var(--line); }
            .stat { padding: 0 40px; border-left: 1px solid var(--line); }
            .stat:first-child { padding-left: 0; border-left: 0; }
            .stat b { display: block; font-family: var(--display); font-weight: 700; font-size: 28px; letter-spacing: -.02em; line-height: 1.15; white-space: nowrap; }
            .stat span { font-size: 14px; color: var(--muted); }

            .hero-visual { position: relative; }
            .visual { position: relative; margin: 0; aspect-ratio: 543 / 580; border-radius: 32px; overflow: hidden; background: var(--sky-a); }
            .scene { position: absolute; inset: 0; width: 100%; height: 100%; display: block; transform-origin: 50% 100%; }
            .badge { position: absolute; left: -40px; display: flex; align-items: center; gap: 14px; padding: 14px 24px 14px 14px; border-radius: 20px; background: var(--card); box-shadow: 0 10px 30px rgba(27, 22, 18, .12); transition: background-color .6s; animation: float 6s ease-in-out infinite alternate; }
            .badge.b1 { top: 90px; }
            .badge.b2 { bottom: 28px; animation-delay: -3s; }
            .badge i { display: grid; place-items: center; width: 44px; height: 44px; border-radius: 14px; background: var(--peach); color: var(--accent); }
            .badge.b2 i { background: var(--sun); }
            .badge b { display: block; font-size: 15px; line-height: 1.25; }
            .badge small { font-size: 13px; color: var(--muted); }
            @keyframes float { from { transform: translateY(-4px); } to { transform: translateY(4px); } }

            /* marquee */
            .marquee { background: var(--mq-bg); color: var(--mq-ink); overflow: hidden; transition: background-color .6s; }
            .marquee-track { display: flex; width: max-content; animation: scroll 38s linear infinite; }
            .marquee:hover .marquee-track { animation-play-state: paused; }
            .marquee-group { display: flex; align-items: center; gap: 36px; padding: 22px 18px; flex: none; font-family: var(--display); font-weight: 700; font-size: 28px; letter-spacing: -.015em; line-height: 1; white-space: nowrap; }
            .marquee-group i { width: 10px; height: 10px; border-radius: 50%; background: currentColor; opacity: .55; }
            @keyframes scroll { to { transform: translateX(-50%); } }

            /* menu */
            .menu { padding-top: clamp(64px, 8vw, 112px); }
            .menu-head-end { display: flex; flex-direction: column; align-items: flex-end; gap: 20px; }
            .tabs { display: flex; flex-wrap: wrap; justify-content: flex-end; gap: 8px; }
            .tab { height: 40px; padding: 0 20px; border-radius: 999px; border: 1.5px solid var(--line); background: none; font-size: 14px; font-weight: 600; color: var(--muted); cursor: pointer; transition: background .2s, color .2s, border-color .2s; }
            .tab:hover { border-color: var(--ink); color: var(--ink); }
            .tab.is-on { background: var(--btn); border-color: var(--btn); color: var(--on-btn); }
            .grid4 { display: grid; grid-template-columns: repeat(4, minmax(0, 1fr)); gap: 24px; }
            .card { display: flex; flex-direction: column; padding: 12px; border-radius: 28px; background: var(--card); border: 1px solid var(--line); transition: transform .3s cubic-bezier(.3, .9, .3, 1), box-shadow .3s, background-color .6s, border-color .6s; }
            .card:hover { transform: translateY(-4px); box-shadow: 0 14px 34px rgba(27, 22, 18, .1); }
            .card[hidden] { display: none; }
            .card-img { aspect-ratio: 276 / 268; border-radius: 20px; overflow: hidden; background: var(--sand); }
            .card-img img { width: 100%; height: 100%; object-fit: cover; transition: transform .6s cubic-bezier(.2, .7, .2, 1); }
            .card:hover .card-img img { transform: scale(1.04); }
            .card-body { display: flex; flex-direction: column; align-items: flex-start; flex: 1; padding: 18px 8px 10px; }
            .card-cat { font-size: 14px; font-weight: 600; color: var(--muted); }
            .card-name { margin-top: 6px; font-family: var(--display); font-weight: 700; font-size: 24px; line-height: 1.16; letter-spacing: -.02em; }
            .card-price { margin-top: 8px; font-family: var(--display); font-weight: 700; font-size: 22px; letter-spacing: -.01em; color: var(--accent); }
            .status { display: inline-flex; align-items: center; gap: 8px; margin-top: auto; height: 28px; padding: 0 14px; border-radius: 999px; font-size: 13px; font-weight: 600; background: var(--sand); color: var(--muted); }
            .card-body .status { margin-top: 16px; }
            .status::before { content: ''; width: 7px; height: 7px; border-radius: 50%; background: currentColor; }
            .status.is-ready { background: var(--mint); color: var(--ok); }
            .status.is-order { background: var(--peach); color: var(--accent); }
            .empty { padding: 40px 0; color: var(--muted); }

            /* keunggulan */
            .feat { display: flex; flex-direction: column; padding: 14px 14px 30px; border-radius: 32px; transition: transform .3s cubic-bezier(.3, .9, .3, 1), background-color .6s; }
            .feat:hover { transform: translateY(-4px); }
            .feat.f1 { background: var(--peach); } .feat.f2 { background: var(--mint); } .feat.f3 { background: var(--sun); } .feat.f4 { background: var(--rose); }
            .feat-img { aspect-ratio: 277 / 190; border-radius: 22px; overflow: hidden; }
            .feat-img img { width: 100%; height: 100%; object-fit: cover; }
            .feat h3 { margin: 24px 12px 0; font-family: var(--display); font-weight: 700; font-size: 28px; line-height: 1.14; letter-spacing: -.02em; }
            .feat p { margin: 12px 12px 0; font-size: 15px; line-height: 1.6; color: var(--muted); }
            .dark .feat p { color: #BDB3A9; }

            /* kue kustom */
            .band { display: grid; grid-template-columns: minmax(0, 6fr) minmax(0, 5fr); gap: clamp(32px, 6vw, 80px); align-items: center; padding: clamp(32px, 5.6vw, 72px); border-radius: 44px; background: var(--band); color: var(--band-ink); transition: background-color .6s; }
            .band .h2 { font-size: clamp(38px, 4.4vw, 64px); line-height: .97; }
            .band .h2 em { font-style: normal; color: var(--band-accent); }
            .band-price { display: flex; align-items: baseline; gap: 12px; margin-top: 28px; color: var(--band-muted); font-size: 17px; }
            .band-price b { font-family: var(--display); font-weight: 700; font-size: 32px; letter-spacing: -.015em; color: var(--band-ink); }
            .chips { display: flex; flex-wrap: wrap; gap: 10px; margin-top: 24px; }
            .chips span { display: inline-flex; align-items: center; height: 38px; padding: 0 18px; border-radius: 999px; border: 1.5px solid var(--band-line); font-size: 14px; font-weight: 600; }
            .btn-band { display: inline-flex; align-items: center; gap: 28px; margin-top: 32px; padding: 8px 8px 8px 28px; border-radius: 999px; background: var(--band-accent); color: var(--band-btn-ink); font-weight: 700; font-size: 17px; text-decoration: none; }
            .btn-band span { display: grid; place-items: center; width: 48px; height: 48px; border-radius: 50%; background: #14110F; color: #F3EEE7; transition: transform .3s cubic-bezier(.3, .9, .3, 1.2); }
            .btn-band:hover span { transform: rotate(-45deg); }
            .steps { border-top: 1px solid var(--band-line); }
            .steps li { display: grid; grid-template-columns: 52px 1fr; gap: 0 20px; padding: 30px 0; border-bottom: 1px solid var(--band-line); }
            .step-n { display: grid; place-items: center; width: 52px; height: 52px; border-radius: 50%; background: var(--band-num); color: var(--band-accent); font-family: var(--display); font-weight: 800; font-size: 24px; }
            .step-t { font-family: var(--display); font-weight: 700; font-size: 26px; letter-spacing: -.02em; line-height: 1.15; }
            .step-d { margin-top: 6px; color: var(--band-muted); font-size: 16px; line-height: 1.5; }

            /* dari dapur */
            .gallery { display: grid; grid-template-columns: minmax(0, 740fr) minmax(0, 520fr); gap: 24px 20px; align-items: start; }
            .gallery figure.big { grid-row: 1 / 3; }
            .shot { border-radius: 32px; overflow: hidden; background: var(--sand); }
            .shot img { width: 100%; height: 100%; object-fit: cover; transition: transform .8s cubic-bezier(.2, .7, .2, 1); }
            figure:hover .shot img { transform: scale(1.03); }
            .big .shot { aspect-ratio: 740 / 500; }
            .small .shot { aspect-ratio: 520 / 220; }
            figcaption { margin-top: 14px; font-size: 14px; color: var(--muted); }

            /* jam buka */
            .hours { display: grid; grid-template-columns: minmax(0, 836fr) minmax(0, 420fr); gap: 24px; }
            .hours-card { display: flex; flex-direction: column; justify-content: space-between; gap: 48px; min-height: 461px; padding: clamp(28px, 4.4vw, 56px); border-radius: 44px; background: var(--sun); transition: background-color .6s; }
            .hours-time { font-family: var(--display); font-weight: 800; font-size: clamp(60px, 9.6vw, 132px); line-height: .92; letter-spacing: -.05em; white-space: nowrap; margin-left: -.04em; }
            .hours-foot { display: flex; flex-wrap: wrap; align-items: center; gap: 16px 24px; }
            .hours-foot p { max-width: 30ch; color: var(--hours-text); font-size: 16px; line-height: 1.5; }
            .hours-foot p.open { color: var(--ink); max-width: none; }
            .week { padding: 36px 40px; border-radius: 44px; background: var(--card); border: 1px solid var(--line); transition: background-color .6s, border-color .6s; }
            .week h3 { font-family: var(--display); font-weight: 700; font-size: 26px; letter-spacing: -.02em; }
            .week ul { margin-top: 16px; }
            .week li { display: flex; justify-content: space-between; gap: 16px; padding: 12px 0; border-top: 1px solid var(--line); font-size: 16px; font-weight: 500; }
            .week li span { color: var(--muted); font-weight: 600; font-variant-numeric: tabular-nums; }
            .week li.is-today, .week li.is-today span { color: var(--accent); }

            /* faq */
            .faq { display: grid; grid-template-columns: minmax(0, 520fr) minmax(0, 760fr); gap: clamp(32px, 6vw, 80px); align-items: start; }
            .faq .h2 { font-size: clamp(38px, 3.6vw, 52px); line-height: 1.04; }
            .faq-note { margin-top: 20px; max-width: 28ch; color: var(--muted); font-size: 16px; line-height: 1.55; }
            .qa { border-radius: 28px; border: 1px solid var(--line); background: var(--bg); transition: background-color .3s, border-color .6s; }
            .qa + .qa { margin-top: 12px; }
            .qa.is-open { background: var(--card); }
            .qa-q { display: flex; align-items: center; justify-content: space-between; gap: 20px; width: 100%; padding: 28px 32px; background: none; border: 0; text-align: left; cursor: pointer; font-family: var(--display); font-weight: 700; font-size: 22px; letter-spacing: -.015em; line-height: 1.25; }
            .qa-ico { position: relative; flex: none; width: 36px; height: 36px; border-radius: 50%; background: var(--peach); transition: background-color .3s; }
            .qa-ico::before, .qa-ico::after { content: ''; position: absolute; left: 50%; top: 50%; width: 14px; height: 2px; margin: -1px 0 0 -7px; border-radius: 2px; background: var(--accent); transition: transform .3s, background-color .3s, opacity .3s; }
            .qa-ico::after { transform: rotate(90deg); }
            .qa.is-open .qa-ico { background: var(--accent); }
            .qa.is-open .qa-ico::before { background: #fff; }
            .dark .qa.is-open .qa-ico::before { background: #14110F; }
            .qa.is-open .qa-ico::after { transform: rotate(0deg); opacity: 0; }
            .qa-a { display: grid; grid-template-rows: 0fr; transition: grid-template-rows .35s cubic-bezier(.2, .7, .2, 1); }
            .qa.is-open .qa-a { grid-template-rows: 1fr; }
            .qa-a > div { overflow: hidden; }
            .qa-a p { padding: 0 32px 28px; max-width: 60ch; color: var(--muted); font-size: 16px; line-height: 1.6; }

            /* ajakan */
            .cta-band { display: grid; grid-template-columns: minmax(0, 1fr) minmax(0, 500px); gap: clamp(32px, 6vw, 80px); align-items: center; padding: clamp(32px, 5.6vw, 72px); border-radius: 48px; background: var(--cta-bg); color: var(--cta-ink); transition: background-color .6s; }
            .cta-band h2 { font-family: var(--display); font-weight: 800; font-size: clamp(38px, 3.75vw, 54px); line-height: 1; letter-spacing: -.045em; }
            .cta-band p { margin-top: 24px; max-width: 34ch; font-size: 19px; line-height: 1.5; }
            .cta-btns { display: flex; flex-wrap: wrap; gap: 12px; margin-top: 32px; }
            .btn-solid, .btn-line { display: inline-flex; align-items: center; gap: 12px; height: 56px; padding: 0 32px; border-radius: 999px; font-weight: 700; font-size: 17px; text-decoration: none; transition: transform .1s, opacity .2s; }
            .btn-solid { background: var(--cta-btn-bg); color: var(--cta-btn-ink); }
            .btn-line { border: 1.5px solid var(--cta-line); color: var(--cta-ink); }
            .btn-solid:hover, .btn-line:hover { opacity: .88; }
            .btn-solid:active, .btn-line:active { transform: scale(.985); }
            .cta-photo { aspect-ratio: 500 / 440; border-radius: 32px; overflow: hidden; }
            .cta-photo img { width: 100%; height: 100%; object-fit: cover; }

            /* footer */
            .foot { background: var(--foot); color: var(--foot-ink); padding: clamp(48px, 6vw, 80px) 0 40px; transition: background-color .6s; }
            .foot-top { display: flex; flex-wrap: wrap; justify-content: space-between; gap: 40px 64px; }
            .foot .brand { color: var(--foot-ink); }
            .foot .mark { background: var(--foot-accent); }
            .foot-about { margin-top: 24px; max-width: 28ch; color: var(--foot-muted); font-size: 16px; line-height: 1.6; }
            .foot-cols { display: flex; flex-wrap: wrap; gap: 32px clamp(32px, 6vw, 80px); }
            .foot-col h4 { margin-bottom: 14px; font-size: 14px; font-weight: 600; color: var(--foot-accent); }
            .foot-col li { margin-bottom: 14px; font-size: 15px; font-weight: 500; }
            .foot-col a { text-decoration: none; }
            .foot-col a:hover { color: var(--foot-accent); }
            .foot-word { margin: clamp(32px, 5vw, 64px) 0 24px; font-family: var(--display); font-weight: 800; font-size: clamp(40px, 13vw, 190px); line-height: .9; letter-spacing: -.05em; color: var(--word); user-select: none; white-space: nowrap; overflow: hidden; }
            .foot-word span { display: inline-block; }
            .foot-row { display: flex; flex-wrap: wrap; justify-content: space-between; gap: 12px 24px; padding-top: 24px; border-top: 1px solid var(--foot-line); font-size: 14px; color: var(--foot-muted); }
            .foot-row a { color: var(--foot-ink); font-weight: 600; text-underline-offset: 4px; }
            .foot-row a:hover { color: var(--foot-accent); }

            /* gerak */
            @keyframes lift { from { opacity: 0; transform: translateY(16px); } to { opacity: 1; transform: none; } }
            @keyframes settle { from { transform: scale(1.07); } to { transform: scale(1); } }
            .hero-copy > * { animation: lift .8s cubic-bezier(.2, .7, .2, 1) both; }
            .hero-copy > :nth-child(2) { animation-delay: .06s; }
            .hero-copy > :nth-child(3) { animation-delay: .12s; }
            .hero-copy > :nth-child(4) { animation-delay: .2s; }
            .hero-copy > :nth-child(5) { animation-delay: .28s; }
            .scene { animation: settle 2.4s cubic-bezier(.2, .7, .2, 1) both; }
            .js .rv { opacity: 0; transform: translateY(24px); transition: opacity .8s cubic-bezier(.2, .7, .2, 1), transform .8s cubic-bezier(.2, .7, .2, 1); transition-delay: var(--d, 0s); }
            .js .rv.in { opacity: 1; transform: none; }

            @media (max-width: 1100px) {
                .nav { display: none; }
                .top-in { grid-template-columns: 1fr auto; }
                .hero { grid-template-columns: 1fr; align-items: start; }
                .hero-visual { margin-left: 40px; }
                .grid4 { grid-template-columns: repeat(2, minmax(0, 1fr)); }
                .band, .hours, .faq, .cta-band { grid-template-columns: 1fr; }
                .cta-photo { max-width: 500px; }
                .gallery { grid-template-columns: 1fr; }
                .gallery figure.big { grid-row: auto; }
                .sec-head { flex-direction: column; align-items: flex-start; }
                .sec-note { text-align: left; }
                .menu-head-end { align-items: flex-start; }
                .tabs { justify-content: flex-start; }
            }
            @media (max-width: 640px) {
                .top-in { height: 68px; }
                .brand { font-size: 21px; gap: 10px; }
                .pill { height: 36px; padding: 0 16px; }
                .top-end { gap: 10px; }
                .ann-in { font-size: 13px; }
                .hero { padding-top: 32px; }
                .hero-visual { margin-left: 0; }
                .badge { left: 12px; padding: 10px 18px 10px 10px; }
                .badge.b1 { top: 16px; }
                .badge.b2 { bottom: 16px; }
                .visual { aspect-ratio: 4 / 4.4; }
                .scene { inset: auto; left: 50%; bottom: -30px; width: 520px; height: 650px; transform: translateX(-50%); animation: none; }
                .stats { grid-template-columns: 1fr 1fr; gap: 20px 0; }
                .stat { padding: 0 20px; }
                .stat:nth-child(odd) { padding-left: 0; border-left: 0; }
                .stat:nth-child(3) { grid-column: 1 / -1; padding-left: 0; border-left: 0; padding-top: 20px; border-top: 1px solid var(--line); }
                .grid4 { grid-template-columns: 1fr; }
                .marquee-group { font-size: 22px; gap: 28px; }
                .band, .cta-band { border-radius: 32px; }
                .hours-card, .week { border-radius: 32px; }
                .week { padding: 28px 24px; }
                .qa-q { padding: 22px 20px; font-size: 19px; }
                .qa-a p { padding: 0 20px 22px; }
                .steps li { grid-template-columns: 44px 1fr; gap: 0 14px; }
                .step-n { width: 44px; height: 44px; font-size: 20px; }
            }
            @media (prefers-reduced-motion: reduce) {
                *, *::before, *::after { animation: none !important; transition-duration: .01ms !important; scroll-behavior: auto !important; }
                .js .rv { opacity: 1; transform: none; }
                .puff { opacity: 0 !important; }
            }
        </style>
    </head>
    <body>
        <div class="ann">
            <p class="ann-in">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M8.5 14.5A2.5 2.5 0 0 0 11 12c0-1.38-.5-2-1-3-1.07-2.14-.22-4.05 2-6 .5 2.5 2 4.9 4 6.5 2 1.6 3 3.5 3 5.5a7 7 0 1 1-14 0c0-1.15.43-2.29 1-3a2.5 2.5 0 0 0 2.5 2.5z"/></svg>
                <span>Buka setiap hari 06.00 &ndash; 21.00 &nbsp;&middot;&nbsp; Kue ulang tahun kustom bisa dipesan langsung di kasir</span>
            </p>
        </div>

        <header class="top">
            <div class="wrap top-in">
                <a href="{{ route('landing') }}" class="brand"><span class="mark" aria-hidden="true"></span>Kandekande</a>
                <nav class="nav" aria-label="Utama">
                    <a href="#menu">Menu</a>
                    <a href="#keunggulan">Keunggulan</a>
                    <a href="#kustom">Kue kustom</a>
                    <a href="#buka">Jam buka</a>
                    <a href="#faq">FAQ</a>
                </nav>
                <div class="top-end">
                    <button type="button" class="mode" id="mode-btn" role="switch" aria-checked="false" aria-label="Mode malam" onclick="toggleTheme()">
                        <span class="mode-track"><span class="mode-thumb"></span></span>
                    </button>
                    @auth
                        <a class="pill" href="{{ route('dashboard') }}">Dashboard</a>
                    @else
                        <a class="pill" href="{{ route('login') }}">Masuk staf</a>
                    @endauth
                </div>
            </div>
        </header>

        <main>
            <section class="wrap hero">
                <div class="hero-copy">
                    <p class="open" data-open>Toko tutup</p>
                    <h1>Roti dan kue<br>yang masih <em>hangat.</em></h1>
                    <p class="lede">Toko roti di sudut jalan. Datang pagi untuk roti yang baru keluar dari oven, atau pesan kue ulang tahun sesuai keinginanmu.</p>
                    <div class="cta">
                        <a href="#menu" class="btn">Lihat menu <span aria-hidden="true"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M13 6l6 6-6 6"/></svg></span></a>
                        <a href="#kustom" class="link">Pesan kue kustom</a>
                    </div>
                    <div class="stats">
                        <p class="stat"><b>06.00&ndash;21.00</b><span>Buka setiap hari</span></p>
                        <p class="stat"><b>{{ $hargaMin ? $rp($hargaMin) : '-' }}</b><span>Harga mulai dari</span></p>
                        <p class="stat"><b>Kustom</b><span>Kue ulang tahun</span></p>
                    </div>
                </div>

                <div class="hero-visual">
                    <figure class="visual" aria-hidden="true">
                        @include('partials.storefront-scene')
                    </figure>
                    <div class="badge b1" aria-hidden="true">
                        <i><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M8.5 14.5A2.5 2.5 0 0 0 11 12c0-1.38-.5-2-1-3-1.07-2.14-.22-4.05 2-6 .5 2.5 2 4.9 4 6.5 2 1.6 3 3.5 3 5.5a7 7 0 1 1-14 0c0-1.15.43-2.29 1-3a2.5 2.5 0 0 0 2.5 2.5z"/></svg></i>
                        <span><b>Baru keluar oven</b><small>Setiap pagi sejak 06.00</small></span>
                    </div>
                    <div class="badge b2" aria-hidden="true">
                        <i><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 6V3M8 7V5M16 7V5M4 21v-7a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v7M3 21h18M4 17c2 1.5 3 1.5 4 0s2-1.5 4 0 3 1.5 4 0 2-1.5 4 0"/></svg></i>
                        <span><b>Kue ulang tahun kustom</b><small>{{ $hargaKustom ? 'Mulai ' . $rp($hargaKustom) : 'Dibuat sesuai pesanan' }}</small></span>
                    </div>
                </div>
            </section>

            <div class="marquee" aria-hidden="true">
                <div class="marquee-track">
                    @foreach (range(1, 2) as $dup)
                        <div class="marquee-group">
                            @foreach (['Roti hangat', 'Kue basah', 'Donat kentang', 'Cheesecake', 'Roti sobek', 'Kue ulang tahun'] as $kata)
                                <span>{{ $kata }}</span><i></i>
                            @endforeach
                        </div>
                    @endforeach
                </div>
            </div>

            <section class="wrap sec menu" id="menu">
                <div class="sec-head rv">
                    <h2 class="h2">Pilih yang masih<br>hangat dari oven</h2>
                    <div class="menu-head-end">
                        <p class="sec-note">Ketersediaan mengikuti etalase toko.<br>Harga dalam rupiah.</p>
                        @if ($kategoriAda->count() > 1)
                            <div class="tabs" role="tablist" aria-label="Filter kategori">
                                <button type="button" class="tab is-on" data-filter="all" role="tab" aria-selected="true">Semua</button>
                                @foreach ($kategoriAda as $k)
                                    <button type="button" class="tab" data-filter="{{ $k['id'] }}" role="tab" aria-selected="false">{{ $k['nama'] }}</button>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>

                @if ($semua->isEmpty())
                    <p class="empty">Menu sedang disiapkan.</p>
                @else
                    <div class="grid4" id="menu-grid">
                        @foreach ($menu as $idKategori => $produks)
                            @foreach ($produks as $p)
                                <article class="card rv" data-cat="{{ $idKategori }}" style="--d: {{ ($loop->index % 4) * .08 }}s">
                                    <div class="card-img"><img src="{{ $img($foto($p)) }}" alt="{{ $p->nama_produk }}" loading="lazy" width="928" height="900"></div>
                                    <div class="card-body">
                                        <p class="card-cat">{{ Str::ucfirst(Str::lower($kategori[$idKategori] ?? 'Lainnya')) }}</p>
                                        <h3 class="card-name">{{ $p->nama_produk }}</h3>
                                        <p class="card-price">{{ $p->isKustom() ? 'mulai ' : '' }}{{ $rp($p->harga_dasar) }}</p>
                                        @if ($p->isKustom())
                                            <span class="status is-order">Dibuat sesuai pesanan</span>
                                        @elseif ($p->stok_etalase > 0)
                                            <span class="status is-ready">Tersedia di etalase</span>
                                        @else
                                            <span class="status">Habis untuk saat ini</span>
                                        @endif
                                    </div>
                                </article>
                            @endforeach
                        @endforeach
                    </div>
                @endif
            </section>

            <section class="wrap sec" id="keunggulan">
                <div class="sec-head rv">
                    <h2 class="h2">Sederhana, hangat,<br>dan jujur soal stok.</h2>
                    <p class="sec-note">Toko kecil dengan dapur yang rapi.<br>Apa yang kamu lihat di menu,<br>itu yang ada di etalase.</p>
                </div>
                <div class="grid4">
                    <article class="feat f1 rv" style="--d: 0s">
                        <div class="feat-img"><img src="{{ $img('oven') }}" alt="Roti baru keluar dari oven" loading="lazy" width="1264" height="848"></div>
                        <h3>Hangat dari oven</h3>
                        <p>Roti dan kue dibuat setiap hari. Datang pagi untuk yang paling baru keluar dari oven.</p>
                    </article>
                    <article class="feat f2 rv" style="--d: .08s">
                        <div class="feat-img"><img src="{{ $img('etalase') }}" alt="Etalase roti dan kue" loading="lazy" width="1264" height="848"></div>
                        <h3>Stok yang jujur</h3>
                        <p>Ketersediaan di daftar menu mengikuti etalase toko, jadi kamu tahu apa yang masih ada.</p>
                    </article>
                    <article class="feat f3 rv" style="--d: .16s">
                        <div class="feat-img"><img src="{{ $img('kue-ulang-tahun') }}" alt="Kue ulang tahun" loading="lazy" width="928" height="1152"></div>
                        <h3>Kue sesuai pesanan</h3>
                        <p>Ukuran, rasa, dan tulisan di atas kue diatur sesuai keinginanmu{{ $hargaKustom ? ', mulai ' . $rp($hargaKustom) : '' }}.</p>
                    </article>
                    <article class="feat f4 rv" style="--d: .24s">
                        <div class="feat-img"><img src="{{ $img('adonan') }}" alt="Adonan dibentuk di dapur" loading="lazy" width="1264" height="848"></div>
                        <h3>Pesanan tercatat rapi</h3>
                        <p>Catatan pesananmu langsung sampai ke layar dapur, jadi tidak ada yang terlewat.</p>
                    </article>
                </div>
            </section>

            <section class="wrap sec" id="kustom">
                <div class="band rv">
                    <div>
                        <h2 class="h2">Kue ulang tahun, dibuat <em>sesuai pesananmu.</em></h2>
                        @if ($hargaKustom)
                            <p class="band-price">Mulai dari <b>{{ $rp($hargaKustom) }}</b></p>
                        @endif
                        <div class="chips"><span>Ukuran</span><span>Rasa</span><span>Tulisan di kue</span><span>Hiasan</span></div>
                        <a href="#faq" class="btn-band">Pesan lewat kasir <span aria-hidden="true"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M13 6l6 6-6 6"/></svg></span></a>
                    </div>
                    <ol class="steps">
                        <li><span class="step-n">1</span><div><p class="step-t">Ceritakan keinginanmu</p><p class="step-d">Ukuran, rasa, dan tulisan di atas kue. Sampaikan langsung ke kasir.</p></div></li>
                        <li><span class="step-n">2</span><div><p class="step-t">Dapur mengerjakannya</p><p class="step-d">Pesananmu masuk ke layar dapur lengkap dengan catatannya.</p></div></li>
                        <li><span class="step-n">3</span><div><p class="step-t">Ambil di toko</p><p class="step-d">Datang saat kue sudah siap, lalu bawa pulang.</p></div></li>
                    </ol>
                </div>
            </section>

            <section class="wrap sec" id="dapur">
                <div class="sec-head rv">
                    <h2 class="h2">Dari dapur ke etalase</h2>
                    <p class="sec-note">Adonan dibentuk, dipanggang, lalu disusun di etalase untuk hari itu.</p>
                </div>
                <div class="gallery">
                    <figure class="big rv">
                        <div class="shot"><img src="{{ $img('adonan') }}" alt="Adonan dibentuk di dapur" loading="lazy" width="1264" height="848"></div>
                        <figcaption>Adonan dibentuk di dapur</figcaption>
                    </figure>
                    <figure class="small rv" style="--d: .1s">
                        <div class="shot"><img src="{{ $img('oven') }}" alt="Roti keluar dari oven" loading="lazy" width="1264" height="848"></div>
                        <figcaption>Roti keluar dari oven</figcaption>
                    </figure>
                    <figure class="small rv" style="--d: .2s">
                        <div class="shot"><img src="{{ $img('etalase') }}" alt="Etalase terisi untuk hari ini" loading="lazy" width="1264" height="848"></div>
                        <figcaption>Etalase terisi untuk hari ini</figcaption>
                    </figure>
                </div>
            </section>

            <section class="wrap sec" id="buka">
                <div class="hours rv">
                    <div class="hours-card">
                        <p class="hours-time">06.00&ndash;21.00</p>
                        <div class="hours-foot">
                            <p class="open" data-open>Toko tutup</p>
                            <p>Buka setiap hari. Roti paling hangat biasanya ada di pagi hari.</p>
                        </div>
                    </div>
                    <div class="week">
                        <h3>Jadwal mingguan</h3>
                        <ul id="week">
                            @foreach (['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'] as $i => $hari)
                                <li data-day="{{ ($i + 1) % 7 }}">{{ $hari }} <span>06.00 &ndash; 21.00</span></li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </section>

            <section class="wrap sec" id="faq">
                <div class="faq">
                    <div class="rv">
                        <h2 class="h2">Pertanyaan yang sering ditanyakan</h2>
                        <p class="faq-note">Belum ketemu jawabannya? Tanyakan langsung ke kasir saat mampir ke toko.</p>
                    </div>
                    <div class="rv" style="--d: .1s">
                        @php
                            $faq = [
                                ['Bagaimana cara memesan kue ulang tahun?', 'Sampaikan ukuran, rasa, dan tulisan di atas kue langsung ke kasir. Pesananmu dicatat lalu diteruskan ke dapur.' . ($hargaKustom ? ' Harga mulai ' . $rp($hargaKustom) . '.' : '')],
                                ['Apakah semua menu selalu tersedia?', 'Tidak selalu. Daftar menu mengikuti etalase toko, jadi menu yang sedang habis ditandai di daftar.'],
                                ['Jam berapa toko buka?', 'Setiap hari pukul 06.00 sampai 21.00.'],
                                ['Berapa kisaran harganya?', 'Menu etalase mulai dari ' . ($hargaMin ? $rp($hargaMin) : 'harga yang tertera di daftar menu') . '.' . ($hargaKustom ? ' Kue ulang tahun kustom mulai dari ' . $rp($hargaKustom) . '.' : '')],
                                ['Kapan kue kustomku bisa diambil?', 'Pesananmu masuk ke layar dapur lengkap dengan catatannya. Tanyakan perkiraan waktunya ke kasir saat memesan.'],
                            ];
                        @endphp
                        @foreach ($faq as $i => [$tanya, $jawab])
                            <div class="qa {{ $i === 0 ? 'is-open' : '' }}">
                                <button type="button" class="qa-q" aria-expanded="{{ $i === 0 ? 'true' : 'false' }}" aria-controls="qa-{{ $i }}">
                                    <span>{{ $tanya }}</span><span class="qa-ico" aria-hidden="true"></span>
                                </button>
                                <div class="qa-a" id="qa-{{ $i }}"><div><p>{{ $jawab }}</p></div></div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </section>

            <section class="wrap sec">
                <div class="cta-band rv">
                    <div>
                        <h2>Mampir pagi ini,<br>rotinya masih hangat.</h2>
                        <p>Buka setiap hari pukul 06.00 &ndash; 21.00. Pilih dari etalase atau pesan kue ulang tahun sesuai keinginanmu.</p>
                        <div class="cta-btns">
                            <a href="#menu" class="btn-solid">Lihat menu <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12h14M13 6l6 6-6 6"/></svg></a>
                            <a href="#kustom" class="btn-line">Pesan kue kustom</a>
                        </div>
                    </div>
                    <div class="cta-photo"><img src="{{ $img('roti-banner') }}" alt="Roti baru keluar dari oven di atas meja kayu" loading="lazy" width="1264" height="848"></div>
                </div>
            </section>
        </main>

        <footer class="foot">
            <div class="wrap">
                <div class="foot-top">
                    <div>
                        <a href="{{ route('landing') }}" class="brand"><span class="mark" aria-hidden="true"></span>Kandekande</a>
                        <p class="foot-about">Toko roti dan kue di sudut jalan. Roti hangat setiap pagi, kue ulang tahun sesuai pesananmu.</p>
                    </div>
                    <div class="foot-cols">
                        <div class="foot-col">
                            <h4>Jelajahi</h4>
                            <ul>
                                <li><a href="#menu">Menu</a></li>
                                <li><a href="#keunggulan">Keunggulan</a></li>
                                <li><a href="#kustom">Kue kustom</a></li>
                                <li><a href="#buka">Jam buka</a></li>
                                <li><a href="#faq">FAQ</a></li>
                            </ul>
                        </div>
                        <div class="foot-col">
                            <h4>Toko</h4>
                            <ul>
                                <li>Buka setiap hari</li>
                                <li>06.00 &ndash; 21.00</li>
                                @if ($hargaKustom)<li>Kue kustom mulai {{ $rp($hargaKustom) }}</li>@endif
                            </ul>
                        </div>
                        <div class="foot-col">
                            <h4>Staf</h4>
                            <ul>
                                <li><a href="{{ route('login') }}">Masuk staf</a></li>
                                <li><a href="{{ route('dashboard') }}">Dashboard</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <p class="foot-word" aria-hidden="true"><span id="wordmark">Kandekande</span></p>
                <div class="foot-row">
                    <span>&copy; {{ now()->year }} Kandekande, roti dan kue.</span>
                    @auth
                        <a href="{{ route('dashboard') }}">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}">Masuk staf</a>
                    @endauth
                </div>
            </div>
        </footer>

        <script>
            function syncMode() {
                var dark = document.documentElement.classList.contains('dark');
                document.getElementById('mode-btn').setAttribute('aria-checked', dark ? 'true' : 'false');
            }
            function toggleTheme() {
                var dark = !document.documentElement.classList.contains('dark');
                document.documentElement.classList.toggle('dark', dark);
                try { localStorage.setItem('theme_guest', dark ? 'dark' : 'light'); localStorage.setItem('theme', dark ? 'dark' : 'light'); } catch (e) {}
                syncMode();
            }
            syncMode();

            // status buka / tutup dan hari ini di jadwal, mengikuti jam perangkat
            (function () {
                var els = document.querySelectorAll('[data-open]');
                var days = document.querySelectorAll('#week li');
                function tick() {
                    var now = new Date(), h = now.getHours(), open = h >= 6 && h < 21;
                    els.forEach(function (el) {
                        el.classList.toggle('is-open', open);
                        el.textContent = open ? 'Toko sedang buka, tutup pukul 21.00' : 'Toko sedang tutup, buka pukul 06.00';
                    });
                    days.forEach(function (li) { li.classList.toggle('is-today', +li.dataset.day === now.getDay()); });
                }
                tick(); setInterval(tick, 60000);
            })();

            // filter kategori menu
            (function () {
                var tabs = document.querySelectorAll('.tab');
                var cards = document.querySelectorAll('#menu-grid .card');
                tabs.forEach(function (tab) {
                    tab.addEventListener('click', function () {
                        var f = tab.dataset.filter;
                        tabs.forEach(function (t) { var on = t === tab; t.classList.toggle('is-on', on); t.setAttribute('aria-selected', on ? 'true' : 'false'); });
                        cards.forEach(function (c) { c.hidden = !(f === 'all' || c.dataset.cat === f); });
                    });
                });
            })();

            // FAQ: satu pertanyaan terbuka dalam satu waktu
            (function () {
                var items = document.querySelectorAll('.qa');
                items.forEach(function (item) {
                    item.querySelector('.qa-q').addEventListener('click', function () {
                        var wasOpen = item.classList.contains('is-open');
                        items.forEach(function (o) { o.classList.remove('is-open'); o.querySelector('.qa-q').setAttribute('aria-expanded', 'false'); });
                        if (!wasOpen) { item.classList.add('is-open'); item.querySelector('.qa-q').setAttribute('aria-expanded', 'true'); }
                    });
                });
            })();

            // wordmark footer selalu pas selebar kolom, apa pun font yang terpasang
            (function () {
                var span = document.getElementById('wordmark');
                if (!span) return;
                var box = span.parentElement;
                function fit() {
                    box.style.fontSize = '';
                    var base = parseFloat(getComputedStyle(box).fontSize);
                    var w = span.getBoundingClientRect().width;
                    if (w > 0) box.style.fontSize = Math.floor(base * (box.clientWidth / w) * 0.985) + 'px';
                }
                fit();
                if (document.fonts && document.fonts.ready) document.fonts.ready.then(fit);
                window.addEventListener('resize', fit);
            })();

            // muncul halus saat masuk layar
            (function () {
                var items = document.querySelectorAll('.rv');
                if (!('IntersectionObserver' in window)) { items.forEach(function (el) { el.classList.add('in'); }); return; }
                var io = new IntersectionObserver(function (entries) {
                    entries.forEach(function (e) { if (e.isIntersecting) { e.target.classList.add('in'); io.unobserve(e.target); } });
                }, { threshold: .1, rootMargin: '0px 0px -5% 0px' });
                items.forEach(function (el) { io.observe(el); });
            })();
        </script>
    </body>
</html>
