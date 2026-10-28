<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="font-extrabold text-xl text-gray-800 dark:text-gray-100 tracking-tight flex items-center gap-2">
                <svg class="w-6 h-6 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                </svg>
                Kelola Karyawan & Akun Login
            </h2>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">
                Buat dan atur hak akses akun staf restoran (Kasir, Dapur, Admin Stok, Owner)
            </p>
        </div>
    </x-slot>

    <div class="py-8 bg-gray-100 dark:bg-gray-900 min-h-screen transition-colors duration-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            <!-- Flash Message Alerts -->
            @if(session('success'))
                <div class="bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-300 dark:border-emerald-700 text-emerald-800 dark:text-emerald-200 px-5 py-4 rounded-2xl shadow-sm flex items-center gap-3 animate-fadeIn">
                    <div class="w-8 h-8 rounded-xl bg-emerald-500 text-white flex items-center justify-center font-bold flex-shrink-0">
                        ✓
                    </div>
                    <span class="text-sm font-semibold">{{ session('success') }}</span>
                </div>
            @endif

            @if(session('error'))
                <div class="bg-red-50 dark:bg-red-950/40 border border-red-300 dark:border-red-700 text-red-800 dark:text-red-200 px-5 py-4 rounded-2xl shadow-sm flex items-center gap-3">
                    <svg class="w-6 h-6 text-red-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span class="text-sm font-semibold">{{ session('error') }}</span>
                </div>
            @endif

            <!-- Role Summary Stat Cards -->
            <div class="grid grid-cols-2 sm:grid-cols-5 gap-4">
                <!-- Total Staf -->
                <div class="bg-white dark:bg-gray-800 p-4 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 flex items-center justify-between">
                    <div>
                        <p class="text-[11px] font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400">Total Akun</p>
                        <p class="text-2xl font-black text-gray-900 dark:text-white mt-0.5">{{ $totalCount }}</p>
                    </div>
                    <div class="w-10 h-10 rounded-xl bg-gray-100 dark:bg-gray-700 flex items-center justify-center text-lg">
                        👥
                    </div>
                </div>

                <!-- Kasir -->
                <div class="bg-white dark:bg-gray-800 p-4 rounded-2xl shadow-sm border border-emerald-100 dark:border-emerald-800/40 flex items-center justify-between">
                    <div>
                        <p class="text-[11px] font-bold uppercase tracking-wider text-emerald-600 dark:text-emerald-400">Kasir</p>
                        <p class="text-2xl font-black text-gray-900 dark:text-white mt-0.5">{{ $kasirCount }}</p>
                    </div>
                    <div class="w-10 h-10 rounded-xl bg-emerald-50 dark:bg-emerald-900/30 flex items-center justify-center text-lg">
                        💼
                    </div>
                </div>

                <!-- Dapur -->
                <div class="bg-white dark:bg-gray-800 p-4 rounded-2xl shadow-sm border border-orange-100 dark:border-orange-800/40 flex items-center justify-between">
                    <div>
                        <p class="text-[11px] font-bold uppercase tracking-wider text-orange-600 dark:text-orange-400">Dapur</p>
                        <p class="text-2xl font-black text-gray-900 dark:text-white mt-0.5">{{ $dapurCount }}</p>
                    </div>
                    <div class="w-10 h-10 rounded-xl bg-orange-50 dark:bg-orange-900/30 flex items-center justify-center text-lg">
                        🍳
                    </div>
                </div>

                <!-- Admin Gudang -->
                <div class="bg-white dark:bg-gray-800 p-4 rounded-2xl shadow-sm border border-blue-100 dark:border-blue-800/40 flex items-center justify-between">
                    <div>
                        <p class="text-[11px] font-bold uppercase tracking-wider text-blue-600 dark:text-blue-400">Admin Gudang</p>
                        <p class="text-2xl font-black text-gray-900 dark:text-white mt-0.5">{{ $stokCount }}</p>
                    </div>
                    <div class="w-10 h-10 rounded-xl bg-blue-50 dark:bg-blue-900/30 flex items-center justify-center text-lg">
                        📦
                    </div>
                </div>

                <!-- Kustom -->
                <div class="bg-white dark:bg-gray-800 p-4 rounded-2xl shadow-sm border border-purple-100 dark:border-purple-800/40 flex items-center justify-between">
                    <div>
                        <p class="text-[11px] font-bold uppercase tracking-wider text-purple-600 dark:text-purple-400">Peran Kustom</p>
                        <p class="text-2xl font-black text-gray-900 dark:text-white mt-0.5">{{ $kustomCount }}</p>
                    </div>
                    <div class="w-10 h-10 rounded-xl bg-purple-50 dark:bg-purple-900/30 flex items-center justify-center text-lg">
                        🛠️
                    </div>
                </div>
            </div>

            <!-- Filter & Search Bar -->
            <div class="bg-white dark:bg-gray-800 p-4 sm:p-5 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 flex flex-col xl:flex-row justify-between items-stretch xl:items-center gap-4">
                
                <!-- Role Filter Pills -->
                <div class="flex items-center gap-1.5 overflow-x-auto pb-1 xl:pb-0 custom-scrollbar">
                    <a href="{{ route('karyawan.index') }}" 
                       class="px-3.5 py-2.5 rounded-xl text-xs font-bold transition-all whitespace-nowrap {{ !request('role') ? 'bg-[#D19D9D] dark:bg-indigo-600 text-gray-900 dark:text-white shadow-sm' : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-200' }}">
                        Semua ({{ $totalCount }})
                    </a>
                    <a href="{{ route('karyawan.index', ['role' => 'Cashier']) }}" 
                       class="px-3.5 py-2.5 rounded-xl text-xs font-bold transition-all whitespace-nowrap {{ request('role') == 'Cashier' ? 'bg-emerald-500 text-white shadow-sm' : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-200' }}">
                        💼 Kasir ({{ $kasirCount }})
                    </a>
                    <a href="{{ route('karyawan.index', ['role' => 'Kitchen']) }}" 
                       class="px-3.5 py-2.5 rounded-xl text-xs font-bold transition-all whitespace-nowrap {{ request('role') == 'Kitchen' ? 'bg-orange-500 text-white shadow-sm' : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-200' }}">
                        🍳 Dapur ({{ $dapurCount }})
                    </a>
                    <a href="{{ route('karyawan.index', ['role' => 'Stock_Admin']) }}" 
                       class="px-3.5 py-2.5 rounded-xl text-xs font-bold transition-all whitespace-nowrap {{ request('role') == 'Stock_Admin' ? 'bg-blue-500 text-white shadow-sm' : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-200' }}">
                        📦 Admin Gudang ({{ $stokCount }})
                    </a>
                    <a href="{{ route('karyawan.index', ['role' => 'Kustom']) }}" 
                       class="px-3.5 py-2.5 rounded-xl text-xs font-bold transition-all whitespace-nowrap {{ request('role') == 'Kustom' ? 'bg-purple-600 text-white shadow-sm' : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-200' }}">
                        🛠️ Kustom ({{ $kustomCount }})
                    </a>
                    <a href="{{ route('karyawan.index', ['role' => 'Owner']) }}" 
                       class="px-3.5 py-2.5 rounded-xl text-xs font-bold transition-all whitespace-nowrap {{ request('role') == 'Owner' ? 'bg-amber-500 text-white shadow-sm' : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-200' }}">
                        👑 Owner ({{ $ownerCount }})
                    </a>
                </div>

                <!-- Search & Action Buttons Container -->
                <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3">
                    <!-- Search Form -->
                    <form action="{{ route('karyawan.index') }}" method="GET" class="relative min-w-[220px] sm:w-64">
                        @if(request('role'))
                            <input type="hidden" name="role" value="{{ request('role') }}">
                        @endif
                        <input type="text" 
                               name="search" 
                               value="{{ request('search') }}" 
                               placeholder="Cari nama / email..." 
                               class="w-full pl-10 pr-4 py-2.5 text-xs font-medium rounded-xl border border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-gray-800 dark:text-white outline-none focus:ring-2 focus:ring-[#D19D9D] dark:focus:ring-indigo-500 transition-all">
                        <div class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        </div>
                    </form>

                    <!-- Tombol Tambah Karyawan -->
                    <a href="{{ route('karyawan.create') }}" 
                       class="inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl bg-[#D19D9D] hover:bg-[#C48686] active:bg-[#B06E6E] dark:bg-indigo-600 dark:hover:bg-indigo-500 text-gray-900 dark:text-white font-extrabold text-xs shadow-md transition-all whitespace-nowrap flex-shrink-0">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path>
                        </svg>
                        Tambah Akun Karyawan
                    </a>
                </div>
            </div>

            <!-- Employee Table -->
            <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-gray-50/75 dark:bg-gray-700/50 border-b border-gray-100 dark:border-gray-700 text-[11px] font-extrabold uppercase tracking-wider text-gray-500 dark:text-gray-400">
                                <th class="py-4 px-6">Pengguna / Karyawan</th>
                                <th class="py-4 px-6">Email Login</th>
                                <th class="py-4 px-6 text-center">Jenis Peran (Role)</th>
                                <th class="py-4 px-6 text-center">Hak Akses Utama</th>
                                <th class="py-4 px-6 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-gray-700/60 text-sm">
                            @forelse($karyawans as $item)
                            <tr class="hover:bg-gray-50/60 dark:hover:bg-gray-700/40 transition-colors">
                                <!-- User Info -->
                                <td class="py-4 px-6">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-xl bg-indigo-50 dark:bg-indigo-900/40 text-indigo-700 dark:text-indigo-300 font-extrabold flex items-center justify-center text-sm border border-indigo-200 dark:border-indigo-700 flex-shrink-0 shadow-sm">
                                            {{ strtoupper(substr($item->name, 0, 1)) }}
                                        </div>
                                        <div>
                                            <div class="font-extrabold text-gray-900 dark:text-white flex items-center gap-2">
                                                {{ $item->name }}
                                                @if($item->id === Auth::id())
                                                    <span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-indigo-100 dark:bg-indigo-900/60 text-indigo-700 dark:text-indigo-300">Akun Anda</span>
                                                @endif
                                            </div>
                                            <div class="text-xs text-gray-400 mt-0.5">ID: #{{ $item->id }}</div>
                                        </div>
                                    </div>
                                </td>

                                <!-- Email -->
                                <td class="py-4 px-6 text-xs font-semibold text-gray-600 dark:text-gray-300">
                                    {{ $item->email }}
                                </td>

                                <!-- Role Badge -->
                                <td class="py-4 px-6 text-center">
                                    @if($item->isOwner())
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-extrabold bg-amber-100 dark:bg-amber-900/40 text-amber-800 dark:text-amber-300 border border-amber-300 dark:border-amber-700">
                                            👑 Owner
                                        </span>
                                    @elseif($item->isKasir())
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-extrabold bg-emerald-100 dark:bg-emerald-900/40 text-emerald-800 dark:text-emerald-300 border border-emerald-300 dark:border-emerald-700">
                                            💼 Kasir
                                        </span>
                                    @elseif($item->isDapur())
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-extrabold bg-orange-100 dark:bg-orange-900/40 text-orange-800 dark:text-orange-300 border border-orange-300 dark:border-orange-700">
                                            🍳 Dapur
                                        </span>
                                    @elseif($item->isStockAdmin())
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-extrabold bg-blue-100 dark:bg-blue-900/40 text-blue-800 dark:text-blue-300 border border-blue-300 dark:border-blue-700">
                                            📦 Admin Stok
                                        </span>
                                    @elseif($item->isCustom())
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-extrabold bg-purple-100 dark:bg-purple-900/40 text-purple-800 dark:text-purple-300 border border-purple-300 dark:border-purple-700">
                                            🛠️ Kustom
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-extrabold bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-300">
                                            {{ $item->role }}
                                        </span>
                                    @endif
                                </td>

                                <!-- Scope explanation -->
                                <td class="py-4 px-6 text-center text-xs text-gray-500 dark:text-gray-400">
                                    @if($item->isOwner())
                                        <span class="font-bold text-amber-700 dark:text-amber-300">Akses Penuh Semua Modul</span>
                                    @elseif($item->isKasir())
                                        POS Kasir & Cetak Struk
                                    @elseif($item->isDapur())
                                        Monitor Pesanan & Produksi
                                    @elseif($item->isStockAdmin())
                                        Bahan Baku, Resep & Restock
                                    @elseif($item->isCustom())
                                        <div class="flex flex-wrap gap-1 justify-center max-w-xs mx-auto">
                                            @forelse($item->permission_labels as $lbl)
                                                <span class="px-2 py-0.5 rounded-md text-[10px] font-bold bg-purple-50 dark:bg-purple-950/40 text-purple-700 dark:text-purple-300 border border-purple-200 dark:border-purple-800/60">
                                                    {{ $lbl }}
                                                </span>
                                            @empty
                                                <span class="text-gray-400 italic text-[11px]">Belum diatur izin</span>
                                            @endforelse
                                        </div>
                                    @else
                                        -
                                    @endif
                                </td>

                                <!-- Actions -->
                                <td class="py-4 px-6 text-right whitespace-nowrap">
                                    <div class="inline-flex items-center gap-2">
                                        <!-- Edit Button -->
                                        <a href="{{ route('karyawan.edit', $item->id) }}" 
                                           class="p-2 rounded-xl text-gray-600 hover:text-indigo-600 dark:text-gray-300 dark:hover:text-indigo-400 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors"
                                           title="Edit Akun">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                        </a>

                                        <!-- Delete Button (Only for other accounts) -->
                                        @if($item->id !== Auth::id())
                                            <form action="{{ route('karyawan.destroy', $item->id) }}" 
                                                  method="POST" 
                                                  onsubmit="return confirm('Apakah Anda yakin ingin menghapus akun karyawan {{ $item->name }}?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" 
                                                        class="p-2 rounded-xl text-gray-400 hover:text-red-600 dark:hover:text-red-400 hover:bg-red-50 dark:hover:bg-red-950/40 transition-colors"
                                                        title="Hapus Akun">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                </button>
                                            </form>
                                        @else
                                            <span class="p-2 text-gray-300 dark:text-gray-600 cursor-not-allowed" title="Tidak dapat menghapus akun sendiri">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                                            </span>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="py-12 text-center text-gray-400 dark:text-gray-500">
                                    Tidak ada data karyawan ditemukan.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
