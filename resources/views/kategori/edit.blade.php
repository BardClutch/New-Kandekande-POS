<x-app-layout>
    <x-page title="Ubah kategori" narrow>
        <x-panel>
            <form action="{{ route('kategori.update', $kategori->id_kategori) }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')
                <div class="fld">
                    <label for="nama_kategori">Nama kategori</label>
                    <input type="text" name="nama_kategori" id="nama_kategori" value="{{ old('nama_kategori', $kategori->nama_kategori) }}" class="inp" required autofocus>
                </div>
                <div class="flex items-center gap-4 pt-5 border-t border-gray-200 dark:border-gray-700">
                    <x-btn type="submit">Simpan perubahan</x-btn>
                    <x-btn variant="link" :href="route('kategori.index')">Batal</x-btn>
                </div>
            </form>
        </x-panel>
    </x-page>
</x-app-layout>
