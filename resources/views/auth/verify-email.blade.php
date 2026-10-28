<x-guest-layout heading="Cek emailmu" lede="Kami sudah mengirim tautan verifikasi ke email yang kamu daftarkan. Klik tautan itu untuk melanjutkan.">
    @if (session('status') == 'verification-link-sent')
        <p class="status">Tautan verifikasi baru sudah dikirim ke emailmu.</p>
    @endif

    <form method="POST" action="{{ route('verification.send') }}">
        @csrf
        <button type="submit" class="btn">Kirim ulang email verifikasi <span>→</span></button>
    </form>

    <form method="POST" action="{{ route('logout') }}" class="split">
        @csrf
        <button type="submit" class="btn btn-quiet">Keluar</button>
    </form>
</x-guest-layout>
