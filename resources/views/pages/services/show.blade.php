@extends('layouts.site')

@section('content')
<section class="px-6 pt-16 pb-10">
    <div class="mx-auto max-w-4xl">
        <p class="text-xs font-semibold uppercase tracking-[0.35em] text-slate-400">Detail layanan</p>
        <h1 class="mt-4 text-4xl font-semibold text-white">{{ $service->name }}</h1>
        <p class="mt-3 text-base text-slate-300">{{ $service->description }}</p>
        <div class="mt-8 grid gap-6 rounded-3xl border border-white/10 bg-white/5 p-6 md:grid-cols-2">
            <div>
                <p class="text-xs uppercase tracking-[0.35em] text-slate-400">Investasi</p>
                <div class="mt-2">
                    <p class="text-4xl font-semibold text-white">Rp{{ number_format($service->effective_price, 0, ',', '.') }}</p>
                    @if($service->has_active_discount)
                        <p class="text-sm text-slate-400 line-through">Rp{{ number_format($service->price, 0, ',', '.') }}</p>
                        <div class="mt-2 inline-flex items-center gap-2 rounded-full border border-rose-400/40 bg-rose-400/10 px-3 py-1 text-xs font-semibold text-rose-100">
                            <span>-{{ number_format($service->discount_percentage, (floor($service->discount_percentage) == $service->discount_percentage) ? 0 : 1) }}%</span>
                            @if($service->discount_label)
                                <span>{{ $service->discount_label }}</span>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
            <div>
                <p class="text-xs uppercase tracking-[0.35em] text-slate-400">Estimasi pengerjaan</p>
                <p class="mt-2 text-2xl font-semibold text-white">{{ $service->delivery_days ?? 14 }} hari kerja</p>
            </div>
        </div>
    </div>
</section>

<section class="px-6 pb-16">
    <div class="mx-auto grid max-w-5xl gap-8 md:grid-cols-[1.2fr_0.8fr]">
        <div class="space-y-6">
            <div class="rounded-3xl border border-white/10 bg-white/5 p-6">
                <h2 class="text-2xl font-semibold text-white">Apa yang kamu dapatkan</h2>
                <ul class="mt-4 space-y-3 text-sm text-slate-200">
                    @forelse($service->features ?? [] as $feature)
                        <li class="flex items-start gap-3">
                            <span class="mt-1 h-2 w-2 rounded-full bg-emerald-300"></span>
                            <span>{{ $feature }}</span>
                        </li>
                    @empty
                        <li class="text-slate-400">Belum ada daftar fitur, silakan hubungi admin untuk detail.</li>
                    @endforelse
                </ul>
            </div>

            <div class="rounded-3xl border border-white/10 bg-white/5 p-6">
                <h3 class="text-xl font-semibold text-white">Langkah memesan</h3>
                <ol class="mt-4 space-y-3 text-sm text-slate-200">
                    <li>1. Daftar / masuk akun Deadlineku.</li>
                    <li>2. Pilih layanan ini saat membuat order baru dan lengkapi brief.</li>
                    <li>3. Lakukan pembayaran via QRIS manual, tunggu verifikasi admin.</li>
                    <li>4. Pantau progres di dashboard dan gunakan modul tiket untuk komunikasi.</li>
                </ol>
            </div>
        </div>
        <aside class="space-y-6">
            <div class="rounded-3xl border border-white/10 bg-gradient-to-br from-indigo-500/20 via-slate-900 to-slate-950 p-6">
                <p class="text-xs font-semibold uppercase tracking-[0.35em] text-slate-200">Siap lanjut?</p>
                <p class="mt-3 text-lg text-slate-100">Klik tombol di bawah untuk membuka form order dengan isian otomatis.</p>
                <div class="mt-6 space-y-3">
                    <a href="{{ route('register') }}?service={{ $service->slug }}" class="block rounded-2xl bg-white px-4 py-3 text-center text-sm font-semibold text-slate-950 transition hover:bg-slate-100">Buat akun & pesan</a>
                    <a href="{{ route('services.index') }}" class="block rounded-2xl border border-white/20 px-4 py-3 text-center text-sm font-semibold text-white transition hover:border-white/60">Kembali ke katalog</a>
                </div>
            </div>
            <div class="rounded-3xl border border-white/10 bg-white/5 p-6 text-sm text-slate-300">
                <p class="text-xs uppercase tracking-[0.35em] text-slate-400">Kontak tim</p>
                <p class="mt-2">Email: support@deadlineku.id</p>
                <p class="mt-1">WhatsApp: +62 812-1234-5678</p>
                <p class="mt-4 text-xs text-slate-500">Sertakan kode layanan <span class="font-semibold text-white">#{{ $service->slug }}</span> saat menghubungi kami.</p>
            </div>
        </aside>
    </div>
</section>
@endsection
