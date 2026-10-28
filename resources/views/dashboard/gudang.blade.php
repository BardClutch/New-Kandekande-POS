<!-- DASHBOARD ADMIN GUDANG -->
@php \Carbon\Carbon::setLocale('id'); @endphp
<div class="space-y-10">
    <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-5">
        <div class="min-w-0">
            <p class="text-sm text-gray-500 dark:text-gray-400">{{ \Carbon\Carbon::now()->translatedFormat('l, j F Y') }}</p>
            <h1 class="mt-1 font-display text-4xl sm:text-5xl font-extrabold tracking-tight leading-[1.05] text-gray-900 dark:text-gray-50 break-words">Halo, {{ Auth::user()->name }}</h1>
            <p class="mt-2 text-gray-500 dark:text-gray-400 max-w-xl">Pantau stok bahan baku, peringatan stok kritis, dan catat restock yang masuk.</p>
        </div>
        <div class="flex items-center gap-2 flex-shrink-0">
            <a href="{{ route('restock.index') }}" class="inline-flex items-center gap-2 rounded-full bg-gray-900 dark:bg-gray-100 text-gray-50 dark:text-gray-900 h-[2.625rem] px-5 text-sm font-semibold hover:bg-indigo-600 dark:hover:bg-indigo-400 dark:hover:text-gray-900 transition-colors">
                Catat restock
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 5v14M5 12h14"/></svg>
            </a>
            <a href="{{ route('bahanbaku.index') }}" class="inline-flex items-center rounded-full border border-gray-300 dark:border-gray-700 h-[2.625rem] px-5 text-sm font-semibold text-gray-700 dark:text-gray-200 hover:border-gray-900 dark:hover:border-gray-300 transition-colors">Semua bahan</a>
        </div>
    </div>

    <dl class="grid grid-cols-2 lg:grid-cols-4 rounded-xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-800 divide-x divide-y lg:divide-y-0 divide-gray-200 dark:divide-gray-700 overflow-hidden">
        <div class="p-5 sm:p-6">
            <dt class="text-sm text-gray-500 dark:text-gray-400">Jenis bahan</dt>
            <dd class="mt-2 font-display text-2xl sm:text-3xl font-extrabold tabular-nums text-gray-900 dark:text-gray-50">{{ $totalBahan }}<span class="ml-1.5 text-base font-semibold text-gray-400">item</span></dd>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Terdata di gudang</p>
        </div>
        <div class="p-5 sm:p-6">
            <dt class="text-sm text-gray-500 dark:text-gray-400">Stok kritis</dt>
            <dd class="mt-2 font-display text-2xl sm:text-3xl font-extrabold tabular-nums {{ $bahanMenipis->count() > 0 ? 'text-red-600 dark:text-red-400' : 'text-gray-900 dark:text-gray-50' }}">{{ $bahanMenipis->count() }}<span class="ml-1.5 text-base font-semibold text-gray-400">item</span></dd>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Di bawah batas aman</p>
        </div>
        <div class="p-5 sm:p-6">
            <dt class="text-sm text-gray-500 dark:text-gray-400">Restock bulan ini</dt>
            <dd class="mt-2 font-display text-2xl sm:text-3xl font-extrabold tabular-nums text-gray-900 dark:text-gray-50">{{ $totalRestockBulanIni }}<span class="ml-1.5 text-base font-semibold text-gray-400">kali</span></dd>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Penerimaan pasokan</p>
        </div>
        <div class="p-5 sm:p-6">
            <dt class="text-sm text-gray-500 dark:text-gray-400">Restock hari ini</dt>
            <dd class="mt-2 font-display text-2xl sm:text-3xl font-extrabold tabular-nums text-gray-900 dark:text-gray-50">{{ $totalRestockHariIni }}<span class="ml-1.5 text-base font-semibold text-gray-400">kali</span></dd>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Sudah dicatat</p>
        </div>
    </dl>

    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
        <section class="xl:col-span-2 rounded-xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-800 overflow-hidden">
            <div class="flex items-baseline justify-between gap-4 px-5 sm:px-6 pt-5 sm:pt-6 pb-4">
                <div>
                    <h2 class="font-display text-xl font-extrabold text-gray-900 dark:text-gray-50">Perlu restock segera</h2>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Bahan di batas minimum atau di bawahnya</p>
                </div>
                <a href="{{ route('restock.index') }}" class="flex-shrink-0 text-sm font-semibold text-indigo-600 dark:text-indigo-400 hover:underline underline-offset-4">Form restock</a>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="border-y border-gray-200 dark:border-gray-700 text-gray-500 dark:text-gray-400">
                        <tr>
                            <th class="py-2.5 px-5 sm:px-6 text-left font-medium">Bahan</th>
                            <th class="py-2.5 px-3 text-right font-medium">Sisa</th>
                            <th class="py-2.5 px-3 text-right font-medium">Minimum</th>
                            <th class="py-2.5 px-3 text-right font-medium">Harga beli</th>
                            <th class="py-2.5 px-5 sm:px-6 text-right font-medium"><span class="sr-only">Tindakan</span></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse($bahanMenipis as $b)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/40 transition-colors">
                            <td class="py-3 px-5 sm:px-6 font-semibold text-gray-900 dark:text-gray-100">{{ $b->nama_bahan }}</td>
                            <td class="py-3 px-3 text-right font-semibold tabular-nums text-red-600 dark:text-red-400">{{ $b->jumlah_stok }} {{ $b->satuan }}</td>
                            <td class="py-3 px-3 text-right tabular-nums text-gray-500 dark:text-gray-400">{{ $b->stok_minimum }} {{ $b->satuan }}</td>
                            <td class="py-3 px-3 text-right tabular-nums text-gray-700 dark:text-gray-200">Rp {{ number_format($b->harga_beli_satuan ?? 0, 0, ',', '.') }} / {{ $b->satuan }}</td>
                            <td class="py-3 px-5 sm:px-6 text-right"><a href="{{ route('restock.index') }}" class="text-sm font-semibold text-indigo-600 dark:text-indigo-400 hover:underline underline-offset-4">Restock</a></td>
                        </tr>
                        @empty
                        <tr><td colspan="5" class="py-12 text-center text-gray-400">Semua persediaan bahan baku dalam kondisi aman.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>

        <section class="rounded-xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-800 p-5 sm:p-6">
            <div class="flex items-baseline justify-between gap-3">
                <h2 class="font-display text-xl font-extrabold text-gray-900 dark:text-gray-50">Masuk terakhir</h2>
                <a href="{{ route('restock.index') }}" class="text-sm font-semibold text-indigo-600 dark:text-indigo-400 hover:underline underline-offset-4">Semua</a>
            </div>
            <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">Lima pencatatan restock terbaru</p>
            <ul class="divide-y divide-gray-200 dark:divide-gray-700">
                @forelse($riwayatRestockTerbaru as $r)
                <li class="flex items-center justify-between gap-3 py-3">
                    <div class="min-w-0">
                        <p class="font-semibold text-gray-900 dark:text-gray-100 truncate">{{ $r->bahanBaku->nama_bahan ?? 'Bahan dihapus' }}</p>
                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ \Carbon\Carbon::parse($r->tanggal_waktu)->format('d M Y, H:i') }} · {{ $r->user->name ?? 'Staf' }}</p>
                    </div>
                    <span class="flex-shrink-0 text-sm font-semibold tabular-nums text-emerald-600 dark:text-emerald-400">+{{ $r->jumlah_tambah }} {{ $r->bahanBaku->satuan ?? '' }}</span>
                </li>
                @empty
                <li class="py-8 text-center text-sm text-gray-400">Belum ada riwayat restock.</li>
                @endforelse
            </ul>
        </section>
    </div>
</div>
