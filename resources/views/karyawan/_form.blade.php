@php
    $k = $karyawan ?? null;
    $roleVal = old('role', $k->role ?? 'Cashier');
    if (in_array($roleVal, ['Kasir'])) { $roleVal = 'Cashier'; }
    if (in_array($roleVal, ['Dapur'])) { $roleVal = 'Kitchen'; }
    if (in_array($roleVal, ['Custom'])) { $roleVal = 'Kustom'; }
    $existingPerms = [];
    if ($k) {
        $existingPerms = is_array($k->permissions) ? $k->permissions : (json_decode($k->permissions ?? '[]', true) ?: []);
    }
@endphp
<form action="{{ $action }}" method="POST" class="space-y-6">
    @csrf
    @if($k) @method('PUT') @endif

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
        <div class="fld">
            <label for="name">Nama lengkap</label>
            <input type="text" id="name" name="name" value="{{ old('name', $k->name ?? '') }}" required placeholder="Rian Pratama" class="inp">
            @error('name')<p class="hint !text-red-600">{{ $message }}</p>@enderror
        </div>
        <div class="fld">
            <label for="email">Email untuk login</label>
            <input type="email" id="email" name="email" value="{{ old('email', $k->email ?? '') }}" required placeholder="karyawan@kandekande.com" class="inp">
            @error('email')<p class="hint !text-red-600">{{ $message }}</p>@enderror
        </div>
    </div>

    <div class="fld">
        <label for="role">Peran</label>
        <select name="role" id="role" required onchange="updateRoleDescription()" class="inp">
            <option value="Cashier" {{ $roleVal == 'Cashier' ? 'selected' : '' }}>Kasir</option>
            <option value="Kitchen" {{ $roleVal == 'Kitchen' ? 'selected' : '' }}>Dapur</option>
            <option value="Stock_Admin" {{ $roleVal == 'Stock_Admin' ? 'selected' : '' }}>Admin gudang (bahan baku dan restock)</option>
            <option value="Kustom" {{ $roleVal == 'Kustom' ? 'selected' : '' }}>Kustom (atur hak akses sendiri)</option>
            <option value="Owner" {{ $roleVal == 'Owner' ? 'selected' : '' }}>Owner (akses penuh)</option>
        </select>
        @error('role')<p class="hint !text-red-600">{{ $message }}</p>@enderror
        <div id="role-desc-box" class="mt-3 rounded-lg border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/40 px-4 py-3">
            <p class="font-semibold text-gray-900 dark:text-gray-100" id="role-desc-title">Hak akses</p>
            <p class="mt-0.5 text-sm text-gray-600 dark:text-gray-300" id="role-desc-text"></p>
        </div>
    </div>

    <div id="custom-permissions-container" class="hidden space-y-4">
        <div class="flex flex-wrap items-end justify-between gap-3">
            <div>
                <p class="font-semibold text-gray-900 dark:text-gray-100">Modul yang boleh diakses</p>
                <p class="text-sm text-gray-500 dark:text-gray-400">Centang modul untuk akun ini.</p>
            </div>
            <div class="flex flex-wrap items-center gap-1.5 text-sm">
                <span class="text-gray-500">Pilih cepat:</span>
                @foreach(['all' => 'Semua', 'none' => 'Kosongkan', 'kasir' => '+ Kasir', 'dapur' => '+ Dapur', 'gudang' => '+ Gudang'] as $pk => $pl)
                    <button type="button" onclick="setPermissionsPreset('{{ $pk }}')" class="px-3 py-1 rounded-full border border-gray-300 dark:border-gray-600 font-medium hover:border-gray-900 dark:hover:border-gray-300 transition-colors">{{ $pl }}</button>
                @endforeach
            </div>
        </div>
        @error('permissions')<p class="text-sm text-red-600">{{ $message }}</p>@enderror
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
            @foreach($availablePermissions as $key => $perm)
                @php $isChecked = in_array($key, old('permissions', $existingPerms)); @endphp
                <label class="flex items-start gap-3 rounded-lg border border-gray-300 dark:border-gray-600 p-4 cursor-pointer has-[:checked]:border-indigo-600 has-[:checked]:bg-indigo-50 dark:has-[:checked]:bg-indigo-950/30 transition-colors">
                    <input type="checkbox" name="permissions[]" value="{{ $key }}" {{ $isChecked ? 'checked' : '' }} class="perm-checkbox mt-1 w-4 h-4 accent-[#D4501E]">
                    <span class="min-w-0">
                        <span class="flex items-baseline justify-between gap-2">
                            <span class="font-semibold text-gray-900 dark:text-gray-100">{{ $perm['label'] }}</span>
                            <span class="text-xs text-gray-400">{{ $perm['category'] }}</span>
                        </span>
                        <span class="block text-sm text-gray-500 dark:text-gray-400">{{ $perm['desc'] }}</span>
                    </span>
                </label>
            @endforeach
        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
        <div class="fld">
            <label for="password">Kata sandi</label>
            <input type="password" id="password" name="password" {{ $k ? '' : 'required' }} placeholder="{{ $k ? 'Kosongkan jika tidak diganti' : 'Minimal 6 karakter' }}" class="inp">
            @error('password')<p class="hint !text-red-600">{{ $message }}</p>@enderror
        </div>
        <div class="fld">
            <label for="password_confirmation">Ulangi kata sandi</label>
            <input type="password" id="password_confirmation" name="password_confirmation" {{ $k ? '' : 'required' }} placeholder="{{ $k ? 'Ketik ulang jika diisi' : 'Ketik ulang kata sandi' }}" class="inp">
        </div>
    </div>

    <div class="flex items-center gap-4 pt-5 border-t border-gray-200 dark:border-gray-700">
        <x-btn type="submit">{{ $k ? 'Simpan perubahan' : 'Simpan akun' }}</x-btn>
        <x-btn variant="link" :href="route('karyawan.index')">Batal</x-btn>
    </div>
