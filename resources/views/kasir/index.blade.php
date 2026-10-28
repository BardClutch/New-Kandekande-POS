<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 w-full">
            <div class="flex items-center gap-3">
                <h2 class="font-bold text-xl text-gray-800 dark:text-gray-100 tracking-tight flex items-center gap-2">
                    <svg class="w-6 h-6 text-[#B06E6E] dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                    Kasir (POS)
                </h2>

                <!-- Role Badge & Shift Badge -->
                <div class="flex items-center gap-2 flex-wrap">
                    @if(Auth::user()->isOwner())
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-amber-100 dark:bg-amber-900/40 text-amber-800 dark:text-amber-300 border border-amber-300 dark:border-amber-700 shadow-sm">
                            <svg class="w-3.5 h-3.5 text-amber-600 dark:text-amber-400" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                            </svg>
                            Mode Owner
                        </span>
                    @else
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-emerald-100 dark:bg-emerald-900/40 text-emerald-800 dark:text-emerald-300 border border-emerald-300 dark:border-emerald-700 shadow-sm">
                            <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                            Mode Kasir
                        </span>
                    @endif

                    @if($activeShift)
                    <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-emerald-50 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-300 border border-emerald-300 dark:border-emerald-700">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                        <span>Shift #{{ $activeShift->id_shift }}</span>
                        <span class="text-gray-300 dark:text-gray-600">|</span>
                        <span>Laci: Rp {{ number_format($activeShift->kas_akhir_sistem, 0, ',', '.') }}</span>
                    </div>
                    @else
                    <button type="button" onclick="openModalBukaShiftPos()" class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-extrabold bg-rose-100 hover:bg-rose-200 text-rose-800 dark:bg-rose-950/60 dark:text-rose-300 border border-rose-300 dark:border-rose-700 shadow-sm transition-all animate-pulse">
                        <span>⚠️ Shift Belum Buka</span>
                    </button>
                    @endif
                </div>
            </div>

            <!-- Header Right Section: Quick links & Clock -->
            <div class="flex items-center gap-2.5 flex-wrap">
                <!-- Shift Actions -->
                @if($activeShift)
                <button type="button" 
                        onclick="openModalTutupShiftPos({{ $activeShift->id_shift }}, {{ (float)$activeShift->modal_awal }}, {{ (float)$activeShift->total_penjualan_tunai }}, {{ (float)$activeShift->kas_akhir_sistem }})"
                        class="px-3 py-1.5 bg-rose-600 hover:bg-rose-700 text-white font-extrabold rounded-xl text-xs shadow-sm transition-all flex items-center gap-1.5 active:scale-95">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                    <span>Tutup Shift</span>
                </button>
                @else
                <button type="button" 
                        onclick="openModalBukaShiftPos()"
                        class="px-3 py-1.5 bg-indigo-600 hover:bg-indigo-700 text-white font-extrabold rounded-xl text-xs shadow-sm transition-all flex items-center gap-1.5 active:scale-95">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    <span>Buka Shift</span>
                </button>
                @endif

                <a href="{{ route('shift.index') }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-bold rounded-xl bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-200 hover:text-indigo-600 dark:hover:text-indigo-400 border border-gray-200 dark:border-gray-600 shadow-sm transition-colors" title="Rekap & Riwayat Shift">
                    <svg class="w-3.5 h-3.5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <span class="hidden md:inline">Rekap Kas</span>
                </a>

                <!-- Realtime Clock -->
                <div class="text-right hidden sm:flex items-center gap-2 text-xs text-gray-600 dark:text-gray-300 font-semibold bg-gray-100 dark:bg-gray-700/60 px-3.5 py-1.5 rounded-xl border border-gray-200 dark:border-gray-700">
                    <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span id="pos-realtime-clock">Memuat waktu...</span>
                </div>

                @if(Auth::user()->isOwner())
                    <!-- Owner Quick Navigation -->
                    <div class="flex items-center gap-1.5">
                        <a href="{{ route('produk.index') }}" class="inline-flex items-center gap-1 px-2.5 py-1.5 text-xs font-bold rounded-xl bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-200 hover:text-indigo-600 dark:hover:text-indigo-400 border border-gray-200 dark:border-gray-600 shadow-sm transition-colors" title="Katalog Produk">
                            <span class="hidden md:inline">Katalog</span>
                        </a>
                        <a href="{{ route('laporan.index') }}" class="inline-flex items-center gap-1 px-2.5 py-1.5 text-xs font-bold rounded-xl bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-200 hover:text-indigo-600 dark:hover:text-indigo-400 border border-gray-200 dark:border-gray-600 shadow-sm transition-colors" title="Laporan Penjualan">
                            <span class="hidden md:inline">Laporan</span>
                        </a>
                    </div>
                @else
                    <!-- Kasir Quick Navigation -->
                    <a href="{{ route('laporan.index') }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-bold rounded-xl bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-200 hover:text-indigo-600 dark:hover:text-indigo-400 border border-gray-200 dark:border-gray-600 shadow-sm transition-colors" title="Riwayat Transaksi">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <span>Riwayat</span>
                    </a>
                @endif
            </div>
        </div>
    </x-slot>

    <!-- Wireframe Aesthetic Main Container -->
    <div class="bg-[#D9D9D9] dark:bg-gray-900 min-h-[calc(100vh-4rem)] p-4 sm:p-6 lg:p-8 flex flex-col lg:flex-row gap-6 transition-colors duration-300">
        
        <!-- Product Section (Left Side) -->
        <div class="flex-1 flex flex-col min-w-0">
            
            @if(!$activeShift)
            <!-- Banner Peringatan Shift Belum Dibuka -->
            <div class="mb-5 bg-gradient-to-r from-amber-500 to-orange-600 text-white p-4 sm:p-5 rounded-2xl shadow-md flex flex-col sm:flex-row sm:items-center justify-between gap-3 animate-fadeIn">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-white/20 backdrop-blur-md flex items-center justify-center font-bold text-xl flex-shrink-0">
                        ⚠️
                    </div>
                    <div>
                        <h4 class="font-extrabold text-sm sm:text-base text-white">Shift Kasir Belum Dibuka Hari Ini</h4>
                        <p class="text-xs text-amber-100 mt-0.5">Harap masukkan modal awal uang kembalian di laci kasir agar semua transaksi tunai tercatat ke rekap shift secara akurat.</p>
                    </div>
                </div>
                <button type="button" 
                        onclick="openModalBukaShiftPos()"
                        class="px-4 py-2.5 bg-white text-gray-900 font-black rounded-xl text-xs shadow hover:bg-amber-50 transition-all self-start sm:self-auto whitespace-nowrap active:scale-95">
                    🔓 Buka Shift Sekarang
                </button>
            </div>
            @endif
            
            <!-- Flash Message Banner -->
            @if(session('success'))
                <div class="mb-5 bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-300 dark:border-emerald-700 text-emerald-800 dark:text-emerald-200 px-5 py-4 rounded-2xl shadow-sm flex flex-col sm:flex-row sm:items-center justify-between gap-3 animate-fadeIn">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-xl bg-emerald-500 text-white flex items-center justify-center font-bold flex-shrink-0 shadow-sm">
                            ✓
                        </div>
                        <div>
                            <p class="font-bold text-sm">{{ session('success') }}</p>
                            <p class="text-xs text-emerald-600 dark:text-emerald-400 mt-0.5">Stok produk otomatis terpotong dari etalase.</p>
                        </div>
                    </div>
                    @if(session('cetak_id'))
                        <a href="{{ route('kasir.cetak', session('cetak_id')) }}" target="_blank" class="inline-flex items-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-2 px-4 rounded-xl shadow transition-all text-xs tracking-wide flex-shrink-0 self-start sm:self-auto">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                            🖨️ Cetak Struk Transaksi Ini
                        </a>
                    @endif
                </div>
            @endif

            @if(session('error'))
                <div class="mb-5 bg-red-50 dark:bg-red-950/40 border border-red-300 dark:border-red-700 text-red-800 dark:text-red-200 px-5 py-4 rounded-2xl shadow-sm flex items-center gap-3">
                    <svg class="w-6 h-6 text-red-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <p class="text-sm font-semibold">{{ session('error') }}</p>
                </div>
            @endif

            <!-- Search & Filter Controls -->
            <div class="flex flex-col sm:flex-row gap-3 mb-6">
                <!-- Search Box matching wireframe -->
                <div class="relative flex-1">
                    <input type="text" 
                           id="searchInput" 
                           placeholder="Cari produk atau menu..." 
                           class="w-full pl-12 pr-4 py-3.5 rounded-2xl border-none shadow-sm focus:ring-2 focus:ring-[#D19D9D] dark:focus:ring-indigo-500 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 text-sm font-medium transition-colors outline-none" 
                           onkeyup="filterProducts()">
                    <div class="absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400 dark:text-gray-500">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                </div>

                <!-- Category Pills Filter -->
                @php
                    $uniqueCategories = $produks->pluck('kategori')->filter()->unique('id_kategori');
                @endphp
                @if($uniqueCategories->count() > 0)
                <div class="flex items-center gap-1.5 overflow-x-auto pb-1 sm:pb-0 custom-scrollbar">
                    <button type="button" 
                            onclick="filterByCategory('all')" 
                            id="cat-btn-all"
                            class="category-filter-btn px-4 py-3 rounded-2xl text-xs font-bold bg-[#D19D9D] dark:bg-indigo-600 text-gray-900 dark:text-white shadow-sm transition-all whitespace-nowrap active-category">
                        Semua Menu
                    </button>
                    @foreach($uniqueCategories as $cat)
                    <button type="button" 
                            onclick="filterByCategory('{{ $cat->id_kategori }}')" 
                            id="cat-btn-{{ $cat->id_kategori }}"
                            class="category-filter-btn px-4 py-3 rounded-2xl text-xs font-bold bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 shadow-sm transition-all whitespace-nowrap">
                        {{ $cat->nama_kategori }}
                    </button>
                    @endforeach
                </div>
                @endif
            </div>

            <!-- Product Grid -->
            <div class="flex-1 overflow-y-auto pr-1 custom-scrollbar">
                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-3 xl:grid-cols-4 2xl:grid-cols-5 gap-4 sm:gap-5 pb-16" id="productGrid">
                    @forelse($produks as $item)
                    @php
                        $activePromo = $item->diskonPromos->first();
                        $discountPercent = $activePromo ? $activePromo->persentase : 0;
                        $discountAmount = $item->harga_dasar * ($discountPercent / 100);
                        $finalPrice = $item->harga_dasar - $discountAmount;
                    @endphp
                    <div class="product-card group flex flex-col items-center bg-transparent" 
                         data-name="{{ strtolower($item->nama_produk) }}"
                         data-category="{{ $item->kategori->id_kategori ?? 'uncategorized' }}">
                        
                        <!-- Image Box matching wireframe -->
                        <div class="bg-white dark:bg-gray-800 w-full aspect-square rounded-2xl shadow-sm hover:shadow-md transition-all duration-300 flex items-center justify-center relative overflow-hidden mb-2.5 border-2 border-transparent hover:border-[#D19D9D] dark:hover:border-indigo-500 cursor-pointer" 
                             onclick="addToCart({{ $item->id_produk }}, '{{ addslashes($item->nama_produk) }}', {{ $item->harga_dasar }}, {{ $item->stok_etalase }}, {{ $discountPercent }})">
                            
                            @if($activePromo)
                                <div class="absolute top-2.5 right-2.5 bg-red-500 text-white text-[11px] font-extrabold px-2 py-0.5 rounded-full shadow z-10 animate-pulse">
                                    -{{ $discountPercent }}%
                                </div>
                            @endif
                            
                            <!-- Stylized Food/Beverage Icon -->
                            <div class="w-16 h-16 rounded-2xl bg-gray-50 dark:bg-gray-700/60 flex items-center justify-center text-gray-400 dark:text-gray-500 group-hover:text-[#B06E6E] dark:group-hover:text-indigo-400 group-hover:scale-110 transition-all duration-300">
                                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                </svg>
                            </div>
                            
                            <!-- Hover Overlay for Stok -->
                            <div class="absolute inset-0 bg-black/50 backdrop-blur-[1px] flex flex-col items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-200">
                                <span class="text-white font-bold bg-black/60 px-3 py-1 rounded-full text-xs shadow">
                                    Stok: {{ $item->stok_etalase }}
                                </span>
                                <span class="text-white/80 text-[11px] mt-1 font-medium">Klik untuk tambah</span>
                            </div>
                        </div>

                        <!-- Title & Price -->
                        <div class="text-center w-full px-1">
                            <h4 class="font-bold text-gray-900 dark:text-gray-100 text-sm group-hover:text-[#B06E6E] dark:group-hover:text-indigo-300 transition-colors truncate" title="{{ $item->nama_produk }}">
                                {{ $item->nama_produk }}
                            </h4>
                            <div class="text-xs mt-0.5">
                                @if($activePromo)
                                    <span class="text-gray-400 dark:text-gray-500 line-through mr-1 text-[11px]">Rp{{ number_format($item->harga_dasar, 0, ',', '.') }}</span>
                                @endif
                                <span class="font-bold text-gray-700 dark:text-gray-300">Rp{{ number_format($finalPrice, 0, ',', '.') }}</span>
                            </div>

                            <!-- Grid Quantity Inline Controls -->
                            <div id="grid-controls-{{ $item->id_produk }}" class="hidden items-center justify-center space-x-2 mt-2">
                                <button type="button" 
                                        class="w-7 h-7 rounded-lg bg-red-100 dark:bg-red-900/40 text-red-600 dark:text-red-400 hover:bg-red-200 flex items-center justify-center transition-colors shadow-sm" 
                                        onclick="event.stopPropagation(); window.removeItem({{ $item->id_produk }})" 
                                        title="Hapus Dari Keranjang">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                                <button type="button" 
                                        class="w-7 h-7 rounded-lg bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-200 hover:bg-gray-300 flex items-center justify-center font-bold text-sm transition-colors shadow-sm" 
                                        onclick="event.stopPropagation(); window.updateQty({{ $item->id_produk }}, -1)">
                                    -
                                </button>
                                <span class="text-xs font-bold text-gray-900 dark:text-white w-5 text-center select-none" id="grid-qty-{{ $item->id_produk }}">
                                    0
                                </span>
                                <button type="button" 
                                        class="w-7 h-7 rounded-lg bg-emerald-100 dark:bg-emerald-900/40 text-emerald-700 dark:text-emerald-300 hover:bg-emerald-200 flex items-center justify-center font-bold text-sm shadow-sm transition-colors" 
                                        onclick="event.stopPropagation(); addToCart({{ $item->id_produk }}, '{{ addslashes($item->nama_produk) }}', {{ $item->harga_dasar }}, {{ $item->stok_etalase }}, {{ $discountPercent }})">
                                    +
                                </button>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="col-span-full text-center text-gray-500 dark:text-gray-400 py-16 bg-white dark:bg-gray-800 rounded-3xl shadow-sm">
                        <svg class="w-12 h-12 mx-auto text-gray-300 dark:text-gray-600 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                        </svg>
                        <p class="text-base font-bold text-gray-700 dark:text-gray-300">Tidak ada produk dengan stok tersedia di etalase.</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Lakukan restock atau tambahkan produk baru di katalog.</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Cart Section (Right Side Panel - Exact Wireframe Model) -->
        <div class="w-full lg:w-[380px] xl:w-[420px] bg-white dark:bg-gray-800 rounded-3xl shadow-xl flex flex-col p-6 xl:p-8 transition-colors duration-300 shrink-0 self-start lg:sticky lg:top-6">
            <form action="{{ route('kasir.store') }}" method="POST" id="formKasir" class="flex flex-col">
                @csrf
                
                <!-- Rincian Produk Header with wireframe dark underline -->
                <div class="mb-3 flex justify-between items-end border-b-2 border-gray-900 dark:border-gray-700 pb-2 w-full">
                    <h3 class="text-lg font-extrabold text-gray-900 dark:text-white tracking-tight">Rincian Produk</h3>
                    <button type="button" class="text-xs font-bold text-red-500 hover:text-red-700 transition-colors" onclick="window.clearCart()">
                        Kosongkan
                    </button>
                </div>

                <!-- Cart Items Container -->
                <div class="max-h-[220px] overflow-y-auto pr-1 custom-scrollbar space-y-2.5 my-2" id="cart-items">
                    <!-- Default empty cart state -->
                    <div class="flex flex-col items-center justify-center py-8 opacity-60">
                        <svg class="w-10 h-10 text-gray-400 dark:text-gray-500 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                        <p class="text-gray-500 dark:text-gray-400 font-medium text-xs text-center">Keranjang masih kosong</p>
                    </div>
                </div>

                <!-- Customer Section (Daya Pelanggan) -->
                <div class="mt-3 mb-2" x-data="{
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
                    <div class="flex items-center justify-between border-b-2 border-gray-900 dark:border-gray-700 pb-1.5 mb-2.5">
                        <h3 class="text-sm font-extrabold text-gray-900 dark:text-white tracking-tight flex items-center gap-1.5">
                            <span>👤</span> Pelanggan <span class="text-[11px] font-normal text-gray-400 dark:text-gray-400">(Opsional)</span>
                        </h3>
                        <template x-if="selectedTier">
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-black"
                                  :class="selectedTier === 'VIP' ? 'bg-amber-100 text-amber-800 dark:bg-amber-900/40 dark:text-amber-300' : 'bg-blue-100 text-blue-800 dark:bg-blue-900/40 dark:text-blue-300'"
                                  x-text="selectedTier === 'VIP' ? '⭐ VIP' : selectedTier">
                            </span>
                        </template>
                    </div>

                    <div class="space-y-2">
                        <div>
                            <input type="text" 
                                   name="nama_pelanggan" 
                                   list="pelanggan_datalist" 
                                   x-model="selectedName"
                                   @input="onCustomerSelect($event.target.value)"
                                   placeholder="Nama pelanggan / cari riwayat..." 
                                   class="w-full px-3 py-1.5 text-xs font-semibold rounded-lg border border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-gray-800 dark:text-gray-200 outline-none focus:ring-1 focus:ring-[#D19D9D] dark:focus:ring-indigo-500">
                            <datalist id="pelanggan_datalist">
                                @foreach($pelanggans as $cust)
                                    <option value="{{ $cust->nama_pelanggan }}">{{ $cust->nama_pelanggan }} ({{ $cust->tipe_pelanggan }} - {{ $cust->no_hp ?? 'No WA -' }})</option>
                                @endforeach
                            </datalist>
                        </div>

                        <div class="grid grid-cols-2 gap-2">
                            <input type="text" 
                                   name="no_hp_pelanggan" 
                                   x-model="selectedPhone"
                                   placeholder="WhatsApp (08...)" 
                                   class="w-full px-2.5 py-1.5 text-xs font-semibold rounded-lg border border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-gray-800 dark:text-gray-200 outline-none focus:ring-1 focus:ring-[#D19D9D] dark:focus:ring-indigo-500">
                            <input type="text" 
                                   name="catatan_pesanan" 
                                   placeholder="Catatan pesanan..." 
                                   class="w-full px-2.5 py-1.5 text-xs font-semibold rounded-lg border border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-gray-800 dark:text-gray-200 outline-none focus:ring-1 focus:ring-[#D19D9D] dark:focus:ring-indigo-500">
                        </div>
                    </div>
                </div>

                <!-- Jenis Pembayaran Section with wireframe dark underline -->
                <div class="mt-2 mb-3">
                    <h3 class="text-base font-extrabold text-gray-900 dark:text-white border-b-2 border-gray-900 dark:border-gray-700 pb-1.5 mb-2.5 inline-block w-full tracking-tight">
                        Jenis Pembayaran
                    </h3>
                    
                    <div class="space-y-2.5">
                        <div class="flex justify-between items-center text-sm font-bold">
                            <span class="text-gray-800 dark:text-gray-200">Jenis Pesanan</span>
                            <select name="jenis_pesanan" class="bg-gray-50 dark:bg-gray-700 text-gray-700 dark:text-gray-200 font-bold text-right rounded-lg border border-gray-200 dark:border-gray-600 py-1 px-2.5 text-xs focus:ring-1 focus:ring-[#D19D9D] dark:focus:ring-indigo-500 cursor-pointer outline-none">
                                <option value="Offline">Offline (Dine-in / Takeaway)</option>
                                <option value="Whatsapp">WhatsApp (Online Delivery)</option>
                            </select>
                        </div>
                        <div class="flex justify-between items-center text-sm font-bold">
                            <span class="text-gray-800 dark:text-gray-200">Metode Bayar</span>
                            <select name="metode_pembayaran" id="metode_pembayaran" onchange="handlePaymentMethodChange()" class="bg-gray-50 dark:bg-gray-700 text-gray-700 dark:text-gray-200 font-bold text-right rounded-lg border border-gray-200 dark:border-gray-600 py-1 px-2.5 text-xs focus:ring-1 focus:ring-[#D19D9D] dark:focus:ring-indigo-500 cursor-pointer outline-none">
                                <option value="Tunai">Tunai (Cash)</option>
                                <option value="Transfer">Transfer / QRIS</option>
                            </select>
                        </div>

                        <!-- Cashier Payment Amount Calculator (Tunai) -->
                        <div id="cash-calculator-box" class="pt-2 border-t border-gray-100 dark:border-gray-700/60 space-y-2">
                            <div class="flex justify-between items-center text-xs font-bold">
                                <span class="text-gray-600 dark:text-gray-400">Uang Diterima</span>
                                <div class="relative w-36">
                                    <span class="absolute left-2.5 top-1/2 -translate-y-1/2 text-gray-400 font-bold text-xs">Rp</span>
                                    <input type="number" 
                                           id="uangDiterimaInput" 
                                           placeholder="0" 
                                           class="w-full pl-8 pr-2 py-1 text-right text-xs font-bold rounded-lg border border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-gray-800 dark:text-gray-200 outline-none focus:ring-1 focus:ring-[#D19D9D] dark:focus:ring-indigo-500"
                                           oninput="calculateChange()">
                                </div>
                            </div>

                            <!-- Quick Cash Pills -->
                            <div class="flex items-center justify-end gap-1 flex-wrap">
                                <button type="button" onclick="setQuickCash('pas')" class="text-[10px] font-bold px-2 py-0.5 rounded bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-200">Uang Pas</button>
                                <button type="button" onclick="setQuickCash(20000)" class="text-[10px] font-bold px-2 py-0.5 rounded bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-200">20rb</button>
                                <button type="button" onclick="setQuickCash(50000)" class="text-[10px] font-bold px-2 py-0.5 rounded bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-200">50rb</button>
                                <button type="button" onclick="setQuickCash(100000)" class="text-[10px] font-bold px-2 py-0.5 rounded bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-200">100rb</button>
                            </div>

                            <div class="flex justify-between items-center text-xs font-bold pt-1">
                                <span class="text-gray-600 dark:text-gray-400">Kembalian</span>
                                <span class="font-extrabold text-gray-800 dark:text-gray-200" id="display-kembalian">Rp 0</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Subtotal Section with wireframe dark underline -->
                <div class="mt-2 mb-5">
                    <h3 class="text-lg font-extrabold text-gray-900 dark:text-white border-b-2 border-gray-900 dark:border-gray-700 pb-2 mb-3 inline-block w-full tracking-tight">
                        Subtotal
                    </h3>
                    <div class="space-y-1.5">
                        <div class="flex justify-between items-center text-xs font-bold">
                            <span class="text-gray-600 dark:text-gray-400">Total Potongan Diskon</span>
                            <span class="text-emerald-600 dark:text-emerald-400 font-bold" id="total-diskon">Rp 0</span>
                        </div>
                        <div class="flex justify-between items-center text-base font-bold pt-1">
                            <span class="text-gray-900 dark:text-white">Total Bayar</span>
                            <span class="text-[#B06E6E] dark:text-indigo-400 font-extrabold text-xl" id="total-display">Rp 0</span>
                        </div>
                    </div>
                </div>

                <!-- Wireframe Dusty Rose / Dark Indigo Checkout Button -->
                <button type="submit" 
                        class="w-full bg-[#D19D9D] hover:bg-[#C48686] active:bg-[#B06E6E] dark:bg-indigo-600 dark:hover:bg-indigo-500 dark:active:bg-indigo-700 text-gray-900 dark:text-white font-extrabold py-3.5 px-6 rounded-2xl shadow-md hover:shadow-lg transition-all opacity-50 cursor-not-allowed text-base tracking-wide border border-transparent" 
                        id="btn-checkout" 
                        disabled>
                    Proses Pembayaran
                </button>
            </form>
        </div>
    </div>

    <!-- Pos & Cart Scripts -->
    <script>
        // Real-time Clock (matches header format)
        function updatePosClock() {
            const now = new Date();
            const days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
            const months = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
            
            const dayName = days[now.getDay()];
            const dayNum = now.getDate();
            const month = months[now.getMonth()];
            const year = now.getFullYear();
            
            let hours = String(now.getHours()).padStart(2, '0');
            let minutes = String(now.getMinutes()).padStart(2, '0');
            let seconds = String(now.getSeconds()).padStart(2, '0');
            
            const clockEl = document.getElementById('pos-realtime-clock');
            if (clockEl) {
                clockEl.innerText = `${dayName}, ${dayNum} ${month} ${year} • ${hours}:${minutes}:${seconds} WIB`;
            }
        }
        setInterval(updatePosClock, 1000);
        updatePosClock();

        // Search & Category Filter
        let currentCategory = 'all';

        function filterProducts() {
            let input = document.getElementById('searchInput').value.toLowerCase();
            let cards = document.getElementsByClassName('product-card');
            
            for (let i = 0; i < cards.length; i++) {
                let name = cards[i].getAttribute('data-name');
                let cat = cards[i].getAttribute('data-category');
                
                let matchesSearch = name.includes(input);
                let matchesCat = (currentCategory === 'all' || cat === currentCategory);

                if (matchesSearch && matchesCat) {
                    cards[i].style.display = "";
                } else {
                    cards[i].style.display = "none";
                }
            }
        }

        function filterByCategory(categoryId) {
            currentCategory = categoryId;
            
            // Update active pill styling
            document.querySelectorAll('.category-filter-btn').forEach(btn => {
                btn.classList.remove('bg-[#D19D9D]', 'dark:bg-indigo-600', 'text-gray-900', 'dark:text-white', 'active-category');
                btn.classList.add('bg-white', 'dark:bg-gray-800', 'text-gray-700', 'dark:text-gray-300');
            });

            const activeBtn = document.getElementById('cat-btn-' + categoryId);
            if (activeBtn) {
                activeBtn.classList.remove('bg-white', 'dark:bg-gray-800', 'text-gray-700', 'dark:text-gray-300');
                activeBtn.classList.add('bg-[#D19D9D]', 'dark:bg-indigo-600', 'text-gray-900', 'dark:text-white', 'active-category');
            }

            filterProducts();
        }

        // Cart State & Logic
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
                cart[id] = {
                    id: id,
                    name: name,
                    price: price,
                    qty: 1,
                    maxStock: maxStock,
                    discountPercent: discountPercent
                };
            }
            renderCart();
        }

        window.updateQty = function(id, change) {
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

        window.removeItem = function(id) {
            id = parseInt(id);
            if (cart[id]) {
                delete cart[id];
                renderCart();
            }
        };

        window.clearCart = function() {
            if (Object.keys(cart).length > 0) {
                cart = {};
                renderCart();
            }
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
                if(cart.hasOwnProperty(key)) {
                    const item = cart[key];
                    const discountAmount = item.price * (item.discountPercent / 100);
                    const finalPrice = item.price - discountAmount;
                    const subtotal = finalPrice * item.qty;
                    const totalItemDiscount = discountAmount * item.qty;
                    
                    total += subtotal;
                    totalDiscountVal += totalItemDiscount;
                    itemCount++;

                    const row = document.createElement('div');
                    row.className = 'flex justify-between items-center bg-gray-50 dark:bg-gray-700/50 p-2.5 rounded-xl border border-gray-100 dark:border-gray-700/60 transition-colors';
                    
                    row.innerHTML = `
                        <div class="flex-1 min-w-0 pr-2">
                            <div class="text-xs font-bold text-gray-900 dark:text-gray-100 truncate">${item.name}</div>
                            <div class="text-[11px] text-gray-500 dark:text-gray-400 mt-0.5">
                                Rp${Math.round(finalPrice).toLocaleString('id-ID')} × ${item.qty}
                            </div>
                            
                            <!-- Hidden inputs for backend form processing -->
                            <input type="hidden" name="id_produk[]" value="${item.id}">
                            <input type="hidden" name="qty_beli[]" value="${item.qty}">
                            
                            <div class="flex items-center space-x-1.5 mt-1.5">
                                <button type="button" class="w-6 h-6 rounded bg-gray-200 dark:bg-gray-600 text-gray-700 dark:text-gray-200 hover:bg-gray-300 flex items-center justify-center font-bold text-xs transition-colors" onclick="window.updateQty(${item.id}, -1)">-</button>
                                <span class="text-xs font-bold text-gray-800 dark:text-gray-200 w-5 text-center select-none">${item.qty}</span>
                                <button type="button" class="w-6 h-6 rounded bg-emerald-100 dark:bg-emerald-900/40 text-emerald-700 dark:text-emerald-300 hover:bg-emerald-200 flex items-center justify-center font-bold text-xs transition-colors" onclick="window.updateQty(${item.id}, 1)">+</button>
                            </div>
                        </div>
                        <div class="text-right whitespace-nowrap flex flex-col justify-between items-end h-full py-0.5">
                            <div class="text-xs font-bold text-gray-700 dark:text-gray-300">
                                Rp${Math.round(subtotal).toLocaleString('id-ID')}
                            </div>
                            <button type="button" class="text-red-400 hover:text-red-600 dark:text-red-400 dark:hover:text-red-300 mt-2 transition-colors p-1" onclick="window.removeItem(${item.id})" title="Hapus">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            </button>
                        </div>
                    `;
                    container.appendChild(row);
                }
            }

            // Sync grid inline controls
            document.querySelectorAll('[id^="grid-controls-"]').forEach(el => {
                el.classList.add('hidden');
                el.classList.remove('flex');
            });
            for (const key in cart) {
                if(cart.hasOwnProperty(key)) {
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
                container.innerHTML = `
                    <div class="flex flex-col items-center justify-center py-8 opacity-60">
                        <svg class="w-10 h-10 text-gray-400 dark:text-gray-500 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                        <p class="text-gray-500 dark:text-gray-400 font-medium text-xs text-center">Keranjang masih kosong</p>
                    </div>
                `;
                btnCheckout.disabled = true;
                btnCheckout.classList.add('opacity-50', 'cursor-not-allowed');
                btnCheckout.classList.remove('hover:bg-[#C48686]', 'dark:hover:bg-[#955454]', 'active:scale-95');
            } else {
                btnCheckout.disabled = false;
                btnCheckout.classList.remove('opacity-50', 'cursor-not-allowed');
                btnCheckout.classList.add('hover:bg-[#C48686]', 'dark:hover:bg-[#955454]', 'active:scale-95');
            }
        }

        // Cash Calculator Functions
        function handlePaymentMethodChange() {
            const method = document.getElementById('metode_pembayaran').value;
            const cashBox = document.getElementById('cash-calculator-box');
            if (method === 'Tunai') {
                cashBox.style.display = 'block';
            } else {
                cashBox.style.display = 'none';
            }
        }

        function calculateChange() {
            const inputVal = parseFloat(document.getElementById('uangDiterimaInput')?.value || 0);
            const changeDisplay = document.getElementById('display-kembalian');
            if (!changeDisplay) return;

            if (inputVal > 0 && currentGrandTotal > 0) {
                const change = inputVal - currentGrandTotal;
                if (change >= 0) {
                    changeDisplay.className = 'font-extrabold text-emerald-600 dark:text-emerald-400';
                    changeDisplay.innerText = 'Rp ' + Math.round(change).toLocaleString('id-ID');
                } else {
                    changeDisplay.className = 'font-bold text-red-500';
                    changeDisplay.innerText = 'Kurang Rp ' + Math.round(Math.abs(change)).toLocaleString('id-ID');
                }
            } else {
                changeDisplay.className = 'font-extrabold text-gray-800 dark:text-gray-200';
                changeDisplay.innerText = 'Rp 0';
            }
        }

        function setQuickCash(amount) {
            const input = document.getElementById('uangDiterimaInput');
            if (!input) return;
            if (amount === 'pas') {
                input.value = currentGrandTotal;
            } else {
                input.value = amount;
            }
            calculateChange();
        }

        // Shift Kasir POS Modals
        let currentTargetKasSistemPos = 0;

        function openModalBukaShiftPos() {
            document.getElementById('modalBukaShiftPos').classList.remove('hidden');
        }

        function closeModalBukaShiftPos() {
            document.getElementById('modalBukaShiftPos').classList.add('hidden');
        }

        function setModalAwalPos(val) {
            document.getElementById('modal_awal_pos_input').value = val;
        }

        function openModalTutupShiftPos(shiftId, modalAwal, penjualanTunai, kasSistem) {
            currentTargetKasSistemPos = kasSistem;
            document.getElementById('titleTutupShiftPos').innerText = `Tutup Shift Kasir #${shiftId}`;
            document.getElementById('tutupModalAwalPos').innerText = `Rp ${Number(modalAwal).toLocaleString('id-ID')}`;
            document.getElementById('tutupPenjualanTunaiPos').innerText = `Rp ${Number(penjualanTunai).toLocaleString('id-ID')}`;
            document.getElementById('tutupKasSistemPos').innerText = `Rp ${Number(kasSistem).toLocaleString('id-ID')}`;
            
            document.getElementById('formTutupShiftPos').action = `/shift/${shiftId}/tutup`;
            document.getElementById('kas_akhir_fisik_pos_input').value = '';
            
            hitungSelisihLivePos();
            document.getElementById('modalTutupShiftPos').classList.remove('hidden');
        }

        function closeModalTutupShiftPos() {
            document.getElementById('modalTutupShiftPos').classList.add('hidden');
        }

        function hitungSelisihLivePos() {
            const inputVal = document.getElementById('kas_akhir_fisik_pos_input').value;
            const box = document.getElementById('boxSelisihPos');
            const text = document.getElementById('textSelisihPos');

            if (inputVal === '' || isNaN(inputVal)) {
                box.className = "p-3.5 rounded-xl border transition-all text-xs flex items-center justify-between font-bold bg-gray-50 dark:bg-gray-700 border-gray-200 dark:border-gray-600 text-gray-500 dark:text-gray-400";
                text.innerText = "Masukkan nominal kas fisik";
                return;
            }

            const fisik = parseFloat(inputVal);
            const selisih = fisik - currentTargetKasSistemPos;

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

    <!-- MODAL BUKA SHIFT KASIR (POS) -->
    <div id="modalBukaShiftPos" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity" onclick="closeModalBukaShiftPos()"></div>
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
                            <button type="button" onclick="closeModalBukaShiftPos()" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
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
                                           id="modal_awal_pos_input"
                                           min="0" 
                                           step="500" 
                                           value="100000"
                                           required
                                           class="w-full pl-11 pr-4 py-3 rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white font-extrabold text-lg focus:ring-2 focus:ring-indigo-500">
                                </div>
                                <div class="flex items-center gap-2 mt-2 flex-wrap">
                                    <span class="text-[11px] text-gray-400">Pilihan cepat:</span>
                                    <button type="button" onclick="setModalAwalPos(50000)" class="px-2.5 py-1 rounded-lg bg-gray-100 dark:bg-gray-700 hover:bg-indigo-50 text-[11px] font-bold text-gray-700 dark:text-gray-300 transition-colors">50k</button>
                                    <button type="button" onclick="setModalAwalPos(100000)" class="px-2.5 py-1 rounded-lg bg-gray-100 dark:bg-gray-700 hover:bg-indigo-50 text-[11px] font-bold text-gray-700 dark:text-gray-300 transition-colors">100k</button>
                                    <button type="button" onclick="setModalAwalPos(200000)" class="px-2.5 py-1 rounded-lg bg-gray-100 dark:bg-gray-700 hover:bg-indigo-50 text-[11px] font-bold text-gray-700 dark:text-gray-300 transition-colors">200k</button>
                                    <button type="button" onclick="setModalAwalPos(500000)" class="px-2.5 py-1 rounded-lg bg-gray-100 dark:bg-gray-700 hover:bg-indigo-50 text-[11px] font-bold text-gray-700 dark:text-gray-300 transition-colors">500k</button>
                                </div>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-1.5">
                                    Catatan / Keterangan (Opsional)
                                </label>
                                <textarea name="catatan" 
                                          rows="2" 
                                          placeholder="Contoh: Modal pecahan kecil 2.000 (10 lembar), 5.000 (10 lembar)..."
                                          class="w-full px-3.5 py-2.5 rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white text-xs focus:ring-2 focus:ring-indigo-500"></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="px-6 py-4 bg-gray-50 dark:bg-gray-700/50 flex items-center justify-end gap-3 border-t border-gray-100 dark:border-gray-700">
                        <button type="button" onclick="closeModalBukaShiftPos()" class="px-4 py-2.5 text-xs font-bold text-gray-600 dark:text-gray-400 hover:text-gray-800">
                            Batal
                        </button>
                        <button type="submit" class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-extrabold shadow transition-all active:scale-95">
                            Buka Shift Kasir
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- MODAL TUTUP SHIFT KASIR (POS) -->
    <div id="modalTutupShiftPos" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity" onclick="closeModalTutupShiftPos()"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
            <div class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-3xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border border-gray-100 dark:border-gray-700">
                <form id="formTutupShiftPos" method="POST" action="">
                    @csrf
                    <div class="p-6">
                        <div class="flex items-center justify-between pb-4 border-b border-gray-100 dark:border-gray-700">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-2xl bg-rose-50 dark:bg-rose-950/60 text-rose-600 dark:text-rose-400 flex items-center justify-center text-xl font-bold">
                                    🔒
                                </div>
                                <div>
                                    <h3 class="text-base font-extrabold text-gray-900 dark:text-white" id="titleTutupShiftPos">Tutup Shift Kasir</h3>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">Rekap uang kas laci dan hitung selisih fisik.</p>
                                </div>
                            </div>
                            <button type="button" onclick="closeModalTutupShiftPos()" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                            </button>
                        </div>

                        <!-- Ringkasan Kas Sistem -->
                        <div class="mt-4 p-4 rounded-2xl bg-gray-50 dark:bg-gray-700/40 border border-gray-100 dark:border-gray-700 space-y-2 text-xs">
                            <div class="flex justify-between items-center text-gray-600 dark:text-gray-400">
                                <span>Modal Awal Kasir:</span>
                                <span class="font-bold text-gray-900 dark:text-white" id="tutupModalAwalPos">Rp 0</span>
                            </div>
                            <div class="flex justify-between items-center text-gray-600 dark:text-gray-400">
                                <span>(+) Penjualan Tunai:</span>
                                <span class="font-bold text-emerald-600 dark:text-emerald-400" id="tutupPenjualanTunaiPos">Rp 0</span>
                            </div>
                            <div class="pt-2 border-t border-gray-200 dark:border-gray-600 flex justify-between items-center text-sm font-extrabold">
                                <span class="text-gray-900 dark:text-white">Uang Seharusnya di Laci:</span>
                                <span class="text-indigo-600 dark:text-indigo-400 font-black text-base" id="tutupKasSistemPos">Rp 0</span>
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
                                           id="kas_akhir_fisik_pos_input"
                                           min="0" 
                                           step="500" 
                                           oninput="hitungSelisihLivePos()"
                                           required
                                           placeholder="0"
                                           class="w-full pl-11 pr-4 py-3 rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white font-black text-lg focus:ring-2 focus:ring-rose-500">
                                </div>
                                <p class="text-[11px] text-gray-400 mt-1">Hitung semua uang tunai fisik di laci saat ini.</p>
                            </div>

                            <!-- Live Selisih Box -->
                            <div id="boxSelisihPos" class="p-3.5 rounded-xl border transition-all text-xs flex items-center justify-between font-bold bg-gray-50 border-gray-200 text-gray-600">
                                <span>Status Selisih Kas:</span>
                                <span id="textSelisihPos">Masukkan uang fisik di atas</span>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-1.5">
                                    Catatan Penutupan Shift (Opsional)
                                </label>
                                <textarea name="catatan" 
                                          rows="2" 
                                          placeholder="Keterangan jika ada selisih kas..."
                                          class="w-full px-3.5 py-2.5 rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white text-xs focus:ring-2 focus:ring-rose-500"></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="px-6 py-4 bg-gray-50 dark:bg-gray-700/50 flex items-center justify-end gap-3 border-t border-gray-100 dark:border-gray-700">
                        <button type="button" onclick="closeModalTutupShiftPos()" class="px-4 py-2.5 text-xs font-bold text-gray-600 dark:text-gray-400 hover:text-gray-800">
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
</x-app-layout>
