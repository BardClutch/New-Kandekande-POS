<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight transition-colors duration-300">
            {{ __('Pencatatan Komposisi Resep') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            
            @if(session('error'))
                <div class="mb-4 bg-red-100 dark:bg-red-900/40 border-l-4 border-red-500 text-red-700 dark:text-red-300 p-4 rounded-xl shadow-sm">
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

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-xl border border-gray-100 dark:border-gray-700 p-8 transition-colors duration-300">
                <form action="{{ route('resep.store') }}" method="POST" id="form_resep">
                    @csrf
                    
                    <!-- Pilih Produk -->
                    <div class="mb-6">
                        <label for="id_produk" class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2 transition-colors">
                            Pilih Produk Roti / Kue
                        </label>
                        <select name="id_produk" id="id_produk" class="shadow-sm border border-gray-300 dark:border-gray-600 rounded-lg w-full py-3 px-4 bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-200 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('id_produk') border-red-500 dark:border-red-500 focus:ring-red-500 @enderror" required>
                            <option value="">-- Pilih Produk yang Ingin Diberi Resep --</option>
                            @foreach($produks as $produk)
                                <option value="{{ $produk->id_produk }}" 
                                        data-tipe="{{ $produk->tipe_produksi }}" 
                                        data-yield="{{ $produk->jumlah_per_batch }}"
                                        {{ (old('id_produk', request('id_produk')) == $produk->id_produk) ? 'selected' : '' }}>
                                    {{ $produk->nama_produk }} ({{ $produk->tipe_produksi === 'Kustom' ? '🎂 Pesanan Kustom' : '📦 1 Batch = ' . $produk->jumlah_per_batch . ' pcs' }})
                                </option>
                            @endforeach
                        </select>
                        @error('id_produk')
                            <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                        @enderror

                        <!-- Info Tipe Produksi Produk -->
                        <div id="produk_info_box" class="mt-2.5 p-3 rounded-lg border border-indigo-200/60 dark:border-indigo-800/40 bg-indigo-50/60 dark:bg-indigo-950/30 text-xs text-indigo-800 dark:text-indigo-300 hidden">
                            <span class="font-bold" id="produk_info_title">Informasi Produk:</span>
                            <span id="produk_info_desc" class="ml-1"></span>
                        </div>
                    </div>
                    
                    <!-- Bagian Daftar Bahan Baku Utama & Takaran (Multi-Row) -->
                    <div class="mb-6 pt-4 border-t border-gray-200 dark:border-gray-700">
                        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-3 gap-2">
                            <div>
                                <label class="block text-gray-800 dark:text-gray-200 text-base font-bold transition-colors">
                                    Daftar Bahan Baku & Takaran
                                </label>
                                <p class="text-xs text-gray-500 dark:text-gray-400">
                                    Takaran dihitung per 1 batch adonan (atau per 1 unit jika pesanan kustom).
                                </p>
                            </div>
                            <button type="button" id="btn_add_bahan" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-blue-50 dark:bg-blue-900/40 text-blue-600 dark:text-blue-300 hover:bg-blue-100 dark:hover:bg-blue-900/60 border border-blue-200 dark:border-blue-700/60 rounded-lg text-xs font-bold transition-all shadow-sm active:scale-95">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                                Tambah Bahan Baku
                            </button>
                        </div>

                        <!-- Header Kolom (Desktop) -->
                        <div class="hidden sm:grid grid-cols-12 gap-3 px-3 py-2 bg-gray-100 dark:bg-gray-700/60 rounded-lg text-xs font-bold text-gray-600 dark:text-gray-300 uppercase tracking-wider mb-2">
                            <div class="col-span-1 text-center">No</div>
                            <div class="col-span-6">Bahan Baku</div>
                            <div class="col-span-4">Takaran Kuantitas</div>
                            <div class="col-span-1 text-center">Aksi</div>
                        </div>

                        <!-- Container Baris Bahan Baku -->
                        <div id="bahan_rows_container" class="space-y-2.5">
                            @php
                                $oldBahans = old('bahans', [ ['id_bahan' => old('id_bahan', ''), 'takaran' => old('takaran', '')] ]);
                            @endphp

                            @foreach($oldBahans as $index => $row)
                            <div class="bahan-row p-3 sm:p-2 bg-gray-50 dark:bg-gray-700/30 rounded-xl border border-gray-200 dark:border-gray-600/60 transition-all">
                                <div class="grid grid-cols-1 sm:grid-cols-12 gap-3 items-center">
                                    <!-- Nomor Urut -->
                                    <div class="col-span-1 flex items-center sm:justify-center">
                                        <span class="row-number w-6 h-6 rounded-full bg-blue-100 dark:bg-blue-900/60 text-blue-700 dark:text-blue-300 font-bold text-xs flex items-center justify-center">
                                            {{ $loop->iteration }}
                                        </span>
                                    </div>

                                    <!-- Pilih Bahan Baku -->
                                    <div class="col-span-6">
                                        <select name="bahans[{{ $index }}][id_bahan]" required class="select-bahan shadow-sm border border-gray-300 dark:border-gray-600 rounded-lg w-full py-2 px-3 bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-200 text-sm focus:ring-2 focus:ring-blue-500 transition-colors">
                                            <option value="">-- Pilih Bahan Baku --</option>
                                            @foreach($bahans as $bahan)
                                                <option value="{{ $bahan->id_bahan }}" 
                                                        data-satuan="{{ $bahan->satuan }}"
                                                        {{ ($row['id_bahan'] == $bahan->id_bahan) ? 'selected' : '' }}>
                                                    {{ $bahan->nama_bahan }} ({{ $bahan->satuan }}) - Stok: {{ $bahan->jumlah_stok }} {{ $bahan->satuan }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <!-- Input Takaran + Satuan -->
                                    <div class="col-span-4">
                                        <div class="relative rounded-lg shadow-sm">
                                            <input type="number" step="0.001" min="0.0001" name="bahans[{{ $index }}][takaran]" value="{{ $row['takaran'] }}" required placeholder="Misal: 0.5" class="input-takaran border border-gray-300 dark:border-gray-600 rounded-lg w-full py-2 px-3 bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-200 text-sm focus:ring-2 focus:ring-blue-500 pr-14">
                                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none text-xs font-semibold text-gray-400 label-satuan">
                                                satuan
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Tombol Hapus Baris -->
                                    <div class="col-span-1 text-center">
                                        <button type="button" class="btn-remove-row text-red-500 hover:text-red-700 dark:text-red-400 dark:hover:text-red-300 p-1.5 rounded-lg hover:bg-red-50 dark:hover:bg-red-900/30 transition-colors" title="Hapus baris bahan ini">
                                            <svg class="w-5 h-5 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>

                        <!-- Tombol Tambah Lagi di Bawah List -->
                        <div class="mt-3">
                            <button type="button" id="btn_add_bahan_bottom" class="inline-flex items-center gap-1.5 text-xs font-bold text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 py-1.5 px-2 rounded-lg hover:bg-blue-50 dark:hover:bg-blue-900/20 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                + Tambah Bahan Baku Lainnya
                            </button>
                        </div>
                    </div>
                    
                    <div class="flex items-center gap-4 pt-4 border-t border-gray-200 dark:border-gray-700 transition-colors mt-6">
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 active:bg-blue-800 text-white font-bold py-2.5 px-6 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 dark:focus:ring-offset-gray-800 transition-all flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            Simpan Komposisi Resep
                        </button>
                        <a href="{{ route('resep.index') }}" class="inline-block align-baseline font-bold text-sm text-gray-500 hover:text-gray-800 dark:text-gray-400 dark:hover:text-gray-200 transition-colors">
                            Kembali ke Buku Resep
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Template Baris Bahan Baku untuk Kloning via JavaScript -->
    <template id="template_bahan_row">
        <div class="bahan-row p-3 sm:p-2 bg-gray-50 dark:bg-gray-700/30 rounded-xl border border-gray-200 dark:border-gray-600/60 transition-all animate-fadeIn">
            <div class="grid grid-cols-1 sm:grid-cols-12 gap-3 items-center">
                <!-- Nomor Urut -->
                <div class="col-span-1 flex items-center sm:justify-center">
                    <span class="row-number w-6 h-6 rounded-full bg-blue-100 dark:bg-blue-900/60 text-blue-700 dark:text-blue-300 font-bold text-xs flex items-center justify-center">
                        __INDEX_NUMBER__
                    </span>
                </div>

                <!-- Pilih Bahan Baku -->
                <div class="col-span-6">
                    <select name="bahans[__INDEX__][id_bahan]" required class="select-bahan shadow-sm border border-gray-300 dark:border-gray-600 rounded-lg w-full py-2 px-3 bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-200 text-sm focus:ring-2 focus:ring-blue-500 transition-colors">
                        <option value="">-- Pilih Bahan Baku --</option>
                        @foreach($bahans as $bahan)
                            <option value="{{ $bahan->id_bahan }}" data-satuan="{{ $bahan->satuan }}">
                                {{ $bahan->nama_bahan }} ({{ $bahan->satuan }}) - Stok: {{ $bahan->jumlah_stok }} {{ $bahan->satuan }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Input Takaran + Satuan -->
                <div class="col-span-4">
                    <div class="relative rounded-lg shadow-sm">
                        <input type="number" step="0.001" min="0.0001" name="bahans[__INDEX__][takaran]" required placeholder="Misal: 0.5" class="input-takaran border border-gray-300 dark:border-gray-600 rounded-lg w-full py-2 px-3 bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-200 text-sm focus:ring-2 focus:ring-blue-500 pr-14">
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none text-xs font-semibold text-gray-400 label-satuan">
                            satuan
                        </div>
                    </div>
                </div>

                <!-- Tombol Hapus Baris -->
                <div class="col-span-1 text-center">
                    <button type="button" class="btn-remove-row text-red-500 hover:text-red-700 dark:text-red-400 dark:hover:text-red-300 p-1.5 rounded-lg hover:bg-red-50 dark:hover:bg-red-900/30 transition-colors" title="Hapus baris bahan ini">
                        <svg class="w-5 h-5 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                    </button>
                </div>
            </div>
        </div>
    </template>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const container = document.getElementById('bahan_rows_container');
            const template = document.getElementById('template_bahan_row').innerHTML;
            const btnAdd = document.getElementById('btn_add_bahan');
            const btnAddBottom = document.getElementById('btn_add_bahan_bottom');
            const selectProduk = document.getElementById('id_produk');
            const infoBox = document.getElementById('produk_info_box');
            const infoDesc = document.getElementById('produk_info_desc');

            let nextIndex = container.querySelectorAll('.bahan-row').length;

            function updateProductInfo() {
                const opt = selectProduk.options[selectProduk.selectedIndex];
                if (!opt || !opt.value) {
                    infoBox.classList.add('hidden');
                    return;
                }
                const tipe = opt.getAttribute('data-tipe');
                const yieldVal = opt.getAttribute('data-yield') || '1';

                infoBox.classList.remove('hidden');
                if (tipe === 'Kustom') {
                    infoDesc.textContent = "Produk ini merupakan pesanan kustom. Takaran bahan yang dimasukkan dihitung untuk 1 unit pesanan kue.";
                } else {
                    infoDesc.textContent = `Produk ini menggunakan sistem Batch dengan kapasitas 1 Batch = ${yieldVal} pcs. Takaran bahan yang dimasukkan dihitung untuk 1 batch adonan tersebut.`;
                }
            }

            selectProduk.addEventListener('change', updateProductInfo);
            updateProductInfo();

            function refreshRowLabels() {
                const rows = container.querySelectorAll('.bahan-row');
                rows.forEach((row, idx) => {
                    const numberEl = row.querySelector('.row-number');
                    if (numberEl) numberEl.textContent = (idx + 1);

                    const select = row.querySelector('.select-bahan');
                    const labelSatuan = row.querySelector('.label-satuan');
                    if (select && labelSatuan) {
                        const opt = select.options[select.selectedIndex];
                        if (opt && opt.getAttribute('data-satuan')) {
                            labelSatuan.textContent = opt.getAttribute('data-satuan');
                        } else {
                            labelSatuan.textContent = 'satuan';
                        }
                    }
                });
            }

            function addRow() {
                const html = template
                    .replace(/__INDEX__/g, nextIndex)
                    .replace(/__INDEX_NUMBER__/g, container.querySelectorAll('.bahan-row').length + 1);
                
                const tempDiv = document.createElement('div');
                tempDiv.innerHTML = html.trim();
                const newRow = tempDiv.firstChild;

                container.appendChild(newRow);
                nextIndex++;
                refreshRowLabels();
            }

            btnAdd.addEventListener('click', addRow);
            btnAddBottom.addEventListener('click', addRow);

            // Event delegation untuk hapus baris dan perubahan satuan
            container.addEventListener('click', function (e) {
                const btnRemove = e.target.closest('.btn-remove-row');
                if (btnRemove) {
                    const rows = container.querySelectorAll('.bahan-row');
                    if (rows.length <= 1) {
                        // Jika tinggal 1 baris, reset saja nilainya bukan dihapus
                        const row = rows[0];
                        row.querySelector('.select-bahan').value = '';
                        row.querySelector('.input-takaran').value = '';
                        row.querySelector('.label-satuan').textContent = 'satuan';
                        return;
                    }
                    btnRemove.closest('.bahan-row').remove();
                    refreshRowLabels();
                }
            });

            container.addEventListener('change', function (e) {
                if (e.target.classList.contains('select-bahan')) {
                    const opt = e.target.options[e.target.selectedIndex];
                    const row = e.target.closest('.bahan-row');
                    const labelSatuan = row.querySelector('.label-satuan');
                    if (opt && opt.getAttribute('data-satuan')) {
                        labelSatuan.textContent = opt.getAttribute('data-satuan');
                    } else {
                        labelSatuan.textContent = 'satuan';
                    }
                }
            });

            // Initial refresh
            refreshRowLabels();
        });
    </script>
</x-app-layout>

