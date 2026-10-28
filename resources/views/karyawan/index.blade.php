<x-app-layout>
    <x-page title="Karyawan" sub="Buat dan atur hak akses akun staf: kasir, dapur, admin gudang, kustom, dan owner.">
        <x-slot name="actions">
            <x-btn :href="route('karyawan.create')">Tambah akun</x-btn>
        </x-slot>

        <x-stats class="lg:!grid-cols-5">
            <x-stat label="Total akun" :value="$totalCount" />
            <x-stat label="Kasir" :value="$kasirCount" />
            <x-stat label="Dapur" :value="$dapurCount" />
            <x-stat label="Admin gudang" :value="$stokCount" />
            <x-stat label="Peran kustom" :value="$kustomCount" />
        </x-stats>

        @php
            $roleNow = request('role');
            $tabs = [
                [null, 'Semua', $totalCount],
                ['Cashier', 'Kasir', $kasirCount],
                ['Kitchen', 'Dapur', $dapurCount],
                ['Stock_Admin', 'Admin gudang', $stokCount],
                ['Kustom', 'Kustom', $kustomCount],
                ['Owner', 'Owner', $ownerCount],
            ];
        @endphp
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
            <nav class="flex flex-wrap items-center gap-1" aria-label="Filter peran">
                @foreach($tabs as $t)
                    <a href="{{ $t[0] ? route('karyawan.index', ['role' => $t[0]]) : route('karyawan.index') }}"
                       class="px-3.5 py-1.5 rounded-full text-sm font-semibold transition-colors {{ ($roleNow ?? null) == $t[0] ? 'bg-gray-900 dark:bg-gray-100 text-gray-50 dark:text-gray-900' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-200/60 dark:hover:bg-gray-800' }}">
                        {{ $t[1] }} <span class="opacity-60 tabular-nums">{{ $t[2] }}</span>
                    </a>
                @endforeach
            </nav>
            <form action="{{ route('karyawan.index') }}" method="GET" class="flex items-center gap-2">
                @if(request('role'))<input type="hidden" name="role" value="{{ request('role') }}">@endif
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama atau email" class="inp !w-full sm:!w-72">
                <x-btn type="submit" variant="secondary">Cari</x-btn>
            </form>
        </div>

        <x-panel flush>
            <div class="overflow-x-auto">
                <table class="tbl">
                    <thead>
                        <tr>
                            <th>Karyawan</th>
                            <th>Email login</th>
                            <th>Peran</th>
                            <th class="wrap">Hak akses</th>
                            <th class="r">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($karyawans as $item)
                            <tr>
                                <td>
                                    <div class="flex items-center gap-3">
                                        <span class="w-9 h-9 rounded-full bg-gray-900 dark:bg-gray-100 text-gray-50 dark:text-gray-900 text-sm font-bold flex items-center justify-center flex-shrink-0">{{ strtoupper(substr($item->name, 0, 1)) }}</span>
                                        <div class="min-w-0">
                                            <p class="font-semibold text-gray-900 dark:text-gray-100">{{ $item->name }}@if($item->id === Auth::id()) <span class="ml-1 text-xs font-medium text-indigo-600 dark:text-indigo-400">akun Anda</span>@endif</p>
                                            <p class="text-xs text-gray-400">ID #{{ $item->id }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-gray-600 dark:text-gray-300">{{ $item->email }}</td>
                                <td class="font-medium">
                                    @if($item->isOwner()) Owner
                                    @elseif($item->isKasir()) Kasir
                                    @elseif($item->isDapur()) Dapur
                                    @elseif($item->isStockAdmin()) Admin stok
                                    @elseif($item->isCustom()) Kustom
                                    @else {{ $item->role }} @endif
                                </td>
                                <td class="wrap text-gray-600 dark:text-gray-300">
                                    @if($item->isOwner()) Akses penuh semua modul
                                    @elseif($item->isKasir()) POS kasir dan cetak struk
                                    @elseif($item->isDapur()) Pantau pesanan dan produksi
                                    @elseif($item->isStockAdmin()) Bahan baku, resep, dan restock
                                    @elseif($item->isCustom())
                                        <span class="flex flex-wrap gap-x-2 gap-y-0.5">
                                            @forelse($item->permission_labels as $lbl)<span>{{ $lbl }}@if(!$loop->last),@endif</span>@empty<span class="text-gray-400">Belum diatur izin</span>@endforelse
                                        </span>
                                    @else - @endif
                                </td>
                                <td class="r">
                                    <div class="inline-flex items-center gap-4">
                                        <x-btn variant="link" :href="route('karyawan.edit', $item->id)">Ubah</x-btn>
                                        @if($item->id !== Auth::id())
                                            <form action="{{ route('karyawan.destroy', $item->id) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus akun {{ addslashes($item->name) }}?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-sm font-semibold text-red-600 dark:text-red-400 hover:underline underline-offset-4">Hapus</button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="py-14 text-center text-gray-400">Tidak ada karyawan ditemukan.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </x-panel>
    </x-page>
</x-app-layout>
