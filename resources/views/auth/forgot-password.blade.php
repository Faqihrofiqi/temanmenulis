<x-guest-layout>
    <div class="space-y-6">
        <div>
            <p class="text-xs uppercase tracking-[0.4em] text-slate-400">Reset Password</p>
            <h2 class="mt-2 text-3xl font-semibold text-slate-900">Kirim tautan pemulihan</h2>
            <p class="mt-2 text-sm text-slate-500">Masukkan email yang kamu pakai di Deadlineku. Kami akan mengirim tautan untuk mengatur ulang kata sandi.</p>
        </div>

        @if (session('status'))
            <x-auth-session-status class="rounded-2xl border border-emerald-100 bg-emerald-50 px-4 py-3 text-sm text-emerald-700" :status="session('status')" />
        @endif

        <form method="POST" action="{{ route('password.email') }}" class="space-y-5">
            @csrf

            <div>
                <label for="email" class="text-sm font-semibold text-slate-700">Email terdaftar</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus class="mt-2 w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-900 placeholder-slate-400 focus:border-slate-900 focus:ring-0" placeholder="email@kampus.ac.id">
                @if ($errors->has('email'))
                    <p class="mt-2 text-sm text-rose-500">{{ $errors->first('email') }}</p>
                @endif
            </div>

            <button type="submit" class="inline-flex w-full items-center justify-center rounded-2xl bg-slate-900 px-4 py-3 text-sm font-semibold text-white transition hover:bg-slate-800">
                Kirim tautan reset
            </button>
        </form>
    </div>
</x-guest-layout>
