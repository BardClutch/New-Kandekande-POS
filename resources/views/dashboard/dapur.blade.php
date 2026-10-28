<!-- DASHBOARD DAPUR -->
@php \Carbon\Carbon::setLocale('id'); @endphp
<div class="space-y-10">
    <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-5">
        <div class="min-w-0">
            <p class="text-sm text-gray-500 dark:text-gray-400">{{ \Carbon\Carbon::now()->translatedFormat('l, j F Y') }}</p>
            <h1 class="mt-1 font-display text-4xl sm:text-5xl font-extrabold tracking-tight leading-none text-gray-900 dark:text-gray-50">Dapur hari ini</h1>
            <p class="mt-2 text-gray-500 dark:text-gray-400 max-w-xl">Pesanan dari kasir masuk secara langsung. Kelola antrean masak dan catat batch roti yang matang.</p>
        </div>
        <a href="{{ route('dapur.index') }}" class="inline-flex items-center gap-2 self-start sm:self-auto flex-shrink-0 rounded-full bg-gray-900 dark:bg-gray-100 text-gray-50 dark:text-gray-900 h-[2.625rem] px-6 text-sm font-semibold hover:bg-indigo-600 dark:hover:bg-indigo-400 dark:hover:text-gray-900 transition-colors">
            Buka layar dapur
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 12h14m-6-6l6 6-6 6"/></svg>
        </a>
    </div>

    <dl class="grid grid-cols-2 lg:grid-cols-4 rounded-xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-800 divide-x divide-y lg:divide-y-0 divide-gray-200 dark:divide-gray-700 overflow-hidden">
        <div class="p-5 sm:p-6">
            <dt class="text-sm text-gray-500 dark:text-gray-400">Menunggu persiapan</dt>
            <dd class="mt-2 font-display text-2xl sm:text-3xl font-extrabold tabular-nums {{ $antreanMenunggu > 0 ? 'text-indigo-600 dark:text-indigo-400' : 'text-gray-900 dark:text-gray-50' }}">{{ $antreanMenunggu }}</dd>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Pesanan baru dari kasir</p>
        </div>
        <div class="p-5 sm:p-6">
            <dt class="text-sm text-gray-500 dark:text-gray-400">Sedang diproses</dt>
            <dd class="mt-2 font-display text-2xl sm:text-3xl font-extrabold tabular-nums text-gray-900 dark:text-gray-50">{{ $sedangDimasak }}<span class="ml-1.5 text-base font-semibold text-gray-400">pesanan</span></dd>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Dalam pengerjaan</p>
        </div>
        <div class="p-5 sm:p-6">
            <dt class="text-sm text-gray-500 dark:text-gray-400">Selesai hari ini</dt>
            <dd class="mt-2 font-display text-2xl sm:text-3xl font-extrabold tabular-nums text-gray-900 dark:text-gray-50">{{ $siapSelesaiHariIni }}<span class="ml-1.5 text-base font-semibold text-gray-400">pesanan</span></dd>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Siap saji atau diambil</p>
        </div>
        <div class="p-5 sm:p-6">
            <dt class="text-sm text-gray-500 dark:text-gray-400">Batch roti hari ini</dt>
            <dd class="mt-2 font-display text-2xl sm:text-3xl font-extrabold tabular-nums text-gray-900 dark:text-gray-50">{{ $totalBatchProduksiHariIni }}<span class="ml-1.5 text-base font-semibold text-gray-400">batch</span></dd>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Sudah dicatat</p>
        </div>
    </dl>

    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
        <section class="xl:col-span-2 rounded-xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-800">
            <div class="flex items-baseline justify-between gap-4 px-5 sm:px-6 pt-5 sm:pt-6 pb-4">
                <div>
                    <h2 class="font-display text-xl font-extrabold text-gray-900 dark:text-gray-50">Antrean pesanan</h2>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Menunggu atau sedang dikerjakan</p>
                </div>
                <a href="{{ route('dapur.index') }}" class="flex-shrink-0 text-sm font-semibold text-indigo-600 dark:text-indigo-400 hover:underline underline-offset-4">Buka dapur</a>
            </div>
            <ul class="divide-y divide-gray-200 dark:divide-gray-700 border-t border-gray-200 dark:border-gray-700">
                @forelse($pesananAktif as $pesanan)
                <li class="px-5 sm:px-6 py-4 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                    <div class="min-w-0">
                        <p class="flex flex-wrap items-baseline gap-x-2">
                            <span class="font-mono font-semibold text-gray-900 dark:text-gray-100">#{{ $pesanan->id_transaksi }}</span>
                            <span class="font-semibold text-gray-900 dark:text-gray-100 truncate">{{ $pesanan->nama_pelanggan ?: ($pesanan->pelanggan->nama_pelanggan ?? 'Umum') }}</span>
                            <span class="text-sm text-gray-400">{{ \Carbon\Carbon::parse($pesanan->tanggal_waktu)->diffForHumans() }}</span>
                        </p>
                        <p class="mt-1 text-sm text-gray-600 dark:text-gray-300">
                            @foreach($pesanan->details as $d)<span class="whitespace-nowrap"><span class="font-semibold text-gray-900 dark:text-gray-100 tabular-nums">{{ $d->qty_beli }}×</span> {{ $d->produk->nama_produk ?? 'Item' }}</span>@if(!$loop->last)<span class="text-gray-300 dark:text-gray-600"> · </span>@endif @endforeach
                        </p>
                        @if($pesanan->catatan_pesanan)
                        <p class="mt-1 text-sm text-amber-700 dark:text-amber-400">Catatan: {{ $pesanan->catatan_pesanan }}</p>
                        @endif
                    </div>
                    <div class="flex items-center gap-4 flex-shrink-0">
                        <span class="text-sm font-semibold {{ $pesanan->status_pesanan == 'Menunggu Dapur' ? 'text-amber-600 dark:text-amber-400' : 'text-gray-500 dark:text-gray-400' }}">{{ $pesanan->status_pesanan }}</span>
                        <a href="{{ route('dapur.index') }}" class="rounded-full border border-gray-300 dark:border-gray-600 px-4 py-1.5 text-sm font-semibold text-gray-700 dark:text-gray-200 hover:border-gray-900 dark:hover:border-gray-300 transition-colors">Proses</a>
                    </div>
                </li>
                @empty
                <li class="py-14 text-center text-gray-400">Tidak ada antrean aktif. Semua pesanan sudah disajikan.</li>
                @endforelse
            </ul>
        </section>

        <section class="rounded-xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-800 p-5 sm:p-6">
            <h2 class="font-display text-xl font-extrabold text-gray-900 dark:text-gray-50">Perlu dipanggang</h2>
            <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">Etalase yang hampir habis</p>
            <ul class="divide-y divide-gray-200 dark:divide-gray-700">
                @forelse($produkMenipis as $p)
                <li class="flex items-center justify-between gap-3 py-3">
                    <div class="min-w-0">
                        <p class="font-semibold text-gray-900 dark:text-gray-100">{{ $p->nama_produk }}</p>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Sisa <span class="font-semibold text-red-600 dark:text-red-400 tabular-nums">{{ $p->stok_etalase }} pcs</span>, batas {{ $p->stok_minimum }}</p>
                    </div>
                    <a href="{{ route('dapur.index') }}" class="flex-shrink-0 text-sm font-semibold text-indigo-600 dark:text-indigo-400 hover:underline underline-offset-4">Buat batch</a>
                </li>
                @empty
                <li class="py-8 text-center text-sm text-gray-400">Semua rak terisi di atas batas aman.</li>
                @endforelse
            </ul>
        </section>
    </div>
</div>
