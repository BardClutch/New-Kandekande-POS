<!-- DASHBOARD KHUSUS KASIR (POS & SHIFT PENJUALAN) -->
<div class="space-y-6">
    <!-- Hero Banner Aksi Kasir -->
    <div class="bg-gradient-to-r from-emerald-600 via-teal-600 to-indigo-600 rounded-3xl p-6 sm:p-8 text-white shadow-xl relative overflow-hidden">
        <div class="absolute -right-8 -bottom-10 opacity-10 pointer-events-none">
            <svg class="w-64 h-64 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
        </div>
        <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-5">
            <div class="min-w-0 flex-1">
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-black bg-white/20 backdrop-blur-md text-white mb-2">
                    💼 Shift Kasir Aktif
                </span>
                <h1 class="text-2xl sm:text-3xl font-black tracking-tight text-white">
                    Halo, {{ Auth::user()->name }}! 👋
                </h1>
                <p class="text-emerald-100 text-xs sm:text-sm mt-1 max-w-xl leading-relaxed">
                    Siap melayani transaksi pelanggan hari ini? Pantau aktivitas pembayaran, cetak ulang struk, dan cek stok etalase.
                </p>
            </div>
            <a href="{{ route('kasir.index') }}" 
               class="px-6 py-3.5 bg-white text-emerald-700 hover:bg-emerald-50 active:scale-95 font-black text-sm rounded-2xl shadow-xl hover:shadow-2xl transition-all flex items-center justify-center gap-2 self-start md:self-auto flex-shrink-0 whitespace-nowrap group">
                <svg class="w-5 h-5 text-emerald-600 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                <span>Buka Layar Kasir (POS) &rarr;</span>
            </a>
        </div>
    </div>

    <!-- 4 Kasir Operational KPI Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
        <!-- 1. Struk Dilayani Kasir Ini -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm hover:shadow-md transition-all p-5 border border-gray-100 dark:border-gray-700/80 border-l-4 border-l-emerald-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-[11px] font-bold uppercase tracking-wider text-emerald-600 dark:text-emerald-400 mb-1">Struk Anda Hari Ini</p>
                    <p class="text-2xl font-black text-gray-900 dark:text-white">{{ $transaksiKasirHariIni }} <span class="text-sm font-normal text-gray-400">transaksi</span></p>
                    <p class="text-[11px] text-gray-500 dark:text-gray-400 mt-1">Dilayani shift Anda</p>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-emerald-50 dark:bg-emerald-950/50 text-emerald-600 dark:text-emerald-400 flex items-center justify-center font-bold text-xl shadow-inner">
                    🧾
                </div>
            </div>
        </div>

        <!-- 2. Roti/Item Terjual -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm hover:shadow-md transition-all p-5 border border-gray-100 dark:border-gray-700/80 border-l-4 border-l-blue-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-[11px] font-bold uppercase tracking-wider text-blue-600 dark:text-blue-400 mb-1">Total Item Terjual</p>
                    <p class="text-2xl font-black text-gray-900 dark:text-white">{{ number_format($totalItemTerjual, 0, ',', '.') }} <span class="text-sm font-normal text-gray-400">pcs</span></p>
                    <p class="text-[11px] text-gray-500 dark:text-gray-400 mt-1">Roti & kue terjual</p>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-blue-50 dark:bg-blue-950/50 text-blue-600 dark:text-blue-400 flex items-center justify-center font-bold text-xl shadow-inner">
                    🥖
                </div>
            </div>
        </div>

        <!-- 3. Rata-Rata Nilai Belanja -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm hover:shadow-md transition-all p-5 border border-gray-100 dark:border-gray-700/80 border-l-4 border-l-indigo-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-[11px] font-bold uppercase tracking-wider text-indigo-600 dark:text-indigo-400 mb-1">Rata-rata per Struk</p>
                    <p class="text-2xl font-black text-gray-900 dark:text-white">Rp {{ number_format($rataRataBelanja, 0, ',', '.') }}</p>
                    <p class="text-[11px] text-gray-500 dark:text-gray-400 mt-1">Basket size pelanggan</p>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-indigo-50 dark:bg-indigo-950/50 text-indigo-600 dark:text-indigo-400 flex items-center justify-center font-bold text-xl shadow-inner">
                    🧮
                </div>
            </div>
        </div>

        <!-- 4. Pesanan Siap Disajikan -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm hover:shadow-md transition-all p-5 border border-gray-100 dark:border-gray-700/80 border-l-4 border-l-amber-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-[11px] font-bold uppercase tracking-wider text-amber-600 dark:text-amber-400 mb-1">Pesanan Siap Diambil</p>
                    <p class="text-2xl font-black text-gray-900 dark:text-white">{{ $pesananSiapDisajikan }} <span class="text-sm font-normal text-gray-400">pesanan</span></p>
                    <p class="text-[11px] text-emerald-600 dark:text-emerald-400 font-bold mt-1">Dapur selesai siapkan</p>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-amber-50 dark:bg-amber-950/50 text-amber-600 dark:text-amber-400 flex items-center justify-center font-bold text-xl shadow-inner">
                    🛎️
                </div>
            </div>
        </div>
    </div>

    <!-- Main Section: 2 Columns -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- 5 Transaksi Terakhir yang Dilayani Kasir Ini -->
        <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-sm border border-gray-100 dark:border-gray-700 p-6 lg:col-span-2 transition-colors">
            <div class="flex items-center justify-between mb-5">
                <div>
                    <h3 class="text-base font-extrabold text-gray-800 dark:text-gray-100">Riwayat Transaksi Terakhir Anda</h3>
                    <p class="text-xs text-gray-400 mt-0.5">Struk belanja yang baru saja Anda proses di shift ini</p>
                </div>
                <a href="{{ route('kasir.index') }}" class="text-xs font-bold text-indigo-600 dark:text-indigo-400 hover:underline">
                    + Transaksi Baru
                </a>
            </div>

            <div class="overflow-x-auto rounded-2xl border border-gray-100 dark:border-gray-700">
                <table class="min-w-full divide-y divide-gray-100 dark:divide-gray-700 text-xs">
                    <thead class="bg-gray-50 dark:bg-gray-700/50 text-gray-500 dark:text-gray-300 font-bold uppercase tracking-wider">
                        <tr>
                            <th class="py-3 px-4 text-left">No. Struk</th>
                            <th class="py-3 px-4 text-left">Pelanggan</th>
                            <th class="py-3 px-4 text-center">Waktu</th>
                            <th class="py-3 px-4 text-center">Metode</th>
                            <th class="py-3 px-4 text-right">Total</th>
                            <th class="py-3 px-4 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700/60 bg-white dark:bg-gray-800">
                        @forelse($transaksiTerakhir as $trx)
                        <tr class="hover:bg-gray-50/60 dark:hover:bg-gray-700/40 transition-colors">
                            <td class="py-3 px-4 font-mono font-bold text-gray-900 dark:text-gray-100">
                                #{{ $trx->id_transaksi }}
                            </td>
                            <td class="py-3 px-4 font-medium text-gray-800 dark:text-gray-200">
                                {{ $trx->nama_pelanggan ?: ($trx->pelanggan->nama_pelanggan ?? 'Umum / Walk-in') }}
                            </td>
                            <td class="py-3 px-4 text-center text-gray-500 dark:text-gray-400">
                                {{ \Carbon\Carbon::parse($trx->tanggal_waktu)->format('H:i') }}
                            </td>
                            <td class="py-3 px-4 text-center">
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold {{ $trx->metode_pembayaran == 'Tunai' ? 'bg-emerald-100 dark:bg-emerald-900/40 text-emerald-700 dark:text-emerald-300' : 'bg-blue-100 dark:bg-blue-900/40 text-blue-700 dark:text-blue-300' }}">
                                    {{ $trx->metode_pembayaran }}
                                </span>
                            </td>
                            <td class="py-3 px-4 text-right font-black text-gray-900 dark:text-gray-100">
                                Rp {{ number_format($trx->total_belanja, 0, ',', '.') }}
                            </td>
                            <td class="py-3 px-4 text-center">
                                <a href="{{ route('kasir.cetak', $trx->id_transaksi) }}" 
                                   target="_blank"
                                   class="px-2.5 py-1 bg-gray-100 dark:bg-gray-700 hover:bg-indigo-50 dark:hover:bg-indigo-900/40 text-gray-700 dark:text-gray-200 hover:text-indigo-600 dark:hover:text-indigo-400 font-bold rounded-lg transition-colors inline-flex items-center gap-1">
                                    🖨️ Cetak
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="py-8 text-center text-gray-400 text-xs">
                                Belum ada transaksi yang Anda proses hari ini.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Cek Cepat Stok Roti di Etalase -->
        <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-sm border border-gray-100 dark:border-gray-700 p-6 transition-colors">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h3 class="text-base font-extrabold text-gray-800 dark:text-gray-100">Etalase Roti Menipis</h3>
                    <p class="text-xs text-gray-400 mt-0.5">Periksa stok sebelum menawarkan ke pembeli</p>
                </div>
                <span class="w-2.5 h-2.5 rounded-full bg-amber-500 animate-pulse" title="Live update"></span>
            </div>

            <div class="space-y-3">
                @forelse($produkMenipis as $p)
                <div class="p-3 rounded-2xl bg-amber-50/60 dark:bg-amber-950/20 border border-amber-100 dark:border-amber-900/40 flex items-center justify-between">
                    <div class="min-w-0 pr-2">
                        <p class="font-bold text-xs text-gray-900 dark:text-gray-100 truncate">{{ $p->nama_produk }}</p>
                        <p class="text-[10px] text-amber-700 dark:text-amber-400 font-semibold">
                            Batas aman: min {{ $p->stok_minimum }} pcs
                        </p>
                    </div>
                    <span class="px-2.5 py-1 rounded-xl text-xs font-black {{ $p->stok_etalase <= 0 ? 'bg-red-100 text-red-700 dark:bg-red-900/50 dark:text-red-300' : 'bg-amber-200 text-amber-800 dark:bg-amber-900/50 dark:text-amber-300' }} whitespace-nowrap">
                        {{ $p->stok_etalase <= 0 ? 'HABIS' : 'Sisa ' . $p->stok_etalase . ' pcs' }}
                    </span>
                </div>
                @empty
                <div class="p-6 text-center rounded-2xl bg-emerald-50/50 dark:bg-emerald-950/20 border border-emerald-100 dark:border-emerald-900/30">
                    <span class="text-2xl">✨</span>
                    <p class="text-xs font-bold text-emerald-800 dark:text-emerald-300 mt-1">Stok Etalase Aman</p>
                    <p class="text-[11px] text-emerald-600/70 dark:text-emerald-400/70 mt-0.5">Semua roti dan kue tersedia cukup di rak etalase.</p>
                </div>
                @endforelse
            </div>

            <div class="mt-5 pt-4 border-t border-gray-100 dark:border-gray-700 flex items-center justify-between text-xs text-gray-500 dark:text-gray-400">
                <span>Roti Siap Jual:</span>
                <strong class="text-gray-800 dark:text-gray-200 font-black">{{ $totalProdukReady }} jenis menu</strong>
            </div>
        </div>
    </div>
</div>
