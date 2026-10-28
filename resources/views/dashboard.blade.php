<x-app-layout>
    <div class="py-8 sm:py-10">
        <div class="w-full px-4 sm:px-6 lg:px-8">
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
