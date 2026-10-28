@php
    $p = $produk ?? null;
    $tipe = old('tipe_produksi', $p->tipe_produksi ?? 'Batch');
@endphp
<form action="{{ $action }}" method="POST" class="space-y-6">
    @csrf
    @if($p) @method('PUT') @endif

    <div class="fld">
        <label for="nama_produk">Nama produk</label>
        <input type="text" name="nama_produk" id="nama_produk" value="{{ old('nama_produk', $p->nama_produk ?? '') }}" class="inp" placeholder="Contoh: Roti cokelat keju" required>
        @error('nama_produk')<p class="hint !text-red-600">{{ $message }}</p>@enderror
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
        <div class="fld">
            <label for="id_kategori">Kategori</label>
            <select name="id_kategori" id="id_kategori" class="inp" required>
                @unless($p)<option value="">Pilih kategori</option>@endunless
                @foreach($kategoris as $kategori)
                    <option value="{{ $kategori->id_kategori }}" {{ old('id_kategori', $p->id_kategori ?? null) == $kategori->id_kategori ? 'selected' : '' }}>{{ $kategori->nama_kategori }}</option>
                @endforeach
            </select>
            @error('id_kategori')<p class="hint !text-red-600">{{ $message }}</p>@enderror
        </div>
        <div class="fld">
            <label for="harga_dasar">Harga jual satuan (Rp)</label>
            <input type="number" name="harga_dasar" id="harga_dasar" value="{{ old('harga_dasar', $p->harga_dasar ?? '') }}" class="inp" placeholder="15000" required>
            @error('harga_dasar')<p class="hint !text-red-600">{{ $message }}</p>@enderror
        </div>
    </div>

    <div class="fld">
        <span class="lbl">Sistem produksi di dapur</span>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
            <label class="flex items-start gap-3 rounded-lg border border-gray-300 dark:border-gray-600 p-4 cursor-pointer has-[:checked]:border-indigo-600 has-[:checked]:bg-indigo-50 dark:has-[:checked]:bg-indigo-950/30">
                <input type="radio" name="tipe_produksi" value="Batch" id="radio_batch" {{ $tipe !== 'Kustom' ? 'checked' : '' }} class="mt-1 accent-[#D4501E]" onchange="toggleBatchField(true)">
                <span>
                    <span class="block font-semibold text-gray-900 dark:text-gray-100">Sistem batch</span>
                    <span class="block text-sm text-gray-500 dark:text-gray-400">Produk standar massal: roti manis, donat, pastry harian.</span>
                </span>
            </label>
            <label class="flex items-start gap-3 rounded-lg border border-gray-300 dark:border-gray-600 p-4 cursor-pointer has-[:checked]:border-indigo-600 has-[:checked]:bg-indigo-50 dark:has-[:checked]:bg-indigo-950/30">
                <input type="radio" name="tipe_produksi" value="Kustom" id="radio_kustom" {{ $tipe === 'Kustom' ? 'checked' : '' }} class="mt-1 accent-[#D4501E]" onchange="toggleBatchField(false)">
                <span>
                    <span class="block font-semibold text-gray-900 dark:text-gray-100">Pesanan kustom</span>
                    <span class="block text-sm text-gray-500 dark:text-gray-400">Kue ulang tahun atau pesanan khusus per pelanggan.</span>
                </span>
            </label>
        </div>

        <div id="wrapper_jumlah_per_batch" class="mt-4 {{ $tipe === 'Kustom' ? 'hidden' : 'block' }}">
            <label for="jumlah_per_batch" class="!font-medium">Hasil jadi per 1 batch resep</label>
            <div class="flex items-center gap-3">
                <input type="number" name="jumlah_per_batch" id="jumlah_per_batch" min="1" value="{{ old('jumlah_per_batch', $p->jumlah_per_batch ?? 1) }}" class="inp !w-28">
                <span class="text-sm text-gray-500 dark:text-gray-400">pcs per adonan resep</span>
            </div>
            <p class="hint">Contoh: 1 batch adonan menghasilkan 20 pcs roti.</p>
        </div>
    </div>

    <script>
        function toggleBatchField(isBatch) {
            const wrapper = document.getElementById('wrapper_jumlah_per_batch');
            if (isBatch) { wrapper.classList.remove('hidden'); } else { wrapper.classList.add('hidden'); }
        }
    </script>

    <div class="flex items-center gap-4 pt-5 border-t border-gray-200 dark:border-gray-700">
        <x-btn type="submit">{{ $p ? 'Simpan perubahan' : 'Daftarkan produk' }}</x-btn>
        <x-btn variant="link" :href="route('produk.index')">Batal</x-btn>
    </div>
</form>
