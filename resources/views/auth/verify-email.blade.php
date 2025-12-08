<x-guest-layout>
    <div class="space-y-6">
        <div>
            <p class="text-xs uppercase tracking-[0.4em] text-slate-400">Verifikasi</p>
            <h2 class="mt-2 text-3xl font-semibold text-slate-900">Konfirmasi email kamu</h2>
            <p class="mt-2 text-sm text-slate-500">Kami baru saja mengirim tautan verifikasi ke email yang kamu gunakan saat pendaftaran. Klik tautan tersebut untuk mengaktifkan akun Deadlineku.</p>
        </div>

        @if (session('status') == 'verification-link-sent')
            <div class="rounded-2xl border border-emerald-100 bg-emerald-50 px-4 py-3 text-sm font-semibold text-emerald-700">
                Tautan verifikasi baru sudah dikirim. Cek inbox atau folder spam kamu.
            </div>
        @endif

        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <form method="POST" action="{{ route('verification.send') }}" class="w-full sm:w-auto">
                @csrf
                <button class="inline-flex w-full items-center justify-center rounded-2xl bg-slate-900 px-4 py-3 text-sm font-semibold text-white transition hover:bg-slate-800">
                    Kirim ulang email verifikasi
                </button>
            </form>

            <form method="POST" action="{{ route('logout') }}" class="w-full sm:w-auto">
                @csrf
                <button type="submit" class="inline-flex w-full items-center justify-center rounded-2xl border border-slate-300 px-4 py-3 text-sm font-semibold text-slate-700 transition hover:border-slate-900 hover:text-slate-900">
                    Keluar akun
                </button>
            </form>
        </div>
    </div>
</x-guest-layout>
