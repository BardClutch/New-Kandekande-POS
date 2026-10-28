<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight transition-colors duration-300">
            {{ __('Manajemen Dapur (Produksi)') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if(session('success'))
                <div class="mb-4 bg-green-100 dark:bg-green-900/40 border-l-4 border-green-500 text-green-700 dark:text-green-300 p-4 rounded-xl shadow-sm" role="alert">
                    <p class="font-medium">{{ session('success') }}</p>
                </div>
            @endif
            @if(session('error'))
                <div class="mb-4 bg-red-100 dark:bg-red-900/40 border-l-4 border-red-500 text-red-700 dark:text-red-300 p-4 rounded-xl shadow-sm" role="alert">
                    <p class="font-medium">{{ session('error') }}</p>
                </div>
            @endif
            @if($errors->any())
                <div class="mb-4 bg-red-100 dark:bg-red-900/40 border-l-4 border-red-500 text-red-700 dark:text-red-300 p-4 rounded-xl shadow-sm">
                    <ul class="list-disc list-inside text-sm">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="flex flex-col lg:flex-row gap-6">
                <!-- Form Pencatatan Produksi -->
                <div class="w-full lg:w-1/3 bg-white dark:bg-gray-800 p-6 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 h-fit transition-colors">
                    <div class="flex items-center space-x-2 mb-4">
                        <span class="p-2 bg-orange-100 dark:bg-orange-900/40 text-orange-600 dark:text-orange-400 rounded-lg">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                        </span>
                        <div>
                            <h3 class="text-lg font-bold text-gray-800 dark:text-gray-100">Catat Produksi Baru</h3>
                            <p class="text-xs text-gray-500 dark:text-gray-400">Sistem Per-Batch & Pesanan Kustom</p>
                        </div>
                    </div>

                    <form action="{{ route('dapur.store') }}" method="POST" id="form_produksi">
                        @csrf
                        <div class="mb-4">
                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">Pilih Produk</label>
                            <select name="id_produk" id="select_produk" required class="block w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 shadow-sm focus:border-orange-500 focus:ring focus:ring-orange-200 dark:focus:ring-orange-800 focus:ring-opacity-50 text-sm">
                                <option value="">-- Pilih Produk Roti / Kue --</option>
                                @foreach($produks as $p)
                                    <option value="{{ $p->id_produk }}" 
                                            data-tipe="{{ $p->tipe_produksi ?? 'Batch' }}" 
                                            data-yield="{{ $p->jumlah_per_batch ?? 1 }}"
                                            data-reseps='@json($p->reseps)'>
                                        {{ $p->nama_produk }} {{ ($p->tipe_produksi ?? 'Batch') === 'Kustom' ? '🎂 [Pesanan Kustom]' : '📦 [Batch: ' . ($p->jumlah_per_batch ?? 1) . ' pcs/batch]' }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Panel Mode BATCH -->
                        <div id="panel_batch" class="space-y-4">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">
                                    Jumlah Batch yang Dimasak
                                </label>
                                <div class="relative rounded-lg shadow-sm">
                                    <input type="number" name="jumlah_batch" id="input_jumlah_batch" min="1" value="1" 
                                           class="block w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 shadow-sm focus:border-orange-500 focus:ring focus:ring-orange-200 dark:focus:ring-orange-800 focus:ring-opacity-50 text-sm pr-16" 
                                           placeholder="Misal: 1 atau 2">
                                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none text-xs font-bold text-gray-400 dark:text-gray-400">
                                        Batch
                                    </div>
                                </div>
                            </div>

                            <!-- Info Box Hasil Matang -->
                            <div class="p-3.5 bg-indigo-50/70 dark:bg-indigo-950/40 rounded-xl border border-indigo-200/80 dark:border-indigo-800/60 transition-all">
                                <div class="flex items-center justify-between text-xs text-indigo-700 dark:text-indigo-300">
                                    <span class="flex items-center gap-1">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        Resep 1 Batch:
                                    </span>
                                    <span class="font-bold text-indigo-900 dark:text-indigo-200" id="badge_yield_per_batch">1 pcs</span>
                                </div>
                                <div class="flex items-center justify-between mt-2 pt-2 border-t border-indigo-200/60 dark:border-indigo-800/40">
                                    <span class="text-xs font-semibold text-gray-700 dark:text-gray-300">Target Total Matang:</span>
                                    <span class="text-base font-extrabold text-indigo-600 dark:text-indigo-400" id="badge_target_matang">1 pcs</span>
                                </div>
                            </div>
                        </div>

                        <!-- Panel Mode KUSTOM (Kue Ulang Tahun / Custom Order) -->
                        <div id="panel_kustom" class="space-y-4 hidden">
                            <div class="p-3.5 bg-purple-50/70 dark:bg-purple-950/40 rounded-xl border border-purple-200/80 dark:border-purple-800/60">
                                <div class="flex items-center gap-1.5 text-xs font-bold text-purple-700 dark:text-purple-300">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path></svg>
                                    Mode Pesanan Kustom
                                </div>
                                <p class="text-xs text-purple-600 dark:text-purple-400 mt-1">
                                    Pesanan khusus (misal Kue Ulang Tahun). Dihitung per unit pesanan pelanggan, bukan sistem batch massal.
                                </p>
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">
                                    Target Jumlah Pesanan (Unit / Kue)
                                </label>
                                <input type="number" name="jumlah_matang" id="input_jumlah_kustom" min="1" value="1" 
                                       class="block w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 shadow-sm focus:border-purple-500 focus:ring focus:ring-purple-200 dark:focus:ring-purple-800 focus:ring-opacity-50 text-sm" 
                                       placeholder="Misal: 1 atau 2 kue">
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">
                                    Catatan / Spesifikasi Kustom <span class="text-xs font-normal text-gray-400">(Opsional)</span>
                                </label>
                                <input type="text" name="catatan_produksi" id="input_catatan_kustom" maxlength="255"
                                       class="block w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 shadow-sm focus:border-purple-500 focus:ring focus:ring-purple-200 dark:focus:ring-purple-800 focus:ring-opacity-50 text-sm" 
                                       placeholder="Contoh: Kue Ultah uk. 20cm - 'Happy Birthday Sarah'">
                            </div>
                        </div>

                        <!-- Live Breakdown Pengurangan Bahan Baku -->
                        <div id="panel_kebutuhan_bahan" class="my-4 hidden">
                            <label class="block text-xs font-bold uppercase tracking-wider text-gray-600 dark:text-gray-400 mb-1.5 flex items-center justify-between">
                                <span>Estimasi Bahan Terpakai:</span>
                                <span id="label_kebutuhan_mode" class="text-[10px] text-gray-500"></span>
                            </label>
                            <div id="list_kebutuhan_bahan" class="space-y-1.5 max-h-36 overflow-y-auto text-xs p-2.5 bg-gray-50 dark:bg-gray-700/50 rounded-lg border border-gray-200 dark:border-gray-600/60 divide-y divide-gray-100 dark:divide-gray-600/40">
                                <!-- Populated dynamically by JS -->
                            </div>
                        </div>

                        <!-- Info Tanggal Otomatis -->
                        <div class="my-4 p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg border border-gray-200 dark:border-gray-600/60 flex items-center justify-between text-xs text-gray-600 dark:text-gray-300">
                            <span class="flex items-center gap-1.5 font-medium">
                                <svg class="w-4 h-4 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                Tanggal Produksi:
                            </span>
                            <span class="font-bold text-gray-800 dark:text-gray-100 bg-white dark:bg-gray-600 px-2.5 py-1 rounded border border-gray-200 dark:border-gray-500">
                                {{ date('d/m/Y') }} (Hari Ini)
                            </span>
                        </div>

                        <button type="submit" id="btn_submit_produksi" class="w-full bg-orange-500 hover:bg-orange-600 active:bg-orange-700 text-white font-bold py-2.5 px-4 rounded-lg shadow transition-all duration-200 flex items-center justify-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 18.657A8 8 0 016.343 7.343S7 9 9 10c0-2 .5-5 2.986-7C14 5 16.09 5.777 17.656 7.343A7.975 7.975 0 0120 13a7.975 7.975 0 01-2.343 5.657z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.879 16.121A3 3 0 1012.015 11L11 14H9c0 .768.293 1.536.879 2.121z"></path>
                            </svg>
                            Mulai Produksi
                        </button>
                    </form>
                </div>

                <!-- Bagian Kanan: Tab Antrean Pesanan Kasir (Live) & Aktivitas Produksi -->
                <div class="w-full lg:w-2/3 space-y-4" x-data="{ activeTabDapur: 'pesanan_masuk' }">
                    
                    <!-- Tabs Header & Live Status Bar -->
                    <div class="bg-white dark:bg-gray-800 p-4 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 transition-colors">
                        <div class="flex items-center gap-2">
                            <button @click="activeTabDapur = 'pesanan_masuk'" 
                                    :class="activeTabDapur === 'pesanan_masuk' ? 'bg-orange-500 text-white shadow-sm' : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600'"
                                    class="px-4 py-2 rounded-xl text-xs font-bold transition-all flex items-center gap-2">
                                <span class="relative flex h-2 w-2">
                                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                                    <span class="relative inline-flex rounded-full h-2 w-2 bg-red-500"></span>
                                </span>
                                🔔 Pesanan Kasir Masuk
                                <span id="badge_count_pesanan" class="px-2 py-0.5 rounded-full text-[10px] font-black" :class="activeTabDapur === 'pesanan_masuk' ? 'bg-white/20 text-white' : 'bg-orange-100 dark:bg-orange-900/40 text-orange-700 dark:text-orange-300'">
                                    {{ count($pesananMasuk) }}
                                </span>
                            </button>

                            <button @click="activeTabDapur = 'produksi_batch'" 
                                    :class="activeTabDapur === 'produksi_batch' ? 'bg-indigo-600 text-white shadow-sm' : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600'"
                                    class="px-4 py-2 rounded-xl text-xs font-bold transition-all flex items-center gap-2">
                                <span>🍞</span>
                                Antrean Produksi Batch
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-black" :class="activeTabDapur === 'produksi_batch' ? 'bg-white/20 text-white' : 'bg-gray-200 dark:bg-gray-600 text-gray-700 dark:text-gray-300'">
                                    {{ $produksis->count() }}
                                </span>
                            </button>
                        </div>

                        <!-- Audio Chime & Realtime Controls -->
                        <div class="flex items-center gap-2 self-end sm:self-auto">
                            <button type="button" 
                                    id="btn_toggle_audio" 
                                    onclick="toggleKitchenAudio()"
                                    class="px-3 py-1.5 rounded-lg text-xs font-bold bg-amber-50 dark:bg-amber-950/40 text-amber-700 dark:text-amber-300 border border-amber-200 dark:border-amber-800 flex items-center gap-1.5 shadow-sm transition-colors hover:bg-amber-100">
                                <span id="audio_icon">🔔</span>
                                <span id="audio_text">Bel Dapur: Aktif</span>
                            </button>
                            <button type="button" 
                                    onclick="testKitchenSound()" 
                                    class="p-1.5 rounded-lg text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors"
                                    title="Uji Suara Bel">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.536 8.464a5 5 0 010 7.072m2.828-9.9a9 9 0 010 12.728M5.586 15H4a1 1 0 01-1-1v-4a1 1 0 011-1h1.586l4.707-4.707C10.923 3.663 12 4.109 12 5v14c0 .891-1.077 1.337-1.707.707L5.586 15z"></path></svg>
                            </button>
                        </div>
                    </div>

                    <!-- TAB 1: Pesanan Kasir Masuk (Real-time Live Orders) -->
                    <div x-show="activeTabDapur === 'pesanan_masuk'" x-transition class="space-y-3">
                        <div class="flex items-center justify-between px-1">
                            <div>
                                <h3 class="text-base font-extrabold text-gray-800 dark:text-gray-100 flex items-center gap-2">
                                    <span>👨‍🍳</span>
                                    Antrean Pesanan Masuk dari Kasir
                                </h3>
                                <p class="text-xs text-gray-500 dark:text-gray-400">
                                    Pesanan yang dibuat oleh kasir akan otomatis muncul di sini lengkap dengan notifikasi suara.
                                </p>
                            </div>
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-bold bg-emerald-100 dark:bg-emerald-950/50 text-emerald-700 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800">
                                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                                Live Sync (4s)
                            </span>
                        </div>

                        <!-- Container Kartu Pesanan Kasir -->
                        <div id="container_pesanan_masuk" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @forelse($pesananMasuk as $trx)
                            <div class="order-card bg-white dark:bg-gray-800 rounded-2xl p-5 shadow-sm border {{ $trx->status_pesanan == 'Menunggu Dapur' ? 'border-amber-300 dark:border-amber-700/80 ring-2 ring-amber-400/20' : 'border-blue-200 dark:border-blue-800' }} transition-all" 
                                 id="order-card-{{ $trx->id_transaksi }}"
                                 data-id="{{ $trx->id_transaksi }}">
                                
                                <div class="flex items-start justify-between gap-2 border-b border-gray-100 dark:border-gray-700 pb-3 mb-3">
                                    <div>
                                        <div class="flex items-center gap-2">
                                            <span class="text-base font-black text-gray-900 dark:text-gray-100">#{{ $trx->id_transaksi }}</span>
                                            <span class="px-2 py-0.5 rounded text-[10px] font-bold {{ $trx->jenis_pesanan == 'Whatsapp' ? 'bg-green-100 text-green-800 dark:bg-green-900/40 dark:text-green-300' : 'bg-blue-100 text-blue-800 dark:bg-blue-900/40 dark:text-blue-300' }}">
                                                {{ $trx->jenis_pesanan }}
                                            </span>
                                        </div>
                                        <div class="text-[11px] text-gray-500 dark:text-gray-400 mt-0.5 flex items-center gap-1">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                            {{ $trx->tanggal_waktu->format('H:i') }} ({{ $trx->tanggal_waktu->diffForHumans() }})
                                        </div>
                                    </div>

                                    <!-- Status Pill -->
                                    <span class="order-status-badge px-2.5 py-1 rounded-full text-xs font-black {{ $trx->status_pesanan == 'Menunggu Dapur' ? 'bg-amber-100 dark:bg-amber-900/40 text-amber-800 dark:text-amber-300' : 'bg-blue-100 dark:bg-blue-900/40 text-blue-800 dark:text-blue-300' }}">
                                        {{ $trx->status_pesanan }}
                                    </span>
                                </div>

                                <!-- Customer Details -->
                                <div class="mb-3 text-xs bg-gray-50 dark:bg-gray-700/40 p-2.5 rounded-xl border border-gray-100 dark:border-gray-700">
                                    <div class="flex items-center justify-between">
                                        <span class="font-bold text-gray-800 dark:text-gray-200">
                                            👤 {{ $trx->nama_pelanggan ?: 'Pelanggan Umum' }}
                                        </span>
                                        @if($trx->pelanggan && $trx->pelanggan->tipe_pelanggan == 'VIP')
                                            <span class="text-[10px] font-black text-amber-600 dark:text-amber-400">⭐ VIP</span>
                                        @endif
                                    </div>
                                    @if($trx->catatan_pesanan)
                                        <p class="text-orange-600 dark:text-orange-400 mt-1 font-semibold italic text-[11px]">
                                            Catatan: "{{ $trx->catatan_pesanan }}"
                                        </p>
                                    @endif
                                </div>

                                <!-- Items List -->
                                <div class="space-y-1.5 mb-4 text-xs">
                                    @foreach($trx->detailTransaksis as $d)
                                    <div class="flex items-center justify-between py-1 border-b border-gray-50 dark:border-gray-700/40">
                                        <div class="flex items-center gap-2">
                                            <span class="w-5 h-5 rounded bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-200 font-bold flex items-center justify-center text-[11px]">
                                                {{ $d->qty_beli }}x
                                            </span>
                                            <span class="font-bold text-gray-800 dark:text-gray-200">
                                                {{ $d->produk->nama_produk ?? 'Produk' }}
                                            </span>
                                        </div>
                                        <span class="text-[11px] text-gray-400">
                                            {{ $d->produk->tipe_produksi ?? 'Batch' }}
                                        </span>
                                    </div>
                                    @endforeach
                                </div>

                                <!-- Interactive Action Buttons for Kitchen -->
                                <div class="flex items-center gap-2 pt-2 border-t border-gray-100 dark:border-gray-700">
                                    @if($trx->status_pesanan == 'Menunggu Dapur')
                                    <button type="button" 
                                            onclick="updateKitchenOrderStatus({{ $trx->id_transaksi }}, 'Sedang Disiapkan')" 
                                            class="w-full bg-blue-600 hover:bg-blue-700 active:bg-blue-800 text-white font-bold py-2 px-3 rounded-xl text-xs transition-all shadow-sm flex items-center justify-center gap-1.5">
                                        <span>👨‍🍳</span> Mulai Siapkan
                                    </button>
                                    @elseif($trx->status_pesanan == 'Sedang Disiapkan')
                                    <button type="button" 
                                            onclick="updateKitchenOrderStatus({{ $trx->id_transaksi }}, 'Siap Disajikan')" 
                                            class="w-full bg-emerald-600 hover:bg-emerald-700 active:bg-emerald-800 text-white font-bold py-2 px-3 rounded-xl text-xs transition-all shadow-sm flex items-center justify-center gap-1.5">
                                        <span>✅</span> Pesanan Siap (Selesai)
                                    </button>
                                    @endif
                                </div>

                            </div>
                            @empty
                            <div class="col-span-full py-16 text-center bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700" id="empty_pesanan_masuk_msg">
                                <div class="w-12 h-12 rounded-full bg-emerald-50 dark:bg-emerald-950/40 text-emerald-600 dark:text-emerald-400 flex items-center justify-center mx-auto mb-3">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                </div>
                                <h4 class="font-extrabold text-sm text-gray-800 dark:text-gray-100">Dapur Sedang Santai</h4>
                                <p class="text-xs text-gray-400 dark:text-gray-500 mt-1 max-w-sm mx-auto">
                                    Tidak ada antrean pesanan kasir yang menunggu. Saat kasir melakukan transaksi baru, pesanan akan langsung muncul di sini disertai bunyi bel.
                                </p>
                            </div>
                            @endforelse
                        </div>
                    </div>

                    <!-- TAB 2: Riwayat/Progres Produksi Batch Dapur -->
                    <div x-show="activeTabDapur === 'produksi_batch'" x-transition class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 transition-colors">
                        <div class="flex items-center justify-between mb-4">
                            <div>
                                <h3 class="text-lg font-bold text-gray-800 dark:text-gray-100">Daftar Aktivitas Dapur</h3>
                                <p class="text-xs text-gray-500 dark:text-gray-400">Monitoring status produksi roti batch & pesanan kustom</p>
                            </div>
                            <span class="text-xs px-2.5 py-1 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg font-semibold border border-gray-200 dark:border-gray-600">
                                Total: {{ $produksis->count() }} antrean
                            </span>
                        </div>
                        
                        <div class="overflow-x-auto border rounded-xl border-gray-200 dark:border-gray-700">
                            <table class="min-w-full bg-white dark:bg-gray-800 transition-colors">
                                <thead>
                                    <tr class="bg-gray-50 dark:bg-gray-700 text-gray-600 dark:text-gray-300 uppercase text-xs leading-normal font-semibold">
                                        <th class="py-3 px-4 text-left border-b border-gray-200 dark:border-gray-700">ID / Tgl</th>
                                        <th class="py-3 px-4 text-left border-b border-gray-200 dark:border-gray-700">Produk & Tipe</th>
                                        <th class="py-3 px-4 text-center border-b border-gray-200 dark:border-gray-700">Jumlah Target</th>
                                        <th class="py-3 px-4 text-center border-b border-gray-200 dark:border-gray-700">Status</th>
                                        <th class="py-3 px-4 text-center border-b border-gray-200 dark:border-gray-700">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="text-gray-600 dark:text-gray-300 text-sm divide-y divide-gray-100 dark:divide-gray-700">
                                    @forelse($produksis as $item)
                                    <tr class="hover:bg-gray-50/80 dark:hover:bg-gray-700/50 transition-colors">
                                        <td class="py-3.5 px-4 text-left whitespace-nowrap">
                                            <div class="font-bold text-gray-800 dark:text-gray-200">#{{ $item->id_produksi }}</div>
                                            <div class="text-xs text-gray-500 dark:text-gray-400">{{ $item->tanggal_produksi ? $item->tanggal_produksi->format('d/m/Y') : '-' }}</div>
                                        </td>
                                        <td class="py-3.5 px-4 text-left">
                                            <div class="font-bold text-gray-800 dark:text-gray-100">
                                                {{ $item->produk->nama_produk ?? '-' }}
                                            </div>
                                            @if(($item->produk->tipe_produksi ?? '') === 'Kustom' || $item->catatan_produksi)
                                                <div class="mt-1 flex flex-wrap items-center gap-1.5">
                                                    <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[11px] font-bold bg-purple-100 dark:bg-purple-900/40 text-purple-700 dark:text-purple-300 border border-purple-200 dark:border-purple-800/50">
                                                        🎂 Pesanan Kustom
                                                    </span>
                                                    @if($item->catatan_produksi)
                                                        <span class="text-xs text-purple-700 dark:text-purple-300 italic font-medium">
                                                            "{{ $item->catatan_produksi }}"
                                                        </span>
                                                    @endif
                                                </div>
                                            @else
                                                <div class="mt-0.5 text-xs text-gray-500 dark:text-gray-400 flex items-center gap-1">
                                                    <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[11px] font-semibold bg-indigo-50 dark:bg-indigo-900/30 text-indigo-700 dark:text-indigo-300 border border-indigo-200/60 dark:border-indigo-800/40">
                                                        📦 Standar Batch
                                                    </span>
                                                </div>
                                            @endif
                                        </td>
                                        <td class="py-3.5 px-4 text-center">
                                            @if(($item->produk->tipe_produksi ?? '') === 'Kustom')
                                                <div class="inline-flex flex-col items-center">
                                                    <span class="font-extrabold text-purple-600 dark:text-purple-400 text-base">
                                                        {{ $item->jumlah_matang }}
                                                    </span>
                                                    <span class="text-[11px] text-gray-500 dark:text-gray-400 font-medium">Unit / Kue</span>
                                                </div>
                                            @else
                                                <div class="inline-flex flex-col items-center">
                                                    <span class="font-bold text-xs px-2 py-0.5 rounded-full bg-indigo-100 dark:bg-indigo-900/40 text-indigo-700 dark:text-indigo-300 mb-0.5">
                                                        {{ $item->jumlah_batch ?? 1 }} Batch
                                                    </span>
                                                    <span class="text-xs font-semibold text-gray-700 dark:text-gray-300">
                                                        {{ $item->jumlah_matang }} pcs
                                                    </span>
                                                </div>
                                            @endif
                                        </td>
                                        <td class="py-3.5 px-4 text-center whitespace-nowrap">
                                            @if($item->status_produksi == 'Persiapan')
                                                <span class="bg-amber-100 dark:bg-amber-900/50 text-amber-800 dark:text-amber-300 py-1 px-3 rounded-full text-xs font-bold">Persiapan</span>
                                            @elseif($item->status_produksi == 'Dipanggang')
                                                <span class="bg-orange-100 dark:bg-orange-900/50 text-orange-800 dark:text-orange-300 py-1 px-3 rounded-full text-xs font-bold">Dipanggang</span>
                                            @else
                                                <span class="bg-green-100 dark:bg-green-900/50 text-green-800 dark:text-green-300 py-1 px-3 rounded-full text-xs font-bold">Selesai</span>
                                            @endif
                                        </td>
                                        <td class="py-3.5 px-4 text-center whitespace-nowrap">
                                            <div class="flex items-center justify-center space-x-2">
                                                <form action="{{ route('dapur.updateStatus', $item->id_produksi) }}" method="POST" class="inline">
                                                    @csrf
                                                    @method('PATCH')
                                                    <select name="status_produksi" onchange="this.form.submit()" class="text-xs rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 shadow-sm focus:ring focus:ring-orange-200 dark:focus:ring-orange-800 {{ $item->status_produksi == 'Selesai' ? 'bg-gray-100 dark:bg-gray-700 cursor-not-allowed opacity-80' : '' }}" {{ $item->status_produksi == 'Selesai' ? 'disabled' : '' }}>
                                                        <option value="Persiapan" {{ $item->status_produksi == 'Persiapan' ? 'selected' : '' }}>Persiapan</option>
                                                        <option value="Dipanggang" {{ $item->status_produksi == 'Dipanggang' ? 'selected' : '' }}>Dipanggang</option>
                                                        <option value="Selesai" {{ $item->status_produksi == 'Selesai' ? 'selected' : '' }}>Selesai</option>
                                                    </select>
                                                </form>
                                                
                                                <form action="{{ route('dapur.destroy', $item->id_produksi) }}" method="POST" onsubmit="return confirm('Yakin ingin membatalkan produksi ini? Stok bahan baku akan dikembalikan.');" class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-white bg-red-500 hover:bg-red-600 active:bg-red-700 font-medium rounded-lg text-xs px-2.5 py-1.5 focus:outline-none transition-colors shadow-sm" title="Batalkan produksi dan kembalikan stok bahan baku">
                                                        Batal
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="5" class="py-8 text-center text-gray-400 dark:text-gray-500">
                                            <div class="flex flex-col items-center justify-center space-y-2">
                                                <svg class="w-8 h-8 text-gray-300 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                                                </svg>
                                                <span>Belum ada aktivitas produksi dapur.</span>
                                            </div>
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

    <!-- Live Toast Container for Kitchen Real-time Notifications -->
    <div id="kitchen-toast-container" class="fixed top-20 right-6 z-50 flex flex-col gap-3 max-w-sm w-full pointer-events-none"></div>

    <!-- Script Kalkulator Reaktif Produksi & Real-time Live Order Polling -->
    <script>
        // Kitchen Web Audio API Synthesized Chime (Soft 2-Tone Ding-Dong)
        let kitchenAudioEnabled = localStorage.getItem('kitchen_audio_enabled') !== 'false';

        function updateAudioButtonUI() {
            const btn = document.getElementById('btn_toggle_audio');
            const icon = document.getElementById('audio_icon');
            const text = document.getElementById('audio_text');
            if (!btn) return;
            if (kitchenAudioEnabled) {
                icon.textContent = '🔔';
                text.textContent = 'Bel Dapur: Aktif';
                btn.className = 'px-3 py-1.5 rounded-lg text-xs font-bold bg-amber-50 dark:bg-amber-950/40 text-amber-700 dark:text-amber-300 border border-amber-200 dark:border-amber-800 flex items-center gap-1.5 shadow-sm transition-colors hover:bg-amber-100';
            } else {
                icon.textContent = '🔕';
                text.textContent = 'Bel Dapur: Bisu';
                btn.className = 'px-3 py-1.5 rounded-lg text-xs font-bold bg-gray-100 dark:bg-gray-700 text-gray-500 dark:text-gray-400 border border-gray-300 dark:border-gray-600 flex items-center gap-1.5 shadow-sm transition-colors hover:bg-gray-200';
            }
        }

        function toggleKitchenAudio() {
            kitchenAudioEnabled = !kitchenAudioEnabled;
            localStorage.setItem('kitchen_audio_enabled', kitchenAudioEnabled);
            updateAudioButtonUI();
            if (kitchenAudioEnabled) {
                playKitchenBell();
            }
        }

        function testKitchenSound() {
            playKitchenBell();
        }

        function playKitchenBell() {
            try {
                const AudioCtx = window.AudioContext || window.webkitAudioContext;
                if (!AudioCtx) return;
                const ctx = new AudioCtx();

                // Tone 1: E5 (659 Hz)
                const osc1 = ctx.createOscillator();
                const gain1 = ctx.createGain();
                osc1.type = 'sine';
                osc1.frequency.setValueAtTime(659.25, ctx.currentTime);
                gain1.gain.setValueAtTime(0.2, ctx.currentTime);
                gain1.gain.exponentialRampToValueAtTime(0.001, ctx.currentTime + 0.5);
                osc1.connect(gain1);
                gain1.connect(ctx.destination);
                osc1.start(ctx.currentTime);
                osc1.stop(ctx.currentTime + 0.5);

                // Tone 2: A5 (880 Hz)
                const osc2 = ctx.createOscillator();
                const gain2 = ctx.createGain();
                osc2.type = 'sine';
                osc2.frequency.setValueAtTime(880, ctx.currentTime + 0.16);
                gain2.gain.setValueAtTime(0.25, ctx.currentTime + 0.16);
                gain2.gain.exponentialRampToValueAtTime(0.001, ctx.currentTime + 1.1);
                osc2.connect(gain2);
                gain2.connect(ctx.destination);
                osc2.start(ctx.currentTime + 0.16);
                osc2.stop(ctx.currentTime + 1.1);
            } catch (e) {
                console.warn('Audio not playable:', e);
            }
        }

        // Floating Toast Notification
        function showKitchenToast(order) {
            const container = document.getElementById('kitchen-toast-container');
            if (!container) return;

            const toast = document.createElement('div');
            toast.className = 'pointer-events-auto bg-white dark:bg-gray-800 rounded-2xl p-4 shadow-2xl border-2 border-orange-500 transform transition-all duration-300 translate-y-2 opacity-0 flex items-start gap-3';
            
            let itemSummary = '';
            if (order.detail_transaksis && order.detail_transaksis.length > 0) {
                itemSummary = order.detail_transaksis.map(d => `${d.qty_beli}x ${d.produk ? d.produk.nama_produk : 'Item'}`).join(', ');
            }

            toast.innerHTML = `
                <div class="w-10 h-10 rounded-xl bg-orange-100 dark:bg-orange-900/50 text-orange-600 dark:text-orange-400 flex items-center justify-center text-lg flex-shrink-0 animate-bounce">
                    🔔
                </div>
                <div class="flex-1 min-w-0">
                    <div class="flex items-center justify-between gap-1">
                        <span class="font-black text-sm text-gray-900 dark:text-gray-100">Pesanan Baru #${order.id_transaksi}</span>
                        <span class="text-[10px] font-bold px-2 py-0.5 rounded bg-orange-100 text-orange-700">Baru Masuk</span>
                    </div>
                    <p class="text-xs font-semibold text-gray-700 dark:text-gray-300 mt-0.5 truncate">
                        👤 ${order.nama_pelanggan || 'Pelanggan Umum'}
                    </p>
                    <p class="text-[11px] text-gray-500 dark:text-gray-400 mt-1 line-clamp-2">
                        🍞 ${itemSummary || 'Detail item pesanan'}
                    </p>
                </div>
                <button type="button" class="text-gray-400 hover:text-gray-600 text-xs font-bold" onclick="this.parentElement.remove()">✕</button>
            `;

            container.appendChild(toast);
            requestAnimationFrame(() => {
                toast.classList.remove('translate-y-2', 'opacity-0');
            });

            setTimeout(() => {
                toast.classList.add('opacity-0', 'translate-x-full');
                setTimeout(() => toast.remove(), 400);
            }, 6500);
        }

        // Update Kitchen Order Status via AJAX
        async function updateKitchenOrderStatus(orderId, newStatus) {
            try {
                const res = await fetch(`{{ url('/dapur/pesanan') }}/${orderId}/status`, {
                    method: 'PATCH',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ status_pesanan: newStatus })
                });

                if (res.ok) {
                    const card = document.getElementById(`order-card-${orderId}`);
                    if (card) {
                        if (newStatus === 'Siap Disajikan' || newStatus === 'Selesai') {
                            card.classList.add('opacity-40', 'scale-95');
                            setTimeout(() => {
                                card.remove();
                                updateOrderCountBadge();
                            }, 500);
                        } else {
                            const badge = card.querySelector('.order-status-badge');
                            if (badge) {
                                badge.textContent = newStatus;
                                badge.className = 'order-status-badge px-2.5 py-1 rounded-full text-xs font-black bg-blue-100 dark:bg-blue-900/40 text-blue-800 dark:text-blue-300';
                            }
                            const actions = card.querySelector('.border-t button');
                            if (actions) {
                                actions.outerHTML = `
                                    <button type="button" 
                                            onclick="updateKitchenOrderStatus(${orderId}, 'Siap Disajikan')" 
                                            class="w-full bg-emerald-600 hover:bg-emerald-700 active:bg-emerald-800 text-white font-bold py-2 px-3 rounded-xl text-xs transition-all shadow-sm flex items-center justify-center gap-1.5">
                                        <span>✅</span> Pesanan Siap (Selesai)
                                    </button>
                                `;
                            }
                        }
                    }
                }
            } catch (err) {
                console.error('Failed to update status:', err);
            }
        }

        function updateOrderCountBadge() {
            const count = document.querySelectorAll('#container_pesanan_masuk .order-card').length;
            const badge = document.getElementById('badge_count_pesanan');
            if (badge) badge.textContent = count;
            const emptyMsg = document.getElementById('empty_pesanan_masuk_msg');
            if (count === 0 && !emptyMsg) {
                const container = document.getElementById('container_pesanan_masuk');
                if (container) {
                    container.innerHTML = `
                        <div class="col-span-full py-16 text-center bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700" id="empty_pesanan_masuk_msg">
                            <div class="w-12 h-12 rounded-full bg-emerald-50 dark:bg-emerald-950/40 text-emerald-600 dark:text-emerald-400 flex items-center justify-center mx-auto mb-3">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            </div>
                            <h4 class="font-extrabold text-sm text-gray-800 dark:text-gray-100">Dapur Sedang Santai</h4>
                            <p class="text-xs text-gray-400 dark:text-gray-500 mt-1 max-w-sm mx-auto">
                                Tidak ada antrean pesanan kasir yang menunggu. Saat kasir melakukan transaksi baru, pesanan akan langsung muncul di sini disertai bunyi bel.
                            </p>
                        </div>
                    `;
                }
            }
        }

        // Live Polling every 4 seconds
        let lastKnownOrderId = {{ $latestTransaksiId ?? 0 }};

        async function pollKitchenOrders() {
            try {
                const res = await fetch(`{{ route('dapur.cek_pesanan') }}?last_id=${lastKnownOrderId}`, {
                    headers: { 'Accept': 'application/json' }
                });
                if (!res.ok) return;
                const data = await res.json();
                
                if (data.has_new && data.new_orders && data.new_orders.length > 0) {
                    lastKnownOrderId = data.latest_id;
                    if (kitchenAudioEnabled) {
                        playKitchenBell();
                    }

                    data.new_orders.forEach(order => {
                        showKitchenToast(order);
                        appendOrderCard(order);
                    });

                    updateOrderCountBadge();
                }
            } catch (e) {
                console.warn('Kitchen poll error:', e);
            }
        }

        function appendOrderCard(order) {
            const container = document.getElementById('container_pesanan_masuk');
            if (!container) return;
            const emptyMsg = document.getElementById('empty_pesanan_masuk_msg');
            if (emptyMsg) emptyMsg.remove();

            if (document.getElementById(`order-card-${order.id_transaksi}`)) return;

            const card = document.createElement('div');
            card.className = 'order-card bg-white dark:bg-gray-800 rounded-2xl p-5 shadow-sm border border-amber-300 dark:border-amber-700/80 ring-2 ring-amber-400/20 transition-all animate-pulse';
            card.id = `order-card-${order.id_transaksi}`;
            card.setAttribute('data-id', order.id_transaksi);

            let itemsHtml = '';
            if (order.detail_transaksis) {
                order.detail_transaksis.forEach(d => {
                    itemsHtml += `
                        <div class="flex items-center justify-between py-1 border-b border-gray-50 dark:border-gray-700/40">
                            <div class="flex items-center gap-2">
                                <span class="w-5 h-5 rounded bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-200 font-bold flex items-center justify-center text-[11px]">
                                    ${d.qty_beli}x
                                </span>
                                <span class="font-bold text-gray-800 dark:text-gray-200">
                                    ${d.produk ? d.produk.nama_produk : 'Produk'}
                                </span>
                            </div>
                            <span class="text-[11px] text-gray-400">
                                ${d.produk ? d.produk.tipe_produksi : 'Batch'}
                            </span>
                        </div>
                    `;
                });
            }

            card.innerHTML = `
                <div class="flex items-start justify-between gap-2 border-b border-gray-100 dark:border-gray-700 pb-3 mb-3">
                    <div>
                        <div class="flex items-center gap-2">
                            <span class="text-base font-black text-gray-900 dark:text-gray-100">#${order.id_transaksi}</span>
                            <span class="px-2 py-0.5 rounded text-[10px] font-bold ${order.jenis_pesanan == 'Whatsapp' ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800'}">
                                ${order.jenis_pesanan}
                            </span>
                        </div>
                        <div class="text-[11px] text-gray-500 dark:text-gray-400 mt-0.5">
                            Baru saja masuk
                        </div>
                    </div>
                    <span class="order-status-badge px-2.5 py-1 rounded-full text-xs font-black bg-amber-100 dark:bg-amber-900/40 text-amber-800 dark:text-amber-300">
                        Menunggu Dapur
                    </span>
                </div>
                <div class="mb-3 text-xs bg-gray-50 dark:bg-gray-700/40 p-2.5 rounded-xl border border-gray-100 dark:border-gray-700">
                    <div class="flex items-center justify-between">
                        <span class="font-bold text-gray-800 dark:text-gray-200">
                            👤 ${order.nama_pelanggan || 'Pelanggan Umum'}
                        </span>
                    </div>
                    ${order.catatan_pesanan ? `<p class="text-orange-600 dark:text-orange-400 mt-1 font-semibold italic text-[11px]">Note: "${order.catatan_pesanan}"</p>` : ''}
                </div>
                <div class="space-y-1.5 mb-4 text-xs">
                    ${itemsHtml}
                </div>
                <div class="flex items-center gap-2 pt-2 border-t border-gray-100 dark:border-gray-700">
                    <button type="button" 
                            onclick="updateKitchenOrderStatus(${order.id_transaksi}, 'Sedang Disiapkan')" 
                            class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-3 rounded-xl text-xs transition-all shadow-sm flex items-center justify-center gap-1.5">
                        <span>👨‍🍳</span> Mulai Siapkan
                    </button>
                </div>
            `;

            container.prepend(card);
            setTimeout(() => card.classList.remove('animate-pulse'), 3000);
        }

        setInterval(pollKitchenOrders, 4000);
        updateAudioButtonUI();

        // Kalkulator Reaktif Produksi
        document.addEventListener('DOMContentLoaded', function () {
            const selectProduk = document.getElementById('select_produk');
            const panelBatch = document.getElementById('panel_batch');
            const panelKustom = document.getElementById('panel_kustom');
            const inputJumlahBatch = document.getElementById('input_jumlah_batch');
            const inputJumlahKustom = document.getElementById('input_jumlah_kustom');
            const badgeYield = document.getElementById('badge_yield_per_batch');
            const badgeTargetMatang = document.getElementById('badge_target_matang');
            const panelBahan = document.getElementById('panel_kebutuhan_bahan');
            const listBahan = document.getElementById('list_kebutuhan_bahan');
            const labelKebutuhanMode = document.getElementById('label_kebutuhan_mode');

            function updateUI() {
                const selected = selectProduk.options[selectProduk.selectedIndex];
                if (!selected || !selected.value) {
                    panelBatch.classList.remove('hidden');
                    panelKustom.classList.add('hidden');
                    panelBahan.classList.add('hidden');
                    badgeYield.textContent = '-';
                    badgeTargetMatang.textContent = '-';
                    return;
                }

                const tipe = selected.getAttribute('data-tipe') || 'Batch';
                const yieldPerBatch = parseInt(selected.getAttribute('data-yield') || '1', 10);
                const resepsRaw = selected.getAttribute('data-reseps');
                let reseps = [];
                try {
                    reseps = resepsRaw ? JSON.parse(resepsRaw) : [];
                } catch (e) {
                    reseps = [];
                }

                let multiplier = 1;

                if (tipe === 'Kustom') {
                    panelBatch.classList.add('hidden');
                    panelKustom.classList.remove('hidden');
                    inputJumlahBatch.removeAttribute('required');
                    inputJumlahKustom.setAttribute('required', 'required');

                    const qtyKustom = parseInt(inputJumlahKustom.value || '1', 10);
                    multiplier = Math.max(1, isNaN(qtyKustom) ? 1 : qtyKustom);
                    labelKebutuhanMode.textContent = `(Untuk ${multiplier} Unit Kue Kustom)`;
                } else {
                    panelBatch.classList.remove('hidden');
                    panelKustom.classList.add('hidden');
                    inputJumlahKustom.removeAttribute('required');
                    inputJumlahBatch.setAttribute('required', 'required');

                    const batchCount = parseInt(inputJumlahBatch.value || '1', 10);
                    const validBatch = Math.max(1, isNaN(batchCount) ? 1 : batchCount);
                    multiplier = validBatch;

                    const totalPcs = validBatch * yieldPerBatch;
                    badgeYield.textContent = `${yieldPerBatch} pcs / batch`;
                    badgeTargetMatang.textContent = `${totalPcs} pcs (${validBatch} batch)`;
                    labelKebutuhanMode.textContent = `(Untuk ${validBatch} Batch = ${totalPcs} pcs)`;
                }

                // Render live breakdown of raw materials
                if (reseps.length > 0) {
                    panelBahan.classList.remove('hidden');
                    listBahan.innerHTML = '';
                    reseps.forEach(r => {
                        const bahan = r.bahan_baku;
                        const butuh = (parseFloat(r.takaran) * multiplier);
                        const butuhFormatted = Number.isInteger(butuh) ? butuh : butuh.toFixed(2);
                        const stokAda = bahan ? parseFloat(bahan.jumlah_stok) : 0;
                        const cukup = stokAda >= butuh;

                        const itemDiv = document.createElement('div');
                        itemDiv.className = 'flex items-center justify-between py-1.5';
                        itemDiv.innerHTML = `
                            <div class="flex items-center gap-1.5">
                                <span class="w-1.5 h-1.5 rounded-full ${cukup ? 'bg-emerald-500' : 'bg-red-500'}"></span>
                                <span class="font-medium text-gray-700 dark:text-gray-300">${bahan ? bahan.nama_bahan : 'Bahan'}</span>
                            </div>
                            <div class="text-right">
                                <span class="font-bold ${cukup ? 'text-gray-800 dark:text-gray-200' : 'text-red-600 dark:text-red-400'}">
                                    ${butuhFormatted} ${bahan ? bahan.satuan : ''}
                                </span>
                                <span class="text-[10px] text-gray-400 ml-1">(Ada: ${stokAda})</span>
                            </div>
                        `;
                        listBahan.appendChild(itemDiv);
                    });
                } else {
                    panelBahan.classList.remove('hidden');
                    listBahan.innerHTML = `
                        <div class="text-amber-600 dark:text-amber-400 text-xs italic py-1">
                            ⚠️ Produk ini belum memiliki komposisi resep bahan baku. Silakan atur di Buku Resep.
                        </div>
                    `;
                }
            }

            selectProduk.addEventListener('change', updateUI);
            inputJumlahBatch.addEventListener('input', updateUI);
            inputJumlahKustom.addEventListener('input', updateUI);

            // Inisialisasi awal
            updateUI();
        });
    </script>
</x-app-layout>
