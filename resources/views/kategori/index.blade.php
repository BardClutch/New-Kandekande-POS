<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight transition-colors duration-300">
            {{ __('Manajemen Kategori') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg transition-colors duration-300">
                <div class="p-6 text-gray-900 dark:text-gray-100 transition-colors">
                    <div class="flex justify-between mb-6">
                        <h3 class="text-xl font-bold text-gray-700 dark:text-gray-200">Daftar Kategori Produk</h3>
                        <a href="{{ route('kategori.create') }}" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded font-bold shadow-sm transition-all">+ Tambah Kategori</a>
                    </div>
                    
                    <div class="overflow-x-auto border rounded border-gray-200 dark:border-gray-700 transition-colors">
                        <table class="min-w-full bg-white dark:bg-gray-800 transition-colors">
                            <thead>
                                <tr class="bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 uppercase text-sm leading-normal transition-colors">
                                    <th class="py-3 px-6 text-center border-b">ID</th>
                                    <th class="py-3 px-6 text-left border-b">Nama Kategori</th>
                                    <th class="py-3 px-6 text-center border-b">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="text-gray-600 dark:text-gray-300 text-sm font-light transition-colors">
                                @forelse($kategoris as $kategori)
                                <tr class="border-b border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                    <td class="py-3 px-6 text-center whitespace-nowrap font-medium">{{ $kategori->id_kategori }}</td>
                                    <td class="py-3 px-6 text-left font-bold text-gray-800 dark:text-gray-100">{{ $kategori->nama_kategori }}</td>
                                    <td class="py-3 px-6 text-center">
                                        <div class="flex item-center justify-center space-x-4">
                                            <a href="{{ route('kategori.edit', $kategori->id_kategori) }}" class="text-amber-500 hover:text-amber-700 font-medium">Ubah</a>
                                            <form action="{{ route('kategori.destroy', $kategori->id_kategori) }}" method="POST" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-500 hover:text-red-700 font-medium" onclick="return confirm('Apakah Anda yakin ingin menghapus kategori {{ $kategori->nama_kategori }}?')">Hapus</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="3" class="py-6 text-center text-gray-400 dark:text-gray-500">Belum ada Kategori yang ditambahkan. Silakan klik tombol Tambah Kategori di atas.</td>
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
