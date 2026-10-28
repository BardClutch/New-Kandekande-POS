<x-app-layout>
    <x-page title="Daftarkan produk baru" narrow>
        <x-panel>
            @include('produk._form', ['action' => route('produk.store')])
        </x-panel>
    </x-page>
</x-app-layout>
