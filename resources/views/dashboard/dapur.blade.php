<!-- DASHBOARD KHUSUS DAPUR (KITCHEN & PRODUKSI BAKER) -->
<div class="space-y-6">
    <!-- Hero Banner Aksi Dapur -->
    <div class="bg-gradient-to-r from-orange-600 via-amber-600 to-rose-600 rounded-3xl p-6 sm:p-8 text-white shadow-xl relative overflow-hidden">
        <div class="absolute -right-8 -bottom-10 opacity-10 pointer-events-none">
            <svg class="w-64 h-64 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17.657 18.657A8 8 0 016.343 7.343S7 9 9 10c0-2 .5-5 2.986-7C14 5 16.09 5.777 17.656 7.343A7.975 7.975 0 0120 13a7.975 7.975 0 01-2.343 5.657z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.879 16.121A3 3 0 1012.015 11L11 14H9c0 .768.293 1.536.879 2.121z"></path></svg>
        </div>
        <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-5">
            <div class="min-w-0 flex-1">
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-black bg-white/20 backdrop-blur-md text-white mb-2">
                    🍳 Kitchen Display & Bakery Hub
                </span>
                <h1 class="text-2xl sm:text-3xl font-black tracking-tight text-white">
                    Semangat Memanggang, Tim Dapur! 🍞
                </h1>
                <p class="text-amber-100 text-xs sm:text-sm mt-1 max-w-xl leading-relaxed">
                    Pantau pesanan kasir secara real-time dengan notifikasi suara melodis, kelola antrean masak, dan perbarui stok batch roti matang.
                </p>
            </div>
            <a href="{{ route('dapur.index') }}" 
               class="px-6 py-3.5 bg-white text-orange-700 hover:bg-orange-50 active:scale-95 font-black text-sm rounded-2xl shadow-xl hover:shadow-2xl transition-all flex items-center justify-center gap-2 self-start md:self-auto flex-shrink-0 whitespace-nowrap group">
                <svg class="w-5 h-5 text-orange-600 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                <span>Buka Layar Dapur Live (KDS) &rarr;</span>
            </a>
        </div>
    </div>

    <!-- 4 Kitchen Operational KPI Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
        <!-- 1. Menunggu Dapur -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm hover:shadow-md transition-all p-5 border border-gray-100 dark:border-gray-700/80 border-l-4 border-l-amber-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-[11px] font-bold uppercase tracking-wider text-amber-600 dark:text-amber-400 mb-1">Menunggu Persiapan</p>
                    <p class="text-2xl font-black text-gray-900 dark:text-white flex items-center gap-2">
                        {{ $antreanMenunggu }}
                        @if($antreanMenunggu > 0)
                        <span class="w-2.5 h-2.5 rounded-full bg-amber-500 animate-ping"></span>
                        @endif
                    </p>
                    <p class="text-[11px] text-gray-500 dark:text-gray-400 mt-1">Pesanan kasir baru</p>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-amber-50 dark:bg-amber-950/50 text-amber-600 dark:text-amber-400 flex items-center justify-center font-bold text-xl shadow-inner">
                    ⏳
                </div>
            </div>
        </div>

        <!-- 2. Sedang Disiapkan -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm hover:shadow-md transition-all p-5 border border-gray-100 dark:border-gray-700/80 border-l-4 border-l-blue-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-[11px] font-bold uppercase tracking-wider text-blue-600 dark:text-blue-400 mb-1">Sedang Diproses</p>
                    <p class="text-2xl font-black text-gray-900 dark:text-white">{{ $sedangDimasak }} <span class="text-sm font-normal text-gray-400">pesanan</span></p>
                    <p class="text-[11px] text-gray-500 dark:text-gray-400 mt-1">Dalam pengerjaan chef</p>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-blue-50 dark:bg-blue-950/50 text-blue-600 dark:text-blue-400 flex items-center justify-center font-bold text-xl shadow-inner">
                    👨‍🍳
                </div>
            </div>
        </div>

        <!-- 3. Selesai Hari Ini -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm hover:shadow-md transition-all p-5 border border-gray-100 dark:border-gray-700/80 border-l-4 border-l-emerald-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-[11px] font-bold uppercase tracking-wider text-emerald-600 dark:text-emerald-400 mb-1">Selesai Hari Ini</p>
                    <p class="text-2xl font-black text-gray-900 dark:text-white">{{ $siapSelesaiHariIni }} <span class="text-sm font-normal text-gray-400">pesanan</span></p>
                    <p class="text-[11px] text-gray-500 dark:text-gray-400 mt-1">Siap saji / diambil</p>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-emerald-50 dark:bg-emerald-950/50 text-emerald-600 dark:text-emerald-400 flex items-center justify-center font-bold text-xl shadow-inner">
                    ✅
                </div>
            </div>
        </div>

        <!-- 4. Batch Produksi Selesai -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm hover:shadow-md transition-all p-5 border border-gray-100 dark:border-gray-700/80 border-l-4 border-l-indigo-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-[11px] font-bold uppercase tracking-wider text-indigo-600 dark:text-indigo-400 mb-1">Produksi Batch Roti</p>
                    <p class="text-2xl font-black text-gray-900 dark:text-white">{{ $totalBatchProduksiHariIni }} <span class="text-sm font-normal text-gray-400">batch</span></p>
                    <p class="text-[11px] text-gray-500 dark:text-gray-400 mt-1">Dicatat hari ini</p>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-indigo-50 dark:bg-indigo-950/50 text-indigo-600 dark:text-indigo-400 flex items-center justify-center font-bold text-xl shadow-inner">
                    🥖
                </div>
            </div>
        </div>
    </div>

    <!-- Main Section: 2 Columns -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Antrean Pesanan Kasir Terkini -->
        <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-sm border border-gray-100 dark:border-gray-700 p-6 lg:col-span-2 transition-colors">
            <div class="flex items-center justify-between mb-5">
                <div>
                    <h3 class="text-base font-extrabold text-gray-800 dark:text-gray-100">Antrean Pesanan Kasir Masuk</h3>
                    <p class="text-xs text-gray-400 mt-0.5">Pesanan yang perlu disiapkan atau sedang dalam proses</p>
                </div>
                <a href="{{ route('dapur.index') }}" class="text-xs font-bold text-orange-600 dark:text-orange-400 hover:underline flex items-center gap-1">
                    Buka Dapur KDS &rarr;
                </a>
            </div>

            <div class="space-y-3">
                @forelse($pesananAktif as $pesanan)
                <div class="p-4 rounded-2xl bg-gray-50 dark:bg-gray-700/40 border border-gray-200 dark:border-gray-700 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                    <div class="space-y-1 min-w-0">
                        <div class="flex items-center gap-2">
                            <span class="px-2 py-0.5 rounded-lg font-mono font-black text-xs bg-indigo-100 dark:bg-indigo-900/50 text-indigo-700 dark:text-indigo-300">
                                #{{ $pesanan->id_transaksi }}
                            </span>
                            <span class="text-sm font-extrabold text-gray-900 dark:text-white truncate">
                                {{ $pesanan->nama_pelanggan ?: ($pesanan->pelanggan->nama_pelanggan ?? 'Umum') }}
                            </span>
                            <span class="text-xs text-gray-400">
                                ({{ \Carbon\Carbon::parse($pesanan->tanggal_waktu)->diffForHumans() }})
                            </span>
                        </div>
                        
                        <!-- Rincian Produk -->
                        <div class="text-xs text-gray-600 dark:text-gray-300 space-y-0.5 pt-1">
                            @foreach($pesanan->details as $d)
                                <div class="flex items-center gap-1.5">
                                    <span class="font-bold text-gray-900 dark:text-white">{{ $d->qty_beli }}x</span>
                                    <span>{{ $d->produk->nama_produk ?? 'Item' }}</span>
                                </div>
                            @endforeach
                        </div>

                        @if($pesanan->catatan_pesanan)
                        <div class="text-[11px] text-amber-700 dark:text-amber-300 bg-amber-50 dark:bg-amber-950/40 px-2 py-0.5 rounded-md inline-block font-semibold mt-1">
                            Catatan: {{ $pesanan->catatan_pesanan }}
                        </div>
                        @endif
                    </div>

                    <div class="flex items-center gap-3 self-end sm:self-center flex-shrink-0">
                        <span class="px-3 py-1 rounded-full text-xs font-black {{ $pesanan->status_pesanan == 'Menunggu Dapur' ? 'bg-amber-100 text-amber-800 dark:bg-amber-900/50 dark:text-amber-300' : 'bg-blue-100 text-blue-800 dark:bg-blue-900/50 dark:text-blue-300' }}">
                            {{ $pesanan->status_pesanan }}
                        </span>
                        <a href="{{ route('dapur.index') }}" class="px-3 py-1.5 bg-orange-600 hover:bg-orange-700 text-white font-bold text-xs rounded-xl shadow-sm transition-all">
                            Proses &rarr;
                        </a>
                    </div>
                </div>
                @empty
                <div class="text-center py-12 text-gray-400 text-xs">
                    <span class="text-3xl block mb-2">🎉</span>
                    Tidak ada antrean pesanan kasir aktif. Semua pesanan telah disajikan!
                </div>
                @endforelse
            </div>
        </div>

        <!-- Prioritas Produksi Roti Menipis -->
        <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-sm border border-gray-100 dark:border-gray-700 p-6 transition-colors">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h3 class="text-base font-extrabold text-gray-800 dark:text-gray-100">Prioritas Panggang Roti</h3>
                    <p class="text-xs text-gray-400 mt-0.5">Etalase roti yang hampir habis</p>
                </div>
                <span class="text-xs font-bold text-red-500">Perlu Diproduksi</span>
            </div>

            <div class="space-y-3">
                @forelse($produkMenipis as $p)
                <div class="p-3.5 rounded-2xl bg-red-50/60 dark:bg-red-950/20 border border-red-100 dark:border-red-900/40 flex items-center justify-between">
                    <div class="min-w-0 pr-2">
                        <p class="font-bold text-xs text-gray-900 dark:text-gray-100 truncate">{{ $p->nama_produk }}</p>
                        <p class="text-[11px] text-red-600 dark:text-red-400 font-semibold mt-0.5">
                            Sisa di rak: <strong>{{ $p->stok_etalase }} pcs</strong> (Batas: {{ $p->stok_minimum }})
                        </p>
                    </div>
                    <a href="{{ route('dapur.index') }}" class="px-2.5 py-1.5 bg-orange-600 hover:bg-orange-700 text-white font-bold text-xs rounded-xl shadow-sm transition-all whitespace-nowrap">
                        🔥 Buat Batch
                    </a>
                </div>
                @empty
                <div class="p-6 text-center rounded-2xl bg-emerald-50/50 dark:bg-emerald-950/20 border border-emerald-100 dark:border-emerald-900/30">
                    <span class="text-2xl">🥐</span>
                    <p class="text-xs font-bold text-emerald-800 dark:text-emerald-300 mt-1">Stok Roti Cukup</p>
                    <p class="text-[11px] text-emerald-600/70 dark:text-emerald-400/70 mt-0.5">Semua rak etalase terisi penuh di atas batas aman.</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
