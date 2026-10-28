<x-app-layout>
    <x-page title="Ubah komposisi resep" narrow>
        <x-panel>
            <form action="{{ route('resep.update', $resep->id_resep) }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')

                <div class="fld">
                    <label for="id_produk">Produk roti</label>
                    <select name="id_produk" id="id_produk" class="inp" required>
                        <option value="">Pilih produk</option>
                        @foreach($produks as $produk)
                            <option value="{{ $produk->id_produk }}" {{ old('id_produk', $resep->id_produk) == $produk->id_produk ? 'selected' : '' }}>{{ $produk->nama_produk }}</option>
                        @endforeach
                    </select>
                    @error('id_produk')<p class="hint !text-red-600">{{ $message }}</p>@enderror
                </div>

                <div class="fld">
                    <label for="id_bahan">Bahan baku</label>
                    <select name="id_bahan" id="id_bahan" class="inp" required>
                        <option value="">Pilih bahan baku</option>
                        @foreach($bahans as $bahan)
                            <option value="{{ $bahan->id_bahan }}" {{ old('id_bahan', $resep->id_bahan) == $bahan->id_bahan ? 'selected' : '' }}>{{ $bahan->nama_bahan }} ({{ $bahan->satuan }})</option>
                        @endforeach
                    </select>
                    @error('id_bahan')<p class="hint !text-red-600">{{ $message }}</p>@enderror
                </div>

                <div class="fld">
                    <label for="takaran">Takaran (per 1 batch atau 1 unit kustom)</label>
                    <input type="number" step="0.01" name="takaran" id="takaran" value="{{ old('takaran', $resep->takaran) }}" class="inp" placeholder="0.5 atau 2" required>
                    <p class="hint">Untuk produk batch, takaran ini untuk 1 batch adonan. Untuk pesanan kustom, takaran untuk 1 unit kue.</p>
                    @error('takaran')<p class="hint !text-red-600">{{ $message }}</p>@enderror
                </div>

                <div class="flex items-center gap-4 pt-5 border-t border-gray-200 dark:border-gray-700">
                    <x-btn type="submit">Perbarui komposisi</x-btn>
                    <x-btn variant="link" :href="route('resep.index')">Batal</x-btn>
                </div>
            </form>
        </x-panel>
    </x-page>
</x-app-layout>