</form>

<script>
    const roleDetails = {
        'Cashier': { title: 'Kasir', text: 'Mengakses POS, melayani pesanan offline dan WhatsApp, menghitung kembalian, dan mencetak struk. Laporan keuangan dan ekspor disembunyikan.' },
        'Kitchen': { title: 'Dapur', text: 'Memantau antrean pesanan dari kasir, melihat resep, dan mengubah status masak sampai pesanan siap disajikan.' },
        'Stock_Admin': { title: 'Admin gudang', text: 'Hanya mengelola data bahan baku dan mencatat restock. Katalog produk, resep, kasir, dapur, dan laporan keuangan ditutup.' },
        'Kustom': { title: 'Peran kustom', text: 'Owner menentukan sendiri modul yang boleh diakses lewat daftar centang di bawah.' },
        'Owner': { title: 'Owner', text: 'Akses penuh: dashboard analitik, akun karyawan, produk, kasir, dapur, dan ekspor laporan.' }
    };
    function updateRoleDescription() {
        const role = document.getElementById('role').value;
        const d = roleDetails[role] || roleDetails['Cashier'];
        document.getElementById('role-desc-title').innerText = 'Hak akses: ' + d.title;
        document.getElementById('role-desc-text').innerText = d.text;
        document.getElementById('custom-permissions-container').classList.toggle('hidden', role !== 'Kustom');
    }
    function setPermissionsPreset(type) {
        const presets = { all: ['kasir', 'pelanggan', 'dapur', 'produk', 'resep', 'diskon', 'bahanbaku', 'laporan', 'karyawan'], none: [], kasir: ['kasir', 'pelanggan'], dapur: ['dapur', 'resep'], gudang: ['bahanbaku'] };
        const selected = presets[type] || [];
        document.querySelectorAll('.perm-checkbox').forEach(cb => {
            if (type === 'all') cb.checked = true;
            else if (type === 'none') cb.checked = false;
            else if (selected.includes(cb.value)) cb.checked = true;
        });
    }
    document.addEventListener('DOMContentLoaded', updateRoleDescription);
</script>
