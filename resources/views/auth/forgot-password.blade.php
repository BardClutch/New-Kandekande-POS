<x-guest-layout heading="Lupa kata sandi" lede="Masukkan email akunmu. Kami kirim tautan untuk membuat kata sandi baru.">
    @if (session('status'))
        <p class="status">{{ session('status') }}</p>
    @endif

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <div class="field">
            <label for="email">Email</label>
            <input id="email" class="input" type="email" name="email" value="{{ old('email') }}"
                   required autofocus placeholder="nama@kandekande.com"
                   @error('email') aria-invalid="true" @enderror>
            @error('email')<ul class="error"><li>{{ $message }}</li></ul>@enderror
        </div>

        <button type="submit" class="btn">Kirim tautan reset <span>→</span></button>
    </form>

    <p class="alt"><a href="{{ route('login') }}">Kembali ke halaman masuk</a></p>
</x-guest-layout>
