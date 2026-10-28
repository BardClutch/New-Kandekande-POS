<x-app-layout>
    <x-page title="Produk" sub="Daftar roti dan kue yang dijual di etalase.">
        <x-slot name="actions">
            <x-btn :href="route('produk.create')">Daftarkan produk</x-btn>
        </x-slot>

        <x-panel flush>
            <div class="overflow-x-auto">
                <table class="tbl">
                    <thead>
                        <tr>
                            <th>Produk</th>
                            <th>Kategori</th>
                            <th class="r">Harga jual</th>
                            <th class="r">HPP dan margin</th>
                            <th class="r">Etalase</th>
                            <th class="r">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($produks as $item)
                            @php
                                $hpp = $item->hpp;
                                $margin = $item->margin_laba;
                                $persen = $item->persentase_margin;
                                $isMenipis = $item->isStokMenipis();
                            @endphp
                            <tr>
                                <td>
                                    <p class="font-semibold text-gray-900 dark:text-gray-100">{{ $item->nama_produk }}</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">
                                        #{{ $item->id_produk }} ·
                                        @if($item->tipe_produksi === 'Kustom') Pesanan kustom @else {{ $item->jumlah_per_batch ?? 1 }} pcs per batch @endif
                                    </p>
                                </td>
                                <td class="text-gray-600 dark:text-gray-300">{{ $item->kategori->nama_kategori ?? 'Tanpa kategori' }}</td>
                                <td class="r num font-semibold text-gray-900 dark:text-gray-100">Rp {{ number_format($item->harga_dasar, 0, ',', '.') }}</td>
                                <td class="r num">
                                    <p class="text-gray-700 dark:text-gray-200">Rp {{ number_format($hpp, 0, ',', '.') }}</p>
                                    <p class="text-xs text-emerald-600 dark:text-emerald-400">+Rp {{ number_format($margin, 0, ',', '.') }} ({{ $persen }}%)</p>
                                </td>
                                <td class="r num">
                                    <span class="font-semibold {{ $isMenipis ? 'text-red-600 dark:text-red-400' : 'text-gray-900 dark:text-gray-100' }}">{{ $item->stok_etalase }}</span>
                                    @if($isMenipis)<p class="text-xs text-red-600 dark:text-red-400">menipis</p>@endif
                                </td>
                                <td class="r">
                                    <div class="inline-flex items-center gap-4">
                                        <x-btn variant="link" :href="route('produk.edit', $item->id_produk)">Ubah</x-btn>
                                        <form action="{{ route('produk.destroy', $item->id_produk) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-sm font-semibold text-red-600 dark:text-red-400 hover:underline underline-offset-4" onclick="return confirm('Yakin ingin mematikan produk ini?')">Hapus</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="6" class="py-14 text-center text-gray-400">Belum ada produk di etalase. Daftarkan produk pertama.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </x-panel>
    </x-page>
</x-app-layout>
