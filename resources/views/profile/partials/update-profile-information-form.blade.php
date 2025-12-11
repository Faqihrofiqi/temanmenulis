<section class="space-y-6">
    <header>
        <h3 class="text-lg font-semibold text-slate-900 dark:text-white">Data Profil</h3>
        <p class="mt-1 text-sm text-subtle">Perbarui nama dan email agar notifikasi Deadlineku selalu tepat sasaran.</p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="space-y-5">
        @csrf
        @method('patch')

        <div>
            <label for="name" class="form-label">Nama lengkap</label>
            <input id="name" name="name" type="text" value="{{ old('name', $user->name) }}" required autofocus autocomplete="name" class="form-input mt-2">
            @if ($errors->has('name'))
                <p class="mt-2 text-sm text-rose-500 dark:text-rose-300">{{ $errors->first('name') }}</p>
            @endif
        </div>

        <div>
            <label for="email" class="form-label">Email utama</label>
            <input id="email" name="email" type="email" value="{{ old('email', $user->email) }}" required autocomplete="username" class="form-input mt-2">
            @if ($errors->has('email'))
                <p class="mt-2 text-sm text-rose-500 dark:text-rose-300">{{ $errors->first('email') }}</p>
            @endif

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div class="mt-3 rounded-2xl border border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-700 dark:border-amber-400/40 dark:bg-amber-400/10 dark:text-amber-100">
                    Email kamu belum diverifikasi.
                    <button form="send-verification" class="ml-2 font-semibold text-amber-900 underline dark:text-amber-200">Kirim ulang verifikasi</button>
                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-semibold text-emerald-600 dark:text-emerald-300">Tautan baru sudah dikirim ke inbox kamu.</p>
                    @endif
                </div>
            @endif
        </div>

        <div class="grid gap-5 md:grid-cols-2">
            <div>
                <label for="phone" class="form-label">Nomor WhatsApp</label>
                <input id="phone" name="phone" type="text" value="{{ old('phone', $user->phone) }}" autocomplete="tel" class="form-input mt-2" placeholder="08xxxxxxxxxx">
                @error('phone')
                    <p class="mt-2 text-sm text-rose-500 dark:text-rose-300">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label for="city" class="form-label">Domisili</label>
                <input id="city" name="city" type="text" value="{{ old('city', $user->city) }}" class="form-input mt-2" placeholder="Kota / Kabupaten">
                @error('city')
                    <p class="mt-2 text-sm text-rose-500 dark:text-rose-300">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="grid gap-5 md:grid-cols-2">
            <div>
                <label for="institution" class="form-label">Institusi / Kampus</label>
                <input id="institution" name="institution" type="text" value="{{ old('institution', $user->institution) }}" class="form-input mt-2" placeholder="Universitas / Lembaga">
                @error('institution')
                    <p class="mt-2 text-sm text-rose-500 dark:text-rose-300">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label for="study_program" class="form-label">Program studi / Bidang</label>
                <input id="study_program" name="study_program" type="text" value="{{ old('study_program', $user->study_program) }}" class="form-input mt-2" placeholder="Contoh: Teknik Informatika">
                @error('study_program')
                    <p class="mt-2 text-sm text-rose-500 dark:text-rose-300">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="grid gap-5 md:grid-cols-2">
            <div>
                <label for="student_id" class="form-label">ID Mahasiswa / NIP</label>
                <input id="student_id" name="student_id" type="text" value="{{ old('student_id', $user->student_id) }}" class="form-input mt-2" placeholder="Opsional">
                @error('student_id')
                    <p class="mt-2 text-sm text-rose-500 dark:text-rose-300">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label for="linkedin_url" class="form-label">LinkedIn / Portofolio</label>
                <input id="linkedin_url" name="linkedin_url" type="url" value="{{ old('linkedin_url', $user->linkedin_url) }}" class="form-input mt-2" placeholder="https://">
                @error('linkedin_url')
                    <p class="mt-2 text-sm text-rose-500 dark:text-rose-300">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="flex items-center gap-4">
            <button class="inline-flex items-center rounded-2xl bg-slate-900 px-5 py-3 text-sm font-semibold text-white transition hover:bg-slate-700 dark:bg-white dark:text-slate-900">Simpan perubahan</button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2500)"
                    class="text-sm text-subtle"
                >Tersimpan.</p>
            @endif
        </div>
    </form>
</section>
