<x-app-layout>
    <x-page title="Ubah akun karyawan" sub="Perbarui data, peran, dan hak akses akun ini." narrow>
        <x-panel>
            @include('karyawan._form', ['action' => route('karyawan.update', $karyawan->id), 'karyawan' => $karyawan])
        </x-panel>
    </x-page>
</x-app-layout>
