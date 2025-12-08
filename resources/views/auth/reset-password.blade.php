<x-guest-layout>
    <div class="space-y-6">
        <div>
            <p class="text-xs uppercase tracking-[0.4em] text-slate-400">Atur ulang</p>
            <h2 class="mt-2 text-3xl font-semibold text-slate-900">Buat kata sandi baru</h2>
            <p class="mt-2 text-sm text-slate-500">Masukkan email dan kata sandi baru untuk menyelesaikan proses pemulihan akun Deadlineku.</p>
        </div>

        <form method="POST" action="{{ route('password.store') }}" class="space-y-5">
            @csrf

            <input type="hidden" name="token" value="{{ $request->route('token') }}">

            <div>
                <label for="email" class="text-sm font-semibold text-slate-700">Email</label>
                <input id="email" type="email" name="email" value="{{ old('email', $request->email) }}" required autofocus autocomplete="username" class="mt-2 w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-900 placeholder-slate-400 focus:border-slate-900 focus:ring-0">
                @if ($errors->has('email'))
                    <p class="mt-2 text-sm text-rose-500">{{ $errors->first('email') }}</p>
                @endif
            </div>

            <div>
                <label for="password" class="text-sm font-semibold text-slate-700">Kata sandi baru</label>
                <input id="password" type="password" name="password" required autocomplete="new-password" class="mt-2 w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-900 placeholder-slate-400 focus:border-slate-900 focus:ring-0">
                @if ($errors->has('password'))
                    <p class="mt-2 text-sm text-rose-500">{{ $errors->first('password') }}</p>
                @endif
            </div>

            <div>
                <label for="password_confirmation" class="text-sm font-semibold text-slate-700">Konfirmasi kata sandi</label>
                <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password" class="mt-2 w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-900 placeholder-slate-400 focus:border-slate-900 focus:ring-0">
                @if ($errors->has('password_confirmation'))
                    <p class="mt-2 text-sm text-rose-500">{{ $errors->first('password_confirmation') }}</p>
                @endif
            </div>

            <button type="submit" class="inline-flex w-full items-center justify-center rounded-2xl bg-slate-900 px-4 py-3 text-sm font-semibold text-white transition hover:bg-slate-800">
                Simpan kata sandi baru
            </button>
        </form>
    </div>
</x-guest-layout>
