<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight transition-colors duration-300">
            {{ __('Analisis Daya Pelanggan & CRM') }}
        </h2>
    </x-slot>

    <div class="py-12" x-data="{ modalTambah: false }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- Title & Action Header -->
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                <div>
                    <h3 class="text-2xl font-black text-gray-900 dark:text-gray-100 flex items-center gap-2">
                        <span>👥</span>
                        Daya Beli & Profil Pelanggan
                    </h3>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                        Segmentasi daya beli (Baru, Reguler, VIP) dan riwayat loyalitas belanja pelanggan toko roti.
                    </p>
                </div>
                <button @click="modalTambah = true" 
                        class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2.5 px-4 rounded-xl shadow-sm hover:shadow transition-all text-xs flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    Tambah Pelanggan Baru
                </button>
            </div>

            @if(session('success'))
            <div class="p-4 rounded-xl bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-200 dark:border-emerald-800 text-emerald-800 dark:text-emerald-300 text-xs font-bold flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                {{ session('success') }}
            </div>
            @endif

            <!-- KPI Summary Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
                <!-- Total Pelanggan -->
                <div class="bg-white dark:bg-gray-800 p-5 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700">
                    <div class="flex items-center justify-between mb-3">
                        <span class="text-xs font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400">Total Pelanggan</span>
                        <div class="w-9 h-9 rounded-xl bg-indigo-50 dark:bg-indigo-900/40 text-indigo-600 dark:text-indigo-400 flex items-center justify-center">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                        </div>
                    </div>
                    <div class="text-2xl font-black text-gray-900 dark:text-gray-100">{{ $totalPelanggan }}</div>
                    <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">Terdaftar dalam sistem</p>
                </div>

                <!-- Pelanggan VIP -->
                <div class="bg-white dark:bg-gray-800 p-5 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700">
                    <div class="flex items-center justify-between mb-3">
                        <span class="text-xs font-bold uppercase tracking-wider text-amber-600 dark:text-amber-400">Pelanggan VIP</span>
                        <div class="w-9 h-9 rounded-xl bg-amber-50 dark:bg-amber-900/40 text-amber-600 dark:text-amber-400 flex items-center justify-center">
                            ⭐
                        </div>
                    </div>
                    <div class="text-2xl font-black text-amber-600 dark:text-amber-400">{{ $totalVip }}</div>
                    <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">Belanja &ge; Rp 750k / &ge; 5x</p>
                </div>

                <!-- Pelanggan Reguler -->
                <div class="bg-white dark:bg-gray-800 p-5 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700">
                    <div class="flex items-center justify-between mb-3">
                        <span class="text-xs font-bold uppercase tracking-wider text-blue-600 dark:text-blue-400">Pelanggan Reguler</span>
                        <div class="w-9 h-9 rounded-xl bg-blue-50 dark:bg-blue-900/40 text-blue-600 dark:text-blue-400 flex items-center justify-center">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                    </div>
                    <div class="text-2xl font-black text-blue-600 dark:text-blue-400">{{ $totalReguler }}</div>
                    <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">Belanja &ge; Rp 250k / &ge; 2x</p>
                </div>

                <!-- Rata-rata Daya Beli -->
                <div class="bg-white dark:bg-gray-800 p-5 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700">
                    <div class="flex items-center justify-between mb-3">
                        <span class="text-xs font-bold uppercase tracking-wider text-emerald-600 dark:text-emerald-400">Daya Beli Rata-Rata</span>
                        <div class="w-9 h-9 rounded-xl bg-emerald-50 dark:bg-emerald-900/40 text-emerald-600 dark:text-emerald-400 flex items-center justify-center">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                    </div>
                    <div class="text-2xl font-black text-emerald-600 dark:text-emerald-400">
                        Rp {{ number_format($rataRataDayaBeli, 0, ',', '.') }}
                    </div>
                    <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">Basket Size / Transaksi</p>
                </div>
            </div>

            <!-- Filter & Search Toolbar -->
            <div class="bg-white dark:bg-gray-800 p-4 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 flex flex-col sm:flex-row justify-between items-stretch sm:items-center gap-4">
                <form action="{{ route('pelanggan.index') }}" method="GET" class="flex flex-wrap items-center gap-3 flex-1">
                    <div class="relative flex-1 min-w-[200px]">
                        <input type="text" 
                               name="search" 
                               value="{{ request('search') }}" 
                               placeholder="Cari nama atau No. WhatsApp..." 
                               class="w-full pl-9 pr-4 py-2 text-xs rounded-xl border-gray-200 dark:border-gray-700 dark:bg-gray-700 dark:text-gray-200 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                        <svg class="w-4 h-4 text-gray-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </div>

                    <select name="tier" class="py-2 px-3 text-xs rounded-xl border-gray-200 dark:border-gray-700 dark:bg-gray-700 dark:text-gray-200 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                        <option value="">Semua Tiering</option>
                        <option value="VIP" {{ request('tier') == 'VIP' ? 'selected' : '' }}>⭐ VIP</option>
                        <option value="Reguler" {{ request('tier') == 'Reguler' ? 'selected' : '' }}>Reguler</option>
                        <option value="Baru" {{ request('tier') == 'Baru' ? 'selected' : '' }}>Baru</option>
                    </select>

                    <button type="submit" class="bg-gray-800 hover:bg-gray-700 dark:bg-gray-600 dark:hover:bg-gray-500 text-white px-4 py-2 rounded-xl text-xs font-bold transition-colors">
                        Filter
                    </button>
                    @if(request()->hasAny(['search', 'tier']))
                    <a href="{{ route('pelanggan.index') }}" class="text-xs text-gray-500 hover:text-indigo-600 dark:hover:text-indigo-400 font-bold px-2">Reset</a>
                    @endif
                </form>
            </div>

            <!-- Table of Customers -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700/60">
                            <tr>
                                <th class="py-3.5 px-5 text-left text-xs font-bold text-gray-500 dark:text-gray-300 uppercase">Pelanggan</th>
                                <th class="py-3.5 px-5 text-center text-xs font-bold text-gray-500 dark:text-gray-300 uppercase">Tier Daya Beli</th>
                                <th class="py-3.5 px-5 text-center text-xs font-bold text-gray-500 dark:text-gray-300 uppercase">Kunjungan</th>
                                <th class="py-3.5 px-5 text-right text-xs font-bold text-gray-500 dark:text-gray-300 uppercase">Total Akumulasi Belanja</th>
                                <th class="py-3.5 px-5 text-right text-xs font-bold text-gray-500 dark:text-gray-300 uppercase">Daya Beli / Trx</th>
                                <th class="py-3.5 px-5 text-left text-xs font-bold text-gray-500 dark:text-gray-300 uppercase">Menu Favorit</th>
                                <th class="py-3.5 px-5 text-center text-xs font-bold text-gray-500 dark:text-gray-300 uppercase">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse($pelanggans as $cust)
                            @php
                                // Ambil produk terfavorit yang sering dibeli pelanggan ini
                                $productCounts = [];
                                foreach ($cust->transaksis as $trx) {
                                    foreach ($trx->detailTransaksis as $d) {
                                        $prodName = $d->produk->nama_produk ?? null;
                                        if ($prodName) {
                                            $productCounts[$prodName] = ($productCounts[$prodName] ?? 0) + $d->qty_beli;
                                        }
                                    }
                                }
                                arsort($productCounts);
                                $topProducts = array_slice($productCounts, 0, 2, true);
                            @endphp
                            <tr class="hover:bg-gray-50/60 dark:hover:bg-gray-700/40 transition-colors">
                                <td class="py-4 px-5">
                                    <div class="font-extrabold text-sm text-gray-900 dark:text-gray-100">
                                        {{ $cust->nama_pelanggan }}
                                    </div>
                                    @if($cust->no_hp)
                                    <a href="https://wa.me/{{ preg_replace('/^0/', '62', preg_replace('/[^0-9]/', '', $cust->no_hp)) }}" 
                                       target="_blank" 
                                       class="inline-flex items-center gap-1 text-xs text-emerald-600 dark:text-emerald-400 font-semibold hover:underline mt-0.5">
                                        <span>💬</span> {{ $cust->no_hp }}
                                    </a>
                                    @else
                                    <span class="text-xs text-gray-400">-</span>
                                    @endif
                                </td>

                                <td class="py-4 px-5 text-center">
                                    @if($cust->tipe_pelanggan == 'VIP')
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-black bg-amber-100 dark:bg-amber-900/40 text-amber-800 dark:text-amber-300 border border-amber-200 dark:border-amber-800">
                                            ⭐ VIP
                                        </span>
                                    @elseif($cust->tipe_pelanggan == 'Reguler')
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-blue-100 dark:bg-blue-900/40 text-blue-800 dark:text-blue-300 border border-blue-200 dark:border-blue-800">
                                            Reguler
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300">
                                            Baru
                                        </span>
                                    @endif
                                </td>

                                <td class="py-4 px-5 text-center">
                                    <span class="font-extrabold text-sm text-gray-800 dark:text-gray-200">
                                        {{ $cust->total_transaksi }}
                                    </span>
                                    <span class="text-[11px] text-gray-400 block">kunjungan</span>
                                </td>

                                <td class="py-4 px-5 text-right font-black text-sm text-gray-900 dark:text-gray-100">
                                    Rp {{ number_format($cust->total_belanja, 0, ',', '.') }}
                                </td>

                                <td class="py-4 px-5 text-right font-bold text-xs text-indigo-600 dark:text-indigo-400">
                                    Rp {{ number_format($cust->rata_rata_belanja, 0, ',', '.') }}
                                </td>

                                <td class="py-4 px-5 text-left text-xs">
                                    @if(count($topProducts) > 0)
                                        <div class="flex flex-wrap gap-1">
                                            @foreach($topProducts as $pName => $qty)
                                                <span class="inline-block px-2 py-0.5 rounded bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 text-[11px]">
                                                    🍞 {{ $pName }} ({{ $qty }})
                                                </span>
                                            @endforeach
                                        </div>
                                    @else
                                        <span class="text-gray-400 text-xs">Belum ada item</span>
                                    @endif
                                </td>

                                <td class="py-4 px-5 text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        @if($cust->no_hp)
                                        <a href="https://wa.me/{{ preg_replace('/^0/', '62', preg_replace('/[^0-9]/', '', $cust->no_hp)) }}" 
                                           target="_blank" 
                                           class="p-2 rounded-lg bg-emerald-50 dark:bg-emerald-950/40 text-emerald-600 hover:bg-emerald-100 transition-colors" 
                                           title="Chat WhatsApp">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
                                        </a>
                                        @endif
                                        <form action="{{ route('pelanggan.destroy', $cust->id_pelanggan) }}" method="POST" onsubmit="return confirm('Hapus profil pelanggan {{ $cust->nama_pelanggan }}?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-2 rounded-lg text-gray-400 hover:text-red-600 hover:bg-red-50 dark:hover:bg-red-900/30 transition-colors" title="Hapus">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="py-12 text-center text-sm text-gray-400 dark:text-gray-500">
                                    Belum ada data pelanggan yang tersimpan. Saat kasir menginput nama pembeli, data akan muncul di sini secara otomatis.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>

        <!-- Modal Tambah Pelanggan -->
        <div x-show="modalTambah" 
             x-transition 
             class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm"
             x-cloak>
            <div @click.outside="modalTambah = false" 
                 class="bg-white dark:bg-gray-800 rounded-3xl max-w-md w-full p-6 shadow-2xl border border-gray-100 dark:border-gray-700">
                <div class="flex justify-between items-center mb-4">
                    <h4 class="font-extrabold text-base text-gray-900 dark:text-gray-100">Tambah Profil Pelanggan</h4>
                    <button @click="modalTambah = false" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200">
                        ✕
                    </button>
                </div>
                <form action="{{ route('pelanggan.store') }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 uppercase mb-1">Nama Lengkap</label>
                        <input type="text" name="nama_pelanggan" required placeholder="Contoh: Ibu Ani" class="w-full text-xs rounded-xl border-gray-200 dark:border-gray-700 dark:bg-gray-700 dark:text-gray-100">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 uppercase mb-1">No. WhatsApp / HP</label>
                        <input type="text" name="no_hp" placeholder="Contoh: 08123456789" class="w-full text-xs rounded-xl border-gray-200 dark:border-gray-700 dark:bg-gray-700 dark:text-gray-100">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 uppercase mb-1">Catatan Khusus (Opsional)</label>
                        <textarea name="catatan" rows="2" placeholder="Preferensi rasa, alamat kirim, dll." class="w-full text-xs rounded-xl border-gray-200 dark:border-gray-700 dark:bg-gray-700 dark:text-gray-100"></textarea>
                    </div>
                    <div class="flex justify-end gap-2 pt-2">
                        <button type="button" @click="modalTambah = false" class="px-4 py-2 rounded-xl text-xs font-bold text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-700">Batal</button>
                        <button type="submit" class="px-5 py-2 rounded-xl text-xs font-bold bg-indigo-600 hover:bg-indigo-700 text-white shadow">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
