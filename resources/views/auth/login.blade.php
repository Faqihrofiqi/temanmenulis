<x-guest-layout>
    <div class="space-y-8">
        <div>
            <p class="text-xs uppercase tracking-[0.4em] text-slate-400">Masuk</p>
            <h2 class="mt-2 text-3xl font-semibold text-slate-900">Kembali ke Deadlineku</h2>
            <p class="mt-2 text-sm text-slate-500">Kelola semua pesanan dan layanan akademik dalam satu dashboard.</p>
        </div>

        @if (session('status'))
            <x-auth-session-status class="rounded-2xl border border-emerald-100 bg-emerald-50 px-4 py-3 text-sm text-emerald-700" :status="session('status')" />
        @endif

        <form method="POST" action="{{ route('login') }}" class="space-y-5">
            @csrf

            <div>
                <label for="email" class="text-sm font-semibold text-slate-700">Email Deadlineku</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" class="mt-2 w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-900 placeholder-slate-400 focus:border-slate-900 focus:ring-0" placeholder="you@email.com">
                @if ($errors->has('email'))
                    <p class="mt-2 text-sm text-rose-500">{{ $errors->first('email') }}</p>
                @endif
            </div>

            <div>
                <div class="flex items-center justify-between">
                    <label for="password" class="text-sm font-semibold text-slate-700">Kata sandi</label>
                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="text-xs font-semibold text-slate-500 hover:text-slate-900">Lupa password?</a>
                    @endif
                </div>
                <input id="password" type="password" name="password" required autocomplete="current-password" class="mt-2 w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-900 placeholder-slate-400 focus:border-slate-900 focus:ring-0" placeholder="••••••••">
                @if ($errors->has('password'))
                    <p class="mt-2 text-sm text-rose-500">{{ $errors->first('password') }}</p>
                @endif
            </div>

            <label for="remember_me" class="flex items-center gap-2 text-sm text-slate-500">
                <input id="remember_me" type="checkbox" name="remember" class="h-4 w-4 rounded border-slate-300 text-slate-900 focus:ring-slate-900">
                Ingat saya di perangkat ini
            </label>

            <button type="submit" class="inline-flex w-full items-center justify-center rounded-2xl bg-slate-900 px-4 py-3 text-sm font-semibold text-white transition hover:bg-slate-800">
                Masuk ke dashboard
            </button>
            <p class="text-center text-sm text-slate-500">Belum punya akun? <a href="{{ route('register') }}" class="font-semibold text-slate-900">Daftar sekarang</a></p>
        </form>
    </div>
</x-guest-layout>
