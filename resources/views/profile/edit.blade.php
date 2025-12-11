@php
    $completionFields = [
        'phone' => 'Nomor WhatsApp',
        'institution' => 'Institusi',
        'study_program' => 'Program studi',
        'city' => 'Domisili',
        'student_id' => 'ID mahasiswa',
    ];
    $filledCount = collect($completionFields)->filter(fn ($label, $field) => filled($user->{$field}))->count();
    $completionProgress = (int) round(($filledCount / max(count($completionFields), 1)) * 100);
    $profileStatusMap = [
        \App\Models\User::PROFILE_STATUS_DRAFT => ['label' => 'Lengkapi data', 'color' => 'text-amber-600 dark:text-amber-300'],
        \App\Models\User::PROFILE_STATUS_PENDING => ['label' => 'Menunggu verifikasi', 'color' => 'text-sky-600 dark:text-sky-300'],
        \App\Models\User::PROFILE_STATUS_VERIFIED => ['label' => 'Terverifikasi', 'color' => 'text-emerald-600 dark:text-emerald-300'],
    ];
    $profileStatus = $profileStatusMap[$user->profile_verification_status] ?? $profileStatusMap[\App\Models\User::PROFILE_STATUS_DRAFT];
@endphp

<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs uppercase tracking-[0.4em] text-slate-400 dark:text-slate-500">ACCOUNT SETTINGS</p>
                <h2 class="mt-2 text-3xl font-semibold text-slate-900 dark:text-white">Profil {{ $user->name }}</h2>
            </div>
            <span class="rounded-full border border-emerald-200 bg-emerald-50 px-4 py-1 text-sm font-semibold text-emerald-600 dark:border-emerald-400/40 dark:bg-emerald-400/10 dark:text-emerald-100">{{ ucfirst($user->role) }}</span>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-6xl space-y-8 px-4 sm:px-6 lg:px-8">
            <section class="grid gap-6 rounded-3xl border border-slate-100 bg-white/95 p-8 shadow-sm dark:border-white/10 dark:bg-white/5 lg:grid-cols-[1.3fr_1fr]">
                <div>
                    <h3 class="text-lg font-semibold text-slate-900 dark:text-white">Identitas Akun</h3>
                    <p class="mt-2 text-sm text-subtle">Perbarui data kontak agar tim Deadlineku mudah menghubungi kamu.</p>
                    <dl class="mt-6 grid gap-4 text-sm text-muted sm:grid-cols-2">
                        <div class="rounded-2xl border border-slate-100 p-4 dark:border-white/10">
                            <dt class="text-xs uppercase tracking-[0.3em] text-muted">Email</dt>
                            <dd class="mt-2 text-base font-semibold text-slate-900 dark:text-white">{{ $user->email }}</dd>
                        </div>
                        <div class="rounded-2xl border border-slate-100 p-4 dark:border-white/10">
                            <dt class="text-xs uppercase tracking-[0.3em] text-muted">Nomor</dt>
                            <dd class="mt-2 text-base font-semibold text-slate-900 dark:text-white">{{ $user->phone ?? 'Belum diisi' }}</dd>
                        </div>
                        <div class="rounded-2xl border border-slate-100 p-4 dark:border-white/10">
                            <dt class="text-xs uppercase tracking-[0.3em] text-muted">Member sejak</dt>
                            <dd class="mt-2 text-base font-semibold text-slate-900 dark:text-white">{{ $user->created_at?->translatedFormat('d M Y') }}</dd>
                        </div>
                        @php
                            $emailVerified = ! is_null($user->email_verified_at);
                        @endphp
                        <div class="rounded-2xl border border-slate-100 p-4 dark:border-white/10">
                            <dt class="text-xs uppercase tracking-[0.3em] text-muted">Status Email</dt>
                            <dd class="mt-2 text-base font-semibold {{ $emailVerified ? 'text-emerald-600 dark:text-emerald-300' : 'text-amber-600 dark:text-amber-300' }}">
                                {{ $emailVerified ? 'Terverifikasi' : 'Belum verifikasi' }}
                            </dd>
                        </div>
                        <div class="rounded-2xl border border-slate-100 p-4 dark:border-white/10">
                            <dt class="text-xs uppercase tracking-[0.3em] text-muted">Institusi</dt>
                            <dd class="mt-2 text-base font-semibold text-slate-900 dark:text-white">{{ $user->institution ?? 'Belum diisi' }}</dd>
                        </div>
                        <div class="rounded-2xl border border-slate-100 p-4 dark:border-white/10">
                            <dt class="text-xs uppercase tracking-[0.3em] text-muted">Program Studi</dt>
                            <dd class="mt-2 text-base font-semibold text-slate-900 dark:text-white">{{ $user->study_program ?? 'Belum diisi' }}</dd>
                        </div>
                        <div class="rounded-2xl border border-slate-100 p-4 dark:border-white/10">
                            <dt class="text-xs uppercase tracking-[0.3em] text-muted">Domisili</dt>
                            <dd class="mt-2 text-base font-semibold text-slate-900 dark:text-white">{{ $user->city ?? 'Belum diisi' }}</dd>
                        </div>
                    </dl>
                </div>
                <div class="space-y-6">
                    <div class="rounded-3xl border border-slate-100 bg-gradient-to-br from-indigo-50 via-white to-slate-100 p-6 text-slate-900 shadow-sm dark:border-white/10 dark:from-slate-900 dark:via-indigo-950 dark:to-slate-950 dark:text-white">
                        <p class="text-xs uppercase tracking-[0.3em] text-indigo-600 dark:text-indigo-200">Status profil</p>
                        <div class="mt-3 flex items-center justify-between">
                            <h3 class="text-2xl font-semibold">{{ $completionProgress }}% lengkap</h3>
                            <span class="text-sm font-semibold {{ $profileStatus['color'] }}">{{ $profileStatus['label'] }}</span>
                        </div>
                        <div class="mt-4 h-2 rounded-full bg-slate-200/60 dark:bg-white/10">
                            <div class="h-full rounded-full bg-slate-900 transition-all dark:bg-white" style="width: {{ $completionProgress }}%"></div>
                        </div>
                        <ul class="mt-4 space-y-2 text-xs text-subtle">
                            @foreach($completionFields as $field => $label)
                                <li class="flex items-center justify-between">
                                    <span>{{ $label }}</span>
                                    <span class="font-semibold {{ filled($user->{$field}) ? 'text-emerald-600 dark:text-emerald-300' : 'text-amber-600 dark:text-amber-300' }}">{{ filled($user->{$field}) ? 'Lengkap' : 'Belum' }}</span>
                                </li>
                            @endforeach
                        </ul>
                        @if (session('profile_verification_error'))
                            <p class="mt-4 rounded-2xl border border-rose-200 bg-rose-50 px-4 py-2 text-sm text-rose-700 dark:border-rose-400/40 dark:bg-rose-400/10 dark:text-rose-100">{{ session('profile_verification_error') }}</p>
                        @endif
                        @if (session('profile_verification_status') === 'request-submitted')
                            <p class="mt-4 rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-2 text-sm text-emerald-700 dark:border-emerald-400/40 dark:bg-emerald-400/10 dark:text-emerald-100">Permintaan verifikasi dikirim. Tim akan meninjau dalam 1x24 jam.</p>
                        @elseif(session('profile_verification_status') === 'already-verified')
                            <p class="mt-4 rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-2 text-sm text-emerald-700 dark:border-emerald-400/40 dark:bg-emerald-400/10 dark:text-emerald-100">Profil sudah diverifikasi.</p>
                        @endif
                        <form method="POST" action="{{ route('profile.verification.request') }}" class="mt-6">
                            @csrf
                            <button type="submit" class="inline-flex w-full items-center justify-center rounded-2xl bg-slate-900 px-4 py-3 text-sm font-semibold text-white transition hover:bg-slate-700 disabled:cursor-not-allowed disabled:opacity-60 dark:bg-white dark:text-slate-900" {{ $user->profile_verification_status === \App\Models\User::PROFILE_STATUS_VERIFIED ? 'disabled' : '' }}>
                                {{ $user->profile_verification_status === \App\Models\User::PROFILE_STATUS_PENDING ? 'Menunggu verifikasi' : 'Ajukan verifikasi' }}
                            </button>
                        </form>
                    </div>
                    <div class="rounded-3xl border border-slate-100 bg-gradient-to-br from-slate-900 via-slate-950 to-slate-950 p-6 text-white dark:border-white/10">
                        <p class="text-sm uppercase tracking-[0.3em] text-slate-300">Keamanan akun</p>
                        <h3 class="mt-3 text-2xl font-semibold">Aktifkan autentikasi ganda segera.</h3>
                        <p class="mt-3 text-sm text-slate-300">Hubungi admin untuk mengaktifkan fitur advance security seperti OTP email & device approval.</p>
                        <a href="mailto:hello@deadlineku.id" class="mt-6 inline-flex w-full items-center justify-center rounded-2xl bg-white/10 px-4 py-3 text-sm font-semibold text-white transition hover:bg-white/20">Hubungi support</a>
                    </div>
                </div>
            </section>

            <section class="grid gap-6 lg:grid-cols-2">
                <div class="rounded-3xl border border-slate-100 bg-white/90 p-6 shadow-sm dark:border-white/10 dark:bg-white/5">
                    <p class="text-xs uppercase tracking-[0.4em] text-muted">Update Data</p>
                    <h3 class="mt-2 text-xl font-semibold text-slate-900 dark:text-white">Informasi pribadi</h3>
                    <div class="mt-6">
                        @include('profile.partials.update-profile-information-form')
                    </div>
                </div>

                <div class="rounded-3xl border border-slate-100 bg-white/90 p-6 shadow-sm dark:border-white/10 dark:bg-white/5">
                    <p class="text-xs uppercase tracking-[0.4em] text-muted">Kata Sandi</p>
                    <h3 class="mt-2 text-xl font-semibold text-slate-900 dark:text-white">Ubah password berkala</h3>
                    <div class="mt-6">
                        @include('profile.partials.update-password-form')
                    </div>
                </div>
            </section>

            <section class="rounded-3xl border border-rose-100 bg-rose-50 p-6 shadow-sm dark:border-rose-400/30 dark:bg-rose-400/10">
                <p class="text-xs uppercase tracking-[0.4em] text-rose-400">Zona berbahaya</p>
                <h3 class="mt-2 text-xl font-semibold text-rose-700 dark:text-rose-200">Hapus akun</h3>
                <p class="mt-2 text-sm text-rose-500 dark:text-rose-200/80">Tindakan ini tidak dapat dibatalkan dan akan menghapus seluruh riwayat order, tiket, dan pembayaran.</p>
                <div class="mt-6 rounded-2xl border border-white/60 bg-white/95 p-4 dark:border-white/20 dark:bg-white/5">
                    @include('profile.partials.delete-user-form')
                </div>
            </section>
        </div>
    </div>
</x-app-layout>
