<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight transition-colors duration-300">
            {{ __('Logistik Gudang: Bahan Baku') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-xl border border-gray-100 dark:border-gray-700 transition-colors duration-300">
                <div class="p-8 text-gray-900 dark:text-gray-100 transition-colors">
                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8 gap-4">
                        <div>
                            <h3 class="text-2xl font-extrabold text-gray-800 dark:text-gray-100 transition-colors">Ketersediaan Bahan Baku</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1 transition-colors">Kelola dan pantau stok fisik bahan baku logistik.</p>
                        </div>
                        <a href="{{ route('bahanbaku.create') }}" class="px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg font-bold shadow-sm hover:shadow transition-all flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                            Catat Bahan Masuk
                        </a>
                    </div>
                    
                    <div class="overflow-x-auto rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm transition-colors">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 transition-colors">
                            <thead class="bg-gray-50 dark:bg-gray-700 transition-colors">
                                <tr>
                                    <th scope="col" class="py-4 px-6 text-center text-xs font-bold text-gray-500 dark:text-gray-300 uppercase tracking-wider">ID</th>
                                    <th scope="col" class="py-4 px-6 text-left text-xs font-bold text-gray-500 dark:text-gray-300 uppercase tracking-wider">Nama Bahan</th>
                                    <th scope="col" class="py-4 px-6 text-right text-xs font-bold text-gray-500 dark:text-gray-300 uppercase tracking-wider">Harga Beli Satuan</th>
                                    <th scope="col" class="py-4 px-6 text-center text-xs font-bold text-gray-500 dark:text-gray-300 uppercase tracking-wider">Jumlah Stok</th>
                                    <th scope="col" class="py-4 px-6 text-center text-xs font-bold text-gray-500 dark:text-gray-300 uppercase tracking-wider">Batas Min</th>
                                    <th scope="col" class="py-4 px-6 text-center text-xs font-bold text-gray-500 dark:text-gray-300 uppercase tracking-wider">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700 transition-colors">
                                @forelse($bahanbaku as $bahan)
                                @php
                                    $isMenipis = $bahan->isStokMenipis();
                                @endphp
                                <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-700/50 transition-colors {{ $isMenipis ? 'bg-red-50/30 dark:bg-red-950/20' : '' }}">
                                    <td class="py-4 px-6 text-center whitespace-nowrap text-sm text-gray-500 dark:text-gray-400 font-medium">#{{ $bahan->id_bahan }}</td>
                                    <td class="py-4 px-6 text-left">
                                        <div class="text-sm font-bold text-gray-900 dark:text-gray-100">{{ $bahan->nama_bahan }}</div>
                                        @if($isMenipis)
                                            <span class="inline-flex items-center gap-1 mt-1 px-2 py-0.5 rounded text-[11px] font-bold bg-red-100 dark:bg-red-900/40 text-red-700 dark:text-red-300 border border-red-200 dark:border-red-800">
                                                ⚠️ Stok Menipis!
                                            </span>
                                        @endif
                                    </td>
                                    <td class="py-4 px-6 text-right whitespace-nowrap">
                                        <span class="text-sm font-bold text-indigo-600 dark:text-indigo-400">
                                            Rp {{ number_format($bahan->harga_beli_satuan ?? 0, 0, ',', '.') }}
                                        </span>
                                        <span class="text-xs text-gray-400">/ {{ $bahan->satuan }}</span>
                                    </td>
                                    <td class="py-4 px-6 text-center whitespace-nowrap">
                                        <span class="inline-flex items-center justify-center px-3 py-1 rounded-full text-sm font-bold {{ $isMenipis ? 'bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-400 border border-red-200 dark:border-red-800' : 'bg-emerald-100 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-800' }} transition-colors">
                                            {{ $bahan->jumlah_stok }} <span class="ml-1 text-xs font-normal opacity-70">{{ $bahan->satuan }}</span>
                                        </span>
                                    </td>
                                    <td class="py-4 px-6 text-center text-xs text-gray-500 dark:text-gray-400 font-medium">
                                        {{ $bahan->stok_minimum ?? 5 }} {{ $bahan->satuan }}
                                    </td>
                                    <td class="py-4 px-6 text-center text-sm font-medium whitespace-nowrap">
                                        <div class="flex items-center justify-center space-x-3">
                                            <a href="{{ route('bahanbaku.edit', $bahan->id_bahan) }}" class="text-amber-500 hover:text-amber-700 dark:text-amber-400 dark:hover:text-amber-300 bg-amber-50 hover:bg-amber-100 dark:bg-amber-900/20 dark:hover:bg-amber-900/40 px-3 py-1.5 rounded-md transition-colors flex items-center gap-1 text-xs font-semibold">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                                Ubah
                                            </a>
                                            <form action="{{ route('bahanbaku.destroy', $bahan->id_bahan) }}" method="POST" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-500 hover:text-red-700 dark:text-red-400 dark:hover:text-red-300 bg-red-50 hover:bg-red-100 dark:bg-red-900/20 dark:hover:bg-red-900/40 px-3 py-1.5 rounded-md transition-colors flex items-center gap-1 text-xs font-semibold" onclick="return confirm('Menghapus Bahan Baku ini akan berdampak pada Master Resep. Apakah Anda yakin?')">
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
                                        <p class="mt-4 text-sm text-gray-500 dark:text-gray-400">Belum ada Bahan Baku di gudang logistik.</p>
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
