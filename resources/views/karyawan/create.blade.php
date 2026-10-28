<x-app-layout>
    <x-page title="Tambah akun karyawan" sub="Buat akun login baru dan tetapkan peran untuk staf." narrow>
        <x-panel>
            @include('karyawan._form', ['action' => route('karyawan.store')])
        </x-panel>
    </x-page>
</x-app-layout>
