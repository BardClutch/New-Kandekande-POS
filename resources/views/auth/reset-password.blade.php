<x-guest-layout heading="Kata sandi baru" lede="Pilih kata sandi baru untuk akunmu.">
    <form method="POST" action="{{ route('password.store') }}">
        @csrf
        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <div class="field">
            <label for="email">Email</label>
            <input id="email" class="input" type="email" name="email" value="{{ old('email', $request->email) }}"
                   required autofocus autocomplete="username"
                   @error('email') aria-invalid="true" @enderror>
            @error('email')<ul class="error"><li>{{ $message }}</li></ul>@enderror
        </div>

        <div class="field">
            <label for="password">Kata sandi baru</label>
            <input id="password" class="input" type="password" name="password" required autocomplete="new-password"
                   @error('password') aria-invalid="true" @enderror>
            @error('password')<ul class="error"><li>{{ $message }}</li></ul>@enderror
        </div>

        <div class="field">
            <label for="password_confirmation">Ulangi kata sandi baru</label>
            <input id="password_confirmation" class="input" type="password" name="password_confirmation" required autocomplete="new-password"
                   @error('password_confirmation') aria-invalid="true" @enderror>
            @error('password_confirmation')<ul class="error"><li>{{ $message }}</li></ul>@enderror
        </div>

        <button type="submit" class="btn">Simpan kata sandi <span>→</span></button>
    </form>
</x-guest-layout>
