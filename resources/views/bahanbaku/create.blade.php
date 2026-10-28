<x-app-layout>
    <x-page title="Catat bahan baku baru" narrow>
        <x-panel>
            @include('bahanbaku._form', ['action' => route('bahanbaku.store')])
        </x-panel>
    </x-page>
</x-app-layout>
