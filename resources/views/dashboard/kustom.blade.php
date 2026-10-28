<!-- DASHBOARD KHUSUS PERAN KUSTOM (GRANULAR ACCESS) -->
<div class="space-y-6">
    <!-- Hero Banner Aksi Peran Kustom -->
    <div class="bg-gradient-to-r from-indigo-700 via-purple-700 to-slate-900 rounded-3xl p-6 sm:p-8 text-white shadow-xl relative overflow-hidden">
        <div class="absolute -right-8 -bottom-10 opacity-10 pointer-events-none">
            <svg class="w-64 h-64 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
            </svg>
        </div>
        <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-5">
            <div class="min-w-0 flex-1">
                <div class="flex items-center gap-2 mb-2 flex-wrap">
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-black bg-white/20 backdrop-blur-md text-white">
                        🛡️ Peran Akses Kustom
                    </span>
                    <span class="text-xs text-purple-200">
                        Diberikan wewenang khusus oleh Owner
                    </span>
                </div>
                <h1 class="text-2xl sm:text-3xl font-black tracking-tight text-white">
                    Halo, {{ Auth::user()->name }}! 👋
                </h1>
                <p class="text-purple-200 text-xs sm:text-sm mt-1 max-w-2xl leading-relaxed">
                    Selamat datang di ruang kerja Anda. Dashboard ini menampilkan modul dan analitik yang disesuaikan secara khusus dengan hak akses akun Anda.
                </p>
            </div>
            
            <!-- Quick Actions Based on Permissions -->
            <div class="flex items-center gap-2.5 self-start md:self-auto flex-shrink-0 flex-wrap">
                @if(Auth::user()->hasPermission('kasir'))
                <a href="{{ route('kasir.index') }}" 
                   class="px-4 py-2.5 bg-emerald-500 hover:bg-emerald-600 active:scale-95 font-extrabold text-xs sm:text-sm text-white rounded-2xl shadow-lg transition-all flex items-center gap-2 whitespace-nowrap">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    <span>Kasir POS</span>
                </a>
                @endif

                @if(Auth::user()->hasPermission('dapur'))
                <a href="{{ route('dapur.index') }}" 
                   class="px-4 py-2.5 bg-orange-500 hover:bg-orange-600 active:scale-95 font-extrabold text-xs sm:text-sm text-white rounded-2xl shadow-lg transition-all flex items-center gap-2 whitespace-nowrap">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 18.657A8 8 0 016.343 7.343S7 9 9 10c0-2 .5-5 2.986-7C14 5 16.09 5.777 17.656 7.343A7.975 7.975 0 0120 13a7.975 7.975 0 01-2.343 5.657z"></path></svg>
                    <span>Antrean Dapur</span>
                </a>
                @endif

                @if(Auth::user()->hasPermission('bahanbaku'))
                <a href="{{ route('restock.index') }}" 
                   class="px-4 py-2.5 bg-white/10 hover:bg-white/20 text-white font-extrabold text-xs sm:text-sm rounded-2xl border border-white/20 backdrop-blur-md transition-all flex items-center gap-2 whitespace-nowrap">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    <span>Restock</span>
                </a>
                @endif

                @if(Auth::user()->hasPermission('laporan'))
                <a href="{{ route('laporan.index') }}" 
                   class="px-4 py-2.5 bg-white/10 hover:bg-white/20 text-white font-extrabold text-xs sm:text-sm rounded-2xl border border-white/20 backdrop-blur-md transition-all flex items-center gap-2 whitespace-nowrap">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                    <span>Laporan</span>
                </a>
                @endif
            </div>
        </div>
    </div>

    <!-- Overview Kartu Hak Akses Pengguna -->
    <div class="bg-white dark:bg-gray-800 rounded-2xl p-5 border border-gray-100 dark:border-gray-700/80 shadow-sm">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-3">
            <div>
                <h3 class="text-sm font-extrabold text-gray-900 dark:text-white flex items-center gap-2">
                    <span>🔑 Wewenang Modul yang Aktif</span>
                    <span class="px-2 py-0.5 rounded-full text-[11px] font-bold bg-purple-100 dark:bg-purple-900/40 text-purple-700 dark:text-purple-300">
                        {{ count(Auth::user()->permission_labels) }} Modul
                    </span>
                </h3>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">
                    Hanya modul di bawah ini yang dapat Anda akses dan operasikan dalam sistem.
                </p>
            </div>
        </div>

        @if(count(Auth::user()->permission_labels) > 0)
        <div class="flex flex-wrap gap-2 pt-1">
            @foreach(Auth::user()->permission_labels as $key => $label)
            <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-xl bg-purple-50 dark:bg-purple-950/40 border border-purple-200 dark:border-purple-800 text-purple-800 dark:text-purple-300 text-xs font-bold">
                <span class="w-1.5 h-1.5 rounded-full bg-purple-600 dark:bg-purple-400"></span>
                <span>{{ $label }}</span>
            </div>
            @endforeach
        </div>
        @else
        <div class="p-4 rounded-xl bg-amber-50 dark:bg-amber-950/30 border border-amber-200 dark:border-amber-800 text-amber-800 dark:text-amber-300 text-xs flex items-center gap-3">
            <svg class="w-5 h-5 flex-shrink-0 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
            <div>
                <strong>Perhatian:</strong> Belum ada hak akses modul yang ditugaskan ke akun Anda. Silakan hubungi <strong>Owner</strong> untuk mengaktifkan wewenang modul yang diperlukan.
            </div>
        </div>
        @endif
    </div>

    <!-- Dynamic Operational KPI Cards Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
        @if(Auth::user()->hasPermission('laporan'))
        <!-- KPI: Pendapatan Hari Ini -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm hover:shadow-md transition-all p-5 border border-gray-100 dark:border-gray-700/80 border-l-4 border-l-emerald-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-[11px] font-bold uppercase tracking-wider text-emerald-600 dark:text-emerald-400 mb-1">Pendapatan Hari Ini</p>
                    <p class="text-2xl font-black text-gray-900 dark:text-white">Rp {{ number_format($pendapatanHariIni ?? 0, 0, ',', '.') }}</p>
                    <p class="text-[11px] text-gray-500 dark:text-gray-400 mt-1">Bulan ini: Rp {{ number_format($pendapatanBulanIni ?? 0, 0, ',', '.') }}</p>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-emerald-50 dark:bg-emerald-950/50 text-emerald-600 dark:text-emerald-400 flex items-center justify-center font-bold text-xl shadow-inner">
                    💰
                </div>
            </div>
        </div>

        <!-- KPI: Total Pesanan Hari Ini -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm hover:shadow-md transition-all p-5 border border-gray-100 dark:border-gray-700/80 border-l-4 border-l-indigo-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-[11px] font-bold uppercase tracking-wider text-indigo-600 dark:text-indigo-400 mb-1">Total Pesanan Toko</p>
                    <p class="text-2xl font-black text-gray-900 dark:text-white">{{ $pesananHariIni ?? 0 }} <span class="text-sm font-normal text-gray-400">transaksi</span></p>
                    <p class="text-[11px] text-gray-500 dark:text-gray-400 mt-1">Hari ini</p>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-indigo-50 dark:bg-indigo-950/50 text-indigo-600 dark:text-indigo-400 flex items-center justify-center font-bold text-xl shadow-inner">
                    🧾
                </div>
            </div>
        </div>
        @endif

        @if(Auth::user()->hasPermission('kasir'))
        <!-- KPI: Transaksi Kasir Pengguna -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm hover:shadow-md transition-all p-5 border border-gray-100 dark:border-gray-700/80 border-l-4 border-l-blue-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-[11px] font-bold uppercase tracking-wider text-blue-600 dark:text-blue-400 mb-1">Transaksi Anda</p>
                    <p class="text-2xl font-black text-gray-900 dark:text-white">{{ $transaksiKasirHariIni ?? 0 }} <span class="text-sm font-normal text-gray-400">nota</span></p>
                    <p class="text-[11px] text-gray-500 dark:text-gray-400 mt-1">Dicatat akun Anda hari ini</p>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-blue-50 dark:bg-blue-950/50 text-blue-600 dark:text-blue-400 flex items-center justify-center font-bold text-xl shadow-inner">
                    🛒
                </div>
            </div>
        </div>

        <!-- KPI: Pesanan Siap Disajikan -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm hover:shadow-md transition-all p-5 border border-gray-100 dark:border-gray-700/80 border-l-4 border-l-teal-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-[11px] font-bold uppercase tracking-wider text-teal-600 dark:text-teal-400 mb-1">Siap Disajikan</p>
                    <p class="text-2xl font-black text-gray-900 dark:text-white flex items-center gap-2">
                        {{ $pesananSiapDisajikan ?? 0 }} <span class="text-sm font-normal text-gray-400">order</span>
                        @if(($pesananSiapDisajikan ?? 0) > 0)
                        <span class="w-2.5 h-2.5 rounded-full bg-teal-500 animate-ping"></span>
                        @endif
                    </p>
                    <p class="text-[11px] text-teal-600 dark:text-teal-400 font-bold mt-1">Ambil dari dapur</p>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-teal-50 dark:bg-teal-950/50 text-teal-600 dark:text-teal-400 flex items-center justify-center font-bold text-xl shadow-inner">
                    🔔
                </div>
            </div>
        </div>
        @endif

        @if(Auth::user()->hasPermission('dapur'))
        <!-- KPI: Antrean Dapur Menunggu -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm hover:shadow-md transition-all p-5 border border-gray-100 dark:border-gray-700/80 border-l-4 border-l-amber-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-[11px] font-bold uppercase tracking-wider text-amber-600 dark:text-amber-400 mb-1">Menunggu Dapur</p>
                    <p class="text-2xl font-black text-gray-900 dark:text-white flex items-center gap-2">
                        {{ $antreanMenunggu ?? 0 }} <span class="text-sm font-normal text-gray-400">tiket</span>
                        @if(($antreanMenunggu ?? 0) > 0)
                        <span class="w-2.5 h-2.5 rounded-full bg-amber-500 animate-pulse"></span>
                        @endif
                    </p>
                    <p class="text-[11px] text-amber-600 dark:text-amber-400 font-bold mt-1">Perlu disiapkan</p>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-amber-50 dark:bg-amber-950/50 text-amber-600 dark:text-amber-400 flex items-center justify-center font-bold text-xl shadow-inner">
                    ⏱️
                </div>
            </div>
        </div>

        <!-- KPI: Sedang Dimasak -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm hover:shadow-md transition-all p-5 border border-gray-100 dark:border-gray-700/80 border-l-4 border-l-orange-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-[11px] font-bold uppercase tracking-wider text-orange-600 dark:text-orange-400 mb-1">Sedang Disiapkan</p>
                    <p class="text-2xl font-black text-gray-900 dark:text-white">{{ $sedangDimasak ?? 0 }} <span class="text-sm font-normal text-gray-400">order</span></p>
                    <p class="text-[11px] text-gray-500 dark:text-gray-400 mt-1">Proses produksi</p>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-orange-50 dark:bg-orange-950/50 text-orange-600 dark:text-orange-400 flex items-center justify-center font-bold text-xl shadow-inner">
                    🔥
                </div>
            </div>
        </div>
        @endif

        @if(Auth::user()->hasPermission('bahanbaku'))
        <!-- KPI: Bahan Kritis -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm hover:shadow-md transition-all p-5 border border-gray-100 dark:border-gray-700/80 border-l-4 border-l-red-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-[11px] font-bold uppercase tracking-wider text-red-600 dark:text-red-400 mb-1">Stok Bahan Kritis</p>
                    <p class="text-2xl font-black text-gray-900 dark:text-white flex items-center gap-2">
                        {{ isset($bahanMenipis) ? $bahanMenipis->count() : 0 }} <span class="text-sm font-normal text-gray-400">item</span>
                        @if(isset($bahanMenipis) && $bahanMenipis->count() > 0)
                        <span class="w-2.5 h-2.5 rounded-full bg-red-500 animate-ping"></span>
                        @endif
                    </p>
                    <p class="text-[11px] text-red-600 dark:text-red-400 font-bold mt-1">Di bawah batas aman</p>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-red-50 dark:bg-red-950/50 text-red-600 dark:text-red-400 flex items-center justify-center font-bold text-xl shadow-inner">
                    ⚠️
                </div>
            </div>
        </div>

        <!-- KPI: Total Bahan -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm hover:shadow-md transition-all p-5 border border-gray-100 dark:border-gray-700/80 border-l-4 border-l-blue-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-[11px] font-bold uppercase tracking-wider text-blue-600 dark:text-blue-400 mb-1">Total Bahan Gudang</p>
                    <p class="text-2xl font-black text-gray-900 dark:text-white">{{ $totalBahan ?? 0 }} <span class="text-sm font-normal text-gray-400">item</span></p>
                    <p class="text-[11px] text-gray-500 dark:text-gray-400 mt-1">Terdata di inventaris</p>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-blue-50 dark:bg-blue-950/50 text-blue-600 dark:text-blue-400 flex items-center justify-center font-bold text-xl shadow-inner">
                    📦
                </div>
            </div>
        </div>
        @endif

        @if(Auth::user()->hasPermission('produk'))
        <!-- KPI: Produk Menipis -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm hover:shadow-md transition-all p-5 border border-gray-100 dark:border-gray-700/80 border-l-4 border-l-pink-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-[11px] font-bold uppercase tracking-wider text-pink-600 dark:text-pink-400 mb-1">Produk Etalase Kritis</p>
                    <p class="text-2xl font-black text-gray-900 dark:text-white flex items-center gap-2">
                        {{ isset($produkMenipis) ? $produkMenipis->count() : 0 }} <span class="text-sm font-normal text-gray-400">item</span>
                    </p>
                    <p class="text-[11px] text-pink-600 dark:text-pink-400 font-bold mt-1">Perlu dipanggang/restock</p>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-pink-50 dark:bg-pink-950/50 text-pink-600 dark:text-pink-400 flex items-center justify-center font-bold text-xl shadow-inner">
                    🥖
                </div>
            </div>
        </div>
        @endif

        @if(Auth::user()->hasPermission('pelanggan'))
        <!-- KPI: Total Pelanggan -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm hover:shadow-md transition-all p-5 border border-gray-100 dark:border-gray-700/80 border-l-4 border-l-purple-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-[11px] font-bold uppercase tracking-wider text-purple-600 dark:text-purple-400 mb-1">Total Pelanggan</p>
                    <p class="text-2xl font-black text-gray-900 dark:text-white">{{ $totalPelanggan ?? 0 }} <span class="text-sm font-normal text-gray-400">orang</span></p>
                    <p class="text-[11px] text-gray-500 dark:text-gray-400 mt-1">Member CRM terdaftar</p>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-purple-50 dark:bg-purple-950/50 text-purple-600 dark:text-purple-300 flex items-center justify-center font-bold text-xl shadow-inner">
                    👥
                </div>
            </div>
        </div>
        @endif
    </div>

    <!-- Modul Shortcuts Grid -->
    <div>
        <h3 class="text-sm font-extrabold text-gray-900 dark:text-white mb-3">Pintasan Akses Cepat</h3>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
            @if(Auth::user()->hasPermission('kasir'))
            <a href="{{ route('kasir.index') }}" class="group bg-white dark:bg-gray-800 p-5 rounded-2xl border border-gray-100 dark:border-gray-700/80 hover:border-emerald-300 dark:hover:border-emerald-700 hover:shadow-md transition-all flex items-start gap-4">
                <div class="w-10 h-10 rounded-xl bg-emerald-50 dark:bg-emerald-950/50 text-emerald-600 flex items-center justify-center font-bold text-lg group-hover:scale-110 transition-transform">
                    🛒
                </div>
                <div class="min-w-0 flex-1">
                    <h4 class="text-sm font-bold text-gray-900 dark:text-white group-hover:text-emerald-600 transition-colors">Mesin Kasir (POS)</h4>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Input order baru, keranjang kasir, pembayaran cash & QRIS.</p>
                </div>
            </a>
            @endif

            @if(Auth::user()->hasPermission('dapur'))
            <a href="{{ route('dapur.index') }}" class="group bg-white dark:bg-gray-800 p-5 rounded-2xl border border-gray-100 dark:border-gray-700/80 hover:border-orange-300 dark:hover:border-orange-700 hover:shadow-md transition-all flex items-start gap-4">
                <div class="w-10 h-10 rounded-xl bg-orange-50 dark:bg-orange-950/50 text-orange-600 flex items-center justify-center font-bold text-lg group-hover:scale-110 transition-transform">
                    🔥
                </div>
                <div class="min-w-0 flex-1">
                    <h4 class="text-sm font-bold text-gray-900 dark:text-white group-hover:text-orange-600 transition-colors">Layar Produksi Dapur</h4>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Pantau antrean tiket pesanan dan update status masak.</p>
                </div>
            </a>
            @endif

            @if(Auth::user()->hasPermission('bahanbaku'))
            <a href="{{ route('bahanbaku.index') }}" class="group bg-white dark:bg-gray-800 p-5 rounded-2xl border border-gray-100 dark:border-gray-700/80 hover:border-blue-300 dark:hover:border-blue-700 hover:shadow-md transition-all flex items-start gap-4">
                <div class="w-10 h-10 rounded-xl bg-blue-50 dark:bg-blue-950/50 text-blue-600 flex items-center justify-center font-bold text-lg group-hover:scale-110 transition-transform">
                    📦
                </div>
                <div class="min-w-0 flex-1">
                    <h4 class="text-sm font-bold text-gray-900 dark:text-white group-hover:text-blue-600 transition-colors">Logistik Bahan Baku</h4>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Inventaris tepung, ragi, telur, dan histori restock.</p>
                </div>
            </a>
            @endif

            @if(Auth::user()->hasPermission('produk'))
            <a href="{{ route('produk.index') }}" class="group bg-white dark:bg-gray-800 p-5 rounded-2xl border border-gray-100 dark:border-gray-700/80 hover:border-indigo-300 dark:hover:border-indigo-700 hover:shadow-md transition-all flex items-start gap-4">
                <div class="w-10 h-10 rounded-xl bg-indigo-50 dark:bg-indigo-950/50 text-indigo-600 flex items-center justify-center font-bold text-lg group-hover:scale-110 transition-transform">
                    🥖
                </div>
                <div class="min-w-0 flex-1">
                    <h4 class="text-sm font-bold text-gray-900 dark:text-white group-hover:text-indigo-600 transition-colors">Katalog & Kategori Produk</h4>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Daftar roti, harga jual, foto produk, dan stok etalase.</p>
                </div>
            </a>
            @endif

            @if(Auth::user()->hasPermission('resep'))
            <a href="{{ route('resep.index') }}" class="group bg-white dark:bg-gray-800 p-5 rounded-2xl border border-gray-100 dark:border-gray-700/80 hover:border-amber-300 dark:hover:border-amber-700 hover:shadow-md transition-all flex items-start gap-4">
                <div class="w-10 h-10 rounded-xl bg-amber-50 dark:bg-amber-950/50 text-amber-600 flex items-center justify-center font-bold text-lg group-hover:scale-110 transition-transform">
                    📜
                </div>
                <div class="min-w-0 flex-1">
                    <h4 class="text-sm font-bold text-gray-900 dark:text-white group-hover:text-amber-600 transition-colors">Resep & Batch Produksi</h4>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Komposisi takaran bahan baku per loyang atau batch roti.</p>
                </div>
            </a>
            @endif

            @if(Auth::user()->hasPermission('diskon'))
            <a href="{{ route('diskon.index') }}" class="group bg-white dark:bg-gray-800 p-5 rounded-2xl border border-gray-100 dark:border-gray-700/80 hover:border-rose-300 dark:hover:border-rose-700 hover:shadow-md transition-all flex items-start gap-4">
                <div class="w-10 h-10 rounded-xl bg-rose-50 dark:bg-rose-950/50 text-rose-600 flex items-center justify-center font-bold text-lg group-hover:scale-110 transition-transform">
                    🏷️
                </div>
                <div class="min-w-0 flex-1">
                    <h4 class="text-sm font-bold text-gray-900 dark:text-white group-hover:text-rose-600 transition-colors">Promo & Diskon</h4>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Atur potongan harga, kupon promo, dan periode diskon.</p>
                </div>
            </a>
            @endif

            @if(Auth::user()->hasPermission('pelanggan'))
            <a href="{{ route('pelanggan.index') }}" class="group bg-white dark:bg-gray-800 p-5 rounded-2xl border border-gray-100 dark:border-gray-700/80 hover:border-purple-300 dark:hover:border-purple-700 hover:shadow-md transition-all flex items-start gap-4">
                <div class="w-10 h-10 rounded-xl bg-purple-50 dark:bg-purple-950/50 text-purple-600 flex items-center justify-center font-bold text-lg group-hover:scale-110 transition-transform">
                    👥
                </div>
                <div class="min-w-0 flex-1">
                    <h4 class="text-sm font-bold text-gray-900 dark:text-white group-hover:text-purple-600 transition-colors">Daya Pelanggan (CRM)</h4>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Data kontak pembeli, loyalitas poin, dan riwayat belanja.</p>
                </div>
            </a>
            @endif

            @if(Auth::user()->hasPermission('laporan'))
            <a href="{{ route('laporan.index') }}" class="group bg-white dark:bg-gray-800 p-5 rounded-2xl border border-gray-100 dark:border-gray-700/80 hover:border-cyan-300 dark:hover:border-cyan-700 hover:shadow-md transition-all flex items-start gap-4">
                <div class="w-10 h-10 rounded-xl bg-cyan-50 dark:bg-cyan-950/50 text-cyan-600 flex items-center justify-center font-bold text-lg group-hover:scale-110 transition-transform">
                    📊
                </div>
                <div class="min-w-0 flex-1">
                    <h4 class="text-sm font-bold text-gray-900 dark:text-white group-hover:text-cyan-600 transition-colors">Rekap & Laporan</h4>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Statistik pendapatan, analisis laba kotor, dan ekspor CSV.</p>
                </div>
            </a>
            @endif

            @if(Auth::user()->hasPermission('karyawan'))
            <a href="{{ route('karyawan.index') }}" class="group bg-white dark:bg-gray-800 p-5 rounded-2xl border border-gray-100 dark:border-gray-700/80 hover:border-indigo-300 dark:hover:border-indigo-700 hover:shadow-md transition-all flex items-start gap-4">
                <div class="w-10 h-10 rounded-xl bg-indigo-50 dark:bg-indigo-950/50 text-indigo-600 flex items-center justify-center font-bold text-lg group-hover:scale-110 transition-transform">
                    👔
                </div>
                <div class="min-w-0 flex-1">
                    <h4 class="text-sm font-bold text-gray-900 dark:text-white group-hover:text-indigo-600 transition-colors">Kelola Karyawan</h4>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Manajemen staf kasir, dapur, gudang, dan peran kustom.</p>
                </div>
            </a>
            @endif
        </div>
    </div>

    <!-- Contextual Recent Activity Feeds -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        @if(Auth::user()->hasPermission('kasir') && isset($transaksiTerakhir))
        <!-- Transaksi Kasir Terbaru -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl p-5 border border-gray-100 dark:border-gray-700/80 shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-extrabold text-gray-900 dark:text-white flex items-center gap-2">
                    <span>🧾 Transaksi Terakhir Anda</span>
                </h3>
                <a href="{{ route('kasir.index') }}" class="text-xs font-bold text-indigo-600 dark:text-indigo-400 hover:underline">
                    Buka Kasir &rarr;
                </a>
            </div>
            @if($transaksiTerakhir->count() > 0)
            <div class="divide-y divide-gray-100 dark:divide-gray-700">
                @foreach($transaksiTerakhir as $trx)
                <div class="py-3 flex items-center justify-between">
                    <div>
                        <p class="text-xs font-extrabold text-gray-900 dark:text-white">#{{ $trx->nomor_unik ?? $trx->id }}</p>
                        <p class="text-[11px] text-gray-500 dark:text-gray-400">
                            {{ $trx->pelanggan->nama_pelanggan ?? 'Umum' }} &bull; {{ \Carbon\Carbon::parse($trx->tanggal_waktu)->diffForHumans() }}
                        </p>
                    </div>
                    <div class="text-right">
                        <p class="text-xs font-extrabold text-emerald-600 dark:text-emerald-400">Rp {{ number_format($trx->total_belanja, 0, ',', '.') }}</p>
                        <span class="inline-block px-2 py-0.5 text-[10px] rounded-full font-bold {{ $trx->status_pesanan == 'Selesai' ? 'bg-emerald-50 text-emerald-600' : 'bg-amber-50 text-amber-600' }}">
                            {{ $trx->status_pesanan ?? 'Selesai' }}
                        </span>
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <p class="text-xs text-gray-400 text-center py-6">Belum ada transaksi yang Anda catat hari ini.</p>
            @endif
        </div>
        @endif

        @if(Auth::user()->hasPermission('dapur') && isset($pesananAktifDapur))
        <!-- Pesanan Antrean Dapur Aktif -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl p-5 border border-gray-100 dark:border-gray-700/80 shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-extrabold text-gray-900 dark:text-white flex items-center gap-2">
                    <span>🍳 Antrean Dapur Menunggu</span>
                </h3>
                <a href="{{ route('dapur.index') }}" class="text-xs font-bold text-orange-600 dark:text-orange-400 hover:underline">
                    Buka Dapur &rarr;
                </a>
            </div>
            @if($pesananAktifDapur->count() > 0)
            <div class="divide-y divide-gray-100 dark:divide-gray-700">
                @foreach($pesananAktifDapur as $dapurTrx)
                <div class="py-3 flex items-center justify-between">
                    <div>
                        <p class="text-xs font-extrabold text-gray-900 dark:text-white">#{{ $dapurTrx->nomor_unik ?? $dapurTrx->id }}</p>
                        <p class="text-[11px] text-gray-500 dark:text-gray-400">
                            {{ $dapurTrx->details->sum('qty_beli') }} item menu &bull; {{ \Carbon\Carbon::parse($dapurTrx->tanggal_waktu)->diffForHumans() }}
                        </p>
                    </div>
                    <span class="px-2.5 py-1 text-[11px] rounded-full font-bold {{ $dapurTrx->status_pesanan == 'Menunggu Dapur' ? 'bg-amber-100 text-amber-800 dark:bg-amber-950 dark:text-amber-300' : 'bg-orange-100 text-orange-800 dark:bg-orange-950 dark:text-orange-300' }}">
                        {{ $dapurTrx->status_pesanan }}
                    </span>
                </div>
                @endforeach
            </div>
            @else
            <p class="text-xs text-gray-400 text-center py-6">Semua pesanan dapur sudah selesai diproses! 🎉</p>
            @endif
        </div>
        @endif

        @if(Auth::user()->hasPermission('bahanbaku') && isset($riwayatRestockTerbaru))
        <!-- Riwayat Restock Terbaru -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl p-5 border border-gray-100 dark:border-gray-700/80 shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-extrabold text-gray-900 dark:text-white flex items-center gap-2">
                    <span>📦 Riwayat Restock Masuk</span>
                </h3>
                <a href="{{ route('restock.index') }}" class="text-xs font-bold text-blue-600 dark:text-blue-400 hover:underline">
                    Semua Restock &rarr;
                </a>
            </div>
            @if($riwayatRestockTerbaru->count() > 0)
            <div class="divide-y divide-gray-100 dark:divide-gray-700">
                @foreach($riwayatRestockTerbaru as $rst)
                <div class="py-3 flex items-center justify-between">
                    <div>
                        <p class="text-xs font-extrabold text-gray-900 dark:text-white">{{ $rst->bahanBaku->nama_bahan ?? 'Bahan' }}</p>
                        <p class="text-[11px] text-gray-500 dark:text-gray-400">
                            Oleh: {{ $rst->user->name ?? 'Staf' }} &bull; {{ \Carbon\Carbon::parse($rst->tanggal_waktu)->diffForHumans() }}
                        </p>
                    </div>
                    <span class="text-xs font-extrabold text-emerald-600 dark:text-emerald-400">
                        +{{ $rst->jumlah_masuk }} {{ $rst->bahanBaku->satuan ?? 'unit' }}
                    </span>
                </div>
                @endforeach
            </div>
            @else
            <p class="text-xs text-gray-400 text-center py-6">Belum ada riwayat restock bahan baru.</p>
            @endif
        </div>
        @endif
    </div>
</div>
