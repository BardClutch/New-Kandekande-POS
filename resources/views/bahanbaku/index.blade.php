<x-app-layout>
    <x-page title="Bahan baku" sub="Pantau stok fisik bahan baku di gudang.">
        <x-slot name="actions">
            <x-btn variant="secondary" :href="route('restock.index')">Riwayat restock</x-btn>
            <x-btn :href="route('bahanbaku.create')">Catat bahan masuk</x-btn>
        </x-slot>

        <x-panel flush>
            <div class="overflow-x-auto">
                <table class="tbl">
                    <thead>
                        <tr>
                            <th>Bahan</th>
                            <th class="r hidden sm:table-cell">Harga beli</th>
                            <th class="r">Stok</th>
                            <th class="r hidden md:table-cell">Minimum</th>
                            <th class="r">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($bahanbaku as $bahan)
                            @php $isMenipis = $bahan->isStokMenipis(); @endphp
                            <tr>
                                <td>
                                    <p class="font-semibold text-gray-900 dark:text-gray-100">{{ $bahan->nama_bahan }}</p>
                                    <p class="text-xs text-gray-400">#{{ $bahan->id_bahan }}@if($isMenipis) <span class="font-semibold text-red-600 dark:text-red-400">· stok menipis</span>@endif</p>
                                    <p class="text-xs text-gray-400 sm:hidden">Rp {{ number_format($bahan->harga_beli_satuan ?? 0, 0, ',', '.') }} / {{ $bahan->satuan }}</p>
                                </td>
                                <td class="r num hidden sm:table-cell">Rp {{ number_format($bahan->harga_beli_satuan ?? 0, 0, ',', '.') }} <span class="text-gray-400">/ {{ $bahan->satuan }}</span></td>
                                <td class="r num font-semibold {{ $isMenipis ? 'text-red-600 dark:text-red-400' : 'text-gray-900 dark:text-gray-100' }}">{{ $bahan->jumlah_stok }} <span class="font-normal text-gray-400">{{ $bahan->satuan }}</span></td>
                                <td class="r num text-gray-500 hidden md:table-cell">{{ $bahan->stok_minimum ?? 5 }} {{ $bahan->satuan }}</td>
                                <td class="r">
                                    <div class="inline-flex items-center gap-3 sm:gap-4">
                                        <x-btn variant="link" :href="route('bahanbaku.edit', $bahan->id_bahan)">Ubah</x-btn>
                                        <form action="{{ route('bahanbaku.destroy', $bahan->id_bahan) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-sm font-semibold text-red-600 dark:text-red-400 hover:underline underline-offset-4" onclick="return confirm('Menghapus bahan baku ini akan berdampak pada master resep. Lanjutkan?')">Hapus</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="py-14 text-center text-gray-400">Belum ada bahan baku di gudang.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </x-panel>
    </x-page>
</x-app-layout>
