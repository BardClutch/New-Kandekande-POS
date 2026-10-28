<x-app-layout>
    <x-page title="Resep dan batch" sub="Atur kapasitas batch per produk dan komposisi bahan baku setiap resep.">
        <x-slot name="actions">
            <x-btn :href="route('resep.create')">Tambah komposisi</x-btn>
        </x-slot>

        <x-panel title="Pengaturan batch per produk" sub="Resep dihitung per batch (misal 1 resep jadi 20 pcs) atau per pesanan kustom (misal kue ulang tahun).">
            <div class="mt-4 grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4">
                @forelse($produks as $prod)
                    <div class="rounded-lg border border-gray-200 dark:border-gray-700 p-4 flex flex-col justify-between gap-4">
                        <div>
                            <div class="flex items-start justify-between gap-3">
                                <div class="min-w-0">
                                    <h3 class="font-display font-extrabold text-lg leading-tight text-gray-900 dark:text-gray-50">{{ $prod->nama_produk }}</h3>
                                    <p class="text-sm text-gray-500">{{ $prod->kategori->nama_kategori ?? 'Tanpa kategori' }}</p>
                                </div>
                                <span class="flex-shrink-0 text-sm font-semibold text-gray-600 dark:text-gray-300">
                                    {{ $prod->tipe_produksi === 'Kustom' ? 'Kustom' : 'Batch ' . $prod->jumlah_per_batch . ' pcs' }}
                                </span>
                            </div>

                            <div class="mt-3">
                                <p class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Bahan</p>
                                @if($prod->reseps->isNotEmpty())
                                    <ul class="divide-y divide-gray-200 dark:divide-gray-700 text-sm">
                                        @foreach($prod->reseps as $r)
                                            <li class="flex items-center justify-between py-1.5">
                                                <span class="text-gray-700 dark:text-gray-200">{{ $r->bahanBaku->nama_bahan ?? '-' }}</span>
                                                <span class="tabular-nums text-gray-500">{{ $r->takaran }} {{ $r->bahanBaku->satuan ?? '' }}</span>
                                            </li>
                                        @endforeach
                                    </ul>
                                @else
                                    <p class="text-sm text-gray-400">Belum ada komposisi bahan baku.</p>
                                @endif
                                <x-btn variant="link" class="mt-2 text-sm" :href="route('resep.create', ['id_produk' => $prod->id_produk])">Tambah bahan</x-btn>
                            </div>
                        </div>

                        <form action="{{ route('resep.updateBatch', $prod->id_produk) }}" method="POST" class="flex flex-wrap items-center gap-2 pt-4 border-t border-gray-200 dark:border-gray-700">
                            @csrf
                            @method('PATCH')
                            <select name="tipe_produksi" onchange="toggleBatchInput(this, '{{ $prod->id_produk }}')" class="inp !w-full !py-1.5">
                                <option value="Batch" {{ $prod->tipe_produksi !== 'Kustom' ? 'selected' : '' }}>Mode batch</option>
                                <option value="Kustom" {{ $prod->tipe_produksi === 'Kustom' ? 'selected' : '' }}>Pesanan kustom</option>
                            </select>
                            <div id="container_batch_{{ $prod->id_produk }}" class="{{ $prod->tipe_produksi === 'Kustom' ? 'hidden' : 'flex' }} items-center gap-1.5">
                                <input type="number" name="jumlah_per_batch" value="{{ $prod->jumlah_per_batch ?? 1 }}" min="1" class="inp !w-20 !py-1.5" title="Hasil pcs per 1 batch resep">
                                <span class="text-sm text-gray-500">pcs</span>
                            </div>
                            <x-btn type="submit" size="sm" class="ml-auto flex-shrink-0">Simpan</x-btn>
                        </form>
                    </div>
                @empty
                    <p class="text-gray-400">Belum ada produk terdaftar.</p>
                @endforelse
            </div>
        </x-panel>

        <x-panel flush title="Komposisi resep" sub="Takaran bahan per 1 batch resep atau per 1 unit kue kustom.">
            <div class="overflow-x-auto border-t border-gray-200 dark:border-gray-700">
                <table class="tbl">
                    <thead>
                        <tr>
                            <th>Produk</th>
                            <th>Bahan baku</th>
                            <th class="r">Takaran</th>
                            <th class="r">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($reseps as $item)
                            <tr>
                                <td>
                                    <p class="font-semibold text-gray-900 dark:text-gray-100">{{ $item->produk->nama_produk ?? '-' }}</p>
                                    <p class="text-xs text-gray-500">#{{ $item->id_resep }} · {{ ($item->produk->tipe_produksi ?? '') === 'Kustom' ? 'Pesanan kustom' : 'Batch ' . ($item->produk->jumlah_per_batch ?? 1) . ' pcs' }}</p>
                                </td>
                                <td class="text-gray-700 dark:text-gray-200">{{ $item->bahanBaku->nama_bahan ?? '-' }}</td>
                                <td class="r num font-semibold">{{ $item->takaran }} <span class="font-normal text-gray-400">{{ $item->bahanBaku->satuan ?? '' }}</span></td>
                                <td class="r">
                                    <div class="inline-flex items-center gap-4">
                                        <x-btn variant="link" :href="route('resep.edit', $item->id_resep)">Ubah</x-btn>
                                        <form action="{{ route('resep.destroy', $item->id_resep) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-sm font-semibold text-red-600 dark:text-red-400 hover:underline underline-offset-4" onclick="return confirm('Hapus komposisi resep ini?')">Hapus</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="py-14 text-center text-gray-400">Buku resep masih kosong. Tambahkan resep pertama.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </x-panel>
    </x-page>

    <script>
        function toggleBatchInput(selectElement, id) {
            const container = document.getElementById('container_batch_' + id);
            if (selectElement.value === 'Kustom') {
                container.classList.add('hidden');
                container.classList.remove('flex');
            } else {
                container.classList.remove('hidden');
                container.classList.add('flex');
            }
        }
    </script>
</x-app-layout>
