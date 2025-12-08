<x-guest-layout>
    <div class="space-y-8">
        <div>
            <p class="text-xs uppercase tracking-[0.4em] text-slate-400">Daftar Baru</p>
            <h2 class="mt-2 text-3xl font-semibold text-slate-900">Buat akun Deadlineku</h2>
            <p class="mt-2 text-sm text-slate-500">Akses katalog layanan, pantau progres order, dan kelola pembayaran dengan aman.</p>
        </div>

        <form method="POST" action="{{ route('register') }}" class="space-y-5">
            @csrf

            <div>
                <label for="name" class="text-sm font-semibold text-slate-700">Nama lengkap</label>
                <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name" class="mt-2 w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-900 placeholder-slate-400 focus:border-slate-900 focus:ring-0" placeholder="Nama lengkap">
                @if ($errors->has('name'))
                    <p class="mt-2 text-sm text-rose-500">{{ $errors->first('name') }}</p>
                @endif
            </div>

            <div>
                <label for="email" class="text-sm font-semibold text-slate-700">Email aktif</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="username" class="mt-2 w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-900 placeholder-slate-400 focus:border-slate-900 focus:ring-0" placeholder="email@kampus.ac.id">
                @if ($errors->has('email'))
                    <p class="mt-2 text-sm text-rose-500">{{ $errors->first('email') }}</p>
                @endif
            </div>

            <div>
                <label for="password" class="text-sm font-semibold text-slate-700">Kata sandi</label>
                <input id="password" type="password" name="password" required autocomplete="new-password" class="mt-2 w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-900 placeholder-slate-400 focus:border-slate-900 focus:ring-0" placeholder="Minimal 8 karakter">
                @if ($errors->has('password'))
                    <p class="mt-2 text-sm text-rose-500">{{ $errors->first('password') }}</p>
                @endif
            </div>

            <div>
                <label for="password_confirmation" class="text-sm font-semibold text-slate-700">Konfirmasi kata sandi</label>
                <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password" class="mt-2 w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-900 placeholder-slate-400 focus:border-slate-900 focus:ring-0" placeholder="Ulangi password">
                @if ($errors->has('password_confirmation'))
                    <p class="mt-2 text-sm text-rose-500">{{ $errors->first('password_confirmation') }}</p>
                @endif
            </div>

            <button type="submit" class="inline-flex w-full items-center justify-center rounded-2xl bg-slate-900 px-4 py-3 text-sm font-semibold text-white transition hover:bg-slate-800">
                Buat akun Deadlineku
            </button>

            <p class="text-center text-sm text-slate-500">Sudah punya akun? <a href="{{ route('login') }}" class="font-semibold text-slate-900">Masuk sekarang</a></p>
        </form>
    </div>
</x-guest-layout>
