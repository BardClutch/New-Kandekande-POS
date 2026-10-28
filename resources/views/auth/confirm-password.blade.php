<x-guest-layout heading="Konfirmasi kata sandi" lede="Bagian ini sensitif. Masukkan kata sandimu sekali lagi untuk lanjut.">
    <form method="POST" action="{{ route('password.confirm') }}">
        @csrf

        <div class="field">
            <label for="password">Kata sandi</label>
            <input id="password" class="input" type="password" name="password" required autofocus autocomplete="current-password"
                   @error('password') aria-invalid="true" @enderror>
            @error('password')<ul class="error"><li>{{ $message }}</li></ul>@enderror
        </div>

        <button type="submit" class="btn">Konfirmasi <span>→</span></button>
    </form>
</x-guest-layout>
