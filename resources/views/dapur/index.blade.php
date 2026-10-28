<x-app-layout>
    <x-page title="Dapur" sub="Antrean pesanan dari kasir dan pencatatan produksi batch.">
        <div class="grid grid-cols-1 lg:grid-cols-[340px_1fr] gap-6 items-start">

            <!-- Catat produksi -->
            <x-panel title="Catat produksi baru" sub="Sistem per batch dan pesanan kustom" class="lg:sticky lg:top-6">
                <form action="{{ route('dapur.store') }}" method="POST" id="form_produksi" class="mt-4 space-y-5">
                    @csrf

                    <div class="fld">
                        <label for="select_produk">Produk</label>
                        <select name="id_produk" id="select_produk" required class="inp">
                            <option value="">Pilih produk roti atau kue</option>
                            @foreach($produks as $p)
                                <option value="{{ $p->id_produk }}"
                                        data-tipe="{{ $p->tipe_produksi ?? 'Batch' }}"
                                        data-yield="{{ $p->jumlah_per_batch ?? 1 }}"
                                        data-reseps='@json($p->reseps)'>
                                    {{ $p->nama_produk }} {{ ($p->tipe_produksi ?? 'Batch') === 'Kustom' ? '[kustom]' : '[' . ($p->jumlah_per_batch ?? 1) . ' pcs/batch]' }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Mode batch -->
                    <div id="panel_batch" class="space-y-4">
                        <div class="fld">
                            <label for="input_jumlah_batch">Jumlah batch yang dimasak</label>
                            <div class="relative">
                                <input type="number" name="jumlah_batch" id="input_jumlah_batch" min="1" value="1" class="inp !pr-16" placeholder="1">
                                <span class="absolute inset-y-0 right-3 flex items-center text-sm text-gray-400 pointer-events-none">batch</span>
                            </div>
                        </div>

                        <dl class="rounded-lg border border-gray-200 dark:border-gray-700 divide-y divide-gray-200 dark:divide-gray-700 text-sm">
                            <div class="flex items-center justify-between px-4 py-2.5">
                                <dt class="text-gray-500 dark:text-gray-400">Hasil 1 batch</dt>
                                <dd class="font-semibold tabular-nums" id="badge_yield_per_batch">1 pcs</dd>
                            </div>
                            <div class="flex items-center justify-between px-4 py-2.5">
                                <dt class="font-semibold">Target total matang</dt>
                                <dd class="font-display text-lg font-extrabold tabular-nums text-indigo-600 dark:text-indigo-400" id="badge_target_matang">1 pcs</dd>
                            </div>
                        </dl>
                    </div>

                    <!-- Mode kustom -->
                    <div id="panel_kustom" class="space-y-4 hidden">
                        <p class="text-sm text-gray-600 dark:text-gray-300 rounded-lg border border-gray-200 dark:border-gray-700 px-4 py-3">
                            <span class="font-semibold text-gray-900 dark:text-gray-100">Pesanan kustom.</span> Dihitung per unit pesanan pelanggan, bukan batch massal.
                        </p>
                        <div class="fld">
                            <label for="input_jumlah_kustom">Target jumlah pesanan (unit)</label>
                            <input type="number" name="jumlah_matang" id="input_jumlah_kustom" min="1" value="1" class="inp" placeholder="1">
                        </div>
                        <div class="fld">
                            <label for="input_catatan_kustom">Catatan atau spesifikasi <span class="font-normal text-gray-400">(opsional)</span></label>
                            <input type="text" name="catatan_produksi" id="input_catatan_kustom" maxlength="255" class="inp" placeholder="Kue ultah 20 cm, tulisan 'Happy Birthday Sarah'">
                        </div>
                    </div>

                    <!-- Kebutuhan bahan -->
                    <div id="panel_kebutuhan_bahan" class="hidden">
                        <div class="flex items-baseline justify-between mb-1.5">
                            <p class="text-sm font-semibold text-gray-700 dark:text-gray-200">Estimasi bahan terpakai</p>
                            <span id="label_kebutuhan_mode" class="text-xs text-gray-500"></span>
                        </div>
                        <div id="list_kebutuhan_bahan" class="max-h-44 overflow-y-auto custom-scrollbar rounded-lg border border-gray-200 dark:border-gray-700 px-3 text-sm divide-y divide-gray-200 dark:divide-gray-700"></div>
                    </div>

                    <p class="flex items-center justify-between text-sm text-gray-500 dark:text-gray-400">
                        <span>Tanggal produksi</span>
                        <span class="font-semibold text-gray-900 dark:text-gray-100 tabular-nums">{{ date('d/m/Y') }} (hari ini)</span>
                    </p>

                    <x-btn type="submit" id="btn_submit_produksi" class="w-full !py-3">Mulai produksi</x-btn>
                </form>
            </x-panel>

            <!-- Antrean dan produksi -->
            <div class="space-y-5 min-w-0 order-first lg:order-none" x-data="{ activeTabDapur: 'pesanan_masuk' }">

                <div class="flex flex-wrap items-center justify-between gap-3 border-b border-gray-200 dark:border-gray-800">
                    <div class="flex items-center gap-0 sm:gap-1 w-full sm:w-auto" role="tablist">
                        <button type="button" role="tab" @click="activeTabDapur = 'pesanan_masuk'"
                                :class="activeTabDapur === 'pesanan_masuk' ? 'border-indigo-600 text-gray-900 dark:text-gray-50' : 'border-transparent text-gray-500 hover:text-gray-900 dark:hover:text-gray-100'"
                                class="flex-1 sm:flex-none whitespace-nowrap px-2 sm:px-4 py-3 -mb-px text-sm font-semibold border-b-2 transition-colors">
                            Pesanan kasir masuk <span id="badge_count_pesanan" class="ml-1 text-gray-400 tabular-nums">{{ count($pesananMasuk) }}</span>
                        </button>
                        <button type="button" role="tab" @click="activeTabDapur = 'produksi_batch'"
                                :class="activeTabDapur === 'produksi_batch' ? 'border-indigo-600 text-gray-900 dark:text-gray-50' : 'border-transparent text-gray-500 hover:text-gray-900 dark:hover:text-gray-100'"
                                class="flex-1 sm:flex-none whitespace-nowrap px-2 sm:px-4 py-3 -mb-px text-sm font-semibold border-b-2 transition-colors">
                            Antrean produksi <span class="ml-1 text-gray-400 tabular-nums">{{ $produksis->count() }}</span>
                        </button>
                    </div>

                    <div class="flex items-center gap-3 pb-2 text-sm">
                        <span class="inline-flex items-center gap-1.5 text-gray-500 dark:text-gray-400"><span class="w-2 h-2 rounded-full bg-emerald-500"></span>Sinkron tiap 4 detik</span>
                        <button type="button" id="btn_toggle_audio" onclick="toggleKitchenAudio()" class="font-semibold text-gray-700 dark:text-gray-200 hover:text-indigo-600 dark:hover:text-indigo-400">
                            <span id="audio_text">Bel dapur: aktif</span>
                        </button>
                        <button type="button" onclick="testKitchenSound()" class="font-semibold text-gray-500 hover:text-indigo-600 dark:hover:text-indigo-400" title="Uji suara bel">Uji bel</button>
                    </div>
                </div>

                <!-- Tab 1: pesanan masuk -->
                <div x-show="activeTabDapur === 'pesanan_masuk'">
                    <div id="container_pesanan_masuk" class="grid grid-cols-1 2xl:grid-cols-2 gap-4">
                        @forelse($pesananMasuk as $trx)
                            <div class="order-card rounded-xl border {{ $trx->status_pesanan == 'Menunggu Dapur' ? 'border-amber-400 dark:border-amber-600' : 'border-gray-200 dark:border-gray-700' }} bg-white dark:bg-gray-800 p-5 transition-all"
                                 id="order-card-{{ $trx->id_transaksi }}" data-id="{{ $trx->id_transaksi }}">

                                <div class="flex items-start justify-between gap-3 pb-3 mb-3 border-b border-gray-200 dark:border-gray-700">
                                    <div>
                                        <p class="flex items-baseline gap-2">
                                            <span class="font-display text-xl font-extrabold text-gray-900 dark:text-gray-50">#{{ $trx->id_transaksi }}</span>
                                            <span class="text-sm text-gray-500 dark:text-gray-400">{{ $trx->jenis_pesanan }}</span>
                                        </p>
                                        <p class="text-sm text-gray-500 dark:text-gray-400 tabular-nums">{{ $trx->tanggal_waktu->format('H:i') }} · {{ $trx->tanggal_waktu->diffForHumans() }}</p>
                                    </div>
                                    <span class="order-status-badge text-sm font-semibold {{ $trx->status_pesanan == 'Menunggu Dapur' ? 'text-amber-600 dark:text-amber-400' : 'text-gray-600 dark:text-gray-300' }}">{{ $trx->status_pesanan }}</span>
                                </div>

                                <p class="font-semibold text-gray-900 dark:text-gray-100">
                                    {{ $trx->nama_pelanggan ?: 'Pelanggan umum' }}
                                    @if($trx->pelanggan && $trx->pelanggan->tipe_pelanggan == 'VIP')<span class="ml-1 text-xs font-bold text-indigo-600 dark:text-indigo-400">VIP</span>@endif
                                </p>
                                @if($trx->catatan_pesanan)
                                    <p class="mt-1 text-sm text-amber-700 dark:text-amber-400">Catatan: "{{ $trx->catatan_pesanan }}"</p>
                                @endif

                                <ul class="my-4 divide-y divide-gray-200 dark:divide-gray-700 text-sm">
                                    @foreach($trx->detailTransaksis as $d)
                                        <li class="flex items-center justify-between py-2">
                                            <span><span class="font-semibold tabular-nums text-gray-900 dark:text-gray-100">{{ $d->qty_beli }}×</span> <span class="text-gray-800 dark:text-gray-200">{{ $d->produk->nama_produk ?? 'Produk' }}</span></span>
                                            <span class="text-xs text-gray-400">{{ $d->produk->tipe_produksi ?? 'Batch' }}</span>
                                        </li>
                                    @endforeach
                                </ul>

                                <div class="flex items-center gap-2 pt-4 border-t border-gray-200 dark:border-gray-700">
                                    @if($trx->status_pesanan == 'Menunggu Dapur')
                                        <button type="button" onclick="updateKitchenOrderStatus({{ $trx->id_transaksi }}, 'Sedang Disiapkan')"
                                                class="w-full rounded-full bg-gray-900 dark:bg-gray-100 text-gray-50 dark:text-gray-900 hover:bg-indigo-600 dark:hover:bg-indigo-400 font-semibold py-2.5 text-sm transition-colors">Mulai siapkan</button>
                                    @elseif($trx->status_pesanan == 'Sedang Disiapkan')
                                        <button type="button" onclick="updateKitchenOrderStatus({{ $trx->id_transaksi }}, 'Siap Disajikan')"
                                                class="w-full rounded-full bg-emerald-600 hover:bg-emerald-700 text-white font-semibold py-2.5 text-sm transition-colors">Pesanan siap</button>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <div class="col-span-full py-16 text-center rounded-xl border border-dashed border-gray-300 dark:border-gray-700" id="empty_pesanan_masuk_msg">
                                <h4 class="font-display text-lg font-extrabold text-gray-800 dark:text-gray-100">Dapur sedang santai</h4>
                                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1 max-w-sm mx-auto">Belum ada antrean. Pesanan baru dari kasir akan muncul di sini disertai bunyi bel.</p>
                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- Tab 2: produksi batch -->
                <div x-show="activeTabDapur === 'produksi_batch'" x-cloak>
                    <x-panel flush title="Aktivitas dapur" :sub="'Status produksi roti batch dan pesanan kustom · ' . $produksis->count() . ' antrean'">
                        <ul class="mt-4 divide-y divide-gray-200 dark:divide-gray-700 border-t border-gray-200 dark:border-gray-700">
                            @forelse($produksis as $item)
                                <li class="px-5 sm:px-6 py-4">
                                    <div class="flex items-start justify-between gap-4">
                                        <div class="min-w-0">
                                            <p class="font-semibold text-gray-900 dark:text-gray-100">{{ $item->produk->nama_produk ?? '-' }}</p>
                                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                                #{{ $item->id_produksi }} · {{ $item->tanggal_produksi ? $item->tanggal_produksi->format('d/m/Y') : '-' }}
                                                @if(($item->produk->tipe_produksi ?? '') === 'Kustom' || $item->catatan_produksi)
                                                    · Pesanan kustom @if($item->catatan_produksi) · "{{ $item->catatan_produksi }}"@endif
                                                @else
                                                    · Standar batch
                                                @endif
                                            </p>
                                        </div>
                                        <div class="text-right flex-shrink-0">
                                            <p class="font-semibold tabular-nums text-gray-900 dark:text-gray-100">
                                                @if(($item->produk->tipe_produksi ?? '') === 'Kustom') {{ $item->jumlah_matang }} unit @else {{ $item->jumlah_matang }} pcs @endif
                                            </p>
                                            @if(($item->produk->tipe_produksi ?? '') !== 'Kustom')
                                                <p class="text-sm text-gray-500 dark:text-gray-400">{{ $item->jumlah_batch ?? 1 }} batch</p>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="mt-3 flex flex-wrap items-center justify-between gap-3">
                                        <span class="text-sm font-semibold {{ $item->status_produksi == 'Persiapan' ? 'text-amber-600 dark:text-amber-400' : ($item->status_produksi == 'Dipanggang' ? 'text-indigo-600 dark:text-indigo-400' : 'text-emerald-600 dark:text-emerald-400') }}">{{ $item->status_produksi }}</span>
                                        <div class="flex items-center gap-4">
                                            <form action="{{ route('dapur.updateStatus', $item->id_produksi) }}" method="POST">
                                                @csrf
                                                @method('PATCH')
                                                <select name="status_produksi" onchange="this.form.submit()" aria-label="Ubah status produksi" class="inp !w-auto !text-sm {{ $item->status_produksi == 'Selesai' ? 'opacity-60 cursor-not-allowed' : '' }}" {{ $item->status_produksi == 'Selesai' ? 'disabled' : '' }}>
                                                    <option value="Persiapan" {{ $item->status_produksi == 'Persiapan' ? 'selected' : '' }}>Persiapan</option>
                                                    <option value="Dipanggang" {{ $item->status_produksi == 'Dipanggang' ? 'selected' : '' }}>Dipanggang</option>
                                                    <option value="Selesai" {{ $item->status_produksi == 'Selesai' ? 'selected' : '' }}>Selesai</option>
                                                </select>
                                            </form>
                                            <form action="{{ route('dapur.destroy', $item->id_produksi) }}" method="POST" onsubmit="return confirm('Yakin ingin membatalkan produksi ini? Stok bahan baku akan dikembalikan.');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-sm font-semibold text-red-600 dark:text-red-400 hover:underline underline-offset-4" title="Batalkan produksi dan kembalikan stok bahan baku">Batal</button>
                                            </form>
                                        </div>
                                    </div>
                                </li>
                            @empty
                                <li class="py-14 text-center text-gray-400">Belum ada aktivitas produksi dapur.</li>
                            @endforelse
                        </ul>
                    </x-panel>
                </div>
            </div>
        </div>
    </x-page>

    <!-- Wadah notifikasi pesanan baru -->
    <div id="kitchen-toast-container" class="fixed top-20 right-6 z-50 flex flex-col gap-3 max-w-sm w-full pointer-events-none"></div>

    <script>
        // Bel dapur: dua nada sintetis lewat Web Audio API
        let kitchenAudioEnabled = localStorage.getItem('kitchen_audio_enabled') !== 'false';

        function updateAudioButtonUI() {
            const btn = document.getElementById('btn_toggle_audio');
            const text = document.getElementById('audio_text');
            if (!btn) return;
            text.textContent = kitchenAudioEnabled ? 'Bel dapur: aktif' : 'Bel dapur: bisu';
            btn.classList.toggle('opacity-60', !kitchenAudioEnabled);
        }

        function toggleKitchenAudio() {
            kitchenAudioEnabled = !kitchenAudioEnabled;
            localStorage.setItem('kitchen_audio_enabled', kitchenAudioEnabled);
            updateAudioButtonUI();
            if (kitchenAudioEnabled) playKitchenBell();
        }

        function testKitchenSound() { playKitchenBell(); }

        function playKitchenBell() {
            try {
                const AudioCtx = window.AudioContext || window.webkitAudioContext;
                if (!AudioCtx) return;
                const ctx = new AudioCtx();

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

        // Notifikasi pesanan baru
        function showKitchenToast(order) {
            const container = document.getElementById('kitchen-toast-container');
            if (!container) return;

            const toast = document.createElement('div');
            toast.className = 'pointer-events-auto bg-white dark:bg-gray-800 rounded-xl p-4 shadow-2xl border border-indigo-600 transform transition-all duration-300 translate-y-2 opacity-0 flex items-start gap-3';

            let itemSummary = '';
            if (order.detail_transaksis && order.detail_transaksis.length > 0) {
                itemSummary = order.detail_transaksis.map(d => `${d.qty_beli}x ${d.produk ? d.produk.nama_produk : 'Item'}`).join(', ');
            }

            toast.innerHTML = `
                <div class="flex-1 min-w-0">
                    <p class="font-display font-extrabold text-gray-900 dark:text-gray-100">Pesanan baru #${order.id_transaksi}</p>
                    <p class="text-sm font-semibold text-gray-700 dark:text-gray-300 truncate">${order.nama_pelanggan || 'Pelanggan umum'}</p>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5 line-clamp-2">${itemSummary || 'Detail item pesanan'}</p>
                </div>
                <button type="button" class="text-gray-400 hover:text-gray-600 text-sm font-bold" onclick="this.parentElement.remove()" aria-label="Tutup">✕</button>
            `;

            container.appendChild(toast);
            requestAnimationFrame(() => { toast.classList.remove('translate-y-2', 'opacity-0'); });

            setTimeout(() => {
                toast.classList.add('opacity-0', 'translate-x-full');
                setTimeout(() => toast.remove(), 400);
            }, 6500);
        }

        const BTN_SIAP = 'w-full rounded-full bg-emerald-600 hover:bg-emerald-700 text-white font-semibold py-2.5 text-sm transition-colors';

        // Ubah status pesanan lewat AJAX
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
                            setTimeout(() => { card.remove(); updateOrderCountBadge(); }, 500);
                        } else {
                            const badge = card.querySelector('.order-status-badge');
                            if (badge) {
                                badge.textContent = newStatus;
                                badge.className = 'order-status-badge text-sm font-semibold text-gray-600 dark:text-gray-300';
                            }
                            card.classList.remove('border-amber-400', 'dark:border-amber-600');
                            card.classList.add('border-gray-200', 'dark:border-gray-700');
                            const actions = card.querySelector('.border-t button');
                            if (actions) {
                                actions.outerHTML = `<button type="button" onclick="updateKitchenOrderStatus(${orderId}, 'Siap Disajikan')" class="${BTN_SIAP}">Pesanan siap</button>`;
                            }
                        }
                    }
                }
            } catch (err) {
                console.error('Failed to update status:', err);
            }
        }

        const EMPTY_HTML = `
            <div class="col-span-full py-16 text-center rounded-xl border border-dashed border-gray-300 dark:border-gray-700" id="empty_pesanan_masuk_msg">
                <h4 class="font-display text-lg font-extrabold text-gray-800 dark:text-gray-100">Dapur sedang santai</h4>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1 max-w-sm mx-auto">Belum ada antrean. Pesanan baru dari kasir akan muncul di sini disertai bunyi bel.</p>
            </div>`;

        function updateOrderCountBadge() {
            const count = document.querySelectorAll('#container_pesanan_masuk .order-card').length;
            const badge = document.getElementById('badge_count_pesanan');
            if (badge) badge.textContent = count;
            const emptyMsg = document.getElementById('empty_pesanan_masuk_msg');
            if (count === 0 && !emptyMsg) {
                const container = document.getElementById('container_pesanan_masuk');
                if (container) container.innerHTML = EMPTY_HTML;
            }
        }

        // Polling pesanan baru tiap 4 detik
        let lastKnownOrderId = {{ $latestTransaksiId ?? 0 }};

        async function pollKitchenOrders() {
            try {
                const res = await fetch(`{{ route('dapur.cek_pesanan') }}?last_id=${lastKnownOrderId}`, { headers: { 'Accept': 'application/json' } });
                if (!res.ok) return;
                const data = await res.json();

                if (data.has_new && data.new_orders && data.new_orders.length > 0) {
                    lastKnownOrderId = data.latest_id;
                    if (kitchenAudioEnabled) playKitchenBell();

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
            card.className = 'order-card rounded-xl border border-amber-400 dark:border-amber-600 bg-white dark:bg-gray-800 p-5 transition-all';
            card.id = `order-card-${order.id_transaksi}`;
            card.setAttribute('data-id', order.id_transaksi);

            let itemsHtml = '';
            if (order.detail_transaksis) {
                order.detail_transaksis.forEach(d => {
                    itemsHtml += `
                        <li class="flex items-center justify-between py-2">
                            <span><span class="font-semibold tabular-nums text-gray-900 dark:text-gray-100">${d.qty_beli}×</span> <span class="text-gray-800 dark:text-gray-200">${d.produk ? d.produk.nama_produk : 'Produk'}</span></span>
                            <span class="text-xs text-gray-400">${d.produk ? d.produk.tipe_produksi : 'Batch'}</span>
                        </li>`;
                });
            }

            card.innerHTML = `
                <div class="flex items-start justify-between gap-3 pb-3 mb-3 border-b border-gray-200 dark:border-gray-700">
                    <div>
                        <p class="flex items-baseline gap-2">
                            <span class="font-display text-xl font-extrabold text-gray-900 dark:text-gray-50">#${order.id_transaksi}</span>
                            <span class="text-sm text-gray-500 dark:text-gray-400">${order.jenis_pesanan}</span>
                        </p>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Baru saja masuk</p>
                    </div>
                    <span class="order-status-badge text-sm font-semibold text-amber-600 dark:text-amber-400">Menunggu Dapur</span>
                </div>
                <p class="font-semibold text-gray-900 dark:text-gray-100">${order.nama_pelanggan || 'Pelanggan umum'}</p>
                ${order.catatan_pesanan ? `<p class="mt-1 text-sm text-amber-700 dark:text-amber-400">Catatan: "${order.catatan_pesanan}"</p>` : ''}
                <ul class="my-4 divide-y divide-gray-200 dark:divide-gray-700 text-sm">${itemsHtml}</ul>
                <div class="flex items-center gap-2 pt-4 border-t border-gray-200 dark:border-gray-700">
                    <button type="button" onclick="updateKitchenOrderStatus(${order.id_transaksi}, 'Sedang Disiapkan')" class="w-full rounded-full bg-gray-900 dark:bg-gray-100 text-gray-50 dark:text-gray-900 hover:bg-indigo-600 dark:hover:bg-indigo-400 font-semibold py-2.5 text-sm transition-colors">Mulai siapkan</button>
                </div>
            `;

            container.prepend(card);
        }

        setInterval(pollKitchenOrders, 4000);
        updateAudioButtonUI();

        // Kalkulator reaktif produksi
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
                try { reseps = resepsRaw ? JSON.parse(resepsRaw) : []; } catch (e) { reseps = []; }

                let multiplier = 1;

                if (tipe === 'Kustom') {
                    panelBatch.classList.add('hidden');
                    panelKustom.classList.remove('hidden');
                    inputJumlahBatch.removeAttribute('required');
                    inputJumlahKustom.setAttribute('required', 'required');

                    const qtyKustom = parseInt(inputJumlahKustom.value || '1', 10);
                    multiplier = Math.max(1, isNaN(qtyKustom) ? 1 : qtyKustom);
                    labelKebutuhanMode.textContent = `untuk ${multiplier} unit kue kustom`;
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
                    labelKebutuhanMode.textContent = `untuk ${validBatch} batch = ${totalPcs} pcs`;
                }

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
                        itemDiv.className = 'flex items-center justify-between py-2';
                        itemDiv.innerHTML = `
                            <div class="flex items-center gap-2">
                                <span class="w-1.5 h-1.5 rounded-full ${cukup ? 'bg-emerald-500' : 'bg-red-500'}"></span>
                                <span class="text-gray-700 dark:text-gray-300">${bahan ? bahan.nama_bahan : 'Bahan'}</span>
                            </div>
                            <div class="text-right">
                                <span class="font-semibold tabular-nums ${cukup ? 'text-gray-800 dark:text-gray-200' : 'text-red-600 dark:text-red-400'}">${butuhFormatted} ${bahan ? bahan.satuan : ''}</span>
                                <span class="text-xs text-gray-400 ml-1">(ada ${stokAda})</span>
                            </div>
                        `;
                        listBahan.appendChild(itemDiv);
                    });
                } else {
                    panelBahan.classList.remove('hidden');
                    listBahan.innerHTML = `<p class="text-sm text-amber-600 dark:text-amber-400 py-2">Produk ini belum punya komposisi resep. Atur di Buku Resep.</p>`;
                }
            }

            selectProduk.addEventListener('change', updateUI);
            inputJumlahBatch.addEventListener('input', updateUI);
            inputJumlahKustom.addEventListener('input', updateUI);
            updateUI();
        });
    </script>
</x-app-layout>
