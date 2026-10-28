<x-app-layout>
    <div x-data="{ modalTambah: false }">
        <x-page title="Pelanggan" sub="Segmentasi daya beli (baru, reguler, VIP) dan riwayat belanja pelanggan toko.">
            <x-slot name="actions">
                <x-btn @click="modalTambah = true">Tambah pelanggan</x-btn>
            </x-slot>

            <x-stats>
                <x-stat label="Total pelanggan" :value="$totalPelanggan" sub="Terdaftar di sistem" />
                <x-stat label="Pelanggan VIP" :value="$totalVip" sub="Belanja minimal Rp 750 rb atau 5 kali" tone="accent" />
                <x-stat label="Pelanggan reguler" :value="$totalReguler" sub="Belanja minimal Rp 250 rb atau 2 kali" />
                <x-stat label="Daya beli rata-rata" :value="'Rp ' . number_format($rataRataDayaBeli, 0, ',', '.')" sub="Nilai per transaksi" />
            </x-stats>

            <form action="{{ route('pelanggan.index') }}" method="GET" class="flex flex-wrap items-center gap-3">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama atau nomor WhatsApp" class="inp !w-full sm:!w-80">
                <select name="tier" class="inp !w-auto">
                    <option value="">Semua tier</option>
                    <option value="VIP" {{ request('tier') == 'VIP' ? 'selected' : '' }}>VIP</option>
                    <option value="Reguler" {{ request('tier') == 'Reguler' ? 'selected' : '' }}>Reguler</option>
                    <option value="Baru" {{ request('tier') == 'Baru' ? 'selected' : '' }}>Baru</option>
                </select>
                <x-btn type="submit" variant="secondary">Filter</x-btn>
                @if(request()->hasAny(['search', 'tier']))<x-btn variant="link" :href="route('pelanggan.index')">Reset</x-btn>@endif
            </form>

            <x-panel flush>
                <div class="overflow-x-auto">
                    <table class="tbl">
                        <thead>
                            <tr>
                                <th>Pelanggan</th>
                                <th>Tier</th>
                                <th class="r">Kunjungan</th>
                                <th class="r">Total belanja</th>
                                <th class="r hidden xl:table-cell">Rata-rata</th>
                                <th>Menu favorit</th>
                                <th class="r">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($pelanggans as $cust)
                                @php
                                    $productCounts = [];
                                    foreach ($cust->transaksis as $trx) {
                                        foreach ($trx->detailTransaksis as $d) {
                                            $prodName = $d->produk->nama_produk ?? null;
                                            if ($prodName) { $productCounts[$prodName] = ($productCounts[$prodName] ?? 0) + $d->qty_beli; }
                                        }
                                    }
                                    arsort($productCounts);
                                    $topProducts = array_slice($productCounts, 0, 2, true);
                                    $wa = $cust->no_hp ? 'https://wa.me/' . preg_replace('/^0/', '62', preg_replace('/[^0-9]/', '', $cust->no_hp)) : null;
                                @endphp
                                <tr>
                                    <td>
                                        <p class="font-semibold text-gray-900 dark:text-gray-100">{{ $cust->nama_pelanggan }}</p>
                                        @if($wa)<a href="{{ $wa }}" target="_blank" class="text-sm text-gray-500 hover:text-indigo-600 dark:hover:text-indigo-400">{{ $cust->no_hp }}</a>@else<span class="text-sm text-gray-400">-</span>@endif
                                    </td>
                                    <td>
                                        @if($cust->tipe_pelanggan == 'VIP')
                                            <span class="font-semibold text-indigo-600 dark:text-indigo-400">VIP</span>
                                        @elseif($cust->tipe_pelanggan == 'Reguler')
                                            <span class="font-medium text-gray-700 dark:text-gray-200">Reguler</span>
                                        @else
                                            <span class="text-gray-500">Baru</span>
                                        @endif
                                    </td>
                                    <td class="r num">{{ $cust->total_transaksi }}</td>
                                    <td class="r num font-semibold text-gray-900 dark:text-gray-100">Rp {{ number_format($cust->total_belanja, 0, ',', '.') }}</td>
                                    <td class="r num hidden xl:table-cell">Rp {{ number_format($cust->rata_rata_belanja, 0, ',', '.') }}</td>
                                    <td class="text-gray-600 dark:text-gray-300">
                                        @forelse($topProducts as $pName => $qty)<span class="block">{{ $pName }} <span class="text-gray-400">({{ $qty }})</span></span>@empty<span class="text-gray-400">Belum ada item</span>@endforelse
                                    </td>
                                    <td class="r">
                                        <div class="inline-flex items-center gap-4">
                                            @if($wa)<a href="{{ $wa }}" target="_blank" class="text-sm font-semibold text-indigo-600 dark:text-indigo-400 hover:underline underline-offset-4">WhatsApp</a>@endif
                                            <form action="{{ route('pelanggan.destroy', $cust->id_pelanggan) }}" method="POST" onsubmit="return confirm('Hapus profil pelanggan {{ addslashes($cust->nama_pelanggan) }}?')" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-sm font-semibold text-red-600 dark:text-red-400 hover:underline underline-offset-4">Hapus</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="7" class="py-14 text-center text-gray-400">Belum ada pelanggan. Saat kasir mengisi nama pembeli, datanya muncul di sini otomatis.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </x-panel>
        </x-page>

        <x-dialog show="modalTambah" title="Tambah pelanggan">
            <form action="{{ route('pelanggan.store') }}" method="POST" class="space-y-5">
                @csrf
                <div class="fld"><label>Nama lengkap</label><input type="text" name="nama_pelanggan" required placeholder="Ibu Ani" class="inp"></div>
                <div class="fld"><label>Nomor WhatsApp atau HP</label><input type="text" name="no_hp" placeholder="08123456789" class="inp"></div>
                <div class="fld"><label>Catatan (opsional)</label><textarea name="catatan" rows="3" class="inp" placeholder="Misal: alergi kacang"></textarea></div>
                <div class="flex items-center justify-end gap-3 pt-2">
                    <x-btn variant="secondary" @click="modalTambah = false">Batal</x-btn>
                    <x-btn type="submit">Simpan</x-btn>
                </div>
            </form>
        </x-dialog>
    </div>
</x-app-layout>
