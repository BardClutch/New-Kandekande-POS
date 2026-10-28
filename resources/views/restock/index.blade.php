<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight transition-colors duration-300">
            {{ __('Manajemen Restock Bahan Baku') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <!-- Alert Messages -->
            @if(session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            @if(session('error'))
                <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
            @endif

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6 transition-colors duration-300">
                <div class="p-6 text-gray-900 dark:text-gray-100 flex justify-between items-center">
                    <h3 class="text-lg font-medium text-gray-800 dark:text-gray-200">Catatan Restock</h3>
                    <!-- Alpine Component untuk Modal -->
                    <div x-data="{ open: false }">
                        <button @click="open = true" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            + Catat Restock
                        </button>

                        <!-- Modal -->
                        <div x-show="open" class="fixed inset-0 flex items-center justify-center z-50" style="display: none;">
                            <div class="absolute inset-0 bg-black dark:bg-gray-900 opacity-50 dark:opacity-80"></div>
                            <div class="bg-white dark:bg-gray-800 border dark:border-gray-700 rounded-lg p-8 z-10 w-full max-w-md transition-colors" @click.away="open = false">
                                <h2 class="text-xl font-bold mb-4 text-gray-900 dark:text-gray-100">Catat Restock Bahan Baku</h2>
                                
                                <form action="{{ route('restock.store') }}" method="POST">
                                    @csrf
                                    
                                    <div class="mb-4">
                                        <label for="id_bahan" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Pilih Bahan Baku</label>
                                        <select name="id_bahan" id="id_bahan" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                                            <option value="">-- Pilih Bahan --</option>
                                            @foreach($bahanBakus as $bahan)
                                                <option value="{{ $bahan->id_bahan }}">{{ $bahan->nama_bahan }} (Stok saat ini: {{ $bahan->jumlah_stok }} {{ $bahan->satuan }})</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="mb-4">
                                        <label for="jumlah_tambah" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Jumlah Ditambahkan</label>
                                        <input type="number" step="0.01" name="jumlah_tambah" id="jumlah_tambah" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                                    </div>

                                    <div class="mb-4">
                                        <label for="harga_beli" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Harga Beli (Opsional)</label>
                                        <input type="number" name="harga_beli" id="harga_beli" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 shadow-sm focus:border-blue-500 focus:ring-blue-500" placeholder="Contoh: 50000">
                                    </div>

                                    <div class="mb-4">
                                        <label for="supplier" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Supplier / Toko (Opsional)</label>
                                        <input type="text" name="supplier" id="supplier" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 shadow-sm focus:border-blue-500 focus:ring-blue-500" placeholder="Contoh: Toko Maju">
                                    </div>

                                    <div class="flex justify-end mt-6">
                                        <button type="button" @click="open = false" class="bg-gray-300 hover:bg-gray-400 dark:bg-gray-600 dark:hover:bg-gray-500 text-gray-800 dark:text-gray-200 font-bold py-2 px-4 rounded mr-2 transition-colors">
                                            Batal
                                        </button>
                                        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                            Simpan Restock
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tabel Riwayat Restock -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg transition-colors duration-300">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-medium text-gray-800 dark:text-gray-200 mb-4">Riwayat Pembelian/Restock</h3>
                    <div class="overflow-x-auto border rounded border-gray-200 dark:border-gray-700 transition-colors">
                        <table class="min-w-full bg-white dark:bg-gray-800 transition-colors">
                            <thead>
                                <tr class="bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 uppercase text-xs leading-normal transition-colors">
                                    <th class="py-3 px-6 border-b dark:border-gray-600 text-left">No</th>
                                    <th class="py-3 px-6 border-b dark:border-gray-600 text-left">Waktu</th>
                                    <th class="py-3 px-6 border-b dark:border-gray-600 text-left">Bahan Baku</th>
                                    <th class="py-3 px-6 border-b dark:border-gray-600 text-left">Jumlah Tambah</th>
                                    <th class="py-3 px-6 border-b dark:border-gray-600 text-left">Harga Beli</th>
                                    <th class="py-3 px-6 border-b dark:border-gray-600 text-left">Supplier</th>
                                    <th class="py-3 px-6 border-b dark:border-gray-600 text-left">Dicatat Oleh</th>
                                </tr>
                            </thead>
                            <tbody class="text-gray-600 dark:text-gray-300 text-sm font-light transition-colors">
                                @forelse($restocks as $index => $restock)
                                    <tr class="border-b border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-750 transition-colors">
                                        <td class="py-3 px-6">{{ $index + 1 }}</td>
                                        <td class="py-3 px-6">{{ \Carbon\Carbon::parse($restock->tanggal_waktu)->format('d M Y, H:i') }}</td>
                                        <td class="py-3 px-6 font-bold text-gray-800 dark:text-gray-200">{{ $restock->bahanBaku->nama_bahan ?? '-' }}</td>
                                        <td class="py-3 px-6"><span class="text-green-600 dark:text-green-400 font-bold">+{{ $restock->jumlah_tambah }}</span> {{ $restock->bahanBaku->satuan ?? '' }}</td>
                                        <td class="py-3 px-6">{{ $restock->harga_beli ? 'Rp ' . number_format($restock->harga_beli, 0, ',', '.') : '-' }}</td>
                                        <td class="py-3 px-6">{{ $restock->supplier ?: '-' }}</td>
                                        <td class="py-3 px-6">{{ $restock->user->name ?? 'Sistem' }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="py-6 px-6 text-center text-gray-400 dark:text-gray-500">Belum ada riwayat restock bahan baku.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
