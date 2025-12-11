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

        <!-- Google Login -->
        <div class="space-y-3">
            <a href="{{ route('auth.google') }}" class="inline-flex w-full items-center justify-center gap-3 rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm font-medium text-slate-900 transition hover:bg-slate-50">
                <svg class="h-5 w-5" viewBox="0 0 24 24">
                    <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                    <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                    <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                    <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
                </svg>
                Lanjutkan dengan Google
            </a>

            <div class="relative">
                <div class="absolute inset-0 flex items-center">
                    <span class="w-full border-t border-slate-200" />
                </div>
                <div class="relative flex justify-center text-xs uppercase">
                    <span class="bg-white px-2 text-slate-500">Atau masuk dengan email</span>
                </div>
            </div>
        </div>

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
