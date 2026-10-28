<x-guest-layout>
    <!-- Header Title -->
    <div class="text-center mb-6">
        <h2 class="text-2xl font-black text-gray-900 dark:text-white tracking-tight">
            Masuk ke Sistem POS
        </h2>
        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1 font-medium">
            Pilih jenis akun atau masukkan email & password Anda
        </p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <!-- Role Selection / Jenis Login Guide -->
    <div class="mb-6">
        <label class="block text-xs font-bold text-gray-600 dark:text-gray-300 uppercase tracking-wider mb-2.5">
            Pilih Jenis Akun (Login Cepat):
        </label>
        
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-2.5">
            <!-- 1. Owner -->
            <button type="button" 
                    onclick="selectRoleAccount('owner@kandekande.com', 'password', 'owner')"
                    id="role-btn-owner"
                    class="role-card flex flex-col items-center justify-center p-3 rounded-2xl border-2 border-transparent bg-amber-50 dark:bg-amber-950/30 text-amber-900 dark:text-amber-200 hover:border-amber-400 dark:hover:border-amber-500 transition-all text-center group shadow-sm">
                <span class="text-xl mb-1 group-hover:scale-110 transition-transform">👑</span>
                <span class="text-xs font-extrabold">Owner</span>
                <span class="text-[10px] text-amber-700 dark:text-amber-400 mt-0.5">Akses Penuh</span>
            </button>

            <!-- 2. Kasir -->
            <button type="button" 
                    onclick="selectRoleAccount('kasir@kandekande.com', 'password', 'kasir')"
                    id="role-btn-kasir"
                    class="role-card flex flex-col items-center justify-center p-3 rounded-2xl border-2 border-transparent bg-emerald-50 dark:bg-emerald-950/30 text-emerald-900 dark:text-emerald-200 hover:border-emerald-400 dark:hover:border-emerald-500 transition-all text-center group shadow-sm">
                <span class="text-xl mb-1 group-hover:scale-110 transition-transform">💼</span>
                <span class="text-xs font-extrabold">Kasir</span>
                <span class="text-[10px] text-emerald-700 dark:text-emerald-400 mt-0.5">Transaksi POS</span>
            </button>

            <!-- 3. Dapur -->
            <button type="button" 
                    onclick="selectRoleAccount('dapur@kandekande.com', 'password', 'dapur')"
                    id="role-btn-dapur"
                    class="role-card flex flex-col items-center justify-center p-3 rounded-2xl border-2 border-transparent bg-orange-50 dark:bg-orange-950/30 text-orange-900 dark:text-orange-200 hover:border-orange-400 dark:hover:border-orange-500 transition-all text-center group shadow-sm">
                <span class="text-xl mb-1 group-hover:scale-110 transition-transform">🍳</span>
                <span class="text-xs font-extrabold">Dapur</span>
                <span class="text-[10px] text-orange-700 dark:text-orange-400 mt-0.5">Pesanan Masak</span>
            </button>

            <!-- 4. Admin Stok -->
            <button type="button" 
                    onclick="selectRoleAccount('gudang@kandekande.com', 'password', 'stok')"
                    id="role-btn-stok"
                    class="role-card flex flex-col items-center justify-center p-3 rounded-2xl border-2 border-transparent bg-blue-50 dark:bg-blue-950/30 text-blue-900 dark:text-blue-200 hover:border-blue-400 dark:hover:border-blue-500 transition-all text-center group shadow-sm">
                <span class="text-xl mb-1 group-hover:scale-110 transition-transform">📦</span>
                <span class="text-xs font-extrabold">Admin Stok</span>
                <span class="text-[10px] text-blue-700 dark:text-blue-400 mt-0.5">Bahan & Resep</span>
            </button>
        </div>

        <!-- Role Workspace Banner -->
        <div id="role-info-banner" class="hidden mt-3 p-2.5 rounded-xl text-xs font-semibold bg-gray-50 dark:bg-gray-700/50 border border-gray-200 dark:border-gray-600 text-gray-700 dark:text-gray-300 items-center justify-between transition-all">
            <span id="role-info-text">Pilihan akun aktif</span>
            <span class="text-[11px] text-[#B06E6E] dark:text-indigo-400 font-bold">Otomatis Masuk Halaman Kerja</span>
        </div>
    </div>

    <!-- Login Form -->
    <form method="POST" action="{{ route('login') }}" class="space-y-4">
        @csrf

        <!-- Email Address -->
        <div>
            <label for="email" class="block text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-1">
                Alamat Email
            </label>
            <input id="email" 
                   type="email" 
                   name="email" 
                   value="{{ old('email') }}" 
                   required 
                   autofocus 
                   autocomplete="username"
                   placeholder="nama@kandekande.com"
                   class="block w-full px-4 py-3 rounded-2xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-700/60 text-gray-900 dark:text-white text-sm focus:ring-2 focus:ring-[#D19D9D] dark:focus:ring-indigo-500 focus:border-transparent outline-none transition-all shadow-sm" />
            <x-input-error :messages="$errors->get('email')" class="mt-1" />
        </div>

        <!-- Password -->
        <div>
            <div class="flex items-center justify-between mb-1">
                <label for="password" class="block text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                    Kata Sandi
                </label>
                @if (Route::has('password.request'))
                    <a class="text-xs text-gray-500 hover:text-[#B06E6E] dark:text-gray-400 dark:hover:text-indigo-400 font-medium transition-colors" href="{{ route('password.request') }}">
                        Lupa kata sandi?
                    </a>
                @endif
            </div>

            <input id="password" 
                   type="password"
                   name="password"
                   required 
                   autocomplete="current-password"
                   placeholder="••••••••"
                   class="block w-full px-4 py-3 rounded-2xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-700/60 text-gray-900 dark:text-white text-sm focus:ring-2 focus:ring-[#D19D9D] dark:focus:ring-indigo-500 focus:border-transparent outline-none transition-all shadow-sm" />
            <x-input-error :messages="$errors->get('password')" class="mt-1" />
        </div>

        <!-- Remember Me -->
        <div class="flex items-center justify-between pt-1">
            <label for="remember_me" class="inline-flex items-center cursor-pointer">
                <input id="remember_me" type="checkbox" class="w-4 h-4 rounded text-[#B06E6E] dark:text-indigo-600 focus:ring-[#D19D9D] dark:focus:ring-indigo-500 border-gray-300 dark:border-gray-600 dark:bg-gray-700" name="remember">
                <span class="ms-2 text-xs font-semibold text-gray-600 dark:text-gray-400">Ingat Saya</span>
            </label>
        </div>

        <!-- Submit Button in Wireframe Rose Color (Light) / Indigo (Dark) -->
        <div class="pt-2">
            <button type="submit" class="w-full bg-[#D19D9D] hover:bg-[#C48686] active:bg-[#B06E6E] dark:bg-indigo-600 dark:hover:bg-indigo-500 dark:active:bg-indigo-700 text-gray-900 dark:text-white font-extrabold py-3.5 px-6 rounded-2xl shadow-md hover:shadow-lg transition-all text-sm tracking-wide">
                Masuk ke Sistem POS
            </button>
        </div>
    </form>

    <!-- Footer Notice -->
    <div class="mt-6 pt-4 border-t border-gray-100 dark:border-gray-700 text-center">
        <p class="text-xs text-gray-500 dark:text-gray-400">
            ℹ️ Akun karyawan (Kasir, Dapur, Gudang) dibuat & dikelola langsung oleh <strong>Owner</strong> melalui sistem.
        </p>
    </div>

    <!-- Quick Fill Script -->
    <script>
        function selectRoleAccount(email, password, roleKey) {
            document.getElementById('email').value = email;
            document.getElementById('password').value = password;

            // Reset borders
            document.querySelectorAll('.role-card').forEach(card => {
                card.classList.remove('ring-2', 'ring-offset-2', 'ring-[#D19D9D]', 'dark:ring-indigo-500', 'border-gray-800', 'scale-105');
            });

            // Highlight selected
            const activeCard = document.getElementById('role-btn-' + roleKey);
            if (activeCard) {
                activeCard.classList.add('ring-2', 'ring-offset-2', 'ring-[#D19D9D]', 'dark:ring-indigo-500', 'scale-105');
            }

            // Show banner info
            const banner = document.getElementById('role-info-banner');
            const text = document.getElementById('role-info-text');
            if (banner && text) {
                banner.classList.remove('hidden');
                banner.classList.add('flex');
                
                const roleLabels = {
                    'owner': '👑 Masuk sebagai Owner: Akses penuh analitik & kelola karyawan',
                    'kasir': '💼 Masuk sebagai Kasir: Langsung menuju Layar Transaksi POS',
                    'dapur': '🍳 Masuk sebagai Dapur: Langsung menuju Antrean Pesanan Dapur',
                    'stok': '📦 Masuk sebagai Admin Stok: Langsung menuju Inventaris Bahan Baku'
                };
                text.innerText = roleLabels[roleKey] || 'Akun dipilih';
            }
        }
    </script>
</x-guest-layout>
