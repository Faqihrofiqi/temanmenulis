<section class="space-y-6">
    <header>
        <h3 class="text-lg font-semibold text-slate-900">Keamanan Kata Sandi</h3>
        <p class="mt-1 text-sm text-slate-500">Gunakan sandi unik dan rutin perbarui untuk menjaga workspace kamu aman.</p>
    </header>

    <form method="post" action="{{ route('password.update') }}" class="space-y-5">
        @csrf
        @method('put')

        <div>
            <label for="update_password_current_password" class="text-sm font-semibold text-slate-700">Password saat ini</label>
            <input id="update_password_current_password" name="current_password" type="password" autocomplete="current-password" class="mt-2 w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-900 focus:border-slate-900 focus:ring-0">
            @if ($errors->updatePassword->has('current_password'))
                <p class="mt-2 text-sm text-rose-500">{{ $errors->updatePassword->first('current_password') }}</p>
            @endif
        </div>

        <div>
            <label for="update_password_password" class="text-sm font-semibold text-slate-700">Password baru</label>
            <input id="update_password_password" name="password" type="password" autocomplete="new-password" class="mt-2 w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-900 focus:border-slate-900 focus:ring-0">
            @if ($errors->updatePassword->has('password'))
                <p class="mt-2 text-sm text-rose-500">{{ $errors->updatePassword->first('password') }}</p>
            @endif
        </div>

        <div>
            <label for="update_password_password_confirmation" class="text-sm font-semibold text-slate-700">Ulangi password baru</label>
            <input id="update_password_password_confirmation" name="password_confirmation" type="password" autocomplete="new-password" class="mt-2 w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-900 focus:border-slate-900 focus:ring-0">
            @if ($errors->updatePassword->has('password_confirmation'))
                <p class="mt-2 text-sm text-rose-500">{{ $errors->updatePassword->first('password_confirmation') }}</p>
            @endif
        </div>

        <div class="flex items-center gap-4">
            <button class="inline-flex items-center rounded-2xl bg-slate-900 px-5 py-3 text-sm font-semibold text-white hover:bg-slate-800">Simpan password</button>

            @if (session('status') === 'password-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2500)"
                    class="text-sm text-slate-500"
                >Password diperbarui.</p>
            @endif
        </div>
    </form>
</section>
