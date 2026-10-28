<!-- DASHBOARD KHUSUS OWNER -->
@php
    $perluTindakan = count($bahanMenipis) + count($produkMenipis);
    $terlarisMax = max(1, (int) ($produkTerlaris->max('total_terjual') ?? 1));
    \Carbon\Carbon::setLocale('id');
@endphp

<div class="space-y-10">
    <!-- Sapaan -->
    <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-5">
        <div class="min-w-0">
            <p class="text-sm text-gray-500 dark:text-gray-400">{{ \Carbon\Carbon::now()->translatedFormat('l, j F Y') }}</p>
            <h1 class="mt-1 font-display text-4xl sm:text-5xl font-extrabold tracking-tight leading-[1.05] text-gray-900 dark:text-gray-50 break-words">
                Halo, {{ Auth::user()->name }}
            </h1>
        </div>
        <div class="flex items-center gap-2 flex-shrink-0">
            <a href="{{ route('laporan.index') }}" class="inline-flex items-center gap-2 rounded-full bg-gray-900 dark:bg-gray-100 text-gray-50 dark:text-gray-900 h-[2.625rem] px-5 text-sm font-semibold hover:bg-indigo-600 dark:hover:bg-indigo-400 dark:hover:text-gray-900 transition-colors">
                Laporan lengkap
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 12h14m-6-6l6 6-6 6"/></svg>
            </a>
            <a href="{{ route('pelanggan.index') }}" class="inline-flex items-center rounded-full border border-gray-300 dark:border-gray-700 h-[2.625rem] px-5 text-sm font-semibold text-gray-700 dark:text-gray-200 hover:border-gray-900 dark:hover:border-gray-300 transition-colors">
                Pelanggan
            </a>
        </div>
    </div>

    <!-- Angka utama -->
    <dl class="grid grid-cols-2 lg:grid-cols-4 rounded-xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-800 divide-x divide-y lg:divide-y-0 divide-gray-200 dark:divide-gray-700 overflow-hidden">
        <div class="p-5 sm:p-6">
            <dt class="text-sm text-gray-500 dark:text-gray-400">Pendapatan hari ini</dt>
            <dd class="mt-2 font-display text-2xl sm:text-3xl font-extrabold tabular-nums text-gray-900 dark:text-gray-50">
                <span class="text-base font-semibold text-gray-400 mr-0.5">Rp</span>{{ number_format($pendapatanHariIni, 0, ',', '.') }}
            </dd>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ $pesananHariIni }} transaksi masuk</p>
        </div>
        <div class="p-5 sm:p-6">
            <dt class="text-sm text-gray-500 dark:text-gray-400">Omset bulan ini</dt>
            <dd class="mt-2 font-display text-2xl sm:text-3xl font-extrabold tabular-nums text-gray-900 dark:text-gray-50">
                <span class="text-base font-semibold text-gray-400 mr-0.5">Rp</span>{{ number_format($pendapatanBulanIni, 0, ',', '.') }}
            </dd>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ \Carbon\Carbon::now()->translatedFormat('F Y') }}</p>
        </div>
        <div class="p-5 sm:p-6">
            <dt class="text-sm text-gray-500 dark:text-gray-400">Antrean dapur</dt>
            <dd class="mt-2 font-display text-2xl sm:text-3xl font-extrabold tabular-nums text-gray-900 dark:text-gray-50">
                {{ $pesananDapurAktif }}<span class="ml-1.5 text-base font-semibold text-gray-400">pesanan</span>
            </dd>
            <a href="{{ route('dapur.index') }}" class="mt-1 inline-block text-sm font-semibold text-indigo-600 dark:text-indigo-400 hover:underline underline-offset-4">Buka dapur</a>
        </div>
        <div class="p-5 sm:p-6">
            <dt class="text-sm text-gray-500 dark:text-gray-400">Pelanggan terdaftar</dt>
            <dd class="mt-2 font-display text-2xl sm:text-3xl font-extrabold tabular-nums text-gray-900 dark:text-gray-50">
                {{ $totalPelanggan }}<span class="ml-1.5 text-base font-semibold text-gray-400">orang</span>
            </dd>
            <a href="{{ route('pelanggan.index') }}" class="mt-1 inline-block text-sm font-semibold text-indigo-600 dark:text-indigo-400 hover:underline underline-offset-4">Lihat daftar</a>
        </div>
    </dl>

    <!-- Stok menipis -->
    @if($perluTindakan > 0)
        <section>
            <div class="flex items-baseline justify-between gap-4 mb-3">
                <h2 class="font-display text-xl font-extrabold text-gray-900 dark:text-gray-50">
                    Perlu tindakan <span class="ml-1 text-base font-semibold text-red-600 dark:text-red-400">{{ $perluTindakan }}</span>
                </h2>
                <p class="text-sm text-gray-500 dark:text-gray-400 hidden sm:block">Stok di bawah batas minimum</p>
            </div>
            <ul class="rounded-xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700 max-h-80 overflow-y-auto custom-scrollbar">
                @foreach($bahanMenipis as $bahan)
                    <li class="flex items-center justify-between gap-4 px-5 py-3.5">
                        <div class="min-w-0">
                            <p class="font-semibold text-gray-900 dark:text-gray-100 truncate">{{ $bahan->nama_bahan }}</p>
                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                Bahan baku · sisa <span class="font-semibold text-red-600 dark:text-red-400 tabular-nums">{{ $bahan->jumlah_stok }} {{ $bahan->satuan }}</span>, minimum {{ $bahan->stok_minimum }}
                            </p>
                        </div>
                        <a href="{{ route('restock.index') }}" class="flex-shrink-0 text-sm font-semibold text-indigo-600 dark:text-indigo-400 hover:underline underline-offset-4">Restock</a>
                    </li>
                @endforeach
                @foreach($produkMenipis as $produk)
                    <li class="flex items-center justify-between gap-4 px-5 py-3.5">
                        <div class="min-w-0">
                            <p class="font-semibold text-gray-900 dark:text-gray-100 truncate">{{ $produk->nama_produk }}</p>
                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                Etalase · sisa <span class="font-semibold text-amber-600 dark:text-amber-400 tabular-nums">{{ $produk->stok_etalase }} pcs</span>, minimum {{ $produk->stok_minimum }}
                            </p>
                        </div>
                        <a href="{{ route('dapur.index') }}" class="flex-shrink-0 text-sm font-semibold text-indigo-600 dark:text-indigo-400 hover:underline underline-offset-4">Produksi</a>
                    </li>
                @endforeach
            </ul>
        </section>
    @endif

    <!-- Tren & terlaris -->
    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
        <section class="xl:col-span-2 rounded-xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-800 p-5 sm:p-6">
            <div class="mb-5">
                <h2 class="font-display text-xl font-extrabold text-gray-900 dark:text-gray-50">Penjualan 7 hari terakhir</h2>
                <p class="text-sm text-gray-500 dark:text-gray-400">Omset harian toko</p>
            </div>
            <div class="relative h-72 w-full">
                <canvas id="salesChart"></canvas>
            </div>
        </section>

        <section class="rounded-xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-800 p-5 sm:p-6">
            <div class="mb-5">
                <h2 class="font-display text-xl font-extrabold text-gray-900 dark:text-gray-50">Menu terlaris</h2>
                <p class="text-sm text-gray-500 dark:text-gray-400">Lima teratas</p>
            </div>
            <ol class="space-y-4">
                @forelse($produkTerlaris as $index => $item)
                    <li>
                        <div class="flex items-baseline justify-between gap-3">
                            <p class="min-w-0 truncate font-semibold text-gray-900 dark:text-gray-100">
                                <span class="mr-2 text-gray-400 tabular-nums">{{ $index + 1 }}</span>{{ $item->produk->nama_produk ?? 'Produk dihapus' }}
                            </p>
                            <span class="flex-shrink-0 text-sm font-semibold tabular-nums text-gray-700 dark:text-gray-300">{{ $item->total_terjual }} pcs</span>
                        </div>
                        <div class="mt-1.5 h-1.5 rounded-full bg-gray-100 dark:bg-gray-700 overflow-hidden">
                            <div class="h-full rounded-full bg-indigo-600 dark:bg-indigo-400" style="width: {{ round(($item->total_terjual / $terlarisMax) * 100) }}%"></div>
                        </div>
                        <p class="mt-1 text-xs text-gray-400">{{ $item->produk->kategori->nama_kategori ?? 'Umum' }}</p>
                    </li>
                @empty
                    <li class="py-10 text-center text-sm text-gray-400">Belum ada penjualan tercatat.</li>
                @endforelse
            </ol>
        </section>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const canvas = document.getElementById('salesChart');
        if (!canvas) return;
        const ctx = canvas.getContext('2d');

        const labels = {!! json_encode($grafikTanggal) !!};
        const data = {!! json_encode($grafikPendapatan) !!};

        const palette = () => {
            const dark = document.documentElement.classList.contains('dark');
            return {
                line: dark ? '#F08A57' : '#D4501E',
                fill: dark ? 'rgba(240,138,87,.14)' : 'rgba(212,80,30,.10)',
                grid: dark ? 'rgba(243,238,231,.08)' : 'rgba(27,22,18,.07)',
                tick: dark ? '#A39A91' : '#7D756C',
            };
        };

        const c = palette();
        const chart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Pendapatan (Rp)',
                    data: data,
                    borderColor: c.line,
                    backgroundColor: c.fill,
                    borderWidth: 2.5,
                    tension: 0.3,
                    fill: true,
                    pointRadius: 0,
                    pointHoverRadius: 5,
                    pointHoverBackgroundColor: c.line,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: { mode: 'index', intersect: false },
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        displayColors: false,
                        callbacks: {
                            label: function (context) {
                                return 'Rp ' + (context.parsed.y || 0).toLocaleString('id-ID');
                            }
                        }
                    }
                },
                scales: {
                    x: { grid: { display: false }, border: { display: false }, ticks: { color: c.tick } },
                    y: {
                        beginAtZero: true,
                        suggestedMax: data.some(v => v > 0) ? undefined : 100000,
                        border: { display: false },
                        grid: { color: c.grid },
                        ticks: {
                            color: c.tick,
                            maxTicksLimit: 5,
                            callback: function (value) {
                                if (value >= 1000000) return 'Rp ' + (value / 1000000) + ' jt';
                                if (value >= 1000) return 'Rp ' + (value / 1000) + ' rb';
                                return 'Rp ' + value;
                            }
                        }
                    }
                }
            }
        });

        window.addEventListener('theme-changed', function () {
            const p = palette();
            const ds = chart.data.datasets[0];
            ds.borderColor = p.line; ds.backgroundColor = p.fill; ds.pointHoverBackgroundColor = p.line;
            chart.options.scales.x.ticks.color = p.tick;
            chart.options.scales.y.ticks.color = p.tick;
            chart.options.scales.y.grid.color = p.grid;
            chart.update();
        });
    });
</script>
