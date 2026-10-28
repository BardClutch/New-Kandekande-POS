<!-- DASHBOARD KHUSUS ADMIN GUDANG (LOGISTIK, BAHAN BAKU & RESTOCK) -->
<div class="space-y-6">
    <!-- Hero Banner Aksi Admin Gudang -->
    <div class="bg-gradient-to-r from-blue-700 via-indigo-700 to-slate-800 rounded-3xl p-6 sm:p-8 text-white shadow-xl relative overflow-hidden">
        <div class="absolute -right-8 -bottom-10 opacity-10 pointer-events-none">
            <svg class="w-64 h-64 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
        </div>
        <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-5">
            <div class="min-w-0 flex-1">
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-black bg-white/20 backdrop-blur-md text-white mb-2">
                    📦 Portal Logistik & Gudang
                </span>
                <h1 class="text-2xl sm:text-3xl font-black tracking-tight text-white">
                    Halo, {{ Auth::user()->name }}! 👋
                </h1>
                <p class="text-indigo-200 text-xs sm:text-sm mt-1 max-w-xl leading-relaxed">
                    Kelola ketersediaan fisik bahan baku, pantau peringatan stok kritis sebelum habis, dan catat riwayat restock bahan masuk.
                </p>
            </div>
            <div class="flex items-center gap-2.5 self-start md:self-auto flex-shrink-0 flex-nowrap">
                <a href="{{ route('restock.index') }}" 
                   class="px-4 sm:px-5 py-3 bg-emerald-500 hover:bg-emerald-600 active:scale-95 font-extrabold text-xs sm:text-sm text-white rounded-2xl shadow-lg transition-all flex items-center gap-2 whitespace-nowrap">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    <span>Catat Restock</span>
                </a>
                <a href="{{ route('bahanbaku.index') }}" 
                   class="px-4 sm:px-5 py-3 bg-white/10 hover:bg-white/20 text-white font-extrabold text-xs sm:text-sm rounded-2xl border border-white/20 backdrop-blur-md transition-all flex items-center gap-2 whitespace-nowrap">
                    <span>Lihat Semua Bahan &rarr;</span>
                </a>
            </div>
        </div>
    </div>

    <!-- 4 Warehouse Operational KPI Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
        <!-- 1. Total Jenis Bahan Baku -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm hover:shadow-md transition-all p-5 border border-gray-100 dark:border-gray-700/80 border-l-4 border-l-blue-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-[11px] font-bold uppercase tracking-wider text-blue-600 dark:text-blue-400 mb-1">Total Jenis Bahan</p>
                    <p class="text-2xl font-black text-gray-900 dark:text-white">{{ $totalBahan }} <span class="text-sm font-normal text-gray-400">item</span></p>
                    <p class="text-[11px] text-gray-500 dark:text-gray-400 mt-1">Terdata di gudang</p>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-blue-50 dark:bg-blue-950/50 text-blue-600 dark:text-blue-400 flex items-center justify-center font-bold text-xl shadow-inner">
                    📦
                </div>
            </div>
        </div>

        <!-- 2. Bahan Kritis / Menipis -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm hover:shadow-md transition-all p-5 border border-gray-100 dark:border-gray-700/80 border-l-4 border-l-red-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-[11px] font-bold uppercase tracking-wider text-red-600 dark:text-red-400 mb-1">Stok Bahan Kritis</p>
                    <p class="text-2xl font-black text-gray-900 dark:text-white flex items-center gap-2">
                        {{ $bahanMenipis->count() }} <span class="text-sm font-normal text-gray-400">item</span>
                        @if($bahanMenipis->count() > 0)
                        <span class="w-2.5 h-2.5 rounded-full bg-red-500 animate-ping"></span>
                        @endif
                    </p>
                    <p class="text-[11px] text-red-600 dark:text-red-400 font-bold mt-1">Di bawah batas aman</p>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-red-50 dark:bg-red-950/50 text-red-600 dark:text-red-400 flex items-center justify-center font-bold text-xl shadow-inner">
                    ⚠️
                </div>
            </div>
        </div>

        <!-- 3. Restock Bulan Ini -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm hover:shadow-md transition-all p-5 border border-gray-100 dark:border-gray-700/80 border-l-4 border-l-emerald-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-[11px] font-bold uppercase tracking-wider text-emerald-600 dark:text-emerald-400 mb-1">Restock Bulan Ini</p>
                    <p class="text-2xl font-black text-gray-900 dark:text-white">{{ $totalRestockBulanIni }} <span class="text-sm font-normal text-gray-400">kali</span></p>
                    <p class="text-[11px] text-gray-500 dark:text-gray-400 mt-1">Penerimaan pasokan</p>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-emerald-50 dark:bg-emerald-950/50 text-emerald-600 dark:text-emerald-400 flex items-center justify-center font-bold text-xl shadow-inner">
                    🚚
                </div>
            </div>
        </div>

        <!-- 4. Restock Hari Ini -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm hover:shadow-md transition-all p-5 border border-gray-100 dark:border-gray-700/80 border-l-4 border-l-purple-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-[11px] font-bold uppercase tracking-wider text-purple-600 dark:text-purple-400 mb-1">Restock Hari Ini</p>
                    <p class="text-2xl font-black text-gray-900 dark:text-white">{{ $totalRestockHariIni }} <span class="text-sm font-normal text-gray-400">kali</span></p>
                    <p class="text-[11px] text-gray-500 dark:text-gray-400 mt-1">Dicatat hari ini</p>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-purple-50 dark:bg-purple-950/50 text-purple-600 dark:text-purple-400 flex items-center justify-center font-bold text-xl shadow-inner">
                    📋
                </div>
            </div>
        </div>
    </div>

    <!-- Main Section: 2 Columns -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Daftar Bahan Baku Perlu Restock Segera -->
        <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-sm border border-gray-100 dark:border-gray-700 p-6 lg:col-span-2 transition-colors">
            <div class="flex items-center justify-between mb-5">
                <div>
                    <h3 class="text-base font-extrabold text-gray-800 dark:text-gray-100">Bahan Baku Perlu Restock Segera</h3>
                    <p class="text-xs text-gray-400 mt-0.5">Daftar bahan yang telah mencapai atau di bawah batas minimum</p>
                </div>
                <a href="{{ route('restock.index') }}" class="text-xs font-bold text-indigo-600 dark:text-indigo-400 hover:underline">
                    + Form Restock
                </a>
            </div>

            <div class="overflow-x-auto rounded-2xl border border-gray-100 dark:border-gray-700">
                <table class="min-w-full divide-y divide-gray-100 dark:divide-gray-700 text-xs">
                    <thead class="bg-gray-50 dark:bg-gray-700/50 text-gray-500 dark:text-gray-300 font-bold uppercase tracking-wider">
                        <tr>
                            <th class="py-3 px-4 text-left">Nama Bahan Baku</th>
                            <th class="py-3 px-4 text-center">Sisa Stok Fisik</th>
                            <th class="py-3 px-4 text-center">Batas Minimum</th>
                            <th class="py-3 px-4 text-right">Harga Beli Satuan</th>
                            <th class="py-3 px-4 text-center">Tindakan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700/60 bg-white dark:bg-gray-800">
                        @forelse($bahanMenipis as $b)
                        <tr class="hover:bg-red-50/30 dark:hover:bg-red-950/20 transition-colors">
                            <td class="py-3 px-4 font-bold text-gray-900 dark:text-gray-100">
                                {{ $b->nama_bahan }}
                            </td>
                            <td class="py-3 px-4 text-center">
                                <span class="px-2.5 py-1 rounded-full text-xs font-black bg-red-100 dark:bg-red-900/40 text-red-700 dark:text-red-300">
                                    {{ $b->jumlah_stok }} {{ $b->satuan }}
                                </span>
                            </td>
                            <td class="py-3 px-4 text-center font-medium text-gray-500 dark:text-gray-400">
                                {{ $b->stok_minimum }} {{ $b->satuan }}
                            </td>
                            <td class="py-3 px-4 text-right font-mono font-bold text-gray-900 dark:text-gray-200">
                                Rp {{ number_format($b->harga_beli_satuan ?? 0, 0, ',', '.') }} / {{ $b->satuan }}
                            </td>
                            <td class="py-3 px-4 text-center">
                                <a href="{{ route('restock.index') }}" 
                                   class="px-3 py-1 bg-emerald-50 dark:bg-emerald-900/40 hover:bg-emerald-100 text-emerald-700 dark:text-emerald-300 font-extrabold rounded-lg transition-colors border border-emerald-200 dark:border-emerald-800">
                                    + Restock
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="py-10 text-center text-gray-400 text-xs">
                                <span class="text-2xl block mb-1">🎉</span>
                                Luar biasa! Semua persediaan bahan baku di gudang dalam kondisi aman.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Riwayat Restock Masuk Terkini -->
        <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-sm border border-gray-100 dark:border-gray-700 p-6 transition-colors">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h3 class="text-base font-extrabold text-gray-800 dark:text-gray-100">Riwayat Masuk Terkini</h3>
                    <p class="text-xs text-gray-400 mt-0.5">5 pencatatan restock terakhir</p>
                </div>
                <a href="{{ route('restock.index') }}" class="text-xs font-bold text-indigo-600 dark:text-indigo-400 hover:underline">
                    Semua &rarr;
                </a>
            </div>

            <div class="space-y-3">
                @forelse($riwayatRestockTerbaru as $r)
                <div class="p-3.5 rounded-2xl bg-gray-50 dark:bg-gray-700/40 border border-gray-100 dark:border-gray-700 flex items-center justify-between gap-2">
                    <div class="min-w-0">
                        <p class="font-extrabold text-xs text-gray-900 dark:text-gray-100 truncate">
                            {{ $r->bahanBaku->nama_bahan ?? 'Bahan Dihapus' }}
                        </p>
                        <p class="text-[11px] text-gray-400 mt-0.5">
                            {{ \Carbon\Carbon::parse($r->tanggal_waktu)->format('d M Y, H:i') }}
                        </p>
                        <p class="text-[10px] text-gray-500 dark:text-gray-400">
                            Pencatat: {{ $r->user->name ?? 'Staf' }}
                        </p>
                    </div>
                    <span class="px-2.5 py-1 rounded-xl text-xs font-black bg-emerald-100 text-emerald-800 dark:bg-emerald-900/50 dark:text-emerald-300 whitespace-nowrap">
                        +{{ $r->jumlah_tambah }} {{ $r->bahanBaku->satuan ?? '' }}
                    </span>
                </div>
                @empty
                <div class="text-center py-8 text-gray-400 text-xs">
                    Belum ada riwayat restock bahan baku.
                </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
