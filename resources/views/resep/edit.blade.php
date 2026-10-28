<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight transition-colors duration-300">
            {{ __('Ubah Komposisi Resep') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-xl border-t-4 border-amber-500 dark:border-amber-600 p-8 transition-colors duration-300">
                <form action="{{ route('resep.update', $resep->id_resep) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-6">
                        <label for="id_produk" class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2 transition-colors">Produk Roti</label>
                        <select name="id_produk" id="id_produk" class="shadow-sm border border-gray-300 dark:border-gray-600 rounded-lg w-full py-3 px-4 bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-200 leading-tight focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition-colors @error('id_produk') border-red-500 dark:border-red-500 focus:ring-red-500 @enderror" required>
                            <option value="">-- Pilih Produk --</option>
                            @foreach($produks as $produk)
                                <option value="{{ $produk->id_produk }}" {{ old('id_produk', $resep->id_produk) == $produk->id_produk ? 'selected' : '' }}>{{ $produk->nama_produk }}</option>
                            @endforeach
                        </select>
                        @error('id_produk')
                            <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div class="mb-6">
                        <label for="id_bahan" class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2 transition-colors">Bahan Baku Utama</label>
                        <select name="id_bahan" id="id_bahan" class="shadow-sm border border-gray-300 dark:border-gray-600 rounded-lg w-full py-3 px-4 bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-200 leading-tight focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition-colors @error('id_bahan') border-red-500 dark:border-red-500 focus:ring-red-500 @enderror" required>
                            <option value="">-- Pilih Bahan Baku --</option>
                            @foreach($bahans as $bahan)
                                <option value="{{ $bahan->id_bahan }}" {{ old('id_bahan', $resep->id_bahan) == $bahan->id_bahan ? 'selected' : '' }}>{{ $bahan->nama_bahan }} ({{ $bahan->satuan }})</option>
                            @endforeach
                        </select>
                        @error('id_bahan')
                            <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div class="mb-8">
                        <label for="takaran" class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-1 transition-colors">
                            Takaran Bahan Baku (per 1 Batch / 1 Unit Kustom)
                        </label>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mb-2">
                            Untuk produk bertipe Batch, takaran ini untuk 1 batch adonan. Untuk pesanan kustom, takaran ini untuk 1 unit kue.
                        </p>
                        <input type="number" step="0.01" name="takaran" id="takaran" value="{{ old('takaran', $resep->takaran) }}" class="shadow-sm appearance-none border border-gray-300 dark:border-gray-600 rounded-lg w-full py-3 px-4 bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-200 leading-tight focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition-colors @error('takaran') border-red-500 dark:border-red-500 focus:ring-red-500 @enderror" placeholder="Contoh: 0.5 atau 2" required>
                        @error('takaran')
                            <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div class="flex items-center gap-4 pt-4 border-t border-gray-200 dark:border-gray-700 transition-colors mt-6">
                        <button type="submit" class="bg-amber-500 hover:bg-amber-600 text-white font-bold py-2.5 px-6 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-amber-500 dark:focus:ring-offset-gray-800 transition-all">
                            Perbarui Komposisi
                        </button>
                        <a href="{{ route('resep.index') }}" class="inline-block align-baseline font-bold text-sm text-gray-500 hover:text-gray-800 dark:text-gray-400 dark:hover:text-gray-200 transition-colors">
                            Kembali
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>

