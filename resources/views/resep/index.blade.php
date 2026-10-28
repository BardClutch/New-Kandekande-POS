<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight transition-colors duration-300">
            {{ __('Buku Resep & Kapasitas Batch') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            
            @if(session('success'))
                <div class="bg-green-100 dark:bg-green-900/40 border-l-4 border-green-500 text-green-700 dark:text-green-300 p-4 rounded-xl shadow-sm">
                    <p class="font-medium">{{ session('success') }}</p>
                </div>
            @endif
            @if(session('error'))
                <div class="bg-red-100 dark:bg-red-900/40 border-l-4 border-red-500 text-red-700 dark:text-red-300 p-4 rounded-xl shadow-sm">
                    <p class="font-medium">{{ session('error') }}</p>
                </div>
            @endif

            <!-- Section 1: Pengaturan Kapasitas Batch & Tipe Produksi Produk -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-xl border border-gray-100 dark:border-gray-700 p-6 transition-colors">
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-2">
                    <div>
                        <h3 class="text-lg font-bold text-gray-800 dark:text-gray-100 flex items-center gap-2">
                            <span class="p-1.5 bg-indigo-100 dark:bg-indigo-900/50 text-indigo-600 dark:text-indigo-400 rounded-lg">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                            </span>
                            Pengaturan Batch & Tipe Resep Produk
                        </h3>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                            Tentukan apakah resep dihitung per-batch (misal 1 resep menghasilkan 20 pcs roti) atau pesanan kustom (misal kue ulang tahun).
                        </p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @forelse($produks as $prod)
                    <div class="p-4 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-750/50 flex flex-col justify-between hover:border-indigo-300 dark:hover:border-indigo-600 transition-all">
                        <div>
                            <div class="flex items-start justify-between gap-2">
                                <div>
                                    <h4 class="font-bold text-gray-900 dark:text-gray-100 text-sm">{{ $prod->nama_produk }}</h4>
                                    <span class="text-[11px] text-gray-500 dark:text-gray-400">{{ $prod->kategori->nama_kategori ?? 'Tanpa Kategori' }}</span>
                                </div>
                                @if($prod->tipe_produksi === 'Kustom')
                                    <span class="px-2 py-0.5 rounded-full text-[11px] font-bold bg-purple-100 dark:bg-purple-900/40 text-purple-700 dark:text-purple-300 border border-purple-200 dark:border-purple-800">
                                        🎂 Kustom
                                    </span>
                                @else
                                    <span class="px-2 py-0.5 rounded-full text-[11px] font-bold bg-indigo-100 dark:bg-indigo-900/40 text-indigo-700 dark:text-indigo-300 border border-indigo-200 dark:border-indigo-800">
                                        📦 Batch ({{ $prod->jumlah_per_batch }} pcs)
                                    </span>
                                @endif
                            </div>

                            <div class="mt-3 text-xs text-gray-600 dark:text-gray-300">
                                <span class="font-medium text-gray-400 dark:text-gray-500">Resep Bahan:</span>
                                @if($prod->reseps->isNotEmpty())
                                    <ul class="mt-1 space-y-0.5">
                                        @foreach($prod->reseps as $r)
                                            <li class="flex justify-between text-[11px]">
                                                <span>{{ $r->bahanBaku->nama_bahan ?? '-' }}</span>
                                                <span class="font-semibold text-emerald-600 dark:text-emerald-400">{{ $r->takaran }} {{ $r->bahanBaku->satuan ?? '' }}</span>
                                            </li>
                                        @endforeach
                                    </ul>
                                @else
                                    <p class="text-[11px] text-amber-600 dark:text-amber-400 italic mt-0.5">Belum ada komposisi bahan baku.</p>
                                @endif
                            </div>
                            
                            <!-- Link Cepat Tambah Bahan untuk Produk ini -->
                            <div class="mt-3 pt-2.5 border-t border-gray-200/70 dark:border-gray-700/60 flex items-center justify-between">
                                <a href="{{ route('resep.create', ['id_produk' => $prod->id_produk]) }}" class="inline-flex items-center gap-1 text-xs font-bold text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 transition-colors">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                    + Tambah Bahan Resep
                                </a>
                            </div>
                        </div>

                        <!-- Form Cepat Update Batch Setting -->
                        <form action="{{ route('resep.updateBatch', $prod->id_produk) }}" method="POST" class="mt-3 pt-2.5 border-t border-gray-200/70 dark:border-gray-700/60">
                            @csrf
                            @method('PATCH')
                            <div class="flex items-center gap-2">
                                <select name="tipe_produksi" onchange="toggleBatchInput(this, '{{ $prod->id_produk }}')" class="text-xs rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 py-1 px-2 focus:ring focus:ring-indigo-200 dark:focus:ring-indigo-800">
                                    <option value="Batch" {{ $prod->tipe_produksi !== 'Kustom' ? 'selected' : '' }}>Mode Batch</option>
                                    <option value="Kustom" {{ $prod->tipe_produksi === 'Kustom' ? 'selected' : '' }}>Pesanan Kustom</option>
                                </select>
                                
                                <div id="container_batch_{{ $prod->id_produk }}" class="{{ $prod->tipe_produksi === 'Kustom' ? 'hidden' : 'flex' }} items-center gap-1 flex-1">
                                    <input type="number" name="jumlah_per_batch" value="{{ $prod->jumlah_per_batch ?? 1 }}" min="1" class="text-xs w-16 rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 py-1 px-2 text-center" title="Hasil pcs per 1 batch resep">
                                    <span class="text-[10px] text-gray-500 dark:text-gray-400">pcs/batch</span>
                                </div>

                                <button type="submit" class="text-xs font-semibold px-2.5 py-1 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg transition-colors shadow-sm ml-auto">
                                    Simpan
                                </button>
                            </div>
                        </form>
                    </div>
                    @empty
                    <p class="text-xs text-gray-400 dark:text-gray-500 col-span-3">Belum ada produk yang terdaftar.</p>
                    @endforelse
                </div>
            </div>

            <!-- Section 2: Tabel Detail Komposisi Bahan Resep -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-xl border border-gray-100 dark:border-gray-700 p-6 transition-colors">
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
                    <div>
                        <h3 class="text-xl font-bold text-gray-800 dark:text-gray-100">Buku Detail Komposisi Resep</h3>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Takaran bahan baku per 1 batch resep atau per 1 unit kue kustom.</p>
                    </div>
                    <a href="{{ route('resep.create') }}" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-bold shadow-sm transition-all flex items-center gap-1.5 text-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                        Tambah Komposisi Resep
                    </a>
                </div>
                
                <div class="overflow-x-auto border rounded-xl border-gray-200 dark:border-gray-700 transition-colors">
                    <table class="min-w-full bg-white dark:bg-gray-800 transition-colors">
                        <thead>
                            <tr class="bg-gray-50 dark:bg-gray-700 text-gray-600 dark:text-gray-300 uppercase text-xs leading-normal font-semibold transition-colors">
                                <th class="py-3 px-6 text-center border-b border-gray-200 dark:border-gray-700">ID</th>
                                <th class="py-3 px-6 text-left border-b border-gray-200 dark:border-gray-700">Produk & Tipe</th>
                                <th class="py-3 px-6 text-left border-b border-gray-200 dark:border-gray-700">Bahan Baku</th>
                                <th class="py-3 px-6 text-right border-b border-gray-200 dark:border-gray-700">Takaran (per 1 Batch / Unit)</th>
                                <th class="py-3 px-6 text-center border-b border-gray-200 dark:border-gray-700">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="text-gray-600 dark:text-gray-300 text-sm divide-y divide-gray-100 dark:divide-gray-700 transition-colors">
                            @forelse($reseps as $item)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                                <td class="py-3.5 px-6 text-center whitespace-nowrap font-medium text-xs text-gray-500">#{{ $item->id_resep }}</td>
                                <td class="py-3.5 px-6 text-left">
                                    <div class="font-bold text-gray-800 dark:text-gray-100">{{ $item->produk->nama_produk ?? '-' }}</div>
                                    <div class="mt-0.5">
                                        @if(($item->produk->tipe_produksi ?? '') === 'Kustom')
                                            <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-bold bg-purple-50 dark:bg-purple-900/40 text-purple-700 dark:text-purple-300 border border-purple-200 dark:border-purple-800/50">
                                                🎂 Pesanan Kustom
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-semibold bg-indigo-50 dark:bg-indigo-900/30 text-indigo-700 dark:text-indigo-300 border border-indigo-200/60 dark:border-indigo-800/40">
                                                📦 Batch: {{ $item->produk->jumlah_per_batch ?? 1 }} pcs/batch
                                            </span>
                                        @endif
                                    </div>
                                </td>
                                <td class="py-3.5 px-6 text-left font-medium">{{ $item->bahanBaku->nama_bahan ?? '-' }}</td>
                                <td class="py-3.5 px-6 text-right font-bold text-emerald-600 dark:text-emerald-400">
                                    {{ $item->takaran }} {{ $item->bahanBaku->satuan ?? '' }}
                                </td>
                                <td class="py-3.5 px-6 text-center whitespace-nowrap">
                                    <div class="flex items-center justify-center space-x-3 text-xs font-semibold">
                                        <a href="{{ route('resep.edit', $item->id_resep) }}" class="text-amber-500 hover:text-amber-700 bg-amber-50 hover:bg-amber-100 dark:bg-amber-900/20 px-2.5 py-1 rounded-md transition-colors">Ubah</a>
                                        <form action="{{ route('resep.destroy', $item->id_resep) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-500 hover:text-red-700 bg-red-50 hover:bg-red-100 dark:bg-red-900/20 px-2.5 py-1 rounded-md transition-colors" onclick="return confirm('Apakah Anda yakin ingin menghapus komposisi resep ini?')">Hapus</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="py-8 text-center text-gray-400 dark:text-gray-500">
                                    Buku resep masih kosong. Tambahkan resep pertama Anda!
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>

    <script>
        function toggleBatchInput(selectElement, id) {
            const container = document.getElementById('container_batch_' + id);
            if (selectElement.value === 'Kustom') {
                container.classList.add('hidden');
                container.classList.remove('flex');
            } else {
                container.classList.remove('hidden');
                container.classList.add('flex');
            }
        }
    </script>
</x-app-layout>

