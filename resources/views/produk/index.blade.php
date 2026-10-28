<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight transition-colors duration-300">
            {{ __('Katalog Produk (Etalase)') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-xl border border-gray-100 dark:border-gray-700 transition-colors duration-300">
                <div class="p-8 text-gray-900 dark:text-gray-100 transition-colors">
                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8 gap-4">
                        <div>
                            <h3 class="text-2xl font-extrabold text-gray-800 dark:text-gray-100 transition-colors">Katalog Produk (Etalase)</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1 transition-colors">Kelola daftar produk roti yang siap dijual.</p>
                        </div>
                        <a href="{{ route('produk.create') }}" class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-bold shadow-sm hover:shadow transition-all flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                            Daftarkan Produk
                        </a>
                    </div>
                    
                    <div class="overflow-x-auto rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm transition-colors">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 transition-colors">
                            <thead class="bg-gray-50 dark:bg-gray-700 transition-colors">
                                <tr>
                                    <th scope="col" class="py-4 px-6 text-center text-xs font-bold text-gray-500 dark:text-gray-300 uppercase tracking-wider">ID</th>
                                    <th scope="col" class="py-4 px-6 text-left text-xs font-bold text-gray-500 dark:text-gray-300 uppercase tracking-wider">Nama Produk</th>
                                    <th scope="col" class="py-4 px-6 text-left text-xs font-bold text-gray-500 dark:text-gray-300 uppercase tracking-wider">Kategori</th>
                                    <th scope="col" class="py-4 px-6 text-right text-xs font-bold text-gray-500 dark:text-gray-300 uppercase tracking-wider">Harga Jual</th>
                                    <th scope="col" class="py-4 px-6 text-right text-xs font-bold text-gray-500 dark:text-gray-300 uppercase tracking-wider">HPP Resep (Margin)</th>
                                    <th scope="col" class="py-4 px-6 text-center text-xs font-bold text-gray-500 dark:text-gray-300 uppercase tracking-wider">Sisa Etalase</th>
                                    <th scope="col" class="py-4 px-6 text-center text-xs font-bold text-gray-500 dark:text-gray-300 uppercase tracking-wider">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700 transition-colors">
                                @forelse($produks as $item)
                                @php
                                    $hpp = $item->hpp;
                                    $margin = $item->margin_laba;
                                    $persen = $item->persentase_margin;
                                    $isMenipis = $item->isStokMenipis();
                                @endphp
                                <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-700/50 transition-colors {{ $isMenipis ? 'bg-red-50/30 dark:bg-red-950/20' : '' }}">
                                    <td class="py-4 px-6 text-center whitespace-nowrap text-sm text-gray-500 dark:text-gray-400 font-medium">#{{ $item->id_produk }}</td>
                                    <td class="py-4 px-6 text-left">
                                        <div class="text-sm font-bold text-gray-900 dark:text-gray-100">{{ $item->nama_produk }}</div>
                                        <div class="mt-1">
                                            @if($item->tipe_produksi === 'Kustom')
                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-bold bg-purple-100 dark:bg-purple-900/40 text-purple-700 dark:text-purple-300 border border-purple-200 dark:border-purple-800">
                                                    🎂 Pesanan Kustom
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-medium bg-indigo-50 dark:bg-indigo-900/30 text-indigo-700 dark:text-indigo-300 border border-indigo-200/60 dark:border-indigo-800/40">
                                                    📦 {{ $item->jumlah_per_batch ?? 1 }} pcs / batch
                                                </span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="py-4 px-6 text-left">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200 transition-colors">
                                            {{ $item->kategori->nama_kategori ?? 'Tanpa Kategori' }}
                                        </span>
                                    </td>
                                    <td class="py-4 px-6 text-right font-bold text-emerald-600 dark:text-emerald-400 whitespace-nowrap">
                                        Rp {{ number_format($item->harga_dasar, 0, ',', '.') }}
                                    </td>
                                    <td class="py-4 px-6 text-right whitespace-nowrap">
                                        <div class="text-xs font-semibold text-gray-600 dark:text-gray-400">
                                            HPP: <span class="font-bold text-gray-800 dark:text-gray-200">Rp {{ number_format($hpp, 0, ',', '.') }}</span>
                                        </div>
                                        <div class="text-[11px] font-bold text-emerald-600 dark:text-emerald-400 mt-0.5">
                                            +Rp {{ number_format($margin, 0, ',', '.') }} ({{ $persen }}%)
                                        </div>
                                    </td>
                                    <td class="py-4 px-6 text-center whitespace-nowrap">
                                        <span class="inline-flex items-center justify-center px-3 py-1 rounded-full text-sm font-bold {{ $isMenipis ? 'bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-400 border border-red-200 dark:border-red-800' : 'bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400 border border-blue-200 dark:border-blue-800' }} transition-colors">
                                            {{ $item->stok_etalase }}
                                        </span>
                                        @if($isMenipis)
                                            <div class="text-[10px] text-red-500 font-bold mt-1">⚠️ Menipis!</div>
                                        @endif
                                    </td>
                                    <td class="py-4 px-6 text-center text-sm font-medium">
                                        <div class="flex items-center justify-center space-x-3">
                                            <a href="{{ route('produk.edit', $item->id_produk) }}" class="text-amber-500 hover:text-amber-700 dark:text-amber-400 dark:hover:text-amber-300 bg-amber-50 hover:bg-amber-100 dark:bg-amber-900/20 dark:hover:bg-amber-900/40 px-3 py-1.5 rounded-md transition-colors flex items-center gap-1">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                                Ubah
                                            </a>
                                            <form action="{{ route('produk.destroy', $item->id_produk) }}" method="POST" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-500 hover:text-red-700 dark:text-red-400 dark:hover:text-red-300 bg-red-50 hover:bg-red-100 dark:bg-red-900/20 dark:hover:bg-red-900/40 px-3 py-1.5 rounded-md transition-colors flex items-center gap-1" onclick="return confirm('Apakah Anda yakin ingin mematikan produk ini?')">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                    Hapus
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="py-12 text-center">
                                        <svg class="mx-auto h-12 w-12 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                                        <p class="mt-4 text-sm text-gray-500 dark:text-gray-400">Belum ada barang di rak etalase. Tambahkan produk!</p>
                                    </td>
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
