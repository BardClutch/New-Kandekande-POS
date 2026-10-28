<x-app-layout>
    <div class="flex flex-col lg:h-[calc(100vh-3.5rem)]">

        <!-- Bar atas kasir -->
        <div class="flex-none px-4 sm:px-6 lg:px-8 py-4 border-b border-gray-200 dark:border-gray-800 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
            <div class="flex items-baseline gap-4 flex-wrap min-w-0">
                <h1 class="font-display text-2xl font-extrabold tracking-tight text-gray-900 dark:text-gray-50">Kasir</h1>
                @if($activeShift)
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        <span class="inline-block w-2 h-2 rounded-full bg-emerald-500 mr-1.5 align-middle"></span>
                        Shift #{{ $activeShift->id_shift }} <span class="mx-1 text-gray-300 dark:text-gray-600">·</span> laci Rp {{ number_format($activeShift->kas_akhir_sistem, 0, ',', '.') }}
                    </p>
                @else
                    <button type="button" onclick="openModalBukaShiftPos()" class="text-sm font-semibold text-red-600 dark:text-red-400 hover:underline underline-offset-4">Shift belum dibuka</button>
                @endif
            </div>

            <div class="flex items-center gap-2 flex-wrap">
                <span id="pos-realtime-clock" class="hidden 2xl:inline text-sm text-gray-500 dark:text-gray-400 tabular-nums mr-2">Memuat waktu...</span>
                @if($activeShift)
                    <x-btn variant="danger" size="sm" onclick="openModalTutupShiftPos({{ $activeShift->id_shift }}, {{ (float)$activeShift->modal_awal }}, {{ (float)$activeShift->total_penjualan_tunai }}, {{ (float)$activeShift->kas_akhir_sistem }})">Tutup shift</x-btn>
                @else
                    <x-btn size="sm" onclick="openModalBukaShiftPos()">Buka shift</x-btn>
                @endif
                <x-btn variant="secondary" size="sm" :href="route('shift.index')">Rekap kas</x-btn>
                @if(Auth::user()->isOwner())
                    <x-btn variant="secondary" size="sm" :href="route('produk.index')">Katalog</x-btn>
                    <x-btn variant="secondary" size="sm" :href="route('laporan.index')">Laporan</x-btn>
                @else
                    <x-btn variant="secondary" size="sm" :href="route('laporan.index')">Riwayat</x-btn>
                @endif
            </div>
        </div>

        <div class="flex-1 min-h-0 flex flex-col lg:flex-row">

            <!-- Produk -->
            <div class="flex-1 min-w-0 lg:overflow-y-auto custom-scrollbar px-4 sm:px-6 lg:px-8 py-6">

                @if(!$activeShift)
                    <div class="mb-5 rounded-lg border border-amber-300 dark:border-amber-800 bg-amber-50 dark:bg-amber-950/30 px-4 py-3 flex flex-col xl:flex-row xl:items-center justify-between gap-3">
                        <div>
                            <p class="font-semibold text-amber-900 dark:text-amber-200">Shift kasir belum dibuka hari ini</p>
                            <p class="text-sm text-amber-800/80 dark:text-amber-300/80">Masukkan modal awal laci agar transaksi tunai tercatat di rekap shift.</p>
                        </div>
                        <x-btn size="sm" onclick="openModalBukaShiftPos()">Buka shift sekarang</x-btn>
                    </div>
                @endif

                @if(session('success'))
                    <div class="mb-5 rounded-lg border border-emerald-300 dark:border-emerald-800 bg-emerald-50 dark:bg-emerald-950/30 px-4 py-3 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                        <div>
                            <p class="font-semibold text-emerald-900 dark:text-emerald-200">{{ session('success') }}</p>
                            <p class="text-sm text-emerald-800/80 dark:text-emerald-300/80">Stok produk otomatis terpotong dari etalase.</p>
                        </div>
                        @if(session('cetak_id'))
                            <x-btn size="sm" :href="route('kasir.cetak', session('cetak_id'))" target="_blank">Cetak struk</x-btn>
                        @endif
                    </div>
                @endif

                @if(session('error'))
                    <div class="mb-5 rounded-lg border border-red-300 dark:border-red-800 bg-red-50 dark:bg-red-950/30 px-4 py-3 text-sm font-semibold text-red-800 dark:text-red-300">{{ session('error') }}</div>
                @endif

                <!-- Cari dan kategori -->
                <div class="flex flex-col gap-3 mb-6">
                    <input type="text" id="searchInput" placeholder="Cari produk atau menu" class="inp sm:max-w-xs" onkeyup="filterProducts()">

                    @php $uniqueCategories = $produks->pluck('kategori')->filter()->unique('id_kategori'); @endphp
                    @if($uniqueCategories->count() > 0)
                        <div class="flex items-center gap-1.5 flex-wrap">
                            <button type="button" onclick="filterByCategory('all')" id="cat-btn-all"
                                    class="category-filter-btn px-3.5 py-2 rounded-full text-sm font-semibold whitespace-nowrap transition-colors bg-gray-900 dark:bg-gray-100 text-gray-50 dark:text-gray-900 active-category">Semua</button>
                            @foreach($uniqueCategories as $cat)
                                <button type="button" onclick="filterByCategory('{{ $cat->id_kategori }}')" id="cat-btn-{{ $cat->id_kategori }}"
                                        class="category-filter-btn px-3.5 py-2 rounded-full text-sm font-semibold whitespace-nowrap transition-colors text-gray-600 dark:text-gray-300 hover:bg-gray-200/60 dark:hover:bg-gray-800">{{ $cat->nama_kategori }}</button>
                            @endforeach
                        </div>
                    @endif
                </div>

                <!-- Grid produk -->
                <div class="grid grid-cols-2 sm:grid-cols-3 xl:grid-cols-4 2xl:grid-cols-5 gap-3 pb-10" id="productGrid">
                    @forelse($produks as $item)
                        @php
                            $activePromo = $item->diskonPromos->first();
                            $discountPercent = $activePromo ? $activePromo->persentase : 0;
                            $discountAmount = $item->harga_dasar * ($discountPercent / 100);
                            $finalPrice = $item->harga_dasar - $discountAmount;
                        @endphp
                        <div class="product-card group rounded-lg border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-800 hover:border-gray-400 dark:hover:border-gray-500 has-[[id^=grid-controls-].flex]:border-indigo-600 dark:has-[[id^=grid-controls-].flex]:border-indigo-400 transition-colors flex flex-col"
                             data-name="{{ strtolower($item->nama_produk) }}"
                             data-category="{{ $item->kategori->id_kategori ?? 'uncategorized' }}">

                            <button type="button" class="text-left p-4 flex-1 focus:outline-none"
                                    onclick="addToCart({{ $item->id_produk }}, '{{ addslashes($item->nama_produk) }}', {{ $item->harga_dasar }}, {{ $item->stok_etalase }}, {{ $discountPercent }})">
                                <span class="flex items-start justify-between gap-2">
                                    <span class="font-semibold leading-snug text-gray-900 dark:text-gray-100">{{ $item->nama_produk }}</span>
                                    @if($activePromo)<span class="flex-shrink-0 text-xs font-bold text-red-600 dark:text-red-400">-{{ $discountPercent }}%</span>@endif
                                </span>
                                <span class="mt-3 block font-display text-lg font-extrabold tabular-nums text-gray-900 dark:text-gray-50">
                                    Rp{{ number_format($finalPrice, 0, ',', '.') }}
                                    @if($activePromo)<span class="ml-1 text-xs font-normal text-gray-400 line-through">Rp{{ number_format($item->harga_dasar, 0, ',', '.') }}</span>@endif
                                </span>
                                <span class="block text-xs {{ $item->stok_etalase <= ($item->stok_minimum ?? 0) ? 'text-red-600 dark:text-red-400' : 'text-gray-500 dark:text-gray-400' }}">Stok {{ $item->stok_etalase }}</span>
                            </button>

                            <!-- Kontrol jumlah (tampil saat produk ada di keranjang) -->
                            <div id="grid-controls-{{ $item->id_produk }}" class="hidden items-center justify-between gap-2 px-4 pb-3">
                                <button type="button" class="text-xs font-semibold text-red-600 dark:text-red-400 hover:underline underline-offset-4" onclick="event.stopPropagation(); window.removeItem({{ $item->id_produk }})">Hapus</button>
                                <div class="flex items-center gap-1">
                                    <button type="button" class="w-7 h-7 rounded-md border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-200 hover:border-gray-900 dark:hover:border-gray-300 flex items-center justify-center font-bold" onclick="event.stopPropagation(); window.updateQty({{ $item->id_produk }}, -1)" aria-label="Kurangi">−</button>
                                    <span class="text-sm font-bold w-6 text-center tabular-nums select-none" id="grid-qty-{{ $item->id_produk }}">0</span>
                                    <button type="button" class="w-7 h-7 rounded-md bg-gray-900 dark:bg-gray-100 text-gray-50 dark:text-gray-900 hover:bg-indigo-600 dark:hover:bg-indigo-400 flex items-center justify-center font-bold" onclick="event.stopPropagation(); addToCart({{ $item->id_produk }}, '{{ addslashes($item->nama_produk) }}', {{ $item->harga_dasar }}, {{ $item->stok_etalase }}, {{ $discountPercent }})" aria-label="Tambah">+</button>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-full text-center py-16 rounded-lg border border-dashed border-gray-300 dark:border-gray-700">
                            <p class="font-semibold text-gray-700 dark:text-gray-300">Tidak ada produk dengan stok tersedia.</p>
                            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Restock atau tambahkan produk baru di katalog.</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Keranjang -->
            <form action="{{ route('kasir.store') }}" method="POST" id="formKasir"
                  class="w-full lg:w-[340px] xl:w-[400px] flex-none flex flex-col bg-white dark:bg-gray-800 border-t lg:border-t-0 lg:border-l border-gray-200 dark:border-gray-800 lg:min-h-0">
                @csrf

                <div class="flex-none flex items-baseline justify-between px-5 pt-5 pb-3">
                    <h2 class="font-display text-xl font-extrabold tracking-tight text-gray-900 dark:text-gray-50">Pesanan</h2>
                    <button type="button" class="text-sm font-semibold text-gray-500 hover:text-red-600 dark:hover:text-red-400 transition-colors" onclick="window.clearCart()">Kosongkan</button>
                </div>

                <!-- Isi keranjang -->
                <div class="flex-1 min-h-[140px] lg:min-h-0 overflow-y-auto custom-scrollbar px-5 divide-y divide-gray-200 dark:divide-gray-700 border-y border-gray-200 dark:border-gray-700" id="cart-items">
                    <div class="py-10 text-center">
                        <p class="text-sm text-gray-400">Keranjang masih kosong</p>
                    </div>
                </div>

                <div class="flex-none px-5 py-4 space-y-4">
                    <!-- Pelanggan -->
                    <div x-data="{
                        open: false,
                        selectedName: '',
                        selectedPhone: '',
                        selectedTier: '',
                        pelangganList: @js($pelanggans ?? []),
                        onCustomerSelect(val) {
                            const found = this.pelangganList.find(p => p.nama_pelanggan.toLowerCase() === val.toLowerCase());
                            if (found) {
                                this.selectedPhone = found.no_hp || '';
                                this.selectedTier = found.tipe_pelanggan || '';
                            } else {
                                this.selectedTier = '';
                            }
                        }
                    }">
                        <button type="button" @click="open = !open" class="w-full flex items-center justify-between text-sm font-semibold text-gray-700 dark:text-gray-200">
                            <span>Pelanggan <span class="font-normal text-gray-400">(opsional)</span><span x-show="selectedTier" x-text="' · ' + selectedTier" class="text-indigo-600 dark:text-indigo-400"></span></span>
                            <svg class="w-4 h-4 text-gray-400 transition-transform" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                        </button>
                        <div x-show="open" x-cloak class="mt-3 space-y-2">
                            <input type="text" name="nama_pelanggan" list="pelanggan_datalist" x-model="selectedName" @input="onCustomerSelect($event.target.value)" placeholder="Nama pelanggan atau cari riwayat" class="inp !py-2 !text-sm">
                            <datalist id="pelanggan_datalist">
                                @foreach($pelanggans as $cust)
                                    <option value="{{ $cust->nama_pelanggan }}">{{ $cust->nama_pelanggan }} ({{ $cust->tipe_pelanggan }} - {{ $cust->no_hp ?? 'No WA -' }})</option>
                                @endforeach
                            </datalist>
                            <div class="grid grid-cols-2 gap-2">
                                <input type="text" name="no_hp_pelanggan" x-model="selectedPhone" placeholder="WhatsApp (08...)" class="inp !py-2 !text-sm">
                                <input type="text" name="catatan_pesanan" placeholder="Catatan pesanan" class="inp !py-2 !text-sm">
                            </div>
                        </div>
                    </div>

                    <!-- Pembayaran -->
                    <div class="grid grid-cols-2 gap-3">
                        <div class="fld">
                            <label for="jenis_pesanan" class="!text-xs !mb-1 !text-gray-500">Jenis pesanan</label>
                            <select name="jenis_pesanan" id="jenis_pesanan" class="inp !py-2 !text-sm">
                                <option value="Offline">Offline</option>
                                <option value="Whatsapp">WhatsApp</option>
                            </select>
                        </div>
                        <div class="fld">
                            <label for="metode_pembayaran" class="!text-xs !mb-1 !text-gray-500">Metode bayar</label>
                            <select name="metode_pembayaran" id="metode_pembayaran" onchange="handlePaymentMethodChange()" class="inp !py-2 !text-sm">
                                <option value="Tunai">Tunai</option>
                                <option value="Transfer">Transfer / QRIS</option>
                            </select>
                        </div>
                    </div>

                    <div id="cash-calculator-box" class="space-y-2">
                        <div class="flex items-center justify-between gap-3">
                            <label for="uangDiterimaInput" class="text-sm text-gray-600 dark:text-gray-300">Uang diterima</label>
                            <div class="relative w-40">
                                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-sm text-gray-400">Rp</span>
                                <input type="number" id="uangDiterimaInput" placeholder="0" class="inp !py-2 !text-sm !pl-9 text-right tabular-nums" oninput="calculateChange()">
                            </div>
                        </div>
                        <div class="flex items-center justify-end gap-1.5 flex-wrap">
                            <button type="button" onclick="setQuickCash('pas')" class="text-xs font-semibold px-2.5 py-1 rounded-full border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-200 hover:border-gray-900 dark:hover:border-gray-300">Uang pas</button>
                            <button type="button" onclick="setQuickCash(20000)" class="text-xs font-semibold px-2.5 py-1 rounded-full border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-200 hover:border-gray-900 dark:hover:border-gray-300">20rb</button>
                            <button type="button" onclick="setQuickCash(50000)" class="text-xs font-semibold px-2.5 py-1 rounded-full border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-200 hover:border-gray-900 dark:hover:border-gray-300">50rb</button>
                            <button type="button" onclick="setQuickCash(100000)" class="text-xs font-semibold px-2.5 py-1 rounded-full border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-200 hover:border-gray-900 dark:hover:border-gray-300">100rb</button>
                        </div>
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-gray-600 dark:text-gray-300">Kembalian</span>
                            <span class="font-bold tabular-nums text-gray-800 dark:text-gray-200" id="display-kembalian">Rp 0</span>
                        </div>
                    </div>

                    <!-- Total -->
                    <div class="pt-4 border-t border-gray-200 dark:border-gray-700 space-y-1.5">
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-gray-600 dark:text-gray-300">Potongan diskon</span>
                            <span class="tabular-nums text-emerald-600 dark:text-emerald-400" id="total-diskon">Rp 0</span>
                        </div>
                        <div class="flex items-baseline justify-between">
                            <span class="font-semibold text-gray-900 dark:text-gray-100">Total bayar</span>
                            <span class="font-display text-3xl font-extrabold tabular-nums text-gray-900 dark:text-gray-50" id="total-display">Rp 0</span>
                        </div>
                    </div>

                    <button type="submit" id="btn-checkout" disabled
                            class="w-full rounded-full bg-indigo-600 hover:bg-indigo-700 text-white font-display text-lg font-extrabold py-3.5 transition-colors opacity-50 cursor-not-allowed">
                        Proses pembayaran
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Jam waktu nyata
        function updatePosClock() {
            const now = new Date();
            const days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
            const months = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
            const pad = n => String(n).padStart(2, '0');
            const clockEl = document.getElementById('pos-realtime-clock');
            if (clockEl) {
                clockEl.innerText = `${days[now.getDay()]}, ${now.getDate()} ${months[now.getMonth()]} ${now.getFullYear()} · ${pad(now.getHours())}:${pad(now.getMinutes())}:${pad(now.getSeconds())} WIB`;
            }
        }
        setInterval(updatePosClock, 1000);
        updatePosClock();

        // Cari dan filter kategori
        let currentCategory = 'all';
        const CAT_ON = ['bg-gray-900', 'dark:bg-gray-100', 'text-gray-50', 'dark:text-gray-900', 'active-category'];
        const CAT_OFF = ['text-gray-600', 'dark:text-gray-300', 'hover:bg-gray-200/60', 'dark:hover:bg-gray-800'];

        function filterProducts() {
            const input = document.getElementById('searchInput').value.toLowerCase();
            const cards = document.getElementsByClassName('product-card');
            for (let i = 0; i < cards.length; i++) {
                const name = cards[i].getAttribute('data-name');
                const cat = cards[i].getAttribute('data-category');
                const show = name.includes(input) && (currentCategory === 'all' || cat === currentCategory);
                cards[i].style.display = show ? '' : 'none';
            }
        }

        function filterByCategory(categoryId) {
            currentCategory = categoryId;
            document.querySelectorAll('.category-filter-btn').forEach(btn => {
                btn.classList.remove(...CAT_ON);
                btn.classList.add(...CAT_OFF);
            });
            const activeBtn = document.getElementById('cat-btn-' + categoryId);
            if (activeBtn) {
                activeBtn.classList.remove(...CAT_OFF);
                activeBtn.classList.add(...CAT_ON);
            }
            filterProducts();
        }

        // Keranjang
        let cart = {};
        let currentGrandTotal = 0;

        function addToCart(id, name, price, maxStock, discountPercent) {
            id = parseInt(id);
            price = parseFloat(price);
            maxStock = parseInt(maxStock);
            discountPercent = parseFloat(discountPercent) || 0;

            if (cart[id]) {
                if (cart[id].qty < maxStock) {
                    cart[id].qty += 1;
                } else {
                    alert(`Stok maksimal etalase untuk ${name} adalah ${maxStock}`);
                }
            } else {
                cart[id] = { id: id, name: name, price: price, qty: 1, maxStock: maxStock, discountPercent: discountPercent };
            }
            renderCart();
        }

        window.updateQty = function (id, change) {
            id = parseInt(id);
            change = parseInt(change);
            if (cart[id]) {
                const newQty = cart[id].qty + change;
                if (newQty > 0 && newQty <= cart[id].maxStock) {
                    cart[id].qty = newQty;
                } else if (newQty === 0) {
                    delete cart[id];
                } else if (newQty > cart[id].maxStock) {
                    alert(`Stok maksimal adalah ${cart[id].maxStock}`);
                }
                renderCart();
            }
        };

        window.removeItem = function (id) {
            id = parseInt(id);
            if (cart[id]) { delete cart[id]; renderCart(); }
        };

        window.clearCart = function () {
            if (Object.keys(cart).length > 0) { cart = {}; renderCart(); }
        };

        function renderCart() {
            const container = document.getElementById('cart-items');
            const totalDisplay = document.getElementById('total-display');
            const totalDiskonDisplay = document.getElementById('total-diskon');
            const btnCheckout = document.getElementById('btn-checkout');

            container.innerHTML = '';
            let total = 0;
            let totalDiscountVal = 0;
            let itemCount = 0;

            for (const key in cart) {
                if (cart.hasOwnProperty(key)) {
                    const item = cart[key];
                    const discountAmount = item.price * (item.discountPercent / 100);
                    const finalPrice = item.price - discountAmount;
                    const subtotal = finalPrice * item.qty;

                    total += subtotal;
                    totalDiscountVal += discountAmount * item.qty;
                    itemCount++;

                    const row = document.createElement('div');
                    row.className = 'py-3 flex items-start justify-between gap-3';
                    row.innerHTML = `
                        <div class="min-w-0 flex-1">
                            <div class="font-semibold text-gray-900 dark:text-gray-100 truncate">${item.name}</div>
                            <div class="text-sm text-gray-500 dark:text-gray-400 tabular-nums">Rp${Math.round(finalPrice).toLocaleString('id-ID')} × ${item.qty}</div>
                            <input type="hidden" name="id_produk[]" value="${item.id}">
                            <input type="hidden" name="qty_beli[]" value="${item.qty}">
                            <div class="flex items-center gap-1 mt-2">
                                <button type="button" class="w-7 h-7 rounded-md border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-200 hover:border-gray-900 dark:hover:border-gray-300 flex items-center justify-center font-bold" onclick="window.updateQty(${item.id}, -1)" aria-label="Kurangi">−</button>
                                <span class="text-sm font-bold w-6 text-center tabular-nums select-none">${item.qty}</span>
                                <button type="button" class="w-7 h-7 rounded-md border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-200 hover:border-gray-900 dark:hover:border-gray-300 flex items-center justify-center font-bold" onclick="window.updateQty(${item.id}, 1)" aria-label="Tambah">+</button>
                            </div>
                        </div>
                        <div class="text-right flex-shrink-0">
                            <div class="font-semibold tabular-nums text-gray-900 dark:text-gray-100">Rp${Math.round(subtotal).toLocaleString('id-ID')}</div>
                            <button type="button" class="mt-2 text-xs font-semibold text-red-600 dark:text-red-400 hover:underline underline-offset-4" onclick="window.removeItem(${item.id})">Hapus</button>
                        </div>
                    `;
                    container.appendChild(row);
                }
            }

            // Sinkron kontrol jumlah di grid
            document.querySelectorAll('[id^="grid-controls-"]').forEach(el => {
                el.classList.add('hidden');
                el.classList.remove('flex');
            });
            for (const key in cart) {
                if (cart.hasOwnProperty(key)) {
                    const gridQtyEl = document.getElementById(`grid-qty-${key}`);
                    if (gridQtyEl) gridQtyEl.innerText = cart[key].qty;
                    const gridControlsEl = document.getElementById(`grid-controls-${key}`);
                    if (gridControlsEl) {
                        gridControlsEl.classList.remove('hidden');
                        gridControlsEl.classList.add('flex');
                    }
                }
            }

            currentGrandTotal = total;
            totalDisplay.innerText = 'Rp ' + Math.round(total).toLocaleString('id-ID');
            totalDiskonDisplay.innerText = 'Rp ' + Math.round(totalDiscountVal).toLocaleString('id-ID');
            calculateChange();

            if (itemCount === 0) {
                container.innerHTML = `<div class="py-10 text-center"><p class="text-sm text-gray-400">Keranjang masih kosong</p></div>`;
                btnCheckout.disabled = true;
                btnCheckout.classList.add('opacity-50', 'cursor-not-allowed');
            } else {
                btnCheckout.disabled = false;
                btnCheckout.classList.remove('opacity-50', 'cursor-not-allowed');
            }
        }

        // Kalkulator uang tunai
        function handlePaymentMethodChange() {
            const method = document.getElementById('metode_pembayaran').value;
            document.getElementById('cash-calculator-box').style.display = method === 'Tunai' ? 'block' : 'none';
        }

        function calculateChange() {
            const inputVal = parseFloat(document.getElementById('uangDiterimaInput')?.value || 0);
            const changeDisplay = document.getElementById('display-kembalian');
            if (!changeDisplay) return;

            if (inputVal > 0 && currentGrandTotal > 0) {
                const change = inputVal - currentGrandTotal;
                if (change >= 0) {
                    changeDisplay.className = 'font-bold tabular-nums text-emerald-600 dark:text-emerald-400';
                    changeDisplay.innerText = 'Rp ' + Math.round(change).toLocaleString('id-ID');
                } else {
                    changeDisplay.className = 'font-bold tabular-nums text-red-600 dark:text-red-400';
                    changeDisplay.innerText = 'Kurang Rp ' + Math.round(Math.abs(change)).toLocaleString('id-ID');
                }
            } else {
                changeDisplay.className = 'font-bold tabular-nums text-gray-800 dark:text-gray-200';
                changeDisplay.innerText = 'Rp 0';
            }
        }

        function setQuickCash(amount) {
            const input = document.getElementById('uangDiterimaInput');
            if (!input) return;
            input.value = amount === 'pas' ? currentGrandTotal : amount;
            calculateChange();
        }

        // Modal shift kasir
        let currentTargetKasSistemPos = 0;

        function openModalBukaShiftPos() { document.getElementById('modalBukaShiftPos').classList.remove('hidden'); }
        function closeModalBukaShiftPos() { document.getElementById('modalBukaShiftPos').classList.add('hidden'); }
        function setModalAwalPos(val) { document.getElementById('modal_awal_pos_input').value = val; }

        function openModalTutupShiftPos(shiftId, modalAwal, penjualanTunai, kasSistem) {
            currentTargetKasSistemPos = kasSistem;
            document.getElementById('titleTutupShiftPos').innerText = `Tutup shift kasir #${shiftId}`;
            document.getElementById('tutupModalAwalPos').innerText = `Rp ${Number(modalAwal).toLocaleString('id-ID')}`;
            document.getElementById('tutupPenjualanTunaiPos').innerText = `Rp ${Number(penjualanTunai).toLocaleString('id-ID')}`;
            document.getElementById('tutupKasSistemPos').innerText = `Rp ${Number(kasSistem).toLocaleString('id-ID')}`;
            document.getElementById('formTutupShiftPos').action = `/shift/${shiftId}/tutup`;
            document.getElementById('kas_akhir_fisik_pos_input').value = '';
            hitungSelisihLivePos();
            document.getElementById('modalTutupShiftPos').classList.remove('hidden');
        }

        function closeModalTutupShiftPos() { document.getElementById('modalTutupShiftPos').classList.add('hidden'); }

        function hitungSelisihLivePos() {
            const inputVal = document.getElementById('kas_akhir_fisik_pos_input').value;
            const box = document.getElementById('boxSelisihPos');
            const text = document.getElementById('textSelisihPos');
            const base = 'px-3.5 py-3 rounded-lg border text-sm flex items-center justify-between font-semibold ';

            if (inputVal === '' || isNaN(inputVal)) {
                box.className = base + 'border-gray-300 dark:border-gray-600 text-gray-500 dark:text-gray-400';
                text.innerText = 'Masukkan nominal kas fisik';
                return;
            }

            const selisih = parseFloat(inputVal) - currentTargetKasSistemPos;
            if (selisih === 0) {
                box.className = base + 'border-emerald-300 dark:border-emerald-800 text-emerald-700 dark:text-emerald-300';
                text.innerText = 'Sesuai (Rp 0)';
            } else if (selisih > 0) {
                box.className = base + 'border-amber-300 dark:border-amber-800 text-amber-700 dark:text-amber-300';
                text.innerText = `Surplus +Rp ${Math.abs(selisih).toLocaleString('id-ID')}`;
            } else {
                box.className = base + 'border-red-300 dark:border-red-800 text-red-700 dark:text-red-300';
                text.innerText = `Minus -Rp ${Math.abs(selisih).toLocaleString('id-ID')}`;
            }
        }
    </script>

    <!-- Modal buka shift -->
    <div id="modalBukaShiftPos" class="fixed inset-0 z-50 hidden" role="dialog" aria-modal="true" aria-labelledby="titleBukaShiftPos">
        <div class="absolute inset-0 bg-black/50" onclick="closeModalBukaShiftPos()"></div>
        <div class="relative flex min-h-full items-center justify-center p-4 pointer-events-none">
            <div class="pointer-events-auto w-full max-w-md rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 shadow-2xl">
                <form method="POST" action="{{ route('shift.buka') }}" class="p-6 sm:p-7 space-y-5">
                    @csrf
                    <div>
                        <h2 id="titleBukaShiftPos" class="font-display text-2xl font-extrabold tracking-tight text-gray-900 dark:text-gray-50">Buka shift kasir</h2>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Siapkan uang tunai kembalian awal di laci.</p>
                    </div>

                    <div class="fld">
                        <label for="modal_awal_pos_input">Modal awal (uang kembalian)</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-3 flex items-center text-sm text-gray-400">Rp</span>
                            <input type="number" name="modal_awal" id="modal_awal_pos_input" min="0" step="500" value="100000" required class="inp !pl-10 text-lg font-bold tabular-nums">
                        </div>
                        <div class="flex items-center gap-1.5 mt-2 flex-wrap">
                            <span class="text-xs text-gray-400">Pilihan cepat:</span>
                            @foreach([50000 => '50k', 100000 => '100k', 200000 => '200k', 500000 => '500k'] as $nominal => $lbl)
                                <button type="button" onclick="setModalAwalPos({{ $nominal }})" class="text-xs font-semibold px-2.5 py-1 rounded-full border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-200 hover:border-gray-900 dark:hover:border-gray-300">{{ $lbl }}</button>
                            @endforeach
                        </div>
                    </div>

                    <div class="fld">
                        <label for="catatan_buka_pos">Catatan (opsional)</label>
                        <textarea name="catatan" id="catatan_buka_pos" rows="2" placeholder="Misal: pecahan 2.000 (10 lembar), 5.000 (10 lembar)" class="inp"></textarea>
                    </div>

                    <div class="flex items-center justify-end gap-3 pt-1">
                        <x-btn variant="secondary" onclick="closeModalBukaShiftPos()">Batal</x-btn>
                        <x-btn type="submit">Buka shift</x-btn>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal tutup shift -->
    <div id="modalTutupShiftPos" class="fixed inset-0 z-50 hidden" role="dialog" aria-modal="true" aria-labelledby="titleTutupShiftPos">
        <div class="absolute inset-0 bg-black/50" onclick="closeModalTutupShiftPos()"></div>
        <div class="relative flex min-h-full items-center justify-center p-4 pointer-events-none">
            <div class="pointer-events-auto w-full max-w-lg rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 shadow-2xl">
                <form id="formTutupShiftPos" method="POST" action="" class="p-6 sm:p-7 space-y-5">
                    @csrf
                    <div>
                        <h2 id="titleTutupShiftPos" class="font-display text-2xl font-extrabold tracking-tight text-gray-900 dark:text-gray-50">Tutup shift kasir</h2>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Rekap uang kas laci dan hitung selisih fisik.</p>
                    </div>

                    <dl class="rounded-lg border border-gray-200 dark:border-gray-700 divide-y divide-gray-200 dark:divide-gray-700 text-sm">
                        <div class="flex justify-between px-4 py-2.5"><dt class="text-gray-500 dark:text-gray-400">Modal awal</dt><dd class="font-semibold tabular-nums" id="tutupModalAwalPos">Rp 0</dd></div>
                        <div class="flex justify-between px-4 py-2.5"><dt class="text-gray-500 dark:text-gray-400">Penjualan tunai</dt><dd class="font-semibold tabular-nums text-emerald-600 dark:text-emerald-400" id="tutupPenjualanTunaiPos">Rp 0</dd></div>
                        <div class="flex justify-between px-4 py-3"><dt class="font-semibold">Uang seharusnya di laci</dt><dd class="font-display text-lg font-extrabold tabular-nums" id="tutupKasSistemPos">Rp 0</dd></div>
                    </dl>

                    <div class="fld">
                        <label for="kas_akhir_fisik_pos_input">Uang fisik di laci</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-3 flex items-center text-sm text-gray-400">Rp</span>
                            <input type="number" name="kas_akhir_fisik" id="kas_akhir_fisik_pos_input" min="0" step="500" oninput="hitungSelisihLivePos()" required placeholder="0" class="inp !pl-10 text-lg font-bold tabular-nums">
                        </div>
                        <p class="hint">Hitung semua uang tunai fisik di laci saat ini.</p>
                    </div>

                    <div id="boxSelisihPos" class="px-3.5 py-3 rounded-lg border border-gray-300 dark:border-gray-600 text-sm flex items-center justify-between font-semibold text-gray-500">
                        <span>Status selisih kas</span>
                        <span id="textSelisihPos">Masukkan uang fisik di atas</span>
                    </div>

                    <div class="fld">
                        <label for="catatan_tutup_pos">Catatan penutupan (opsional)</label>
                        <textarea name="catatan" id="catatan_tutup_pos" rows="2" placeholder="Keterangan jika ada selisih kas" class="inp"></textarea>
                    </div>

                    <div class="flex items-center justify-end gap-3 pt-1">
                        <x-btn variant="secondary" onclick="closeModalTutupShiftPos()">Batal</x-btn>
                        <x-btn type="submit" variant="accent">Tutup shift dan rekap kas</x-btn>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
