<x-app-layout>
    <div x-data="{ showModal: false, showEditModal: false, editData: {} }">
        <x-page title="Promo dan diskon" sub="Atur potongan harga per produk beserta periodenya.">
            <x-slot name="actions">
                <x-btn @click="showModal = true">Tambah promo</x-btn>
            </x-slot>

            <x-panel flush>
                <div class="overflow-x-auto">
                    <table class="tbl">
                        <thead>
                            <tr>
                                <th>Promo</th>
                                <th>Produk</th>
                                <th class="r">Diskon</th>
                                <th>Periode</th>
                                <th>Status</th>
                                <th class="r">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($promos as $promo)
                                @php
                                    $today = \Carbon\Carbon::now()->format('Y-m-d');
                                    $is_active = $promo->tgl_mulai <= $today && $promo->tgl_akhir >= $today;
                                    $is_upcoming = $promo->tgl_mulai > $today;
                                @endphp
                                <tr>
                                    <td class="font-semibold text-gray-900 dark:text-gray-100">{{ $promo->nama_promo }}</td>
                                    <td class="text-gray-600 dark:text-gray-300">{{ $promo->produk->nama_produk ?? '-' }}</td>
                                    <td class="r num font-semibold">{{ $promo->persentase }}%</td>
                                    <td class="num text-gray-600 dark:text-gray-300 whitespace-nowrap">{{ \Carbon\Carbon::parse($promo->tgl_mulai)->format('d/m/Y') }} – {{ \Carbon\Carbon::parse($promo->tgl_akhir)->format('d/m/Y') }}</td>
                                    <td>
                                        @if($is_active)
                                            <span class="font-semibold text-emerald-600 dark:text-emerald-400">Aktif</span>
                                        @elseif($is_upcoming)
                                            <span class="font-semibold text-amber-600 dark:text-amber-400">Mendatang</span>
                                        @else
                                            <span class="text-gray-400">Berakhir</span>
                                        @endif
                                    </td>
                                    <td class="r">
                                        <div class="inline-flex items-center gap-4">
                                            <button type="button" @click="editData = {{ json_encode($promo) }}; showEditModal = true" class="text-sm font-semibold text-indigo-600 dark:text-indigo-400 hover:underline underline-offset-4">Ubah</button>
                                            <form action="{{ route('diskon.destroy', $promo->id_promo) }}" method="POST" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-sm font-semibold text-red-600 dark:text-red-400 hover:underline underline-offset-4" onclick="return confirm('Hapus promo ini?')">Hapus</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="6" class="py-14 text-center text-gray-400">Belum ada promo terdaftar.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </x-panel>
        </x-page>

        <x-dialog show="showModal" title="Tambah promo">
            <form action="{{ route('diskon.store') }}" method="POST" class="space-y-5">
                @csrf
                <div class="fld"><label>Nama promo</label><input type="text" name="nama_promo" required class="inp"></div>
                <div class="fld">
                    <label>Produk</label>
                    <select name="id_produk" required class="inp">
                        <option value="">Pilih produk</option>
                        @foreach($produks as $produk)
                            <option value="{{ $produk->id_produk }}">{{ $produk->nama_produk }} (Rp {{ number_format($produk->harga_dasar,0,',','.') }})</option>
                        @endforeach
                    </select>
                </div>
                <div class="fld"><label>Besar diskon (%)</label><input type="number" step="0.01" min="0.01" max="100" name="persentase" required class="inp"></div>
                <div class="grid grid-cols-2 gap-4">
                    <div class="fld"><label>Mulai</label><input type="date" name="tgl_mulai" required class="inp"></div>
                    <div class="fld"><label>Berakhir</label><input type="date" name="tgl_akhir" required class="inp"></div>
                </div>
                <div class="flex items-center justify-end gap-3 pt-2">
                    <x-btn variant="secondary" @click="showModal = false">Batal</x-btn>
                    <x-btn type="submit">Simpan</x-btn>
                </div>
            </form>
        </x-dialog>

        <x-dialog show="showEditModal" title="Ubah promo">
            <form :action="`/diskon/${editData.id_promo}`" method="POST" class="space-y-5">
                @csrf
                @method('PUT')
                <div class="fld"><label>Nama promo</label><input type="text" name="nama_promo" x-model="editData.nama_promo" required class="inp"></div>
                <div class="fld">
                    <label>Produk</label>
                    <select name="id_produk" x-model="editData.id_produk" required class="inp">
                        <option value="">Pilih produk</option>
                        @foreach($produks as $produk)
                            <option value="{{ $produk->id_produk }}">{{ $produk->nama_produk }} (Rp {{ number_format($produk->harga_dasar,0,',','.') }})</option>
                        @endforeach
                    </select>
                </div>
                <div class="fld"><label>Besar diskon (%)</label><input type="number" step="0.01" min="0.01" max="100" name="persentase" x-model="editData.persentase" required class="inp"></div>
                <div class="grid grid-cols-2 gap-4">
                    <div class="fld"><label>Mulai</label><input type="date" name="tgl_mulai" x-model="editData.tgl_mulai" required class="inp"></div>
                    <div class="fld"><label>Berakhir</label><input type="date" name="tgl_akhir" x-model="editData.tgl_akhir" required class="inp"></div>
                </div>
                <div class="flex items-center justify-end gap-3 pt-2">
                    <x-btn variant="secondary" @click="showEditModal = false">Batal</x-btn>
                    <x-btn type="submit">Perbarui</x-btn>
                </div>
            </form>
        </x-dialog>
    </div>
</x-app-layout>
