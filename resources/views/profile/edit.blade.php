<x-app-layout>
    <x-page title="Profil" sub="Kelola data akun, kata sandi, dan penghapusan akun." narrow>
        <x-panel title="Informasi profil" sub="Perbarui nama dan alamat email akun Anda.">
            @include('profile.partials.update-profile-information-form')
        </x-panel>

        <x-panel title="Ganti kata sandi" sub="Gunakan kata sandi yang panjang dan acak agar akun tetap aman.">
            @include('profile.partials.update-password-form')
        </x-panel>

        <x-panel title="Hapus akun" sub="Setelah dihapus, seluruh data akun hilang permanen.">
            @include('profile.partials.delete-user-form')
        </x-panel>
    </x-page>
</x-app-layout>
