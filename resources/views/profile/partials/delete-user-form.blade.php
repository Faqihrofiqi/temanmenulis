<section class="space-y-6">
    <header>
        <h3 class="text-lg font-semibold text-rose-700 dark:text-rose-200">Hapus akun Deadlineku</h3>
        <p class="mt-1 text-sm text-rose-500 dark:text-rose-200/80">Tindakan ini akan menghapus seluruh riwayat order, tiket, dan pembayaran. Tidak ada cara untuk membatalkannya.</p>
    </header>

    <button
        type="button"
        class="rounded-2xl border border-rose-200 bg-white px-4 py-3 text-sm font-semibold text-rose-600 transition hover:border-rose-500 dark:border-rose-400/40 dark:bg-transparent dark:text-rose-200"
        x-data=""
        x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
    >Hapus akun permanen</button>

    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <form method="post" action="{{ route('profile.destroy') }}" class="space-y-5 p-6">
            @csrf
            @method('delete')

            <div>
                <h4 class="text-lg font-semibold text-slate-900 dark:text-white">Yakin mau hapus akun?</h4>
                <p class="mt-1 text-sm text-subtle">Masukkan password untuk konfirmasi. Setelah dihapus, data tidak bisa dipulihkan.</p>
            </div>

            <div>
                <label for="password" class="form-label text-slate-700">Password</label>
                <input id="password" name="password" type="password" class="form-input mt-2" placeholder="••••••••">
                @if ($errors->userDeletion->has('password'))
                    <p class="mt-2 text-sm text-rose-500 dark:text-rose-300">{{ $errors->userDeletion->first('password') }}</p>
                @endif
            </div>

            <div class="flex justify-end gap-3">
                <button type="button" class="rounded-2xl border border-slate-200 px-4 py-2 text-sm font-semibold text-slate-600 transition hover:border-slate-400 dark:border-white/20 dark:text-white" x-on:click="$dispatch('close')">Batal</button>
                <button type="submit" class="rounded-2xl bg-rose-600 px-4 py-2 text-sm font-semibold text-white transition hover:bg-rose-700">Hapus sekarang</button>
            </div>
        </form>
    </x-modal>
</section>
