<x-app-layout>
    <x-page title="Ubah produk" narrow>
        <x-panel>
            @include('produk._form', ['action' => route('produk.update', $produk->id_produk), 'produk' => $produk])
        </x-panel>
    </x-page>
</x-app-layout>
