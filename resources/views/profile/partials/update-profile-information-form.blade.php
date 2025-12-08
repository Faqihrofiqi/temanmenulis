<section class="space-y-6">
    <header>
        <h3 class="text-lg font-semibold text-slate-900">Data Profil</h3>
        <p class="mt-1 text-sm text-slate-500">Perbarui nama dan email agar notifikasi Deadlineku selalu tepat sasaran.</p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="space-y-5">
        @csrf
        @method('patch')

        <div>
            <label for="name" class="text-sm font-semibold text-slate-700">Nama lengkap</label>
            <input id="name" name="name" type="text" value="{{ old('name', $user->name) }}" required autofocus autocomplete="name" class="mt-2 w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-900 focus:border-slate-900 focus:ring-0">
            @if ($errors->has('name'))
                <p class="mt-2 text-sm text-rose-500">{{ $errors->first('name') }}</p>
            @endif
        </div>

        <div>
            <label for="email" class="text-sm font-semibold text-slate-700">Email utama</label>
            <input id="email" name="email" type="email" value="{{ old('email', $user->email) }}" required autocomplete="username" class="mt-2 w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-900 focus:border-slate-900 focus:ring-0">
            @if ($errors->has('email'))
                <p class="mt-2 text-sm text-rose-500">{{ $errors->first('email') }}</p>
            @endif

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div class="mt-3 rounded-2xl border border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-700">
                    Email kamu belum diverifikasi.
                    <button form="send-verification" class="ml-2 font-semibold text-amber-900 underline">Kirim ulang verifikasi</button>
                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-semibold text-emerald-600">Tautan baru sudah dikirim ke inbox kamu.</p>
                    @endif
                </div>
            @endif
        </div>

        <div class="flex items-center gap-4">
            <button class="inline-flex items-center rounded-2xl bg-slate-900 px-5 py-3 text-sm font-semibold text-white hover:bg-slate-800">Simpan perubahan</button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2500)"
                    class="text-sm text-slate-500"
                >Tersimpan.</p>
            @endif
        </div>
    </form>
</section>
