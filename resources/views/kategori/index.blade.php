<x-app-layout>
    <x-page title="Kategori" sub="Kelompokkan produk supaya mudah dicari di kasir dan katalog.">
        <x-slot name="actions">
            <x-btn :href="route('kategori.create')">Tambah kategori</x-btn>
        </x-slot>

        <x-panel flush>
            <table class="tbl">
                <thead>
                    <tr>
                        <th>Kategori</th>
                        <th class="r">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($kategoris as $kategori)
                        <tr>
                            <td><p class="font-semibold text-gray-900 dark:text-gray-100">{{ $kategori->nama_kategori }}</p><p class="text-xs text-gray-400">#{{ $kategori->id_kategori }}</p></td>
                            <td class="r">
                                <div class="inline-flex items-center gap-4">
                                    <x-btn variant="link" :href="route('kategori.edit', $kategori->id_kategori)">Ubah</x-btn>
                                    <form action="{{ route('kategori.destroy', $kategori->id_kategori) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-sm font-semibold text-red-600 dark:text-red-400 hover:underline underline-offset-4" onclick="return confirm('Hapus kategori {{ addslashes($kategori->nama_kategori) }}?')">Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="2" class="py-14 text-center text-gray-400">Belum ada kategori. Klik "Tambah kategori" untuk membuat yang pertama.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </x-panel>
    </x-page>
</x-app-layout>
