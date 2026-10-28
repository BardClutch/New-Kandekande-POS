<!-- DASHBOARD PERAN KUSTOM -->
@php
    \Carbon\Carbon::setLocale('id');
    $u = Auth::user();

    // Angka ringkas sesuai hak akses
    $stats = [];
    if ($u->hasPermission('laporan')) {
        $stats[] = ['Pendapatan hari ini', 'Rp ' . number_format($pendapatanHariIni ?? 0, 0, ',', '.'), 'Bulan ini Rp ' . number_format($pendapatanBulanIni ?? 0, 0, ',', '.'), false];
        $stats[] = ['Pesanan toko', ($pesananHariIni ?? 0) . ' transaksi', 'Hari ini', false];
    }
    if ($u->hasPermission('kasir')) {
        $stats[] = ['Transaksi Anda', ($transaksiKasirHariIni ?? 0) . ' nota', 'Dicatat akun Anda hari ini', false];
        $stats[] = ['Siap disajikan', ($pesananSiapDisajikan ?? 0) . ' order', 'Ambil dari dapur', ($pesananSiapDisajikan ?? 0) > 0];
    }
    if ($u->hasPermission('dapur')) {
        $stats[] = ['Menunggu dapur', ($antreanMenunggu ?? 0) . ' tiket', 'Perlu disiapkan', ($antreanMenunggu ?? 0) > 0];
        $stats[] = ['Sedang disiapkan', ($sedangDimasak ?? 0) . ' order', 'Proses produksi', false];
    }
    if ($u->hasPermission('bahanbaku')) {
        $kritis = isset($bahanMenipis) ? $bahanMenipis->count() : 0;
        $stats[] = ['Stok bahan kritis', $kritis . ' item', 'Di bawah batas aman', $kritis > 0];
        $stats[] = ['Bahan di gudang', ($totalBahan ?? 0) . ' item', 'Terdata di inventaris', false];
    }
    if ($u->hasPermission('produk')) {
        $stats[] = ['Etalase kritis', (isset($produkMenipis) ? $produkMenipis->count() : 0) . ' item', 'Perlu dipanggang atau restock', false];
    }
    if ($u->hasPermission('pelanggan')) {
        $stats[] = ['Pelanggan', ($totalPelanggan ?? 0) . ' orang', 'Member terdaftar', false];
    }

    // Pintasan modul
    $shortcuts = [];
    $defs = [
        ['kasir', 'kasir.index', 'Mesin kasir (POS)', 'Order baru, keranjang, dan pembayaran.'],
        ['dapur', 'dapur.index', 'Layar produksi dapur', 'Antrean tiket pesanan dan status masak.'],
        ['bahanbaku', 'bahanbaku.index', 'Logistik bahan baku', 'Inventaris bahan dan riwayat restock.'],
        ['produk', 'produk.index', 'Katalog dan kategori produk', 'Daftar roti, harga jual, dan stok etalase.'],
        ['resep', 'resep.index', 'Resep dan batch produksi', 'Takaran bahan baku per batch.'],
        ['diskon', 'diskon.index', 'Promo dan diskon', 'Potongan harga dan periode promo.'],
        ['pelanggan', 'pelanggan.index', 'Pelanggan', 'Kontak pembeli dan riwayat belanja.'],
        ['laporan', 'laporan.index', 'Rekap dan laporan', 'Pendapatan, laba kotor, dan ekspor CSV.'],
        ['karyawan', 'karyawan.index', 'Kelola karyawan', 'Staf kasir, dapur, gudang, dan peran kustom.'],
    ];
    foreach ($defs as $d) { if ($u->hasPermission($d[0])) { $shortcuts[] = $d; } }
@endphp

