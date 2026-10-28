<section class="mt-4" x-data="{ confirmDelete: {{ $errors->userDeletion->isNotEmpty() ? 'true' : 'false' }} }">
    <x-btn variant="danger" @click="confirmDelete = true">Hapus akun</x-btn>

    <x-dialog show="confirmDelete" title="Hapus akun ini?">
        <form method="post" action="{{ route('profile.destroy') }}" class="space-y-5">
            @csrf
            @method('delete')
            <p class="text-sm text-gray-600 dark:text-gray-300">Seluruh data akun akan dihapus permanen. Masukkan kata sandi untuk mengonfirmasi.</p>
            <div class="fld">
                <label for="password_delete">Kata sandi</label>
                <input id="password_delete" name="password" type="password" class="inp" placeholder="Kata sandi">
                @foreach($errors->userDeletion->get('password') as $m)<p class="hint !text-red-600">{{ $m }}</p>@endforeach
            </div>
            <div class="flex items-center justify-end gap-3">
                <x-btn variant="secondary" @click="confirmDelete = false">Batal</x-btn>
                <x-btn type="submit" variant="danger">Hapus akun</x-btn>
            </div>
        </form>
    </x-dialog>
</section>
