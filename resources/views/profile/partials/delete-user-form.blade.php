<section class="space-y-6">
    <header>
        <h3 class="text-lg font-semibold text-rose-700">Hapus akun Deadlineku</h3>
        <p class="mt-1 text-sm text-rose-500">Tindakan ini akan menghapus seluruh riwayat order, tiket, dan pembayaran. Tidak ada cara untuk membatalkannya.</p>
    </header>

    <button
        type="button"
        class="rounded-2xl border border-rose-200 bg-white px-4 py-3 text-sm font-semibold text-rose-600 hover:border-rose-500"
        x-data=""
        x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
    >Hapus akun permanen</button>

    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <form method="post" action="{{ route('profile.destroy') }}" class="space-y-5 p-6">
            @csrf
            @method('delete')

            <div>
                <h4 class="text-lg font-semibold text-slate-900">Yakin mau hapus akun?</h4>
                <p class="mt-1 text-sm text-slate-500">Masukkan password untuk konfirmasi. Setelah dihapus, data tidak bisa dipulihkan.</p>
            </div>

            <div>
                <label for="password" class="text-sm font-semibold text-slate-700">Password</label>
                <input id="password" name="password" type="password" class="mt-2 w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-900 focus:border-rose-500 focus:ring-0" placeholder="••••••••">
                @if ($errors->userDeletion->has('password'))
                    <p class="mt-2 text-sm text-rose-500">{{ $errors->userDeletion->first('password') }}</p>
                @endif
            </div>

            <div class="flex justify-end gap-3">
                <button type="button" class="rounded-2xl border border-slate-200 px-4 py-2 text-sm font-semibold text-slate-600 hover:border-slate-400" x-on:click="$dispatch('close')">Batal</button>
                <button type="submit" class="rounded-2xl bg-rose-600 px-4 py-2 text-sm font-semibold text-white hover:bg-rose-700">Hapus sekarang</button>
            </div>
        </form>
    </x-modal>
</section>
