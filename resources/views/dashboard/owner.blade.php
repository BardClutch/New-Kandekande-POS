<!-- DASHBOARD KHUSUS OWNER (SUPER ADMIN) -->
<div class="space-y-6">
    <!-- Header Greeting & Status -->
    <div class="bg-gradient-to-r from-indigo-700 via-indigo-600 to-purple-600 rounded-3xl p-6 sm:p-8 text-white shadow-xl relative overflow-hidden">
        <div class="absolute -right-8 -bottom-10 opacity-10 pointer-events-none">
            <svg class="w-64 h-64 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
        </div>
        <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div class="min-w-0 flex-1">
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-black bg-white/20 backdrop-blur-md text-white mb-2">
                    👑 Owner Control Center
                </span>
                <h1 class="text-2xl sm:text-3xl font-black tracking-tight text-white">
                    Halo, {{ Auth::user()->name }}! 👋
                </h1>
                <p class="text-indigo-100 text-xs sm:text-sm mt-1 max-w-xl leading-relaxed">
                    Ringkasan performa finansial, tren penjualan, dan pemantauan menyeluruh toko bakery Anda hari ini.
                </p>
            </div>
            <div class="flex items-center gap-2.5 self-start md:self-auto flex-shrink-0 flex-nowrap">
                <a href="{{ route('laporan.index') }}" class="px-4 py-2.5 bg-white text-indigo-700 hover:bg-indigo-50 font-extrabold text-xs rounded-2xl shadow-md transition-all flex items-center gap-1.5 whitespace-nowrap">
                    📊 Laporan Lengkap
                </a>
                <a href="{{ route('pelanggan.index') }}" class="px-4 py-2.5 bg-indigo-900/40 hover:bg-indigo-900/60 text-white font-extrabold text-xs rounded-2xl border border-white/20 backdrop-blur-md transition-all flex items-center gap-1.5 whitespace-nowrap">
                    👥 Daya Pelanggan
                </a>
            </div>
        </div>
    </div>

    <!-- Alert Stok Menipis -->
    @if(count($bahanMenipis) > 0 || count($produkMenipis) > 0)
        <div class="bg-amber-50 dark:bg-amber-950/30 border-l-4 border-amber-500 p-5 rounded-2xl shadow-sm transition-colors duration-300">
            <div class="flex items-start gap-4">
                <div class="p-2.5 rounded-xl bg-amber-500/10 text-amber-600 dark:text-amber-400 flex-shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                </div>
                <div class="flex-1">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 mb-2">
                        <div>
                            <h4 class="font-extrabold text-amber-900 dark:text-amber-200 text-sm sm:text-base">⚠️ Peringatan Stok Menipis (Di Bawah Batas Aman)</h4>
                            <p class="text-xs text-amber-700 dark:text-amber-300 mt-0.5">Terdapat bahan baku atau etalase produk yang perlu diisi ulang / diproduksi.</p>
                        </div>
                        <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-amber-200/60 dark:bg-amber-900/60 text-amber-800 dark:text-amber-200 self-start sm:self-auto">
                            {{ count($bahanMenipis) + count($produkMenipis) }} Item Perlu Tindakan
                        </span>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3 mt-4">
                        @foreach($bahanMenipis as $bahan)
                        <div class="bg-white dark:bg-gray-800 p-3.5 rounded-xl border border-amber-200/80 dark:border-amber-800/50 flex items-center justify-between shadow-sm">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-lg bg-red-100 dark:bg-red-900/40 text-red-600 dark:text-red-400 flex items-center justify-center font-bold text-xs flex-shrink-0">
                                    Bahan
                                </div>
                                <div>
                                    <p class="font-bold text-sm text-gray-800 dark:text-gray-100">{{ $bahan->nama_bahan }}</p>
                                    <p class="text-xs text-red-600 dark:text-red-400 font-semibold">
                                        Sisa: {{ $bahan->jumlah_stok }} {{ $bahan->satuan }} <span class="text-gray-400 font-normal">(Min: {{ $bahan->stok_minimum }})</span>
                                    </p>
                                </div>
                            </div>
                            <a href="{{ route('restock.index') }}" class="px-3 py-1.5 rounded-lg bg-indigo-50 dark:bg-indigo-900/40 text-indigo-600 dark:text-indigo-400 hover:bg-indigo-100 dark:hover:bg-indigo-800/60 text-xs font-bold transition-colors flex items-center gap-1 border border-indigo-200 dark:border-indigo-800">
                                + Restock
                            </a>
                        </div>
                        @endforeach

                        @foreach($produkMenipis as $produk)
                        <div class="bg-white dark:bg-gray-800 p-3.5 rounded-xl border border-amber-200/80 dark:border-amber-800/50 flex items-center justify-between shadow-sm">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-lg bg-amber-100 dark:bg-amber-900/40 text-amber-600 dark:text-amber-400 flex items-center justify-center font-bold text-xs flex-shrink-0">
                                    Roti
                                </div>
                                <div>
                                    <p class="font-bold text-sm text-gray-800 dark:text-gray-100">{{ $produk->nama_produk }}</p>
                                    <p class="text-xs text-amber-600 dark:text-amber-400 font-semibold">
                                        Sisa: {{ $produk->stok_etalase }} pcs <span class="text-gray-400 font-normal">(Min: {{ $produk->stok_minimum }})</span>
                                    </p>
                                </div>
                            </div>
                            <a href="{{ route('dapur.index') }}" class="px-3 py-1.5 rounded-lg bg-orange-50 dark:bg-orange-900/40 text-orange-600 dark:text-orange-400 hover:bg-orange-100 dark:hover:bg-orange-800/60 text-xs font-bold transition-colors flex items-center gap-1 border border-orange-200 dark:border-orange-800">
                                🔥 Produksi
                            </a>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- 4 KPI Overview Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
        <!-- 1. Pendapatan Hari Ini -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm hover:shadow-md transition-all p-5 border border-gray-100 dark:border-gray-700/80 border-l-4 border-l-emerald-500 relative overflow-hidden">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-[11px] font-bold uppercase tracking-wider text-emerald-600 dark:text-emerald-400 mb-1">Pendapatan Hari Ini</p>
                    <p class="text-2xl font-black text-gray-900 dark:text-white">Rp {{ number_format($pendapatanHariIni, 0, ',', '.') }}</p>
                    <p class="text-[11px] text-gray-500 dark:text-gray-400 mt-1">{{ $pesananHariIni }} transaksi masuk</p>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-emerald-50 dark:bg-emerald-950/50 text-emerald-600 dark:text-emerald-400 flex items-center justify-center font-bold text-xl shadow-inner">
                    💰
                </div>
            </div>
        </div>

        <!-- 2. Pendapatan Bulan Ini -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm hover:shadow-md transition-all p-5 border border-gray-100 dark:border-gray-700/80 border-l-4 border-l-blue-500 relative overflow-hidden">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-[11px] font-bold uppercase tracking-wider text-blue-600 dark:text-blue-400 mb-1">Omset Bulan Ini</p>
                    <p class="text-2xl font-black text-gray-900 dark:text-white">Rp {{ number_format($pendapatanBulanIni, 0, ',', '.') }}</p>
                    <p class="text-[11px] text-gray-500 dark:text-gray-400 mt-1">Bulan {{ date('F Y') }}</p>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-blue-50 dark:bg-blue-950/50 text-blue-600 dark:text-blue-400 flex items-center justify-center font-bold text-xl shadow-inner">
                    📈
                </div>
            </div>
        </div>

        <!-- 3. Antrean Dapur Aktif -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm hover:shadow-md transition-all p-5 border border-gray-100 dark:border-gray-700/80 border-l-4 border-l-amber-500 relative overflow-hidden">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-[11px] font-bold uppercase tracking-wider text-amber-600 dark:text-amber-400 mb-1">Antrean Dapur Aktif</p>
                    <p class="text-2xl font-black text-gray-900 dark:text-white">{{ $pesananDapurAktif }} <span class="text-sm font-normal text-gray-400">pesanan</span></p>
                    <a href="{{ route('dapur.index') }}" class="text-[11px] font-bold text-indigo-600 dark:text-indigo-400 hover:underline mt-1 inline-block">Lihat Live Dapur &rarr;</a>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-amber-50 dark:bg-amber-950/50 text-amber-600 dark:text-amber-400 flex items-center justify-center font-bold text-xl shadow-inner">
                    🍳
                </div>
            </div>
        </div>

        <!-- 4. Total Pelanggan Terdaftar -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm hover:shadow-md transition-all p-5 border border-gray-100 dark:border-gray-700/80 border-l-4 border-l-purple-500 relative overflow-hidden">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-[11px] font-bold uppercase tracking-wider text-purple-600 dark:text-purple-400 mb-1">Database Pelanggan</p>
                    <p class="text-2xl font-black text-gray-900 dark:text-white">{{ $totalPelanggan }} <span class="text-sm font-normal text-gray-400">orang</span></p>
                    <a href="{{ route('pelanggan.index') }}" class="text-[11px] font-bold text-indigo-600 dark:text-indigo-400 hover:underline mt-1 inline-block">Lihat Daya Beli &rarr;</a>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-purple-50 dark:bg-purple-950/50 text-purple-600 dark:text-purple-400 flex items-center justify-center font-bold text-xl shadow-inner">
                    👥
                </div>
            </div>
        </div>
    </div>

    <!-- Charts & Best Sellers -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Grafik Penjualan 7 Hari Terakhir -->
        <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-sm border border-gray-100 dark:border-gray-700 p-6 lg:col-span-2 transition-colors">
            <div class="flex justify-between items-center mb-6">
                <div>
                    <h3 class="text-base font-extrabold text-gray-800 dark:text-gray-100">Tren Penjualan (7 Hari Terakhir)</h3>
                    <p class="text-xs text-gray-400 mt-0.5">Grafik dinamika omset harian toko</p>
                </div>
                <span class="px-3 py-1 bg-indigo-50 dark:bg-indigo-900/40 text-indigo-600 dark:text-indigo-300 text-xs rounded-full font-bold">
                    Minggu Ini
                </span>
            </div>
            <div class="relative h-72 w-full">
                <canvas id="salesChart"></canvas>
            </div>
        </div>

        <!-- Produk Terlaris -->
        <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-sm border border-gray-100 dark:border-gray-700 p-6 transition-colors">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="text-base font-extrabold text-gray-800 dark:text-gray-100">🏆 Menu Terlaris</h3>
                    <p class="text-xs text-gray-400 mt-0.5">Paling diminati pelanggan</p>
                </div>
                <span class="text-xs font-bold text-indigo-600 dark:text-indigo-400">Top 5</span>
            </div>
            <div class="space-y-4">
                @forelse($produkTerlaris as $index => $item)
                <div class="flex items-center justify-between p-3 rounded-2xl hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-all border border-transparent hover:border-gray-200 dark:hover:border-gray-700">
                    <div class="flex items-center space-x-3 min-w-0">
                        <div class="w-8 h-8 rounded-xl bg-amber-100 dark:bg-amber-900/50 text-amber-700 dark:text-amber-400 flex items-center justify-center font-extrabold text-xs flex-shrink-0">
                            {{ $index + 1 }}
                        </div>
                        <div class="truncate">
                            <p class="font-bold text-sm text-gray-800 dark:text-gray-200 truncate">{{ $item->produk->nama_produk ?? 'Produk Dihapus' }}</p>
                            <p class="text-[11px] text-gray-400">{{ $item->produk->kategori->nama_kategori ?? 'Umum' }}</p>
                        </div>
                    </div>
                    <div class="bg-emerald-50 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-400 font-extrabold px-2.5 py-1 rounded-full text-xs border border-emerald-100 dark:border-emerald-800/50 whitespace-nowrap ml-2">
                        {{ $item->total_terjual }} pcs
                    </div>
                </div>
                @empty
                <div class="text-center py-12 text-gray-400 text-xs">
                    Belum ada data penjualan tercatat.
                </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

<!-- Chart.js Script for Owner -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const ctx = document.getElementById('salesChart')?.getContext('2d');
        if (!ctx) return;
        
        const labels = {!! json_encode($grafikTanggal) !!};
        const data = {!! json_encode($grafikPendapatan) !!};

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Pendapatan (Rp)',
                    data: data,
                    borderColor: '#4f46e5', // indigo-600
                    backgroundColor: 'rgba(79, 70, 229, 0.1)',
                    borderWidth: 3,
                    tension: 0.35,
                    fill: true,
                    pointBackgroundColor: '#4f46e5',
                    pointRadius: 4,
                    pointHoverRadius: 6,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return 'Rp ' + (context.parsed.y || 0).toLocaleString('id-ID');
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                if (value >= 1000000) return 'Rp ' + (value / 1000000) + 'jt';
                                if (value >= 1000) return 'Rp ' + (value / 1000) + 'rb';
                                return 'Rp ' + value;
                            }
                        }
                    }
                }
            }
        });
    });
</script>
