<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-extrabold text-xl text-gray-800 dark:text-gray-200 leading-tight transition-colors duration-300">
                @if(($roleView ?? 'owner') === 'owner')
                    {{ __('Dashboard Analitik Bisnis') }}
                @elseif(($roleView ?? 'owner') === 'kasir')
                    {{ __('Dashboard Operasional Kasir') }}
                @elseif(($roleView ?? 'owner') === 'dapur')
                    {{ __('Dashboard Pusat Produksi Dapur') }}
                @elseif(($roleView ?? 'owner') === 'kustom')
                    {{ __('Dashboard Akses Kustom') }}
                @else
                    {{ __('Dashboard Logistik Gudang') }}
                @endif
            </h2>
            <div class="flex items-center gap-2">
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-indigo-50 dark:bg-indigo-900/40 text-indigo-600 dark:text-indigo-400 border border-indigo-100 dark:border-indigo-800">
                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                    {{ Auth::user()->role_label }}
                </span>
            </div>
        </div>
    </x-slot>

    <div class="py-8 sm:py-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            @if(($roleView ?? 'owner') === 'owner')
                @include('dashboard.owner')
            @elseif(($roleView ?? 'owner') === 'kasir')
                @include('dashboard.kasir')
            @elseif(($roleView ?? 'owner') === 'dapur')
                @include('dashboard.dapur')
            @elseif(($roleView ?? 'owner') === 'kustom')
                @include('dashboard.kustom')
            @else
                @include('dashboard.gudang')
            @endif
        </div>
    </div>
</x-app-layout>
