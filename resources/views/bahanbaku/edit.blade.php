<x-app-layout>
    <x-page title="Ubah data bahan baku" narrow>
        <x-panel>
            @include('bahanbaku._form', ['action' => route('bahanbaku.update', $bahanbaku->id_bahan), 'bahanbaku' => $bahanbaku])
        </x-panel>
    </x-page>
</x-app-layout>
