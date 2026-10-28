<x-app-layout>
    <x-page title="Catat komposisi resep" sub="Pilih produk lalu daftarkan bahan baku beserta takarannya." narrow>
        <x-panel>
            <form action="{{ route('resep.store') }}" method="POST" id="form_resep" class="space-y-7">
                @csrf

                <div class="fld">
                    <label for="id_produk">Produk roti atau kue</label>
                    <select name="id_produk" id="id_produk" class="inp" required>
                        <option value="">Pilih produk yang akan diberi resep</option>
                        @foreach($produks as $produk)
                            <option value="{{ $produk->id_produk }}"
                                    data-tipe="{{ $produk->tipe_produksi }}"
                                    data-yield="{{ $produk->jumlah_per_batch }}"
                                    {{ (old('id_produk', request('id_produk')) == $produk->id_produk) ? 'selected' : '' }}>
                                {{ $produk->nama_produk }} ({{ $produk->tipe_produksi === 'Kustom' ? 'pesanan kustom' : '1 batch = ' . $produk->jumlah_per_batch . ' pcs' }})
                            </option>
                        @endforeach
                    </select>
                    @error('id_produk')<p class="hint !text-red-600">{{ $message }}</p>@enderror
                    <p id="produk_info_box" class="hidden hint"><span id="produk_info_desc"></span></p>
                </div>

                <div>
                    <div class="flex flex-wrap items-end justify-between gap-3 mb-3">
                        <div>
                            <p class="font-semibold text-gray-900 dark:text-gray-100">Bahan baku dan takaran</p>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Takaran dihitung per 1 batch adonan, atau per 1 unit untuk pesanan kustom.</p>
                        </div>
                        <x-btn id="btn_add_bahan" size="sm" variant="secondary">Tambah bahan</x-btn>
                    </div>

                    <div id="bahan_rows_container" class="space-y-3">
                        @php $oldBahans = old('bahans', [ ['id_bahan' => old('id_bahan', ''), 'takaran' => old('takaran', '')] ]); @endphp
                        @foreach($oldBahans as $index => $row)
                            <div class="bahan-row grid grid-cols-[2rem_1fr_auto] sm:grid-cols-[2rem_1fr_14rem_auto] items-center gap-3">
                                <span class="row-number text-sm font-semibold text-gray-400 tabular-nums">{{ $loop->iteration }}</span>
                                <select name="bahans[{{ $index }}][id_bahan]" required class="select-bahan inp">
                                    <option value="">Pilih bahan baku</option>
                                    @foreach($bahans as $bahan)
                                        <option value="{{ $bahan->id_bahan }}" data-satuan="{{ $bahan->satuan }}" {{ ($row['id_bahan'] == $bahan->id_bahan) ? 'selected' : '' }}>
                                            {{ $bahan->nama_bahan }} ({{ $bahan->satuan }}), stok {{ $bahan->jumlah_stok }}
                                        </option>
                                    @endforeach
                                </select>
                                <div class="flex items-center gap-2 col-span-3 sm:col-span-1 order-last sm:order-none">
                                    <input type="number" step="0.001" min="0.0001" name="bahans[{{ $index }}][takaran]" value="{{ $row['takaran'] }}" required placeholder="0.5" class="input-takaran inp">
                                    <span class="label-satuan text-sm text-gray-500 w-12 flex-shrink-0">satuan</span>
                                </div>
                                <button type="button" class="btn-remove-row text-sm font-semibold text-red-600 dark:text-red-400 hover:underline underline-offset-4" title="Hapus baris">Hapus</button>
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-4">
                        <x-btn id="btn_add_bahan_bottom" variant="link">+ Tambah bahan lainnya</x-btn>
                    </div>
                </div>

                <div class="flex items-center gap-4 pt-5 border-t border-gray-200 dark:border-gray-700">
                    <x-btn type="submit">Simpan komposisi</x-btn>
                    <x-btn variant="link" :href="route('resep.index')">Kembali ke buku resep</x-btn>
                </div>
            </form>
        </x-panel>
    </x-page>

    <template id="template_bahan_row">
        <div class="bahan-row grid grid-cols-[2rem_1fr_auto] sm:grid-cols-[2rem_1fr_14rem_auto] items-center gap-3">
            <span class="row-number text-sm font-semibold text-gray-400 tabular-nums">__INDEX_NUMBER__</span>
            <select name="bahans[__INDEX__][id_bahan]" required class="select-bahan inp">
                <option value="">Pilih bahan baku</option>
                @foreach($bahans as $bahan)
                    <option value="{{ $bahan->id_bahan }}" data-satuan="{{ $bahan->satuan }}">{{ $bahan->nama_bahan }} ({{ $bahan->satuan }}), stok {{ $bahan->jumlah_stok }}</option>
                @endforeach
            </select>
            <div class="flex items-center gap-2 col-span-3 sm:col-span-1 order-last sm:order-none">
                <input type="number" step="0.001" min="0.0001" name="bahans[__INDEX__][takaran]" required placeholder="0.5" class="input-takaran inp">
                <span class="label-satuan text-sm text-gray-500 w-12 flex-shrink-0">satuan</span>
            </div>
            <button type="button" class="btn-remove-row text-sm font-semibold text-red-600 dark:text-red-400 hover:underline underline-offset-4" title="Hapus baris">Hapus</button>
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
                if (!opt || !opt.value) { infoBox.classList.add('hidden'); return; }
                const tipe = opt.getAttribute('data-tipe');
                const yieldVal = opt.getAttribute('data-yield') || '1';
                infoBox.classList.remove('hidden');
                infoDesc.textContent = tipe === 'Kustom'
                    ? 'Pesanan kustom: takaran bahan dihitung untuk 1 unit pesanan kue.'
                    : `Sistem batch, 1 batch = ${yieldVal} pcs. Takaran dihitung untuk 1 batch adonan.`;
            }
            selectProduk.addEventListener('change', updateProductInfo);
            updateProductInfo();

            function refreshRowLabels() {
                container.querySelectorAll('.bahan-row').forEach((row, idx) => {
                    const numberEl = row.querySelector('.row-number');
                    if (numberEl) numberEl.textContent = (idx + 1);
                    const select = row.querySelector('.select-bahan');
                    const labelSatuan = row.querySelector('.label-satuan');
                    if (select && labelSatuan) {
                        const opt = select.options[select.selectedIndex];
                        labelSatuan.textContent = (opt && opt.getAttribute('data-satuan')) ? opt.getAttribute('data-satuan') : 'satuan';
                    }
                });
            }

            function addRow() {
                const html = template
                    .replace(/__INDEX__/g, nextIndex)
                    .replace(/__INDEX_NUMBER__/g, container.querySelectorAll('.bahan-row').length + 1);
                const tempDiv = document.createElement('div');
                tempDiv.innerHTML = html.trim();
                container.appendChild(tempDiv.firstChild);
                nextIndex++;
                refreshRowLabels();
            }
            btnAdd.addEventListener('click', addRow);
            btnAddBottom.addEventListener('click', addRow);

            container.addEventListener('click', function (e) {
                const btnRemove = e.target.closest('.btn-remove-row');
                if (!btnRemove) return;
                const rows = container.querySelectorAll('.bahan-row');
                if (rows.length <= 1) {
                    const row = rows[0];
                    row.querySelector('.select-bahan').value = '';
                    row.querySelector('.input-takaran').value = '';
                    row.querySelector('.label-satuan').textContent = 'satuan';
                    return;
                }
                btnRemove.closest('.bahan-row').remove();
                refreshRowLabels();
            });

            container.addEventListener('change', function (e) {
                if (e.target.classList.contains('select-bahan')) {
                    const opt = e.target.options[e.target.selectedIndex];
                    const labelSatuan = e.target.closest('.bahan-row').querySelector('.label-satuan');
                    labelSatuan.textContent = (opt && opt.getAttribute('data-satuan')) ? opt.getAttribute('data-satuan') : 'satuan';
                }
            });

            refreshRowLabels();
        });
    </script>
</x-app-layout>
