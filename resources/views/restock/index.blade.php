<x-app-layout>
    <div x-data="{ showRestock: false }">
        <x-page title="Restock" sub="Riwayat pembelian dan penambahan stok bahan baku.">
            <x-slot name="actions">
                <x-btn @click="showRestock = true">Catat restock</x-btn>
            </x-slot>

            <x-panel flush>
                <div class="overflow-x-auto">
                    <table class="tbl">
                        <thead>
                            <tr>
                                <th>Waktu</th>
                                <th>Bahan baku</th>
                                <th class="r">Jumlah</th>
                                <th class="r">Harga beli</th>
                                <th>Supplier</th>
                                <th>Dicatat oleh</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($restocks as $index => $restock)
                                <tr>
                                    <td class="num text-gray-500 whitespace-nowrap">{{ \Carbon\Carbon::parse($restock->tanggal_waktu)->format('d M Y, H:i') }}</td>
                                    <td class="font-semibold text-gray-900 dark:text-gray-100">{{ $restock->bahanBaku->nama_bahan ?? '-' }}</td>
                                    <td class="r num font-semibold text-emerald-600 dark:text-emerald-400">+{{ $restock->jumlah_tambah }} <span class="font-normal text-gray-400">{{ $restock->bahanBaku->satuan ?? '' }}</span></td>
                                    <td class="r num">{{ $restock->harga_beli ? 'Rp ' . number_format($restock->harga_beli, 0, ',', '.') : '-' }}</td>
                                    <td class="text-gray-600 dark:text-gray-300">{{ $restock->supplier ?: '-' }}</td>
                                    <td class="text-gray-600 dark:text-gray-300">{{ $restock->user->name ?? 'Sistem' }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="6" class="py-14 text-center text-gray-400">Belum ada riwayat restock bahan baku.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </x-panel>
        </x-page>

        <x-dialog show="showRestock" title="Catat restock bahan baku">
            <form action="{{ route('restock.store') }}" method="POST" class="space-y-5">
                @csrf
                <div class="fld">
                    <label for="id_bahan">Bahan baku</label>
                    <select name="id_bahan" id="id_bahan" class="inp" required>
                        <option value="">Pilih bahan</option>
                        @foreach($bahanBakus as $bahan)
                            <option value="{{ $bahan->id_bahan }}">{{ $bahan->nama_bahan }} (stok {{ $bahan->jumlah_stok }} {{ $bahan->satuan }})</option>
                        @endforeach
                    </select>
                </div>
                <div class="fld">
                    <label for="jumlah_tambah">Jumlah ditambahkan</label>
                    <input type="number" step="0.01" name="jumlah_tambah" id="jumlah_tambah" class="inp" placeholder="0" required>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="fld">
                        <label for="harga_beli">Harga beli (opsional)</label>
                        <input type="number" name="harga_beli" id="harga_beli" class="inp" placeholder="50000">
                    </div>
                    <div class="fld">
                        <label for="supplier">Supplier (opsional)</label>
                        <input type="text" name="supplier" id="supplier" class="inp" placeholder="Toko Maju">
                    </div>
                </div>
                <div class="flex items-center justify-end gap-3 pt-2">
                    <x-btn variant="secondary" @click="showRestock = false">Batal</x-btn>
                    <x-btn type="submit">Simpan restock</x-btn>
                </div>
            </form>
        </x-dialog>
    </div>
</x-app-layout>
