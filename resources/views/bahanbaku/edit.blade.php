<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight transition-colors duration-300">
            {{ __('Ubah Master Data Bahan Baku') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-xl border-t-4 border-amber-500 dark:border-amber-600 p-8 transition-colors duration-300">
                <form action="{{ route('bahanbaku.update', $bahanbaku->id_bahan) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-6">
                        <label for="nama_bahan" class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2 transition-colors">Nama Bahan Baku</label>
                        <input type="text" name="nama_bahan" id="nama_bahan" value="{{ old('nama_bahan', $bahanbaku->nama_bahan) }}" class="shadow-sm appearance-none border border-gray-300 dark:border-gray-600 rounded-lg w-full py-3 px-4 bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-200 leading-tight focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition-colors @error('nama_bahan') border-red-500 dark:border-red-500 focus:ring-red-500 @enderror" required>
                        @error('nama_bahan')
                            <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <label for="jumlah_stok" class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2 transition-colors">Sisa Stok Fisik</label>
                            <input type="number" step="0.01" name="jumlah_stok" id="jumlah_stok" value="{{ old('jumlah_stok', $bahanbaku->jumlah_stok) }}" class="shadow-sm appearance-none border border-gray-300 dark:border-gray-600 rounded-lg w-full py-3 px-4 bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-200 leading-tight focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition-colors @error('jumlah_stok') border-red-500 dark:border-red-500 focus:ring-red-500 @enderror" required>
                            @error('jumlah_stok')
                                <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="satuan" class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2 transition-colors">Satuan (Unit)</label>
                            <input type="text" name="satuan" id="satuan" value="{{ old('satuan', $bahanbaku->satuan) }}" class="shadow-sm appearance-none border border-gray-300 dark:border-gray-600 rounded-lg w-full py-3 px-4 bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-200 leading-tight focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition-colors @error('satuan') border-red-500 dark:border-red-500 focus:ring-red-500 @enderror" required>
                            @error('satuan')
                                <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <label for="harga_beli_satuan" class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-1 transition-colors">
                                Harga Beli per Satuan (Rp)
                            </label>
                            <p class="text-xs text-gray-400 dark:text-gray-500 mb-2">Digunakan untuk menghitung HPP resep produk secara otomatis.</p>
                            <input type="number" step="1" name="harga_beli_satuan" id="harga_beli_satuan" value="{{ old('harga_beli_satuan', $bahanbaku->harga_beli_satuan ?? 0) }}" class="shadow-sm appearance-none border border-gray-300 dark:border-gray-600 rounded-lg w-full py-3 px-4 bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-200 leading-tight focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition-colors" placeholder="Misal: 12000">
                        </div>
                        <div>
                            <label for="stok_minimum" class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-1 transition-colors">
                                Batas Minimum Stok Menipis
                            </label>
                            <p class="text-xs text-gray-400 dark:text-gray-500 mb-2">Peringatan otomatis muncul jika stok sama atau di bawah batas ini.</p>
                            <input type="number" step="0.01" name="stok_minimum" id="stok_minimum" value="{{ old('stok_minimum', $bahanbaku->stok_minimum ?? 5) }}" class="shadow-sm appearance-none border border-gray-300 dark:border-gray-600 rounded-lg w-full py-3 px-4 bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-200 leading-tight focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition-colors" placeholder="Default: 5">
                        </div>
                    </div>
                    
                    <div class="flex items-center gap-4 pt-4 border-t border-gray-200 dark:border-gray-700 transition-colors mt-6">
                        <button type="submit" class="bg-amber-500 hover:bg-amber-600 text-white font-bold py-2.5 px-6 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-amber-500 dark:focus:ring-offset-gray-800 transition-all">
                            Perbarui Data
                        </button>
                        <a href="{{ route('bahanbaku.index') }}" class="inline-block align-baseline font-bold text-sm text-gray-500 hover:text-gray-800 dark:text-gray-400 dark:hover:text-gray-200 transition-colors">
                            Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
