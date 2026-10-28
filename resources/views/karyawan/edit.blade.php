<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-extrabold text-xl text-gray-800 dark:text-gray-100 tracking-tight">
                    Edit Akun Karyawan: {{ $karyawan->name }}
                </h2>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">
                    Perbarui informasi identitas, tingkat peran, atau atur ulang kata sandi
                </p>
            </div>
            <a href="{{ route('karyawan.index') }}" 
               class="px-4 py-2 rounded-xl text-xs font-bold bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300 border border-gray-200 dark:border-gray-700 hover:bg-gray-50 shadow-sm transition-all">
                ← Kembali ke Daftar Karyawan
            </a>
        </div>
    </x-slot>

    <div class="py-8 bg-gray-100 dark:bg-gray-900 min-h-screen transition-colors duration-300">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-xl border border-gray-100 dark:border-gray-700 p-6 sm:p-8 transition-colors duration-300">
                
                <form action="{{ route('karyawan.update', $karyawan->id) }}" method="POST" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <!-- Nama Karyawan -->
                    <div>
                        <label for="name" class="block text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-1">
                            Nama Lengkap Karyawan <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               id="name" 
                               name="name" 
                               value="{{ old('name', $karyawan->name) }}" 
                               required 
                               class="w-full px-4 py-3 rounded-2xl border border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white text-sm outline-none focus:ring-2 focus:ring-[#D19D9D] dark:focus:ring-indigo-500 transition-all">
                        @error('name')
                            <p class="text-xs text-red-500 mt-1 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Email Login -->
                    <div>
                        <label for="email" class="block text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-1">
                            Alamat Email (Untuk Login) <span class="text-red-500">*</span>
                        </label>
                        <input type="email" 
                               id="email" 
                               name="email" 
                               value="{{ old('email', $karyawan->email) }}" 
                               required 
                               class="w-full px-4 py-3 rounded-2xl border border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white text-sm outline-none focus:ring-2 focus:ring-[#D19D9D] dark:focus:ring-indigo-500 transition-all">
                        @error('email')
                            <p class="text-xs text-red-500 mt-1 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Peran / Role Selection -->
                    <div>
                        <label for="role" class="block text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-1">
                            Jenis Peran (Tingkat Hak Akses) <span class="text-red-500">*</span>
                        </label>
                        <select name="role" 
                                id="role" 
                                required
                                onchange="updateRoleDescription()"
                                class="w-full px-4 py-3 rounded-2xl border border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white text-sm font-semibold outline-none focus:ring-2 focus:ring-[#D19D9D] dark:focus:ring-indigo-500 transition-all cursor-pointer">
                            <option value="Cashier" {{ old('role', $karyawan->role) == 'Cashier' || old('role', $karyawan->role) == 'Kasir' ? 'selected' : '' }}>💼 Kasir (Cashier)</option>
                            <option value="Kitchen" {{ old('role', $karyawan->role) == 'Kitchen' || old('role', $karyawan->role) == 'Dapur' ? 'selected' : '' }}>🍳 Dapur (Kitchen)</option>
                            <option value="Stock_Admin" {{ old('role', $karyawan->role) == 'Stock_Admin' ? 'selected' : '' }}>📦 Admin Gudang (Bahan Baku & Restock)</option>
                            <option value="Kustom" {{ old('role', $karyawan->role) == 'Kustom' || old('role', $karyawan->role) == 'Custom' ? 'selected' : '' }}>🛠️ Kustom (Atur Hak Akses Fleksibel)</option>
                            <option value="Owner" {{ old('role', $karyawan->role) == 'Owner' ? 'selected' : '' }}>👑 Owner (Super Admin)</option>
                        </select>
                        @error('role')
                            <p class="text-xs text-red-500 mt-1 font-medium">{{ $message }}</p>
                        @enderror

                        <!-- Dynamic Role Description Box -->
                        <div id="role-desc-box" class="mt-3 p-4 rounded-2xl bg-indigo-50/60 dark:bg-indigo-950/30 border border-indigo-100 dark:border-indigo-900/50 flex items-start gap-3">
                            <div class="text-xl mt-0.5" id="role-desc-icon">💼</div>
                            <div>
                                <h4 class="text-xs font-bold text-gray-900 dark:text-white" id="role-desc-title">Hak Akses</h4>
                                <p class="text-xs text-gray-600 dark:text-gray-300 mt-0.5" id="role-desc-text"></p>
                            </div>
                        </div>
                    </div>

                    <!-- Kustom Permissions Selector Panel (Only shown when role === 'Kustom') -->
                    <div id="custom-permissions-container" class="space-y-4 pt-2 border-t border-gray-100 dark:border-gray-700 hidden">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                            <div>
                                <h3 class="text-sm font-extrabold text-gray-900 dark:text-white flex items-center gap-2">
                                    <span>🛠️</span>
                                    <span>Daftar Hak Akses Modul (Izin Kustom)</span>
                                    <span class="text-red-500">*</span>
                                </h3>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">
                                    Centang modul yang diizinkan untuk diakses oleh akun karyawan ini:
                                </p>
                            </div>

                            <!-- Quick Preset Actions -->
                            <div class="flex items-center gap-1.5 flex-wrap">
                                <span class="text-[11px] font-bold text-gray-400 mr-1">Pilih Cepat:</span>
                                <button type="button" onclick="setPermissionsPreset('all')" class="px-2.5 py-1 rounded-lg text-[11px] font-bold bg-indigo-50 dark:bg-indigo-900/40 text-indigo-600 dark:text-indigo-400 hover:bg-indigo-100 transition-colors">
                                    Semua
                                </button>
                                <button type="button" onclick="setPermissionsPreset('none')" class="px-2.5 py-1 rounded-lg text-[11px] font-bold bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 hover:bg-gray-200 transition-colors">
                                    Kosongkan
                                </button>
                                <button type="button" onclick="setPermissionsPreset('kasir')" class="px-2.5 py-1 rounded-lg text-[11px] font-bold bg-emerald-50 dark:bg-emerald-950/40 text-emerald-600 dark:text-emerald-400 hover:bg-emerald-100 transition-colors">
                                    + Kasir
                                </button>
                                <button type="button" onclick="setPermissionsPreset('dapur')" class="px-2.5 py-1 rounded-lg text-[11px] font-bold bg-orange-50 dark:bg-orange-950/40 text-orange-600 dark:text-orange-400 hover:bg-orange-100 transition-colors">
                                    + Dapur
                                </button>
                                <button type="button" onclick="setPermissionsPreset('gudang')" class="px-2.5 py-1 rounded-lg text-[11px] font-bold bg-blue-50 dark:bg-blue-950/40 text-blue-600 dark:text-blue-400 hover:bg-blue-100 transition-colors">
                                    + Gudang
                                </button>
                            </div>
                        </div>

                        @error('permissions')
                            <div class="p-3 rounded-xl bg-red-50 dark:bg-red-950/40 border border-red-200 dark:border-red-800 text-xs text-red-600 dark:text-red-400 font-bold">
                                {{ $message }}
                            </div>
                        @enderror

                        <!-- Grid Permissions Checkboxes -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 pt-1">
                            @php
                                $existingPerms = is_array($karyawan->permissions) 
                                    ? $karyawan->permissions 
                                    : (json_decode($karyawan->permissions ?? '[]', true) ?: []);
                            @endphp
                            @foreach($availablePermissions as $key => $perm)
                            @php
                                $isChecked = in_array($key, old('permissions', $existingPerms));
                            @endphp
                            <label class="relative flex items-start gap-3 p-3.5 rounded-2xl border transition-all cursor-pointer select-none {{ $isChecked ? 'bg-indigo-50/50 dark:bg-indigo-950/30 border-indigo-400 dark:border-indigo-600' : 'bg-gray-50/70 dark:bg-gray-700/40 border-gray-200 dark:border-gray-600 hover:border-gray-300' }}">
                                <input type="checkbox" 
                                       name="permissions[]" 
                                       value="{{ $key }}" 
                                       {{ $isChecked ? 'checked' : '' }}
                                       class="perm-checkbox mt-0.5 rounded-lg text-indigo-600 focus:ring-indigo-500 border-gray-300 dark:border-gray-600 dark:bg-gray-800 w-4 h-4 cursor-pointer"
                                       onchange="togglePermCard(this)">
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center justify-between gap-1">
                                        <span class="text-xs font-extrabold text-gray-900 dark:text-white flex items-center gap-1.5">
                                            <span>{{ $perm['icon'] }}</span>
                                            <span>{{ $perm['label'] }}</span>
                                        </span>
                                        <span class="text-[10px] uppercase font-bold text-gray-400 dark:text-gray-500">
                                            {{ $perm['category'] }}
                                        </span>
                                    </div>
                                    <p class="text-[11px] text-gray-500 dark:text-gray-400 mt-0.5 leading-snug">
                                        {{ $perm['desc'] }}
                                    </p>
                                </div>
                            </label>
                            @endforeach
                        </div>
                    </div>

                    <!-- Reset Password Section (Opsional) -->
                    <div class="pt-4 border-t border-gray-100 dark:border-gray-700">
                        <div class="mb-3">
                            <h4 class="text-xs font-bold uppercase tracking-wider text-gray-800 dark:text-gray-200">
                                Ganti Kata Sandi (Opsional)
                            </h4>
                            <p class="text-xs text-gray-400 mt-0.5">
                                Biarkan kolom di bawah ini kosong jika Anda tidak ingin mengubah kata sandi karyawan.
                            </p>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <!-- Password Baru -->
                            <div>
                                <label for="password" class="block text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-1">
                                    Kata Sandi Baru
                                </label>
                                <input type="password" 
                                       id="password" 
                                       name="password" 
                                       placeholder="Kosongkan jika tetap"
                                       class="w-full px-4 py-3 rounded-2xl border border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white text-sm outline-none focus:ring-2 focus:ring-[#D19D9D] dark:focus:ring-indigo-500 transition-all">
                                @error('password')
                                    <p class="text-xs text-red-500 mt-1 font-medium">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Konfirmasi Password Baru -->
                            <div>
                                <label for="password_confirmation" class="block text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-1">
                                    Ulangi Kata Sandi Baru
                                </label>
                                <input type="password" 
                                       id="password_confirmation" 
                                       name="password_confirmation" 
                                       placeholder="Ketik ulang jika diisi"
                                       class="w-full px-4 py-3 rounded-2xl border border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white text-sm outline-none focus:ring-2 focus:ring-[#D19D9D] dark:focus:ring-indigo-500 transition-all">
                            </div>
                        </div>
                    </div>

                    <!-- Submit Buttons -->
                    <div class="pt-4 flex items-center justify-end gap-3 border-t border-gray-100 dark:border-gray-700">
                        <a href="{{ route('karyawan.index') }}" 
                           class="px-5 py-3 rounded-2xl text-xs font-bold text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                            Batal
                        </a>
                        <button type="submit" 
                                class="px-6 py-3 rounded-2xl bg-[#D19D9D] hover:bg-[#C48686] active:bg-[#B06E6E] dark:bg-indigo-600 dark:hover:bg-indigo-500 text-gray-900 dark:text-white font-extrabold text-xs shadow-md hover:shadow-lg transition-all tracking-wide">
                            Simpan Perubahan
                        </button>
                    </div>

                </form>

            </div>

        </div>
    </div>

    <!-- Script for Dynamic Role Explanation & Custom Permissions -->
    <script>
        const roleDetails = {
            'Cashier': {
                icon: '💼',
                title: 'Hak Akses: Kasir',
                text: 'Dapat mengakses antarmuka Transaksi POS, melayani pesanan Offline & WhatsApp, kalkulator kembalian, dan cetak struk belanja pelanggan. Akses ke laporan keuangan pendapatan dan ekspor rahasia disembunyikan.'
            },
            'Kitchen': {
                icon: '🍳',
                title: 'Hak Akses: Dapur',
                text: 'Dapat memantau antrean pesanan makanan & minuman dari kasir secara langsung, melihat resep porsi, serta mengubah status masak (Menunggu Persiapan -> Sedang Dimasak -> Selesai Disajikan).'
            },
            'Stock_Admin': {
                icon: '📦',
                title: 'Hak Akses: Admin Gudang (Logistik)',
                text: 'Hanya dapat mengakses dan mengelola master data Bahan Baku serta mencatat Riwayat Restock bahan masuk ke gudang. Akses ke katalog produk roti, resep, POS kasir, dapur, dan laporan keuangan ditutup.'
            },
            'Kustom': {
                icon: '🛠️',
                title: 'Hak Akses: Peran Kustom (Fleksibel)',
                text: 'Owner dapat menentukan sendiri modul apa saja yang diizinkan untuk diakses oleh pengguna ini melalui daftar checklist hak akses di atas.'
            },
            'Owner': {
                icon: '👑',
                title: 'Hak Akses: Owner (Akses Penuh)',
                text: 'Akses tanpa batas ke seluruh bagian sistem: Dashboard analitik laba/omzet, kelola akun seluruh karyawan, manajemen produk, kasir, dapur, dan ekspor laporan CSV.'
            }
        };

        function updateRoleDescription() {
            const role = document.getElementById('role').value;
            const details = roleDetails[role] || roleDetails['Cashier'];
            
            document.getElementById('role-desc-icon').innerText = details.icon;
            document.getElementById('role-desc-title').innerText = details.title;
            document.getElementById('role-desc-text').innerText = details.text;

            const permContainer = document.getElementById('custom-permissions-container');
            if (role === 'Kustom') {
                permContainer.classList.remove('hidden');
            } else {
                permContainer.classList.add('hidden');
            }
        }

        function togglePermCard(checkbox) {
            const label = checkbox.closest('label');
            if (checkbox.checked) {
                label.classList.add('bg-indigo-50/50', 'dark:bg-indigo-950/30', 'border-indigo-400', 'dark:border-indigo-600');
                label.classList.remove('bg-gray-50/70', 'dark:bg-gray-700/40', 'border-gray-200', 'dark:border-gray-600');
            } else {
                label.classList.remove('bg-indigo-50/50', 'dark:bg-indigo-950/30', 'border-indigo-400', 'dark:border-indigo-600');
                label.classList.add('bg-gray-50/70', 'dark:bg-gray-700/40', 'border-gray-200', 'dark:border-gray-600');
            }
        }

        function setPermissionsPreset(type) {
            const checkboxes = document.querySelectorAll('.perm-checkbox');
            const presets = {
                'all': ['kasir', 'pelanggan', 'dapur', 'produk', 'resep', 'diskon', 'bahanbaku', 'laporan', 'karyawan'],
                'none': [],
                'kasir': ['kasir', 'pelanggan'],
                'dapur': ['dapur', 'resep'],
                'gudang': ['bahanbaku']
            };

            const selected = presets[type] || [];
            checkboxes.forEach(cb => {
                if (type === 'all') {
                    cb.checked = true;
                } else if (type === 'none') {
                    cb.checked = false;
                } else {
                    if (selected.includes(cb.value)) {
                        cb.checked = true;
                    }
                }
                togglePermCard(cb);
            });
        }

        document.addEventListener('DOMContentLoaded', updateRoleDescription);
    </script>
</x-app-layout>
