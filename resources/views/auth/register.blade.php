<x-guest-layout heading="Buat akun" lede="Isi data di bawah untuk mendaftar.">
    <form method="POST" action="{{ route('register') }}">
        @csrf

        <div class="field">
            <label for="name">Nama</label>
            <input id="name" class="input" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name"
                   @error('name') aria-invalid="true" @enderror>
            @error('name')<ul class="error"><li>{{ $message }}</li></ul>@enderror
        </div>

        <div class="field">
            <label for="email">Email</label>
            <input id="email" class="input" type="email" name="email" value="{{ old('email') }}" required autocomplete="username"
                   @error('email') aria-invalid="true" @enderror>
            @error('email')<ul class="error"><li>{{ $message }}</li></ul>@enderror
        </div>

        <div class="field">
            <label for="password">Kata sandi</label>
            <input id="password" class="input" type="password" name="password" required autocomplete="new-password"
                   @error('password') aria-invalid="true" @enderror>
            @error('password')<ul class="error"><li>{{ $message }}</li></ul>@enderror
        </div>

        <div class="field">
            <label for="password_confirmation">Ulangi kata sandi</label>
            <input id="password_confirmation" class="input" type="password" name="password_confirmation" required autocomplete="new-password"
                   @error('password_confirmation') aria-invalid="true" @enderror>
            @error('password_confirmation')<ul class="error"><li>{{ $message }}</li></ul>@enderror
        </div>

        <button type="submit" class="btn">Daftar <span>→</span></button>
    </form>

    <p class="alt">Sudah punya akun? <a href="{{ route('login') }}">Masuk</a></p>
</x-guest-layout>
