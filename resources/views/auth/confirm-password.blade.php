<x-guest-layout>
    <div class="space-y-6">
        <div>
            <p class="text-xs uppercase tracking-[0.4em] text-slate-400">Konfirmasi</p>
            <h2 class="mt-2 text-3xl font-semibold text-slate-900">Masukkan password kamu</h2>
            <p class="mt-2 text-sm text-slate-500">Untuk keamanan tambahan, kami perlu memastikan ini benar-benar kamu sebelum melanjutkan.</p>
        </div>

        <form method="POST" action="{{ route('password.confirm') }}" class="space-y-5">
            @csrf

            <div>
                <label for="password" class="text-sm font-semibold text-slate-700">Kata sandi</label>
                <input id="password" type="password" name="password" required autocomplete="current-password" class="mt-2 w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-900 placeholder-slate-400 focus:border-slate-900 focus:ring-0">
                @if ($errors->has('password'))
                    <p class="mt-2 text-sm text-rose-500">{{ $errors->first('password') }}</p>
                @endif
            </div>

            <button type="submit" class="inline-flex w-full items-center justify-center rounded-2xl bg-slate-900 px-4 py-3 text-sm font-semibold text-white transition hover:bg-slate-800">
                Konfirmasi dan lanjutkan
            </button>
        </form>
    </div>
</x-guest-layout>
