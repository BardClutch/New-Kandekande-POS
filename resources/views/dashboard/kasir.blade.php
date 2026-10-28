<!-- DASHBOARD KASIR -->
@php \Carbon\Carbon::setLocale('id'); @endphp
<div class="space-y-10">
    <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-5">
        <div class="min-w-0">
            <p class="text-sm text-gray-500 dark:text-gray-400">{{ \Carbon\Carbon::now()->translatedFormat('l, j F Y') }}</p>
            <h1 class="mt-1 font-display text-4xl sm:text-5xl font-extrabold tracking-tight leading-[1.05] text-gray-900 dark:text-gray-50 break-words">Halo, {{ Auth::user()->name }}</h1>
            <p class="mt-2 text-gray-500 dark:text-gray-400 max-w-xl">Pantau transaksi shift Anda, cetak ulang struk, dan cek stok etalase.</p>
        </div>
        <a href="{{ route('kasir.index') }}" class="inline-flex items-center gap-2 self-start sm:self-auto flex-shrink-0 rounded-full bg-gray-900 dark:bg-gray-100 text-gray-50 dark:text-gray-900 h-[2.625rem] px-6 text-sm font-semibold hover:bg-indigo-600 dark:hover:bg-indigo-400 dark:hover:text-gray-900 transition-colors">
            Buka layar kasir
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 12h14m-6-6l6 6-6 6"/></svg>
        </a>
    </div>

    <dl class="grid grid-cols-2 lg:grid-cols-4 rounded-xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-800 divide-x divide-y lg:divide-y-0 divide-gray-200 dark:divide-gray-700 overflow-hidden">
        <div class="p-5 sm:p-6">
            <dt class="text-sm text-gray-500 dark:text-gray-400">Struk Anda hari ini</dt>
            <dd class="mt-2 font-display text-2xl sm:text-3xl font-extrabold tabular-nums text-gray-900 dark:text-gray-50">{{ $transaksiKasirHariIni }}<span class="ml-1.5 text-base font-semibold text-gray-400">transaksi</span></dd>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Dilayani shift Anda</p>
        </div>
        <div class="p-5 sm:p-6">
            <dt class="text-sm text-gray-500 dark:text-gray-400">Item terjual</dt>
            <dd class="mt-2 font-display text-2xl sm:text-3xl font-extrabold tabular-nums text-gray-900 dark:text-gray-50">{{ number_format($totalItemTerjual, 0, ',', '.') }}<span class="ml-1.5 text-base font-semibold text-gray-400">pcs</span></dd>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Roti dan kue</p>
        </div>
        <div class="p-5 sm:p-6">
            <dt class="text-sm text-gray-500 dark:text-gray-400">Rata-rata per struk</dt>
            <dd class="mt-2 font-display text-2xl sm:text-3xl font-extrabold tabular-nums text-gray-900 dark:text-gray-50"><span class="text-base font-semibold text-gray-400 mr-0.5">Rp</span>{{ number_format($rataRataBelanja, 0, ',', '.') }}</dd>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Nilai belanja pelanggan</p>
        </div>
        <div class="p-5 sm:p-6">
            <dt class="text-sm text-gray-500 dark:text-gray-400">Pesanan siap diambil</dt>
            <dd class="mt-2 font-display text-2xl sm:text-3xl font-extrabold tabular-nums text-gray-900 dark:text-gray-50">{{ $pesananSiapDisajikan }}<span class="ml-1.5 text-base font-semibold text-gray-400">pesanan</span></dd>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Sudah selesai di dapur</p>
        </div>
    </dl>

    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
        <section class="xl:col-span-2 rounded-xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-800 overflow-hidden">
            <div class="flex items-baseline justify-between gap-4 px-5 sm:px-6 pt-5 sm:pt-6 pb-4">
                <div>
                    <h2 class="font-display text-xl font-extrabold text-gray-900 dark:text-gray-50">Transaksi terakhir Anda</h2>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Struk yang baru diproses di shift ini</p>
                </div>
                <a href="{{ route('kasir.index') }}" class="flex-shrink-0 text-sm font-semibold text-indigo-600 dark:text-indigo-400 hover:underline underline-offset-4">Transaksi baru</a>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="border-y border-gray-200 dark:border-gray-700 text-gray-500 dark:text-gray-400">
                        <tr>
                            <th class="py-2.5 px-5 sm:px-6 text-left font-medium">No. struk</th>
                            <th class="py-2.5 px-3 text-left font-medium">Pelanggan</th>
                            <th class="py-2.5 px-3 text-left font-medium">Waktu</th>
                            <th class="py-2.5 px-3 text-left font-medium">Metode</th>
                            <th class="py-2.5 px-3 text-right font-medium">Total</th>
                            <th class="py-2.5 px-5 sm:px-6 text-right font-medium"><span class="sr-only">Aksi</span></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse($transaksiTerakhir as $trx)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/40 transition-colors">
                            <td class="py-3 px-5 sm:px-6 font-mono font-semibold text-gray-900 dark:text-gray-100">#{{ $trx->id_transaksi }}</td>
                            <td class="py-3 px-3 text-gray-700 dark:text-gray-200">{{ $trx->nama_pelanggan ?: ($trx->pelanggan->nama_pelanggan ?? 'Umum / Walk-in') }}</td>
                            <td class="py-3 px-3 text-gray-500 dark:text-gray-400 tabular-nums">{{ \Carbon\Carbon::parse($trx->tanggal_waktu)->format('H:i') }}</td>
                            <td class="py-3 px-3 text-gray-700 dark:text-gray-200">{{ $trx->metode_pembayaran }}</td>
                            <td class="py-3 px-3 text-right font-semibold tabular-nums text-gray-900 dark:text-gray-100">Rp {{ number_format($trx->total_belanja, 0, ',', '.') }}</td>
                            <td class="py-3 px-5 sm:px-6 text-right">
                                <a href="{{ route('kasir.cetak', $trx->id_transaksi) }}" target="_blank" class="text-sm font-semibold text-indigo-600 dark:text-indigo-400 hover:underline underline-offset-4">Cetak</a>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="6" class="py-12 text-center text-gray-400">Belum ada transaksi yang Anda proses hari ini.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>

        <section class="rounded-xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-800 p-5 sm:p-6">
            <h2 class="font-display text-xl font-extrabold text-gray-900 dark:text-gray-50">Etalase menipis</h2>
            <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">Cek sebelum menawarkan ke pembeli</p>
            <ul class="divide-y divide-gray-200 dark:divide-gray-700">
                @forelse($produkMenipis as $p)
                <li class="flex items-center justify-between gap-3 py-3">
                    <div class="min-w-0">
                        <p class="font-semibold text-gray-900 dark:text-gray-100">{{ $p->nama_produk }}</p>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Minimum {{ $p->stok_minimum }} pcs</p>
                    </div>
                    <span class="flex-shrink-0 text-sm font-semibold tabular-nums {{ $p->stok_etalase <= 0 ? 'text-red-600 dark:text-red-400' : 'text-amber-600 dark:text-amber-400' }}">
                        {{ $p->stok_etalase <= 0 ? 'Habis' : 'Sisa ' . $p->stok_etalase }}
                    </span>
                </li>
                @empty
                <li class="py-8 text-center text-sm text-gray-400">Semua roti tersedia cukup di etalase.</li>
                @endforelse
            </ul>
            <div class="mt-4 pt-4 border-t border-gray-200 dark:border-gray-700 flex items-center justify-between text-sm text-gray-500 dark:text-gray-400">
                <span>Siap jual</span>
                <strong class="font-semibold text-gray-900 dark:text-gray-100">{{ $totalProdukReady }} jenis menu</strong>
            </div>
        </section>
    </div>
</div>
