<section class="space-y-6">
    <header>
        <h3 class="text-lg font-semibold text-slate-900 dark:text-white">Keamanan Kata Sandi</h3>
        <p class="mt-1 text-sm text-subtle">Gunakan sandi unik dan rutin perbarui untuk menjaga workspace kamu aman.</p>
    </header>

    <form method="post" action="{{ route('password.update') }}" class="space-y-5">
        @csrf
        @method('put')

        <div>
            <label for="update_password_current_password" class="form-label">Password saat ini</label>
            <input id="update_password_current_password" name="current_password" type="password" autocomplete="current-password" class="form-input mt-2">
            @if ($errors->updatePassword->has('current_password'))
                <p class="mt-2 text-sm text-rose-500 dark:text-rose-300">{{ $errors->updatePassword->first('current_password') }}</p>
            @endif
        </div>

        <div>
            <label for="update_password_password" class="form-label">Password baru</label>
            <input id="update_password_password" name="password" type="password" autocomplete="new-password" class="form-input mt-2">
            @if ($errors->updatePassword->has('password'))
                <p class="mt-2 text-sm text-rose-500 dark:text-rose-300">{{ $errors->updatePassword->first('password') }}</p>
            @endif
        </div>

        <div>
            <label for="update_password_password_confirmation" class="form-label">Ulangi password baru</label>
            <input id="update_password_password_confirmation" name="password_confirmation" type="password" autocomplete="new-password" class="form-input mt-2">
            @if ($errors->updatePassword->has('password_confirmation'))
                <p class="mt-2 text-sm text-rose-500 dark:text-rose-300">{{ $errors->updatePassword->first('password_confirmation') }}</p>
            @endif
        </div>

        <div class="flex items-center gap-4">
            <button class="inline-flex items-center rounded-2xl bg-slate-900 px-5 py-3 text-sm font-semibold text-white transition hover:bg-slate-700 dark:bg-white dark:text-slate-900">Simpan password</button>

            @if (session('status') === 'password-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2500)"
                    class="text-sm text-subtle"
                >Password diperbarui.</p>
            @endif
        </div>
    </form>
</section>
