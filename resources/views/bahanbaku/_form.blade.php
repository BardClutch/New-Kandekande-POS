@php $b = $bahanbaku ?? null; @endphp
<form action="{{ $action }}" method="POST" class="space-y-6">
    @csrf
    @if($b) @method('PUT') @endif

    <div class="fld">
        <label for="nama_bahan">Nama bahan baku</label>
        <input type="text" name="nama_bahan" id="nama_bahan" value="{{ old('nama_bahan', $b->nama_bahan ?? '') }}" class="inp" placeholder="Contoh: Tepung segitiga biru" required @error('nama_bahan') aria-invalid="true" @enderror>
        @error('nama_bahan')<p class="hint !text-red-600">{{ $message }}</p>@enderror
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
        <div class="fld">
            <label for="jumlah_stok">{{ $b ? 'Sisa stok fisik' : 'Jumlah stok masuk' }}</label>
            <input type="number" step="0.01" name="jumlah_stok" id="jumlah_stok" value="{{ old('jumlah_stok', $b->jumlah_stok ?? '') }}" class="inp" placeholder="0" required>
            @error('jumlah_stok')<p class="hint !text-red-600">{{ $message }}</p>@enderror
        </div>
        <div class="fld">
            <label for="satuan">Satuan</label>
            <input type="text" name="satuan" id="satuan" value="{{ old('satuan', $b->satuan ?? '') }}" class="inp" placeholder="kg, gram, pcs, liter" required>
            @error('satuan')<p class="hint !text-red-600">{{ $message }}</p>@enderror
        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
        <div class="fld">
            <label for="harga_beli_satuan">Harga beli per satuan (Rp)</label>
            <input type="number" step="1" name="harga_beli_satuan" id="harga_beli_satuan" value="{{ old('harga_beli_satuan', $b->harga_beli_satuan ?? 0) }}" class="inp" placeholder="12000">
            <p class="hint">Dipakai untuk menghitung HPP resep secara otomatis.</p>
        </div>
        <div class="fld">
            <label for="stok_minimum">Batas minimum stok</label>
            <input type="number" step="0.01" name="stok_minimum" id="stok_minimum" value="{{ old('stok_minimum', $b->stok_minimum ?? 5) }}" class="inp" placeholder="5">
            <p class="hint">Peringatan muncul saat stok sama dengan atau di bawah batas ini.</p>
        </div>
    </div>

    <div class="flex items-center gap-4 pt-5 border-t border-gray-200 dark:border-gray-700">
        <x-btn type="submit">{{ $b ? 'Simpan perubahan' : 'Simpan ke gudang' }}</x-btn>
        <x-btn variant="link" :href="route('bahanbaku.index')">Batal</x-btn>
    </div>
</form>
