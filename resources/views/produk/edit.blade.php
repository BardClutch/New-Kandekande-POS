<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight transition-colors duration-300">
            {{ __('Ubah Informasi Produk') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-xl border-t-4 border-amber-500 dark:border-amber-600 p-8 transition-colors duration-300">
                <form action="{{ route('produk.update', $produk->id_produk) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-6">
                        <label for="nama_produk" class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2 transition-colors">Nama Produk Roti</label>
                        <input type="text" name="nama_produk" id="nama_produk" value="{{ old('nama_produk', $produk->nama_produk) }}" class="shadow-sm appearance-none border border-gray-300 dark:border-gray-600 rounded-lg w-full py-3 px-4 bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-200 leading-tight focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition-colors @error('nama_produk') border-red-500 dark:border-red-500 focus:ring-red-500 @enderror" required>
                        @error('nama_produk')
                            <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div class="mb-6">
                        <label for="id_kategori" class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2 transition-colors">Kategori Induk</label>
                        <select name="id_kategori" id="id_kategori" class="shadow-sm border border-gray-300 dark:border-gray-600 rounded-lg w-full py-3 px-4 bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-200 leading-tight focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition-colors @error('id_kategori') border-red-500 dark:border-red-500 focus:ring-red-500 @enderror" required>
                            @foreach($kategoris as $kategori)
                                <option value="{{ $kategori->id_kategori }}" {{ old('id_kategori', $produk->id_kategori) == $kategori->id_kategori ? 'selected' : '' }}>
                                    {{ $kategori->nama_kategori }}
                                </option>
                            @endforeach
                        </select>
                        @error('id_kategori')
                            <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div class="mb-6">
                        <label for="harga_dasar" class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2 transition-colors">Penyesuaian Harga Jual (Rp)</label>
                        <input type="number" name="harga_dasar" id="harga_dasar" value="{{ old('harga_dasar', $produk->harga_dasar) }}" class="shadow-sm appearance-none border border-gray-300 dark:border-gray-600 rounded-lg w-full py-3 px-4 bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-200 leading-tight focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition-colors @error('harga_dasar') border-red-500 dark:border-red-500 focus:ring-red-500 @enderror" required>
                        @error('harga_dasar')
                            <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-6 p-4 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50/70 dark:bg-gray-750/50">
                        <label class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2 transition-colors">
                            Sistem Produksi di Dapur
                        </label>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 mb-4">
                            <label class="flex items-start p-3 bg-white dark:bg-gray-700 rounded-lg border border-gray-200 dark:border-gray-600 cursor-pointer hover:border-amber-400">
                                <input type="radio" name="tipe_produksi" value="Batch" id="radio_batch" {{ old('tipe_produksi', $produk->tipe_produksi) !== 'Kustom' ? 'checked' : '' }} class="mt-1 text-amber-600 focus:ring-amber-500" onchange="toggleBatchField(true)">
                                <div class="ml-3">
                                    <span class="block text-sm font-bold text-gray-800 dark:text-gray-200">📦 Sistem Batch</span>
                                    <span class="block text-xs text-gray-500 dark:text-gray-400">Produk standar massal (Roti manis, donat, pastry harian).</span>
                                </div>
                            </label>
                            <label class="flex items-start p-3 bg-white dark:bg-gray-700 rounded-lg border border-gray-200 dark:border-gray-600 cursor-pointer hover:border-purple-400">
                                <input type="radio" name="tipe_produksi" value="Kustom" id="radio_kustom" {{ old('tipe_produksi', $produk->tipe_produksi) === 'Kustom' ? 'checked' : '' }} class="mt-1 text-purple-600 focus:ring-purple-500" onchange="toggleBatchField(false)">
                                <div class="ml-3">
                                    <span class="block text-sm font-bold text-gray-800 dark:text-gray-200">🎂 Pesanan Kustom</span>
                                    <span class="block text-xs text-gray-500 dark:text-gray-400">Kue ulang tahun / pesanan khusus per pelanggan.</span>
                                </div>
                            </label>
                        </div>

                        <div id="wrapper_jumlah_per_batch" class="{{ old('tipe_produksi', $produk->tipe_produksi) === 'Kustom' ? 'hidden' : 'block' }}">
                            <label for="jumlah_per_batch" class="block text-gray-700 dark:text-gray-300 text-xs font-bold mb-1 transition-colors">
                                Hasil Jadi per 1 Batch Resep (Pcs / Unit)
                            </label>
                            <div class="flex items-center gap-2">
                                <input type="number" name="jumlah_per_batch" id="jumlah_per_batch" min="1" value="{{ old('jumlah_per_batch', $produk->jumlah_per_batch ?? 1) }}" class="shadow-sm border border-gray-300 dark:border-gray-600 rounded-lg w-32 py-2 px-3 bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-200 text-sm leading-tight focus:ring-2 focus:ring-amber-500">
                                <span class="text-xs text-gray-500 dark:text-gray-400">pcs roti/kue per adonan resep</span>
                            </div>
                            <p class="text-[11px] text-gray-400 dark:text-gray-500 mt-1">
                                Contoh: 1 batch adonan resep menghasilkan 20 pcs roti.
                            </p>
                        </div>
                    </div>

                    <script>
                        function toggleBatchField(isBatch) {
                            const wrapper = document.getElementById('wrapper_jumlah_per_batch');
                            if (isBatch) {
                                wrapper.classList.remove('hidden');
                            } else {
                                wrapper.classList.add('hidden');
                            }
                        }
                    </script>
                    
                    <div class="flex items-center gap-4 pt-4 border-t border-gray-200 dark:border-gray-700 transition-colors mt-6">
                        <button type="submit" class="bg-amber-500 hover:bg-amber-600 text-white font-bold py-2.5 px-6 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-amber-500 dark:focus:ring-offset-gray-800 transition-all">
                            Simpan Perubahan
                        </button>
                        <a href="{{ route('produk.index') }}" class="inline-block align-baseline font-bold text-sm text-gray-500 hover:text-gray-800 dark:text-gray-400 dark:hover:text-gray-200 transition-colors">
                            Batalkan
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
