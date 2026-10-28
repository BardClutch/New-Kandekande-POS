<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight transition-colors duration-300">
            {{ Auth::user()->isOwner() ? __('Laporan Penjualan') : __('Riwayat Transaksi') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="mb-8">
                <h3 class="text-2xl font-extrabold text-gray-800 dark:text-gray-100 transition-colors">
                    {{ Auth::user()->isOwner() ? 'Laporan & Analitik Penjualan' : 'Riwayat Transaksi Kasir' }}
                </h3>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1 transition-colors">
                    {{ Auth::user()->isOwner() ? 'Pantau performa penjualan dan export data transaksi.' : 'Daftar riwayat transaksi penjualan dan fasilitas cetak ulang struk pembayaran.' }}
                </p>
            </div>

            <!-- Summary & Filter Section -->
            <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 mb-8 flex flex-col md:flex-row justify-between items-start md:items-center gap-6 transition-colors duration-300">
                <form action="{{ route('laporan.index') }}" method="GET" class="flex flex-wrap items-end gap-4 w-full md:w-auto bg-gray-50 dark:bg-gray-700/50 p-4 rounded-lg border border-gray-200 dark:border-gray-700 transition-colors">
                    <div>
                        <label class="block text-xs font-bold text-gray-600 dark:text-gray-300 uppercase tracking-wider mb-1">Dari Tanggal</label>
                        <input type="date" name="start_date" value="{{ $startDate->format('Y-m-d') }}" class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 text-sm transition-colors">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-600 dark:text-gray-300 uppercase tracking-wider mb-1">Sampai Tanggal</label>
                        <input type="date" name="end_date" value="{{ $endDate->format('Y-m-d') }}" class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 text-sm transition-colors">
                    </div>
                    <div class="flex gap-2 w-full sm:w-auto mt-2 sm:mt-0">
                        <button type="submit" class="w-full sm:w-auto bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2 rounded-md font-medium transition-colors shadow-sm text-sm flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path></svg>
                            Terapkan Filter
                        </button>
                    </div>
                </form>

                @if(Auth::user()->isOwner())
                <div class="flex items-center gap-3 w-full md:w-auto justify-end">
                    <a href="{{ route('laporan.export', request()->all()) }}" class="w-full sm:w-auto bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2.5 rounded-lg shadow-sm hover:shadow transition-all font-semibold text-sm flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                        Export Laporan CSV
                    </a>
                </div>
                @endif
            </div>

            <!-- KPI Summary Cards (Executive Owner Dashboard) -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-8">
                <!-- Total Transaksi -->
                <div class="bg-white dark:bg-gray-800 p-5 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 transition-colors">
                    <div class="flex items-center justify-between mb-3">
                        <span class="text-xs font-bold uppercase tracking-wider text-blue-600 dark:text-blue-400">Total Transaksi</span>
                        <div class="w-9 h-9 rounded-lg bg-blue-50 dark:bg-blue-900/40 text-blue-600 dark:text-blue-400 flex items-center justify-center">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                        </div>
                    </div>
                    <div class="text-2xl font-black text-gray-800 dark:text-gray-100">
                        {{ number_format($totalTransaksi) }}
                    </div>
                    <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">Transaksi Kasir tercatat</p>
                </div>

                @if(Auth::user()->isOwner())
                <!-- Total Omset / Pendapatan -->
                <div class="bg-white dark:bg-gray-800 p-5 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 transition-colors">
                    <div class="flex items-center justify-between mb-3">
                        <span class="text-xs font-bold uppercase tracking-wider text-indigo-600 dark:text-indigo-400">Total Omset</span>
                        <div class="w-9 h-9 rounded-lg bg-indigo-50 dark:bg-indigo-900/40 text-indigo-600 dark:text-indigo-400 flex items-center justify-center">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                    </div>
                    <div class="text-2xl font-black text-gray-800 dark:text-gray-100">
                        Rp {{ number_format($totalPendapatan, 0, ',', '.') }}
                    </div>
                    <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">Gross Revenue (Pendapatan)</p>
                </div>

                <!-- Estimasi HPP Bahan Baku -->
                <div class="bg-white dark:bg-gray-800 p-5 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 transition-colors">
                    <div class="flex items-center justify-between mb-3">
                        <span class="text-xs font-bold uppercase tracking-wider text-amber-600 dark:text-amber-400">HPP Bahan Baku</span>
                        <div class="w-9 h-9 rounded-lg bg-amber-50 dark:bg-amber-900/40 text-amber-600 dark:text-amber-400 flex items-center justify-center">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                        </div>
                    </div>
                    <div class="text-2xl font-black text-amber-600 dark:text-amber-400">
                        Rp {{ number_format($totalHpp, 0, ',', '.') }}
                    </div>
                    <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">Berdasarkan resep terpakai</p>
                </div>

                <!-- Laba Kotor & Margin -->
                <div class="bg-white dark:bg-gray-800 p-5 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 transition-colors">
                    <div class="flex items-center justify-between mb-3">
                        <span class="text-xs font-bold uppercase tracking-wider text-emerald-600 dark:text-emerald-400">Laba Kotor</span>
                        <span class="px-2 py-0.5 rounded-full text-xs font-black {{ $marginPersen >= 40 ? 'bg-emerald-100 dark:bg-emerald-900/40 text-emerald-700 dark:text-emerald-300' : 'bg-amber-100 dark:bg-amber-900/40 text-amber-700 dark:text-amber-300' }}">
                            {{ $marginPersen }}%
                        </span>
                    </div>
                    <div class="text-2xl font-black text-emerald-600 dark:text-emerald-400">
                        Rp {{ number_format($labaKotor, 0, ',', '.') }}
                    </div>
                    <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">Omset dikurangi HPP bahan</p>
                </div>
                @endif
            </div>

            <!-- Tabbed Main Content -->
            <div x-data="{ activeTab: '{{ Auth::user()->isOwner() ? 'produk' : 'transaksi' }}' }" class="space-y-6">
                @if(Auth::user()->isOwner())
                <!-- Navigation Tabs -->
                <div class="flex items-center gap-3 border-b border-gray-200 dark:border-gray-700 pb-3">
                    <button @click="activeTab = 'produk'" 
                            :class="activeTab === 'produk' ? 'bg-indigo-600 text-white shadow-sm' : 'bg-white dark:bg-gray-800 text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 border border-gray-200 dark:border-gray-700'"
                            class="px-5 py-2.5 rounded-xl font-bold text-sm transition-all flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                        Analisis Laba/Rugi per Menu Roti
                        <span class="px-2 py-0.5 text-xs rounded-full" :class="activeTab === 'produk' ? 'bg-white/20 text-white' : 'bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300'">{{ count($laporanProduk) }}</span>
                    </button>
                    <button @click="activeTab = 'transaksi'" 
                            :class="activeTab === 'transaksi' ? 'bg-indigo-600 text-white shadow-sm' : 'bg-white dark:bg-gray-800 text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 border border-gray-200 dark:border-gray-700'"
                            class="px-5 py-2.5 rounded-xl font-bold text-sm transition-all flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        Riwayat Transaksi Penjualan
                        <span class="px-2 py-0.5 text-xs rounded-full" :class="activeTab === 'transaksi' ? 'bg-white/20 text-white' : 'bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300'">{{ $totalTransaksi }}</span>
                    </button>
                </div>
                @endif

                @if(Auth::user()->isOwner())
                <!-- Tab 1: Analisis Laba/Rugi per Menu Roti (Owner Only) -->
                <div x-show="activeTab === 'produk'" x-transition class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-xl border border-gray-100 dark:border-gray-700 transition-colors">
                    <div class="p-6">
                        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
                            <div>
                                <h4 class="text-lg font-bold text-gray-800 dark:text-gray-100 flex items-center gap-2">
                                    <span>🍞</span>
                                    Analisis Profitabilitas & HPP Resep per Menu
                                </h4>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">
                                    HPP dihitung otomatis berdasarkan harga beli bahan baku dalam buku resep standar.
                                </p>
                            </div>
                        </div>

                        <div class="overflow-x-auto rounded-lg border border-gray-200 dark:border-gray-700">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gray-50 dark:bg-gray-700/60">
                                    <tr>
                                        <th class="py-3 px-4 text-left text-xs font-bold text-gray-500 dark:text-gray-300 uppercase">Nama Menu Roti</th>
                                        <th class="py-3 px-4 text-center text-xs font-bold text-gray-500 dark:text-gray-300 uppercase">Tipe</th>
                                        <th class="py-3 px-4 text-center text-xs font-bold text-gray-500 dark:text-gray-300 uppercase">Qty Terjual</th>
                                        <th class="py-3 px-4 text-right text-xs font-bold text-gray-500 dark:text-gray-300 uppercase">Harga Jual</th>
                                        <th class="py-3 px-4 text-right text-xs font-bold text-gray-500 dark:text-gray-300 uppercase">HPP Satuan</th>
                                        <th class="py-3 px-4 text-right text-xs font-bold text-gray-500 dark:text-gray-300 uppercase">Total Omset</th>
                                        <th class="py-3 px-4 text-right text-xs font-bold text-gray-500 dark:text-gray-300 uppercase">Total HPP</th>
                                        <th class="py-3 px-4 text-right text-xs font-bold text-gray-500 dark:text-gray-300 uppercase">Laba Kotor</th>
                                        <th class="py-3 px-4 text-center text-xs font-bold text-gray-500 dark:text-gray-300 uppercase">Margin %</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                    @forelse($laporanProduk as $item)
                                    @php
                                        $marginItemPersen = $item['total_omset'] > 0 ? round(($item['total_laba'] / $item['total_omset']) * 100, 1) : 0;
                                    @endphp
                                    <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-700/40 transition-colors">
                                        <td class="py-3.5 px-4 font-bold text-gray-900 dark:text-gray-100 text-sm">
                                            {{ $item['nama_produk'] }}
                                        </td>
                                        <td class="py-3.5 px-4 text-center">
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-semibold {{ $item['tipe_produksi'] == 'Kustom' ? 'bg-purple-100 dark:bg-purple-900/40 text-purple-700 dark:text-purple-300' : 'bg-blue-100 dark:bg-blue-900/40 text-blue-700 dark:text-blue-300' }}">
                                                {{ $item['tipe_produksi'] }}
                                            </span>
                                        </td>
                                        <td class="py-3.5 px-4 text-center font-bold text-gray-700 dark:text-gray-300 text-sm">
                                            {{ number_format($item['qty_terjual']) }} pcs
                                        </td>
                                        <td class="py-3.5 px-4 text-right text-sm text-gray-600 dark:text-gray-300">
                                            Rp {{ number_format($item['harga_jual_rata'], 0, ',', '.') }}
                                        </td>
                                        <td class="py-3.5 px-4 text-right text-sm font-semibold text-amber-600 dark:text-amber-400">
                                            Rp {{ number_format($item['hpp_satuan'], 0, ',', '.') }}
                                        </td>
                                        <td class="py-3.5 px-4 text-right text-sm font-bold text-gray-900 dark:text-gray-100">
                                            Rp {{ number_format($item['total_omset'], 0, ',', '.') }}
                                        </td>
                                        <td class="py-3.5 px-4 text-right text-sm font-semibold text-amber-600 dark:text-amber-400">
                                            Rp {{ number_format($item['total_hpp'], 0, ',', '.') }}
                                        </td>
                                        <td class="py-3.5 px-4 text-right text-sm font-black text-emerald-600 dark:text-emerald-400">
                                            Rp {{ number_format($item['total_laba'], 0, ',', '.') }}
                                        </td>
                                        <td class="py-3.5 px-4 text-center">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-extrabold {{ $marginItemPersen >= 45 ? 'bg-emerald-100 dark:bg-emerald-900/40 text-emerald-700 dark:text-emerald-300' : ($marginItemPersen >= 25 ? 'bg-amber-100 dark:bg-amber-900/40 text-amber-700 dark:text-amber-300' : 'bg-red-100 dark:bg-red-900/40 text-red-700 dark:text-red-300') }}">
                                                {{ $marginItemPersen }}%
                                            </span>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="9" class="py-10 text-center text-sm text-gray-400 dark:text-gray-500">
                                            Tidak ada riwayat penjualan produk pada rentang tanggal yang dipilih.
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Tab 2: Rincian Transaksi Penjualan -->
                <div x-show="activeTab === 'transaksi'" x-transition class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-xl border border-gray-100 dark:border-gray-700 transition-colors">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <div class="flex justify-between items-center mb-6">
                            <div>
                                <h4 class="text-lg font-bold text-gray-700 dark:text-gray-200">
                                    {{ Auth::user()->isOwner() ? 'Rincian Transaksi Kasir' : 'Daftar Riwayat Transaksi' }}
                                </h4>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Daftar struk transaksi beserta opsi cetak ulang</p>
                            </div>
                        </div>
                        
                        <div class="overflow-x-auto rounded-lg border border-gray-200 dark:border-gray-700">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gray-50 dark:bg-gray-700/60">
                                    <tr>
                                        <th class="py-3 px-5 text-left text-xs font-bold text-gray-500 dark:text-gray-300 uppercase tracking-wider">ID Trx</th>
                                        <th class="py-3 px-5 text-left text-xs font-bold text-gray-500 dark:text-gray-300 uppercase tracking-wider">Waktu</th>
                                        <th class="py-3 px-5 text-left text-xs font-bold text-gray-500 dark:text-gray-300 uppercase tracking-wider">Kasir</th>
                                        <th class="py-3 px-5 text-center text-xs font-bold text-gray-500 dark:text-gray-300 uppercase tracking-wider">Tipe Pesanan</th>
                                        <th class="py-3 px-5 text-center text-xs font-bold text-gray-500 dark:text-gray-300 uppercase tracking-wider">Metode</th>
                                        <th class="py-3 px-5 text-right text-xs font-bold text-gray-500 dark:text-gray-300 uppercase tracking-wider">Total Belanja</th>
                                        @if(Auth::user()->isOwner())
                                        <th class="py-3 px-5 text-right text-xs font-bold text-amber-500 uppercase tracking-wider">HPP Bahan</th>
                                        <th class="py-3 px-5 text-right text-xs font-bold text-emerald-500 uppercase tracking-wider">Laba Kotor</th>
                                        @endif
                                        <th class="py-3 px-5 text-center text-xs font-bold text-gray-500 dark:text-gray-300 uppercase tracking-wider">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                    @forelse($transaksis as $trx)
                                    @php
                                        $trxHpp = 0;
                                        if (Auth::user()->isOwner()) {
                                            foreach($trx->detailTransaksis as $dtl) {
                                                $prodHpp = $dtl->produk ? $dtl->produk->hitungHpp() : 0;
                                                $trxHpp += ($prodHpp * $dtl->qty_beli);
                                            }
                                        }
                                        $trxLaba = $trx->total_belanja - $trxHpp;
                                    @endphp
                                    <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-700/50 transition-colors">
                                        <td class="py-4 px-5 text-left whitespace-nowrap text-sm font-bold text-gray-600 dark:text-gray-300">
                                            #{{ $trx->id_transaksi }}
                                        </td>
                                        <td class="py-4 px-5 text-left text-sm text-gray-600 dark:text-gray-300">
                                            <div class="flex items-center gap-1.5">
                                                <svg class="w-4 h-4 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                                {{ $trx->tanggal_waktu->format('d M Y, H:i') }}
                                            </div>
                                        </td>
                                        <td class="py-4 px-5 text-left text-sm font-medium text-gray-800 dark:text-gray-100">
                                            {{ $trx->user->name ?? '-' }}
                                        </td>
                                        <td class="py-4 px-5 text-center">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold {{ $trx->jenis_pesanan == 'Whatsapp' ? 'bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-300' : 'bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-300' }}">
                                                {{ $trx->jenis_pesanan }}
                                            </span>
                                        </td>
                                        <td class="py-4 px-5 text-center text-sm font-medium text-gray-600 dark:text-gray-300">
                                            {{ $trx->metode_pembayaran }}
                                        </td>
                                        <td class="py-4 px-5 text-right font-bold text-gray-900 dark:text-gray-100 text-sm">
                                            Rp {{ number_format($trx->total_belanja, 0, ',', '.') }}
                                        </td>
                                        @if(Auth::user()->isOwner())
                                        <td class="py-4 px-5 text-right font-semibold text-amber-600 dark:text-amber-400 text-sm">
                                            Rp {{ number_format($trxHpp, 0, ',', '.') }}
                                        </td>
                                        <td class="py-4 px-5 text-right font-black text-emerald-600 dark:text-emerald-400 text-sm">
                                            Rp {{ number_format($trxLaba, 0, ',', '.') }}
                                        </td>
                                        @endif
                                        <td class="py-4 px-5 text-center whitespace-nowrap">
                                            <a href="{{ route('kasir.cetak', $trx->id_transaksi) }}" target="_blank" class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-bold rounded-lg bg-indigo-50 dark:bg-indigo-900/40 text-indigo-600 dark:text-indigo-400 hover:bg-indigo-100 dark:hover:bg-indigo-800/60 border border-indigo-200 dark:border-indigo-700 transition-colors shadow-sm" title="Cetak Ulang Struk">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                                                Cetak
                                            </a>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="{{ Auth::user()->isOwner() ? 9 : 7 }}" class="py-12 text-center">
                                            <svg class="mx-auto h-12 w-12 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                            <p class="mt-4 text-sm text-gray-500 dark:text-gray-400">Tidak ada data transaksi pada rentang tanggal tersebut.</p>
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
    </div>
</x-app-layout>
