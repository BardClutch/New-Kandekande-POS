<section class="mt-4">
    <form id="send-verification" method="post" action="{{ route('verification.send') }}">@csrf</form>

    <form method="post" action="{{ route('profile.update') }}" class="space-y-5">
        @csrf
        @method('patch')

        <div class="fld">
            <label for="name">Nama</label>
            <input id="name" name="name" type="text" class="inp" value="{{ old('name', $user->name) }}" required autofocus autocomplete="name">
            @foreach($errors->get('name') as $m)<p class="hint !text-red-600">{{ $m }}</p>@endforeach
        </div>

        <div class="fld">
            <label for="email">Email</label>
            <input id="email" name="email" type="email" class="inp" value="{{ old('email', $user->email) }}" required autocomplete="username">
            @foreach($errors->get('email') as $m)<p class="hint !text-red-600">{{ $m }}</p>@endforeach

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <p class="hint">
                    Email Anda belum diverifikasi.
                    <button form="send-verification" class="font-semibold text-indigo-600 dark:text-indigo-400 hover:underline underline-offset-4">Kirim ulang email verifikasi</button>
                </p>
                @if (session('status') === 'verification-link-sent')
                    <p class="hint !text-emerald-600">Tautan verifikasi baru sudah dikirim.</p>
                @endif
            @endif
        </div>

        <div class="flex items-center gap-4">
            <x-btn type="submit">Simpan</x-btn>
            @if (session('status') === 'profile-updated')
                <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)" class="text-sm text-gray-500">Tersimpan.</p>
            @endif
        </div>
    </form>
</section>
