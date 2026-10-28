<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="font-extrabold text-xl text-gray-800 dark:text-gray-100 leading-tight flex items-center gap-2">
                    <span class="p-2 rounded-xl bg-indigo-50 dark:bg-indigo-950/60 text-indigo-600 dark:text-indigo-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </span>
                    <span>Manajemen Shift & Rekap Kasir</span>
                </h2>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                    Catat modal awal laci, pantau perputaran uang kas kasir, audit selisih kas fisik, dan cetak struk tutup shift.
                </p>
            </div>

            <!-- Quick Action Buttons -->
            <div class="flex items-center gap-2.5 flex-wrap">
                <a href="{{ route('kasir.index') }}" class="px-4 py-2 bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-200 border border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700 rounded-xl text-xs font-bold transition-all shadow-sm flex items-center gap-1.5">
                    <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    <span>Ke Mesin Kasir</span>
                </a>

                @if($currentActiveShift)
                <button type="button" 
                        onclick="openModalTutupShift({{ $currentActiveShift->id_shift }}, {{ (float)$currentActiveShift->modal_awal }}, {{ (float)$currentActiveShift->total_penjualan_tunai }}, {{ (float)$currentActiveShift->kas_akhir_sistem }})"
                        class="px-4 py-2 bg-rose-600 hover:bg-rose-700 text-white rounded-xl text-xs font-extrabold shadow-sm hover:shadow transition-all flex items-center gap-1.5 active:scale-95">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                    <span>Tutup Shift Anda (#{{ $currentActiveShift->id_shift }})</span>
                </button>
                @else
                <button type="button" 
                        onclick="openModalBukaShift()"
                        class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-extrabold shadow-sm hover:shadow transition-all flex items-center gap-1.5 active:scale-95">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    <span>Buka Shift Baru</span>
                </button>
                @endif
            </div>
        </div>
    </x-slot>

    <div class="py-6 sm:py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            <!-- Flash Alert Messages -->
            @if(session('success'))
                <div class="p-4 rounded-2xl bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-200 dark:border-emerald-800 text-emerald-800 dark:text-emerald-200 text-xs font-semibold flex items-center justify-between gap-3 shadow-sm">
                    <div class="flex items-center gap-2">
                        <span class="w-6 h-6 rounded-lg bg-emerald-500 text-white flex items-center justify-center font-bold text-xs">✓</span>
                        <span>{{ session('success') }}</span>
                    </div>
                    @if(session('cetak_shift_id'))
                    <a href="{{ route('shift.cetak', session('cetak_shift_id')) }}" target="_blank" class="px-3 py-1.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs flex items-center gap-1.5 flex-shrink-0 shadow">
                        🖨️ Cetak Struk Rekap
                    </a>
                    @endif
                </div>
            @endif

            @if(session('error'))
                <div class="p-4 rounded-2xl bg-rose-50 dark:bg-rose-950/40 border border-rose-200 dark:border-rose-800 text-rose-800 dark:text-rose-200 text-xs font-semibold flex items-center gap-2 shadow-sm">
                    <span class="w-6 h-6 rounded-lg bg-rose-500 text-white flex items-center justify-center font-bold text-xs">!</span>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            <!-- 4 Statistik Hari Ini KPI Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <!-- Status Shift Kasir -->
                <div class="p-5 rounded-2xl bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700/80 shadow-sm border-l-4 {{ $currentActiveShift ? 'border-l-emerald-500' : 'border-l-amber-500' }}">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-[11px] font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400">Shift Anda Sekarang</p>
                            <p class="text-xl font-black text-gray-900 dark:text-white mt-1 flex items-center gap-2">
                                @if($currentActiveShift)
                                    <span class="text-emerald-600 dark:text-emerald-400">Aktif (#{{ $currentActiveShift->id_shift }})</span>
                                    <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 animate-pulse"></span>
                                @else
                                    <span class="text-amber-600 dark:text-amber-400">Belum Buka</span>
                                @endif
                            </p>
                            <p class="text-[11px] text-gray-500 dark:text-gray-400 mt-1">
                                @if($currentActiveShift)
                                    Buka sejak {{ $currentActiveShift->waktu_buka->format('H:i') }}
                                @else
                                    Klik tombol untuk mulai
                                @endif
                            </p>
                        </div>
                        <div class="w-12 h-12 rounded-2xl {{ $currentActiveShift ? 'bg-emerald-50 text-emerald-600 dark:bg-emerald-950/60' : 'bg-amber-50 text-amber-600 dark:bg-amber-950/60' }} flex items-center justify-center text-xl shadow-inner font-bold">
                            {{ $currentActiveShift ? '🔓' : '🔒' }}
                        </div>
                    </div>
                </div>

                <!-- Penjualan Tunai Kas -->
                <div class="p-5 rounded-2xl bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700/80 shadow-sm border-l-4 border-l-indigo-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-[11px] font-bold uppercase tracking-wider text-indigo-600 dark:text-indigo-400">Kas Tunai Masuk</p>
                            <p class="text-xl font-black text-gray-900 dark:text-white mt-1">
                                Rp {{ number_format($totalKasHariIni, 0, ',', '.') }}
                            </p>
                            <p class="text-[11px] text-gray-500 dark:text-gray-400 mt-1">Akumulasi shift hari ini</p>
                        </div>
                        <div class="w-12 h-12 rounded-2xl bg-indigo-50 dark:bg-indigo-950/60 text-indigo-600 dark:text-indigo-400 flex items-center justify-center text-xl shadow-inner font-bold">
                            💵
                        </div>
                    </div>
                </div>

                <!-- Penjualan Non-Tunai (QRIS / Transfer) -->
                <div class="p-5 rounded-2xl bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700/80 shadow-sm border-l-4 border-l-teal-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-[11px] font-bold uppercase tracking-wider text-teal-600 dark:text-teal-400">Non-Tunai (QRIS)</p>
                            <p class="text-xl font-black text-gray-900 dark:text-white mt-1">
                                Rp {{ number_format($totalNonTunaiHariIni, 0, ',', '.') }}
                            </p>
                            <p class="text-[11px] text-gray-500 dark:text-gray-400 mt-1">Masuk rekening digital</p>
                        </div>
                        <div class="w-12 h-12 rounded-2xl bg-teal-50 dark:bg-teal-950/60 text-teal-600 dark:text-teal-400 flex items-center justify-center text-xl shadow-inner font-bold">
                            📱
                        </div>
                    </div>
                </div>

                <!-- Audit Selisih Kas -->
                <div class="p-5 rounded-2xl bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700/80 shadow-sm border-l-4 {{ $totalSelisihHariIni >= 0 ? 'border-l-purple-500' : 'border-l-rose-500' }}">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-[11px] font-bold uppercase tracking-wider {{ $totalSelisihHariIni >= 0 ? 'text-purple-600 dark:text-purple-400' : 'text-rose-600 dark:text-rose-400' }}">
                                Audit Selisih Kas
                            </p>
                            <p class="text-xl font-black {{ $totalSelisihHariIni == 0 ? 'text-gray-900 dark:text-white' : ($totalSelisihHariIni > 0 ? 'text-emerald-600 dark:text-emerald-400' : 'text-rose-600 dark:text-rose-400') }} mt-1">
                                {{ $totalSelisihHariIni >= 0 ? '+Rp ' : '-Rp ' }}{{ number_format(abs($totalSelisihHariIni), 0, ',', '.') }}
                            </p>
                            <p class="text-[11px] text-gray-500 dark:text-gray-400 mt-1">Total selisih laci tutup</p>
                        </div>
                        <div class="w-12 h-12 rounded-2xl bg-purple-50 dark:bg-purple-950/60 text-purple-600 dark:text-purple-400 flex items-center justify-center text-xl shadow-inner font-bold">
                            ⚖️
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tabel Riwayat Shift Kasir -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700/80 shadow-sm overflow-hidden">
                <div class="p-5 border-b border-gray-100 dark:border-gray-700/80 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                    <div>
                        <h3 class="text-sm font-extrabold text-gray-900 dark:text-white flex items-center gap-2">
                            <span>📋 Log & Riwayat Rekap Kasir</span>
                            <span class="px-2 py-0.5 rounded-full text-[11px] font-bold bg-indigo-50 dark:bg-indigo-900/40 text-indigo-600 dark:text-indigo-400">
                                {{ $shifts->total() }} Sesi
                            </span>
                        </h3>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Daftar rekonsiliasi kas masuk dan penghitungan fisik uang laci.</p>
                    </div>

                    <!-- Filter Tanggal -->
                    <form method="GET" action="{{ route('shift.index') }}" class="flex items-center gap-2">
                        <input type="date" name="tanggal" value="{{ request('tanggal') }}" class="px-3 py-1.5 text-xs rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-indigo-500 focus:border-indigo-500 shadow-sm">
                        <button type="submit" class="px-3 py-1.5 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-200 rounded-xl text-xs font-bold transition-all">
                            Filter
                        </button>
                        @if(request('tanggal'))
                        <a href="{{ route('shift.index') }}" class="text-xs text-rose-600 hover:underline font-bold">Reset</a>
                        @endif
                    </form>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse text-xs">
                        <thead>
                            <tr class="bg-gray-50/80 dark:bg-gray-700/40 text-gray-500 dark:text-gray-400 font-bold uppercase tracking-wider text-[10px] border-b border-gray-100 dark:border-gray-700">
                                <th class="py-3 px-4">Shift ID</th>
                                <th class="py-3 px-4">Kasir</th>
                                <th class="py-3 px-4">Waktu Buka / Tutup</th>
                                <th class="py-3 px-4 text-right">Modal Awal</th>
                                <th class="py-3 px-4 text-right">Penjualan Tunai</th>
                                <th class="py-3 px-4 text-right">Kas Sistem</th>
                                <th class="py-3 px-4 text-right">Kas Fisik</th>
                                <th class="py-3 px-4 text-center">Selisih</th>
                                <th class="py-3 px-4 text-center">Status</th>
                                <th class="py-3 px-4 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-gray-700/60 font-medium text-gray-800 dark:text-gray-200">
                            @forelse($shifts as $s)
                            <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-700/30 transition-colors">
                                <td class="py-3.5 px-4 font-extrabold text-indigo-600 dark:text-indigo-400">
                                    #{{ str_pad($s->id_shift, 4, '0', STR_PAD_LEFT) }}
                                </td>
                                <td class="py-3.5 px-4">
                                    <div class="font-bold text-gray-900 dark:text-white">{{ $s->user->name ?? 'Kasir' }}</div>
                                    <span class="text-[10px] text-gray-400">{{ $s->user->role_label ?? 'Staff' }}</span>
                                </td>
                                <td class="py-3.5 px-4">
                                    <div><span class="text-emerald-600 dark:text-emerald-400 font-bold">Buka:</span> {{ $s->waktu_buka->format('d/m/y H:i') }}</div>
                                    <div class="text-[11px] text-gray-500 dark:text-gray-400">
                                        @if($s->waktu_tutup)
                                            <span class="text-rose-500 font-bold">Tutup:</span> {{ $s->waktu_tutup->format('d/m/y H:i') }}
                                        @else
                                            <span class="text-amber-500 font-semibold italic">Sedang berjalan...</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="py-3.5 px-4 text-right font-semibold">
                                    Rp {{ number_format($s->modal_awal, 0, ',', '.') }}
                                </td>
                                <td class="py-3.5 px-4 text-right">
                                    <div class="font-bold text-emerald-600 dark:text-emerald-400">Rp {{ number_format($s->total_penjualan_tunai, 0, ',', '.') }}</div>
                                    <div class="text-[10px] text-gray-400">{{ $s->total_transaksi }} trx • Non-Tunai: Rp {{ number_format($s->total_penjualan_nontunai, 0, ',', '.') }}</div>
                                </td>
                                <td class="py-3.5 px-4 text-right font-extrabold text-gray-900 dark:text-white">
                                    Rp {{ number_format($s->kas_akhir_sistem, 0, ',', '.') }}
                                </td>
                                <td class="py-3.5 px-4 text-right font-extrabold">
                                    @if(is_null($s->kas_akhir_fisik))
                                        <span class="text-gray-400 italic">-</span>
                                    @else
                                        Rp {{ number_format($s->kas_akhir_fisik, 0, ',', '.') }}
                                    @endif
                                </td>
                                <td class="py-3.5 px-4 text-center">
                                    @if(is_null($s->selisih))
                                        <span class="text-gray-400 text-[10px]">-</span>
                                    @elseif($s->selisih == 0)
                                        <span class="inline-flex px-2 py-0.5 rounded-full text-[10px] font-extrabold bg-emerald-100 text-emerald-800 dark:bg-emerald-950 dark:text-emerald-300">
                                            Pas (Rp 0)
                                        </span>
                                    @elseif($s->selisih > 0)
                                        <span class="inline-flex px-2 py-0.5 rounded-full text-[10px] font-extrabold bg-blue-100 text-blue-800 dark:bg-blue-950 dark:text-blue-300">
                                            +Rp {{ number_format($s->selisih, 0, ',', '.') }}
                                        </span>
                                    @else
                                        <span class="inline-flex px-2 py-0.5 rounded-full text-[10px] font-extrabold bg-rose-100 text-rose-800 dark:bg-rose-950 dark:text-rose-300">
                                            -Rp {{ number_format(abs($s->selisih), 0, ',', '.') }}
                                        </span>
                                    @endif
                                </td>
                                <td class="py-3.5 px-4 text-center">
                                    @if($s->status === 'Open')
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 dark:bg-emerald-950/60 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-800">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                                            Terbuka
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-bold bg-gray-100 text-gray-700 dark:bg-gray-700/60 dark:text-gray-300">
                                            Ditutup
                                        </span>
                                    @endif
                                </td>
                                <td class="py-3.5 px-4 text-center">
                                    <div class="flex items-center justify-center gap-1.5">
                                        @if($s->status === 'Open')
                                            @if($s->id_user === Auth::id() || Auth::user()->isOwner())
                                            <button type="button" 
                                                    onclick="openModalTutupShift({{ $s->id_shift }}, {{ (float)$s->modal_awal }}, {{ (float)$s->total_penjualan_tunai }}, {{ (float)$s->kas_akhir_sistem }})"
                                                    class="px-2.5 py-1 rounded-lg bg-rose-50 hover:bg-rose-100 text-rose-700 dark:bg-rose-950/50 dark:text-rose-300 font-bold text-[11px] transition-colors"
                                                    title="Tutup Shift">
                                                Tutup
                                            </button>
                                            @endif
                                        @else
                                            <a href="{{ route('shift.cetak', $s->id_shift) }}" 
                                               target="_blank"
                                               class="p-1.5 rounded-lg bg-gray-100 hover:bg-indigo-50 text-gray-600 hover:text-indigo-600 dark:bg-gray-700 dark:text-gray-300 dark:hover:text-indigo-400 transition-colors"
                                               title="Cetak Struk Rekap Shift">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                                            </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="10" class="py-12 text-center text-gray-400">
                                    <div class="text-3xl mb-2">💵</div>
                                    <p class="font-bold">Belum ada catatan shift kasir.</p>
                                    <p class="text-[11px] text-gray-500 mt-1">Buka shift kasir baru untuk mencatat uang modal awal laci.</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($shifts->hasPages())
                <div class="p-4 border-t border-gray-100 dark:border-gray-700">
                    {{ $shifts->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- MODAL BUKA SHIFT KASIR -->
    <div id="modalBukaShift" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity" onclick="closeModalBukaShift()"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
            <div class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-3xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-md sm:w-full border border-gray-100 dark:border-gray-700">
                <form method="POST" action="{{ route('shift.buka') }}">
                    @csrf
                    <div class="p-6">
                        <div class="flex items-center justify-between pb-4 border-b border-gray-100 dark:border-gray-700">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-2xl bg-indigo-50 dark:bg-indigo-950/60 text-indigo-600 dark:text-indigo-400 flex items-center justify-center text-xl font-bold">
                                    🔓
                                </div>
                                <div>
                                    <h3 class="text-base font-extrabold text-gray-900 dark:text-white">Buka Shift Kasir Baru</h3>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">Siapkan uang tunai kembalian awal di laci.</p>
                                </div>
                            </div>
                            <button type="button" onclick="closeModalBukaShift()" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                            </button>
                        </div>

                        <div class="mt-5 space-y-4">
                            <div>
                                <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-1.5">
                                    Modal Awal Kasir (Uang Kembalian) <span class="text-rose-500">*</span>
                                </label>
                                <div class="relative">
                                    <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-gray-500 font-bold text-sm">Rp</span>
                                    <input type="number" 
                                           name="modal_awal" 
                                           id="modal_awal_input"
                                           min="0" 
                                           step="500" 
                                           value="100000"
                                           required
                                           class="w-full pl-11 pr-4 py-3 rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white font-extrabold text-lg focus:ring-2 focus:ring-indigo-500">
                                </div>
                                <div class="flex items-center gap-2 mt-2 flex-wrap">
                                    <span class="text-[11px] text-gray-400">Pilihan cepat:</span>
                                    <button type="button" onclick="setModalAwal(50000)" class="px-2.5 py-1 rounded-lg bg-gray-100 dark:bg-gray-700 hover:bg-indigo-50 text-[11px] font-bold text-gray-700 dark:text-gray-300 transition-colors">50 Ribu</button>
                                    <button type="button" onclick="setModalAwal(100000)" class="px-2.5 py-1 rounded-lg bg-gray-100 dark:bg-gray-700 hover:bg-indigo-50 text-[11px] font-bold text-gray-700 dark:text-gray-300 transition-colors">100 Ribu</button>
                                    <button type="button" onclick="setModalAwal(200000)" class="px-2.5 py-1 rounded-lg bg-gray-100 dark:bg-gray-700 hover:bg-indigo-50 text-[11px] font-bold text-gray-700 dark:text-gray-300 transition-colors">200 Ribu</button>
                                    <button type="button" onclick="setModalAwal(500000)" class="px-2.5 py-1 rounded-lg bg-gray-100 dark:bg-gray-700 hover:bg-indigo-50 text-[11px] font-bold text-gray-700 dark:text-gray-300 transition-colors">500 Ribu</button>
                                </div>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-1.5">
                                    Catatan / Keterangan (Opsional)
                                </label>
                                <textarea name="catatan" 
                                          rows="2" 
                                          placeholder="Contoh: Pecahan 5.000 (10 lembar), 10.000 (5 lembar)..."
                                          class="w-full px-3.5 py-2.5 rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white text-xs focus:ring-2 focus:ring-indigo-500"></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="px-6 py-4 bg-gray-50 dark:bg-gray-700/50 flex items-center justify-end gap-3 border-t border-gray-100 dark:border-gray-700">
                        <button type="button" onclick="closeModalBukaShift()" class="px-4 py-2.5 text-xs font-bold text-gray-600 dark:text-gray-400 hover:text-gray-800">
                            Batal
                        </button>
                        <button type="submit" class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-extrabold shadow transition-all active:scale-95">
                            Buka Shift Kasir Sekarang
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- MODAL TUTUP SHIFT KASIR -->
    <div id="modalTutupShift" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity" onclick="closeModalTutupShift()"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
            <div class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-3xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border border-gray-100 dark:border-gray-700">
                <form id="formTutupShift" method="POST" action="">
                    @csrf
                    <div class="p-6">
                        <div class="flex items-center justify-between pb-4 border-b border-gray-100 dark:border-gray-700">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-2xl bg-rose-50 dark:bg-rose-950/60 text-rose-600 dark:text-rose-400 flex items-center justify-center text-xl font-bold">
                                    🔒
                                </div>
                                <div>
                                    <h3 class="text-base font-extrabold text-gray-900 dark:text-white" id="titleTutupShift">Tutup Shift Kasir</h3>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">Rekap uang tunai laci dan bandingkan dengan catatan sistem.</p>
                                </div>
                            </div>
                            <button type="button" onclick="closeModalTutupShift()" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                            </button>
                        </div>

                        <!-- Ringkasan Kas Sistem -->
                        <div class="mt-4 p-4 rounded-2xl bg-gray-50 dark:bg-gray-700/40 border border-gray-100 dark:border-gray-700 space-y-2 text-xs">
                            <div class="flex justify-between items-center text-gray-600 dark:text-gray-400">
                                <span>Modal Awal Kasir:</span>
                                <span class="font-bold text-gray-900 dark:text-white" id="tutupModalAwal">Rp 0</span>
                            </div>
                            <div class="flex justify-between items-center text-gray-600 dark:text-gray-400">
                                <span>(+) Total Penjualan Tunai:</span>
                                <span class="font-bold text-emerald-600 dark:text-emerald-400" id="tutupPenjualanTunai">Rp 0</span>
                            </div>
                            <div class="pt-2 border-t border-gray-200 dark:border-gray-600 flex justify-between items-center text-sm font-extrabold">
                                <span class="text-gray-900 dark:text-white">Uang Seharusnya di Laci (Sistem):</span>
                                <span class="text-indigo-600 dark:text-indigo-400 font-black text-base" id="tutupKasSistem">Rp 0</span>
                            </div>
                        </div>

                        <div class="mt-4 space-y-4">
                            <div>
                                <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-1.5">
                                    Hitung Uang Fisik di Laci Kasir <span class="text-rose-500">*</span>
                                </label>
                                <div class="relative">
                                    <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-gray-500 font-bold text-sm">Rp</span>
                                    <input type="number" 
                                           name="kas_akhir_fisik" 
                                           id="kas_akhir_fisik_input"
                                           min="0" 
                                           step="500" 
                                           oninput="hitungSelisihLive()"
                                           required
                                           placeholder="0"
                                           class="w-full pl-11 pr-4 py-3 rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white font-black text-lg focus:ring-2 focus:ring-rose-500">
                                </div>
                                <p class="text-[11px] text-gray-400 mt-1">Hitung semua lembaran kertas dan koin fisik yang ada di laci saat ini.</p>
                            </div>

                            <!-- Live Selisih Box -->
                            <div id="boxSelisih" class="p-3.5 rounded-xl border transition-all text-xs flex items-center justify-between font-bold bg-gray-50 border-gray-200 text-gray-600">
                                <span>Status Selisih Kas:</span>
                                <span id="textSelisih">Masukkan uang fisik di atas</span>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-1.5">
                                    Catatan Penutupan Shift (Opsional)
                                </label>
                                <textarea name="catatan" 
                                          rows="2" 
                                          placeholder="Keterangan jika ada selisih kas atau hal khusus selama shift bertugas..."
                                          class="w-full px-3.5 py-2.5 rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white text-xs focus:ring-2 focus:ring-rose-500"></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="px-6 py-4 bg-gray-50 dark:bg-gray-700/50 flex items-center justify-end gap-3 border-t border-gray-100 dark:border-gray-700">
                        <button type="button" onclick="closeModalTutupShift()" class="px-4 py-2.5 text-xs font-bold text-gray-600 dark:text-gray-400 hover:text-gray-800">
                            Batal
                        </button>
                        <button type="submit" class="px-5 py-2.5 bg-rose-600 hover:bg-rose-700 text-white rounded-xl text-xs font-extrabold shadow transition-all active:scale-95">
                            Tutup Shift & Rekap Kas
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        let currentTargetKasSistem = 0;

        function openModalBukaShift() {
            document.getElementById('modalBukaShift').classList.remove('hidden');
        }

        function closeModalBukaShift() {
            document.getElementById('modalBukaShift').classList.add('hidden');
        }

        function setModalAwal(val) {
            document.getElementById('modal_awal_input').value = val;
        }

        function openModalTutupShift(shiftId, modalAwal, penjualanTunai, kasSistem) {
            currentTargetKasSistem = kasSistem;
            document.getElementById('titleTutupShift').innerText = `Tutup Shift Kasir #${shiftId}`;
            document.getElementById('tutupModalAwal').innerText = `Rp ${Number(modalAwal).toLocaleString('id-ID')}`;
            document.getElementById('tutupPenjualanTunai').innerText = `Rp ${Number(penjualanTunai).toLocaleString('id-ID')}`;
            document.getElementById('tutupKasSistem').innerText = `Rp ${Number(kasSistem).toLocaleString('id-ID')}`;
            
            document.getElementById('formTutupShift').action = `/shift/${shiftId}/tutup`;
            document.getElementById('kas_akhir_fisik_input').value = '';
            
            hitungSelisihLive();
            document.getElementById('modalTutupShift').classList.remove('hidden');
        }

        function closeModalTutupShift() {
            document.getElementById('modalTutupShift').classList.add('hidden');
        }

        function hitungSelisihLive() {
            const inputVal = document.getElementById('kas_akhir_fisik_input').value;
            const box = document.getElementById('boxSelisih');
            const text = document.getElementById('textSelisih');

            if (inputVal === '' || isNaN(inputVal)) {
                box.className = "p-3.5 rounded-xl border transition-all text-xs flex items-center justify-between font-bold bg-gray-50 dark:bg-gray-700 border-gray-200 dark:border-gray-600 text-gray-500 dark:text-gray-400";
                text.innerText = "Masukkan nominal kas fisik";
                return;
            }

            const fisik = parseFloat(inputVal);
            const selisih = fisik - currentTargetKasSistem;

            if (selisih === 0) {
                box.className = "p-3.5 rounded-xl border transition-all text-xs flex items-center justify-between font-bold bg-emerald-50 dark:bg-emerald-950/40 border-emerald-300 dark:border-emerald-700 text-emerald-800 dark:text-emerald-200";
                text.innerText = "Sesuai / Pas (Rp 0)";
            } else if (selisih > 0) {
                box.className = "p-3.5 rounded-xl border transition-all text-xs flex items-center justify-between font-bold bg-blue-50 dark:bg-blue-950/40 border-blue-300 dark:border-blue-700 text-blue-800 dark:text-blue-200";
                text.innerText = `Surplus (+Rp ${Math.abs(selisih).toLocaleString('id-ID')})`;
            } else {
                box.className = "p-3.5 rounded-xl border transition-all text-xs flex items-center justify-between font-bold bg-rose-50 dark:bg-rose-950/40 border-rose-300 dark:border-rose-700 text-rose-800 dark:text-rose-200";
                text.innerText = `Minus (-Rp ${Math.abs(selisih).toLocaleString('id-ID')})`;
            }
        }
    </script>
</x-app-layout>
