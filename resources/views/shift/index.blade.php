<x-app-layout>
    <x-page title="Shift dan kas" sub="Catat modal awal laci, pantau kas kasir, audit selisih, dan cetak struk tutup shift.">
        <x-slot name="actions">
            <x-btn variant="secondary" :href="route('kasir.index')">Ke mesin kasir</x-btn>
            @if($currentActiveShift)
                <x-btn variant="danger" onclick="openModalTutupShift({{ $currentActiveShift->id_shift }}, {{ (float)$currentActiveShift->modal_awal }}, {{ (float)$currentActiveShift->total_penjualan_tunai }}, {{ (float)$currentActiveShift->kas_akhir_sistem }})">Tutup shift #{{ $currentActiveShift->id_shift }}</x-btn>
            @else
                <x-btn onclick="openModalBukaShift()">Buka shift baru</x-btn>
            @endif
        </x-slot>

        @if(session('cetak_shift_id'))
            <div class="rounded-lg border border-gray-300 dark:border-gray-700 px-4 py-3 flex items-center justify-between gap-3 text-sm">
                <span>Shift berhasil ditutup.</span>
                <a href="{{ route('shift.cetak', session('cetak_shift_id')) }}" target="_blank" class="font-semibold text-indigo-600 dark:text-indigo-400 hover:underline underline-offset-4">Cetak struk rekap</a>
            </div>
        @endif

        <x-stats>
            <x-stat label="Shift Anda sekarang"
                    :value="$currentActiveShift ? 'Aktif #' . $currentActiveShift->id_shift : 'Belum buka'"
                    :sub="$currentActiveShift ? 'Buka sejak ' . $currentActiveShift->waktu_buka->format('H:i') : 'Klik tombol untuk mulai'"
                    :tone="$currentActiveShift ? 'good' : null" />
            <x-stat label="Kas tunai masuk" :value="'Rp ' . number_format($totalKasHariIni, 0, ',', '.')" sub="Akumulasi shift hari ini" />
            <x-stat label="Non-tunai (QRIS)" :value="'Rp ' . number_format($totalNonTunaiHariIni, 0, ',', '.')" sub="Masuk rekening digital" />
            <x-stat label="Selisih kas" :value="($totalSelisihHariIni >= 0 ? '+Rp ' : '-Rp ') . number_format(abs($totalSelisihHariIni), 0, ',', '.')" sub="Total selisih laci tutup" :tone="$totalSelisihHariIni < 0 ? 'bad' : null" />
        </x-stats>

        <x-panel flush title="Riwayat shift" :sub="$shifts->total() . ' sesi tercatat'">
            <x-slot name="actions">
                <form method="GET" action="{{ route('shift.index') }}" class="flex items-center gap-2">
                    <input type="date" name="tanggal" value="{{ request('tanggal') }}" class="inp !w-auto !py-1.5">
                    <x-btn type="submit" size="sm" variant="secondary">Filter</x-btn>
                    @if(request('tanggal'))<x-btn variant="link" :href="route('shift.index')">Reset</x-btn>@endif
                </form>
            </x-slot>

            <div class="overflow-x-auto border-t border-gray-200 dark:border-gray-700">
                <table class="tbl">
                    <thead>
                        <tr>
                            <th>Shift</th>
                            <th>Kasir</th>
                            <th>Buka dan tutup</th>
                            <th class="r">Modal awal</th>
                            <th class="r">Penjualan tunai</th>
                            <th class="r">Kas sistem</th>
                            <th class="r">Kas fisik</th>
                            <th class="r">Selisih</th>
                            <th>Status</th>
                            <th class="r">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($shifts as $s)
                            <tr>
                                <td class="num font-mono font-semibold">#{{ str_pad($s->id_shift, 4, '0', STR_PAD_LEFT) }}</td>
                                <td>
                                    <p class="font-semibold text-gray-900 dark:text-gray-100">{{ $s->user->name ?? 'Kasir' }}</p>
                                    <p class="text-xs text-gray-500">{{ $s->user->role_label ?? 'Staf' }}</p>
                                </td>
                                <td class="num text-gray-600 dark:text-gray-300 whitespace-nowrap">
                                    <p>{{ $s->waktu_buka->format('d/m/y H:i') }}</p>
                                    <p class="text-gray-400">{{ $s->waktu_tutup ? $s->waktu_tutup->format('d/m/y H:i') : 'sedang berjalan' }}</p>
                                </td>
                                <td class="r num">Rp {{ number_format($s->modal_awal, 0, ',', '.') }}</td>
                                <td class="r num">
                                    <p class="font-semibold">Rp {{ number_format($s->total_penjualan_tunai, 0, ',', '.') }}</p>
                                    <p class="text-xs text-gray-500">{{ $s->total_transaksi }} trx · non-tunai Rp {{ number_format($s->total_penjualan_nontunai, 0, ',', '.') }}</p>
                                </td>
                                <td class="r num">Rp {{ number_format($s->kas_akhir_sistem, 0, ',', '.') }}</td>
                                <td class="r num">{{ is_null($s->kas_akhir_fisik) ? '-' : 'Rp ' . number_format($s->kas_akhir_fisik, 0, ',', '.') }}</td>
                                <td class="r num font-semibold">
                                    @if(is_null($s->selisih))
                                        <span class="text-gray-400 font-normal">-</span>
                                    @elseif($s->selisih == 0)
                                        <span class="text-emerald-600 dark:text-emerald-400">Pas</span>
                                    @elseif($s->selisih > 0)
                                        <span class="text-amber-600 dark:text-amber-400">+Rp {{ number_format($s->selisih, 0, ',', '.') }}</span>
                                    @else
                                        <span class="text-red-600 dark:text-red-400">-Rp {{ number_format(abs($s->selisih), 0, ',', '.') }}</span>
                                    @endif
                                </td>
                                <td>
                                    @if($s->status === 'Open')
                                        <span class="font-semibold text-emerald-600 dark:text-emerald-400">Terbuka</span>
                                    @else
                                        <span class="text-gray-500">Ditutup</span>
                                    @endif
                                </td>
                                <td class="r whitespace-nowrap">
                                    @if($s->status === 'Open')
                                        @if($s->id_user === Auth::id() || Auth::user()->isOwner())
                                            <button type="button" onclick="openModalTutupShift({{ $s->id_shift }}, {{ (float)$s->modal_awal }}, {{ (float)$s->total_penjualan_tunai }}, {{ (float)$s->kas_akhir_sistem }})" class="text-sm font-semibold text-red-600 dark:text-red-400 hover:underline underline-offset-4">Tutup</button>
                                        @endif
                                    @else
                                        <a href="{{ route('shift.cetak', $s->id_shift) }}" target="_blank" class="text-sm font-semibold text-indigo-600 dark:text-indigo-400 hover:underline underline-offset-4">Cetak</a>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="py-14 text-center">
                                    <p class="font-semibold text-gray-700 dark:text-gray-200">Belum ada catatan shift kasir</p>
                                    <p class="text-sm text-gray-400">Buka shift baru untuk mencatat modal awal laci.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($shifts->hasPages())
                <div class="px-5 sm:px-6 py-4 border-t border-gray-200 dark:border-gray-700">{{ $shifts->links() }}</div>
            @endif
        </x-panel>
    </x-page>

    <!-- Modal buka shift -->
    <div id="modalBukaShift" class="fixed inset-0 z-50 hidden" role="dialog" aria-modal="true" aria-labelledby="titleBukaShift">
        <div class="absolute inset-0 bg-black/50" onclick="closeModalBukaShift()"></div>
        <div class="relative flex min-h-full items-center justify-center p-4 pointer-events-none">
            <div class="pointer-events-auto w-full max-w-md rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 shadow-2xl">
                <form method="POST" action="{{ route('shift.buka') }}" class="p-6 sm:p-7 space-y-5">
                    @csrf
                    <div>
                        <h2 id="titleBukaShift" class="font-display text-2xl font-extrabold tracking-tight text-gray-900 dark:text-gray-50">Buka shift kasir</h2>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Siapkan uang tunai kembalian awal di laci.</p>
                    </div>

                    <div class="fld">
                        <label for="modal_awal_input">Modal awal (uang kembalian)</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-3 flex items-center text-sm text-gray-400">Rp</span>
                            <input type="number" name="modal_awal" id="modal_awal_input" min="0" step="500" value="100000" required class="inp !pl-10 text-lg font-bold tabular-nums">
                        </div>
                        <div class="flex items-center gap-1.5 mt-2 flex-wrap">
                            <span class="text-xs text-gray-400">Pilihan cepat:</span>
                            @foreach([50000 => '50 rb', 100000 => '100 rb', 200000 => '200 rb', 500000 => '500 rb'] as $nominal => $lbl)
                                <button type="button" onclick="setModalAwal({{ $nominal }})" class="text-xs font-semibold px-2.5 py-1 rounded-full border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-200 hover:border-gray-900 dark:hover:border-gray-300">{{ $lbl }}</button>
                            @endforeach
                        </div>
                    </div>

                    <div class="fld">
                        <label for="catatan_buka">Catatan (opsional)</label>
                        <textarea name="catatan" id="catatan_buka" rows="2" placeholder="Misal: pecahan 5.000 (10 lembar), 10.000 (5 lembar)" class="inp"></textarea>
                    </div>

                    <div class="flex items-center justify-end gap-3 pt-1">
                        <x-btn variant="secondary" onclick="closeModalBukaShift()">Batal</x-btn>
                        <x-btn type="submit">Buka shift</x-btn>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal tutup shift -->
    <div id="modalTutupShift" class="fixed inset-0 z-50 hidden" role="dialog" aria-modal="true" aria-labelledby="titleTutupShift">
        <div class="absolute inset-0 bg-black/50" onclick="closeModalTutupShift()"></div>
        <div class="relative flex min-h-full items-center justify-center p-4 pointer-events-none">
            <div class="pointer-events-auto w-full max-w-lg rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 shadow-2xl">
                <form id="formTutupShift" method="POST" action="" class="p-6 sm:p-7 space-y-5">
                    @csrf
                    <div>
                        <h2 id="titleTutupShift" class="font-display text-2xl font-extrabold tracking-tight text-gray-900 dark:text-gray-50">Tutup shift kasir</h2>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Rekap uang tunai laci dan bandingkan dengan catatan sistem.</p>
                    </div>

                    <dl class="rounded-lg border border-gray-200 dark:border-gray-700 divide-y divide-gray-200 dark:divide-gray-700 text-sm">
                        <div class="flex justify-between px-4 py-2.5"><dt class="text-gray-500 dark:text-gray-400">Modal awal</dt><dd class="font-semibold tabular-nums" id="tutupModalAwal">Rp 0</dd></div>
                        <div class="flex justify-between px-4 py-2.5"><dt class="text-gray-500 dark:text-gray-400">Total penjualan tunai</dt><dd class="font-semibold tabular-nums text-emerald-600 dark:text-emerald-400" id="tutupPenjualanTunai">Rp 0</dd></div>
                        <div class="flex justify-between px-4 py-3"><dt class="font-semibold">Uang seharusnya di laci</dt><dd class="font-display text-lg font-extrabold tabular-nums" id="tutupKasSistem">Rp 0</dd></div>
                    </dl>

                    <div class="fld">
                        <label for="kas_akhir_fisik_input">Uang fisik di laci</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-3 flex items-center text-sm text-gray-400">Rp</span>
                            <input type="number" name="kas_akhir_fisik" id="kas_akhir_fisik_input" min="0" step="500" oninput="hitungSelisihLive()" required placeholder="0" class="inp !pl-10 text-lg font-bold tabular-nums">
                        </div>
                        <p class="hint">Hitung semua lembaran dan koin yang ada di laci saat ini.</p>
                    </div>

                    <div id="boxSelisih" class="px-3.5 py-3 rounded-lg border border-gray-300 dark:border-gray-600 text-sm flex items-center justify-between font-semibold text-gray-500">
                        <span>Status selisih kas</span>
                        <span id="textSelisih">Masukkan uang fisik di atas</span>
                    </div>

                    <div class="fld">
                        <label for="catatan_tutup">Catatan penutupan (opsional)</label>
                        <textarea name="catatan" id="catatan_tutup" rows="2" placeholder="Keterangan jika ada selisih kas atau hal khusus selama shift" class="inp"></textarea>
                    </div>

                    <div class="flex items-center justify-end gap-3 pt-1">
                        <x-btn variant="secondary" onclick="closeModalTutupShift()">Batal</x-btn>
                        <x-btn type="submit" variant="accent">Tutup shift dan rekap kas</x-btn>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        let currentTargetKasSistem = 0;

        function openModalBukaShift() { document.getElementById('modalBukaShift').classList.remove('hidden'); }
        function closeModalBukaShift() { document.getElementById('modalBukaShift').classList.add('hidden'); }
        function setModalAwal(val) { document.getElementById('modal_awal_input').value = val; }

        function openModalTutupShift(shiftId, modalAwal, penjualanTunai, kasSistem) {
            currentTargetKasSistem = kasSistem;
            document.getElementById('titleTutupShift').innerText = `Tutup shift kasir #${shiftId}`;
            document.getElementById('tutupModalAwal').innerText = `Rp ${Number(modalAwal).toLocaleString('id-ID')}`;
            document.getElementById('tutupPenjualanTunai').innerText = `Rp ${Number(penjualanTunai).toLocaleString('id-ID')}`;
            document.getElementById('tutupKasSistem').innerText = `Rp ${Number(kasSistem).toLocaleString('id-ID')}`;
            document.getElementById('formTutupShift').action = `/shift/${shiftId}/tutup`;
            document.getElementById('kas_akhir_fisik_input').value = '';
            hitungSelisihLive();
            document.getElementById('modalTutupShift').classList.remove('hidden');
        }

        function closeModalTutupShift() { document.getElementById('modalTutupShift').classList.add('hidden'); }

        function hitungSelisihLive() {
            const inputVal = document.getElementById('kas_akhir_fisik_input').value;
            const box = document.getElementById('boxSelisih');
            const text = document.getElementById('textSelisih');
            const base = 'px-3.5 py-3 rounded-lg border text-sm flex items-center justify-between font-semibold ';

            if (inputVal === '' || isNaN(inputVal)) {
                box.className = base + 'border-gray-300 dark:border-gray-600 text-gray-500 dark:text-gray-400';
                text.innerText = 'Masukkan nominal kas fisik';
                return;
            }

            const selisih = parseFloat(inputVal) - currentTargetKasSistem;
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
</x-app-layout>
