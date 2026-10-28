<x-guest-layout heading="Masuk" lede="Masuk untuk membuka kasir, dapur, atau stok bahan.">
    @if (session('status'))
        <p class="status">{{ session('status') }}</p>
    @endif

    {{-- Login cepat untuk debugging --}}
    <div class="debug">
        <span class="debug-title">Login cepat (debug):</span>
        <button type="button" id="role-btn-owner" onclick="selectRoleAccount('owner@kandekande.com', 'password', 'owner')">Owner</button>
        <button type="button" id="role-btn-kasir" onclick="selectRoleAccount('kasir@kandekande.com', 'password', 'kasir')">Kasir</button>
        <button type="button" id="role-btn-dapur" onclick="selectRoleAccount('dapur@kandekande.com', 'password', 'dapur')">Dapur</button>
        <button type="button" id="role-btn-stok" onclick="selectRoleAccount('gudang@kandekande.com', 'password', 'stok')">Admin Stok</button>
    </div>
    <p class="debug-info" id="role-info-text" hidden></p>

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div class="field">
            <label for="email">Email</label>
            <input id="email" class="input" type="email" name="email" value="{{ old('email') }}"
                   required autofocus autocomplete="username" placeholder="nama@kandekande.com"
                   @error('email') aria-invalid="true" @enderror>
            @error('email')<ul class="error"><li>{{ $message }}</li></ul>@enderror
        </div>

        <div class="field">
            <div class="field-row">
                <label for="password">Kata sandi</label>
                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}">Lupa kata sandi?</a>
                @endif
            </div>
            <input id="password" class="input" type="password" name="password"
                   required autocomplete="current-password"
                   @error('password') aria-invalid="true" @enderror>
            @error('password')<ul class="error"><li>{{ $message }}</li></ul>@enderror
        </div>

        <label class="check" for="remember_me">
            <input id="remember_me" type="checkbox" name="remember">
            Ingat saya di perangkat ini
        </label>

        <button type="submit" class="btn">Masuk <span>→</span></button>
    </form>

    <p class="alt">Akun kasir, dapur, dan gudang dibuat oleh Owner.</p>

    <script>
        function selectRoleAccount(email, password, roleKey) {
            document.getElementById('email').value = email;
            document.getElementById('password').value = password;

            document.querySelectorAll('.debug button').forEach(function (b) { b.classList.remove('is-active'); });
            var active = document.getElementById('role-btn-' + roleKey);
            if (active) active.classList.add('is-active');

            var labels = {
                owner: 'Owner: akses penuh analitik dan kelola karyawan',
                kasir: 'Kasir: langsung ke layar transaksi POS',
                dapur: 'Dapur: langsung ke antrean pesanan dapur',
                stok: 'Admin Stok: langsung ke inventaris bahan baku'
            };
            var info = document.getElementById('role-info-text');
            info.textContent = labels[roleKey] || '';
            info.hidden = false;
        }
    </script>
</x-guest-layout>