<div class="space-y-10">
    <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-5">
        <div class="min-w-0">
            <p class="text-sm text-gray-500 dark:text-gray-400">{{ \Carbon\Carbon::now()->translatedFormat('l, j F Y') }}</p>
            <h1 class="mt-1 font-display text-4xl sm:text-5xl font-extrabold tracking-tight leading-[1.05] text-gray-900 dark:text-gray-50 break-words">Halo, {{ $u->name }}</h1>
            <p class="mt-2 text-gray-500 dark:text-gray-400 max-w-xl">Dashboard ini menampilkan modul yang diberikan Owner untuk akun Anda.</p>
        </div>
    </div>

    <!-- Wewenang aktif -->
    <section>
        <h2 class="font-display text-xl font-extrabold text-gray-900 dark:text-gray-50">
            Wewenang aktif <span class="ml-1 text-base font-semibold text-gray-400">{{ count($u->permission_labels) }}</span>
        </h2>
        @if(count($u->permission_labels) > 0)
            <ul class="mt-3 flex flex-wrap gap-2">
                @foreach($u->permission_labels as $key => $label)
                    <li class="rounded-full border border-gray-300 dark:border-gray-700 px-3.5 py-1 text-sm font-medium text-gray-700 dark:text-gray-200">{{ $label }}</li>
                @endforeach
            </ul>
        @else
            <p class="mt-3 rounded-xl border border-amber-300 dark:border-amber-800 bg-amber-50 dark:bg-amber-950/30 px-4 py-3 text-sm text-amber-800 dark:text-amber-300">
                Belum ada hak akses modul untuk akun Anda. Hubungi <strong>Owner</strong> untuk mengaktifkannya.
            </p>
        @endif
    </section>

    @if(count($stats) > 0)
    <dl class="grid grid-cols-2 lg:grid-cols-4 rounded-xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-800 overflow-hidden">
        @foreach($stats as $s)
            <div class="p-5 sm:p-6 border-gray-200 dark:border-gray-700 border-b border-r">
                <dt class="text-sm text-gray-500 dark:text-gray-400">{{ $s[0] }}</dt>
                <dd class="mt-2 font-display text-2xl font-extrabold tabular-nums {{ $s[3] ? 'text-indigo-600 dark:text-indigo-400' : 'text-gray-900 dark:text-gray-50' }}">{{ $s[1] }}</dd>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ $s[2] }}</p>
            </div>
        @endforeach
    </dl>
    @endif

    @if(count($shortcuts) > 0)
    <section>
        <h2 class="font-display text-xl font-extrabold text-gray-900 dark:text-gray-50 mb-3">Pintasan</h2>
        <ul class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-x-8">
            @foreach($shortcuts as $d)
                <li class="border-t border-gray-200 dark:border-gray-800">
                    <a href="{{ route($d[1]) }}" class="group flex items-center justify-between gap-4 py-4">
                        <span class="min-w-0">
                            <span class="block font-semibold text-gray-900 dark:text-gray-100 group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors">{{ $d[2] }}</span>
                            <span class="block text-sm text-gray-500 dark:text-gray-400">{{ $d[3] }}</span>
                        </span>
                        <svg class="w-4 h-4 flex-shrink-0 text-gray-400 group-hover:translate-x-1 group-hover:text-indigo-600 transition-all" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 12h14m-6-6l6 6-6 6"/></svg>
                    </a>
                </li>
            @endforeach
        </ul>
    </section>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        @if($u->hasPermission('kasir') && isset($transaksiTerakhir))
        <section class="rounded-xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-800 p-5 sm:p-6">
            <div class="flex items-baseline justify-between gap-3 mb-3">
                <h2 class="font-display text-xl font-extrabold text-gray-900 dark:text-gray-50">Transaksi terakhir Anda</h2>
                <a href="{{ route('kasir.index') }}" class="text-sm font-semibold text-indigo-600 dark:text-indigo-400 hover:underline underline-offset-4">Buka kasir</a>
            </div>
            <ul class="divide-y divide-gray-200 dark:divide-gray-700">
                @forelse($transaksiTerakhir as $trx)
                <li class="py-3 flex items-center justify-between gap-3">
                    <div class="min-w-0">
                        <p class="font-mono font-semibold text-gray-900 dark:text-gray-100">#{{ $trx->nomor_unik ?? $trx->id }}</p>
                        <p class="text-sm text-gray-500 dark:text-gray-400 truncate">{{ $trx->pelanggan->nama_pelanggan ?? 'Umum' }} · {{ \Carbon\Carbon::parse($trx->tanggal_waktu)->diffForHumans() }}</p>
                    </div>
                    <div class="text-right flex-shrink-0">
                        <p class="font-semibold tabular-nums text-gray-900 dark:text-gray-100">Rp {{ number_format($trx->total_belanja, 0, ',', '.') }}</p>
                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ $trx->status_pesanan ?? 'Selesai' }}</p>
                    </div>
                </li>
                @empty
                <li class="py-8 text-center text-sm text-gray-400">Belum ada transaksi yang Anda catat hari ini.</li>
                @endforelse
            </ul>
        </section>
        @endif

        @if($u->hasPermission('dapur') && isset($pesananAktifDapur))
        <section class="rounded-xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-800 p-5 sm:p-6">
            <div class="flex items-baseline justify-between gap-3 mb-3">
                <h2 class="font-display text-xl font-extrabold text-gray-900 dark:text-gray-50">Antrean dapur</h2>
                <a href="{{ route('dapur.index') }}" class="text-sm font-semibold text-indigo-600 dark:text-indigo-400 hover:underline underline-offset-4">Buka dapur</a>
            </div>
            <ul class="divide-y divide-gray-200 dark:divide-gray-700">
                @forelse($pesananAktifDapur as $dapurTrx)
                <li class="py-3 flex items-center justify-between gap-3">
                    <div class="min-w-0">
                        <p class="font-mono font-semibold text-gray-900 dark:text-gray-100">#{{ $dapurTrx->nomor_unik ?? $dapurTrx->id }}</p>
                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ $dapurTrx->details->sum('qty_beli') }} item menu · {{ \Carbon\Carbon::parse($dapurTrx->tanggal_waktu)->diffForHumans() }}</p>
                    </div>
                    <span class="flex-shrink-0 text-sm font-semibold {{ $dapurTrx->status_pesanan == 'Menunggu Dapur' ? 'text-amber-600 dark:text-amber-400' : 'text-gray-500 dark:text-gray-400' }}">{{ $dapurTrx->status_pesanan }}</span>
                </li>
                @empty
                <li class="py-8 text-center text-sm text-gray-400">Semua pesanan dapur sudah selesai diproses.</li>
                @endforelse
            </ul>
        </section>
        @endif

        @if($u->hasPermission('bahanbaku') && isset($riwayatRestockTerbaru))
        <section class="rounded-xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-800 p-5 sm:p-6">
            <div class="flex items-baseline justify-between gap-3 mb-3">
                <h2 class="font-display text-xl font-extrabold text-gray-900 dark:text-gray-50">Restock terakhir</h2>
                <a href="{{ route('restock.index') }}" class="text-sm font-semibold text-indigo-600 dark:text-indigo-400 hover:underline underline-offset-4">Semua</a>
            </div>
            <ul class="divide-y divide-gray-200 dark:divide-gray-700">
                @forelse($riwayatRestockTerbaru as $rst)
                <li class="py-3 flex items-center justify-between gap-3">
                    <div class="min-w-0">
                        <p class="font-semibold text-gray-900 dark:text-gray-100 truncate">{{ $rst->bahanBaku->nama_bahan ?? 'Bahan' }}</p>
                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ $rst->user->name ?? 'Staf' }} · {{ \Carbon\Carbon::parse($rst->tanggal_waktu)->diffForHumans() }}</p>
                    </div>
                    <span class="flex-shrink-0 text-sm font-semibold tabular-nums text-emerald-600 dark:text-emerald-400">+{{ $rst->jumlah_masuk }} {{ $rst->bahanBaku->satuan ?? 'unit' }}</span>
                </li>
                @empty
                <li class="py-8 text-center text-sm text-gray-400">Belum ada riwayat restock.</li>
                @endforelse
            </ul>
        </section>
        @endif
    </div>
</div>
