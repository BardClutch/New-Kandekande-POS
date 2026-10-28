<x-app-layout>
    <x-page :title="Auth::user()->isOwner() ? 'Laporan penjualan' : 'Riwayat transaksi'"
            :sub="Auth::user()->isOwner() ? 'Pantau performa penjualan dan ekspor data transaksi.' : 'Daftar transaksi penjualan beserta cetak ulang struk.'">
        @if(Auth::user()->isOwner())
            <x-slot name="actions">
                <x-btn variant="secondary" :href="route('laporan.export', request()->all())">Ekspor CSV</x-btn>
            </x-slot>
        @endif

        <!-- Filter periode -->
        <form action="{{ route('laporan.index') }}" method="GET" class="flex flex-wrap items-end gap-4">
            <div class="fld">
                <label for="start_date">Dari tanggal</label>
                <input type="date" id="start_date" name="start_date" value="{{ $startDate->format('Y-m-d') }}" class="inp !w-auto">
            </div>
            <div class="fld">
                <label for="end_date">Sampai tanggal</label>
                <input type="date" id="end_date" name="end_date" value="{{ $endDate->format('Y-m-d') }}" class="inp !w-auto">
            </div>
            <x-btn type="submit">Terapkan</x-btn>
        </form>

        <x-stats :class="Auth::user()->isOwner() ? '' : 'lg:!grid-cols-1 sm:max-w-xs'">
            <x-stat label="Total transaksi" :value="number_format($totalTransaksi)" sub="Transaksi kasir tercatat" />
            @if(Auth::user()->isOwner())
                <x-stat label="Total omset" :value="'Rp ' . number_format($totalPendapatan, 0, ',', '.')" sub="Pendapatan kotor" />
                <x-stat label="HPP bahan baku" :value="'Rp ' . number_format($totalHpp, 0, ',', '.')" sub="Berdasarkan resep terpakai" />
                <x-stat label="Laba kotor" :value="'Rp ' . number_format($labaKotor, 0, ',', '.')" :sub="'Margin ' . $marginPersen . '%'" tone="good" />
            @endif
        </x-stats>

        <div x-data="{ activeTab: '{{ Auth::user()->isOwner() ? 'produk' : 'transaksi' }}' }" class="space-y-5">
            @if(Auth::user()->isOwner())
                <div class="flex items-center gap-1 border-b border-gray-200 dark:border-gray-800" role="tablist">
                    <button type="button" role="tab" @click="activeTab = 'produk'"
                            :class="activeTab === 'produk' ? 'border-indigo-600 text-gray-900 dark:text-gray-50' : 'border-transparent text-gray-500 hover:text-gray-900 dark:hover:text-gray-100'"
                            class="px-4 py-3 -mb-px text-sm font-semibold border-b-2 transition-colors">
                        Laba per menu <span class="ml-1 text-gray-400 tabular-nums">{{ count($laporanProduk) }}</span>
                    </button>
                    <button type="button" role="tab" @click="activeTab = 'transaksi'"
                            :class="activeTab === 'transaksi' ? 'border-indigo-600 text-gray-900 dark:text-gray-50' : 'border-transparent text-gray-500 hover:text-gray-900 dark:hover:text-gray-100'"
                            class="px-4 py-3 -mb-px text-sm font-semibold border-b-2 transition-colors">
                        Riwayat transaksi <span class="ml-1 text-gray-400 tabular-nums">{{ $totalTransaksi }}</span>
                    </button>
                </div>
            @endif

            @if(Auth::user()->isOwner())
                <x-panel flush x-show="activeTab === 'produk'" title="Profitabilitas dan HPP per menu" sub="HPP dihitung dari harga beli bahan baku di resep standar.">
                    <div class="overflow-x-auto border-t border-gray-200 dark:border-gray-700">
                        <table class="tbl">
                            <thead>
                                <tr>
                                    <th>Menu</th>
                                    <th>Tipe</th>
                                    <th class="r">Terjual</th>
                                    <th class="r">Harga jual</th>
                                    <th class="r">HPP satuan</th>
                                    <th class="r">Omset</th>
                                    <th class="r">Total HPP</th>
                                    <th class="r">Laba kotor</th>
                                    <th class="r">Margin</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($laporanProduk as $item)
                                    @php $marginItemPersen = $item['total_omset'] > 0 ? round(($item['total_laba'] / $item['total_omset']) * 100, 1) : 0; @endphp
                                    <tr>
                                        <td class="font-semibold text-gray-900 dark:text-gray-100">{{ $item['nama_produk'] }}</td>
                                        <td class="text-gray-500">{{ $item['tipe_produksi'] }}</td>
                                        <td class="r num">{{ number_format($item['qty_terjual']) }} pcs</td>
                                        <td class="r num">Rp {{ number_format($item['harga_jual_rata'], 0, ',', '.') }}</td>
                                        <td class="r num">Rp {{ number_format($item['hpp_satuan'], 0, ',', '.') }}</td>
                                        <td class="r num">Rp {{ number_format($item['total_omset'], 0, ',', '.') }}</td>
                                        <td class="r num">Rp {{ number_format($item['total_hpp'], 0, ',', '.') }}</td>
                                        <td class="r num font-semibold {{ $item['total_laba'] < 0 ? 'text-red-600 dark:text-red-400' : 'text-emerald-600 dark:text-emerald-400' }}">Rp {{ number_format($item['total_laba'], 0, ',', '.') }}</td>
                                        <td class="r num">{{ $marginItemPersen }}%</td>
                                    </tr>
                                @empty
                                    <tr><td colspan="9" class="py-14 text-center text-gray-400">Tidak ada penjualan produk pada rentang tanggal ini.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </x-panel>
            @endif

            <x-panel flush x-show="activeTab === 'transaksi'" :title="Auth::user()->isOwner() ? 'Rincian transaksi kasir' : 'Daftar transaksi'" sub="Struk transaksi beserta opsi cetak ulang.">
                <div class="overflow-x-auto border-t border-gray-200 dark:border-gray-700">
                    <table class="tbl">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Waktu</th>
                                <th>Kasir</th>
                                <th>Tipe</th>
                                <th>Metode</th>
                                <th class="r">Total</th>
                                @if(Auth::user()->isOwner())
                                    <th class="r">HPP</th>
                                    <th class="r">Laba kotor</th>
                                @endif
                                <th class="r">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($transaksis as $trx)
                                @php
                                    $trxHpp = 0;
                                    if (Auth::user()->isOwner()) {
                                        foreach($trx->detailTransaksis as $dtl) {
                                            $prodHpp = $dtl->produk ? $dtl->produk->hitungHpp() : 0;
                                            $trxHpp += ($prodHpp * $dtl->qty_beli);
                                        }
                                    }
                                    $trxLaba = $trx->total_belanja - $trxHpp;
                                @endphp
                                <tr>
                                    <td class="font-mono font-semibold">#{{ $trx->id_transaksi }}</td>
                                    <td class="num text-gray-500 whitespace-nowrap">{{ $trx->tanggal_waktu->format('d M Y, H:i') }}</td>
                                    <td>{{ $trx->user->name ?? '-' }}</td>
                                    <td class="text-gray-600 dark:text-gray-300">{{ $trx->jenis_pesanan }}</td>
                                    <td class="text-gray-600 dark:text-gray-300">{{ $trx->metode_pembayaran }}</td>
                                    <td class="r num font-semibold text-gray-900 dark:text-gray-100">Rp {{ number_format($trx->total_belanja, 0, ',', '.') }}</td>
                                    @if(Auth::user()->isOwner())
                                        <td class="r num text-gray-500">Rp {{ number_format($trxHpp, 0, ',', '.') }}</td>
                                        <td class="r num font-semibold text-emerald-600 dark:text-emerald-400">Rp {{ number_format($trxLaba, 0, ',', '.') }}</td>
                                    @endif
                                    <td class="r"><a href="{{ route('kasir.cetak', $trx->id_transaksi) }}" target="_blank" class="text-sm font-semibold text-indigo-600 dark:text-indigo-400 hover:underline underline-offset-4">Cetak</a></td>
                                </tr>
                            @empty
                                <tr><td colspan="{{ Auth::user()->isOwner() ? 9 : 7 }}" class="py-14 text-center text-gray-400">Tidak ada transaksi pada rentang tanggal ini.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </x-panel>
        </div>
    </x-page>
</x-app-layout>
