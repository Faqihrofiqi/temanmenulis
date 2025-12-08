<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs uppercase tracking-[0.4em] text-slate-400">ACCOUNT SETTINGS</p>
                <h2 class="mt-2 text-3xl font-semibold text-slate-900">Profil {{ $user->name }}</h2>
            </div>
            <span class="rounded-full border border-emerald-200 bg-emerald-50 px-4 py-1 text-sm font-semibold text-emerald-600">{{ ucfirst($user->role) }}</span>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-6xl space-y-8 px-4 sm:px-6 lg:px-8">
            <section class="grid gap-6 rounded-3xl bg-white/90 p-8 shadow-sm lg:grid-cols-[1.3fr_1fr]">
                <div>
                    <h3 class="text-lg font-semibold text-slate-900">Identitas Akun</h3>
                    <p class="mt-2 text-sm text-slate-500">Perbarui data kontak agar tim Deadlineku mudah menghubungi kamu.</p>
                    <dl class="mt-6 grid gap-4 text-sm text-slate-600 sm:grid-cols-2">
                        <div class="rounded-2xl border border-slate-100 p-4">
                            <dt class="text-xs uppercase tracking-[0.3em] text-slate-400">Email</dt>
                            <dd class="mt-2 text-base font-semibold text-slate-900">{{ $user->email }}</dd>
                        </div>
                        <div class="rounded-2xl border border-slate-100 p-4">
                            <dt class="text-xs uppercase tracking-[0.3em] text-slate-400">Nomor</dt>
                            <dd class="mt-2 text-base font-semibold text-slate-900">{{ $user->phone ?? 'Belum diisi' }}</dd>
                        </div>
                        <div class="rounded-2xl border border-slate-100 p-4">
                            <dt class="text-xs uppercase tracking-[0.3em] text-slate-400">Member sejak</dt>
                            <dd class="mt-2 text-base font-semibold text-slate-900">{{ $user->created_at?->translatedFormat('d M Y') }}</dd>
                        </div>
                        @php
                            $emailVerified = ! is_null($user->email_verified_at);
                        @endphp
                        <div class="rounded-2xl border border-slate-100 p-4">
                            <dt class="text-xs uppercase tracking-[0.3em] text-slate-400">Status Email</dt>
                            <dd class="mt-2 text-base font-semibold {{ $emailVerified ? 'text-emerald-600' : 'text-amber-600' }}">
                                {{ $emailVerified ? 'Terverifikasi' : 'Belum verifikasi' }}
                            </dd>
                        </div>
                    </dl>
                </div>
                <div class="rounded-3xl bg-slate-900 p-6 text-white">
                    <p class="text-sm uppercase tracking-[0.3em] text-indigo-200">Keamanan akun</p>
                    <h3 class="mt-3 text-2xl font-semibold">Aktifkan autentikasi ganda segera.</h3>
                    <p class="mt-3 text-sm text-slate-200">Hubungi admin untuk mengaktifkan fitur advance security seperti OTP email & device approval.</p>
                    <button class="mt-6 inline-flex w-full items-center justify-center rounded-2xl bg-white/95 px-4 py-3 text-sm font-semibold text-slate-900 hover:bg-white">Hubungi support</button>
                </div>
            </section>

            <section class="grid gap-6 lg:grid-cols-2">
                <div class="rounded-3xl bg-white/90 p-6 shadow-sm">
                    <p class="text-xs uppercase tracking-[0.4em] text-slate-400">Update Data</p>
                    <h3 class="mt-2 text-xl font-semibold text-slate-900">Informasi pribadi</h3>
                    <div class="mt-6">
                        @include('profile.partials.update-profile-information-form')
                    </div>
                </div>

                <div class="rounded-3xl bg-white/90 p-6 shadow-sm">
                    <p class="text-xs uppercase tracking-[0.4em] text-slate-400">Kata Sandi</p>
                    <h3 class="mt-2 text-xl font-semibold text-slate-900">Ubah password berkala</h3>
                    <div class="mt-6">
                        @include('profile.partials.update-password-form')
                    </div>
                </div>
            </section>

            <section class="rounded-3xl bg-rose-50 p-6 shadow-sm">
                <p class="text-xs uppercase tracking-[0.4em] text-rose-400">Zona berbahaya</p>
                <h3 class="mt-2 text-xl font-semibold text-rose-700">Hapus akun</h3>
                <p class="mt-2 text-sm text-rose-500">Tindakan ini tidak dapat dibatalkan dan akan menghapus seluruh riwayat order, tiket, dan pembayaran.</p>
                <div class="mt-6 rounded-2xl bg-white/90 p-4">
                    @include('profile.partials.delete-user-form')
                </div>
            </section>
        </div>
    </div>
</x-app-layout>
