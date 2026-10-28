<section class="mt-4">
    <form method="post" action="{{ route('password.update') }}" class="space-y-5">
        @csrf
        @method('put')

        <div class="fld">
            <label for="update_password_current_password">Kata sandi saat ini</label>
            <input id="update_password_current_password" name="current_password" type="password" class="inp" autocomplete="current-password">
            @foreach($errors->updatePassword->get('current_password') as $m)<p class="hint !text-red-600">{{ $m }}</p>@endforeach
        </div>
        <div class="fld">
            <label for="update_password_password">Kata sandi baru</label>
            <input id="update_password_password" name="password" type="password" class="inp" autocomplete="new-password">
            @foreach($errors->updatePassword->get('password') as $m)<p class="hint !text-red-600">{{ $m }}</p>@endforeach
        </div>
        <div class="fld">
            <label for="update_password_password_confirmation">Ulangi kata sandi baru</label>
            <input id="update_password_password_confirmation" name="password_confirmation" type="password" class="inp" autocomplete="new-password">
            @foreach($errors->updatePassword->get('password_confirmation') as $m)<p class="hint !text-red-600">{{ $m }}</p>@endforeach
        </div>

        <div class="flex items-center gap-4">
            <x-btn type="submit">Simpan</x-btn>
            @if (session('status') === 'password-updated')
                <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)" class="text-sm text-gray-500">Tersimpan.</p>
            @endif
        </div>
    </form>
</section>
