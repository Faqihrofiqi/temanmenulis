@extends('layouts.site')

@section('content')
<section class="px-6 pt-16 pb-12 sm:pt-24 bg-gradient-to-b from-white via-slate-50 to-white dark:from-transparent dark:via-transparent dark:to-transparent" id="beranda">
    <div class="mx-auto grid max-w-6xl gap-12 lg:grid-cols-[1.2fr_0.8fr]">
        <div>
            <p class="text-xs font-semibold uppercase tracking-[0.35em] text-slate-500 dark:text-slate-400">Platform layanan akademik</p>
            <h1 class="mt-6 text-4xl font-semibold leading-tight text-slate-900 sm:text-5xl lg:text-6xl dark:text-white">
                Deadlineku menyatukan pesanan, pembayaran, dan ticketing dalam satu orkestrasi Laravel.
            </h1>
            <p class="mt-6 text-lg text-slate-600 sm:text-xl dark:text-slate-300">
                Dari proposal hingga wisuda, tim kami merilis UI baru yang memprioritaskan kejelasan alur dan status order. Dibangun real-time dengan API internal serta integrasi QRIS manual.
            </p>
            <div class="mt-10 flex flex-wrap gap-4">
                <a href="{{ route('orders.create') }}" class="rounded-full bg-gradient-to-r from-indigo-500 via-sky-500 to-cyan-400 px-6 py-3 text-base font-semibold text-white shadow-lg shadow-indigo-500/30 transition hover:opacity-90">
                    Buat pesanan
                </a>
                <a href="#alur" class="rounded-full border border-slate-300/80 px-6 py-3 text-base font-semibold text-slate-900 transition hover:border-slate-500 dark:border-white/20 dark:text-white dark:hover:border-white/60">
                    Pelajari alur
                </a>
            </div>
            <dl class="mt-12 grid grid-cols-2 gap-6 text-sm text-slate-500 sm:grid-cols-3 dark:text-slate-300">
                @foreach($stats as $stat)
                    <div>
                        <dt class="text-xs uppercase tracking-widest text-slate-500 dark:text-slate-400">{{ $stat['label'] }}</dt>
                        <dd class="mt-2 text-3xl font-semibold text-slate-900 dark:text-white">{{ $stat['value'] }}</dd>
                    </div>
                @endforeach
            </dl>
        </div>
        <div class="relative">
            <div class="rounded-3xl border border-slate-200/60 bg-white/80 p-6 shadow-2xl shadow-indigo-200/40 dark:border-white/5 dark:bg-white/5">
                <div class="rounded-2xl bg-slate-100 p-6 dark:bg-slate-900/80">
                    <p class="text-xs font-semibold tracking-[0.35em] text-indigo-500 dark:text-indigo-200">Realtime Monitor</p>
                    <p class="mt-4 text-3xl font-semibold text-slate-900 dark:text-white">Pipeline Deadlineku</p>
                    <p class="mt-2 text-sm text-slate-600 dark:text-slate-400">Autentikasi Breeze, API Sanctum, QRIS service, dan modul ticket menyatu dalam satu timeline.</p>
                    <div class="mt-6 space-y-4">
                        <div class="rounded-2xl border border-slate-200 bg-white p-4 dark:border-white/10 dark:bg-white/5">
                            <div class="flex items-center justify-between text-sm">
                                <span class="font-medium text-slate-900 dark:text-white">Webhook QRIS</span>
                                <span class="text-emerald-600 dark:text-emerald-300">200 OK</span>
                            </div>
                            <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">Pembayaran memicu update order dan log payment.</p>
                        </div>
                        <div class="rounded-2xl border border-slate-200 bg-white p-4 dark:border-white/10 dark:bg-white/5">
                            <div class="flex items-center justify-between text-sm">
                                <span class="font-medium text-slate-900 dark:text-white">Ticket Support</span>
                                <span class="text-sky-500 dark:text-sky-200">3 pesan baru</span>
                            </div>
                            <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">User & admin berbagi kanal yang sama.</p>
                        </div>
                        <div class="rounded-2xl border border-slate-200 bg-white p-4 dark:border-white/10 dark:bg-white/5">
                            <div class="flex items-center justify-between text-sm">
                                <span class="font-medium text-slate-900 dark:text-white">Queue Worker</span>
                                <span class="text-amber-500 dark:text-amber-200">On standby</span>
                            </div>
                            <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">Siap menjalankan notifikasi progres pesanan.</p>
                        </div>
                    </div>
                </div>
                <div class="mt-4 rounded-2xl bg-white/70 p-4 text-sm text-slate-600 dark:bg-white/10 dark:text-slate-200">
                    <p class="font-semibold text-slate-900 dark:text-white">Rilis Desember 2025</p>
                    <p class="mt-1">UI ini siap dipaketkan via Vite, lengkap dengan dark gradient khas Deadlineku.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<section id="layanan" class="px-6 py-16 sm:py-20">
    <div class="mx-auto max-w-6xl">
        <div class="flex flex-col gap-6 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <p class="text-xs font-semibold uppercase tracking-[0.35em] text-slate-500 dark:text-slate-400">Katalog unggulan</p>
                <h2 class="mt-3 text-3xl font-semibold text-slate-900 dark:text-white">Layanan yang sering dipilih</h2>
                <p class="mt-2 text-base text-slate-600 dark:text-slate-300">Data diambil langsung dari tabel services. Klik kartu untuk detail lengkap.</p>
            </div>
            <a href="{{ route('services.index') }}" class="rounded-full border border-slate-300/80 px-5 py-2 text-sm font-semibold text-slate-900 transition hover:border-slate-500 dark:border-white/20 dark:text-white dark:hover:border-white/60">Lihat semua</a>
        </div>

        <div class="mt-10 grid gap-6 md:grid-cols-2 lg:grid-cols-3">
            @foreach($services as $service)
                <article class="rounded-3xl border border-slate-200 bg-white p-6 shadow-lg shadow-slate-200/70 dark:border-white/10 dark:bg-white/5">
                    <div class="flex items-center justify-between text-xs uppercase tracking-[0.3em] text-slate-500 dark:text-slate-400">
                        <span>{{ $service->category }}</span>
                        @if($service->is_featured)
                            <span class="text-emerald-600 dark:text-emerald-300">Featured</span>
                        @endif
                    </div>
                    <h3 class="mt-4 text-2xl font-semibold text-slate-900 dark:text-white">{{ $service->name }}</h3>
                    @if($service->has_active_discount)
                        <div class="mt-2 inline-flex items-center gap-2 rounded-full border border-rose-500/30 bg-rose-100/80 px-3 py-1 text-xs font-semibold text-rose-700 dark:border-rose-400/40 dark:bg-rose-400/10 dark:text-rose-100">
                            <span>-{{ number_format($service->discount_percentage, (floor($service->discount_percentage) == $service->discount_percentage) ? 0 : 1) }}%</span>
                            @if($service->discount_label)
                                <span>{{ $service->discount_label }}</span>
                            @endif
                        </div>
                    @endif
                    <p class="mt-3 text-sm text-slate-600 dark:text-slate-300">{{ $service->short_description ?? \Illuminate\Support\Str::limit($service->description, 80) }}</p>
                    <div class="mt-6">
                        <p class="text-3xl font-semibold text-slate-900 dark:text-white">Rp{{ number_format($service->effective_price, 0, ',', '.') }}</p>
                        @if($service->has_active_discount)
                            <p class="text-xs text-slate-500 line-through">Rp{{ number_format($service->price, 0, ',', '.') }}</p>
                        @endif
                        <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Estimasi pengerjaan ± {{ $service->delivery_days ?? 14 }} hari</p>
                    </div>
                    <div class="mt-8 flex gap-3 text-sm">
                        <a href="{{ route('services.show', $service) }}" class="flex-1 rounded-2xl bg-slate-900 px-4 py-3 text-center font-semibold text-white transition hover:bg-slate-800 dark:bg-white dark:text-slate-950 dark:hover:bg-slate-100">Detail layanan</a>
                        <a href="{{ route('register') }}?service={{ $service->slug }}" class="rounded-2xl border border-slate-300/80 px-4 py-3 text-center font-semibold text-slate-900 transition hover:border-slate-500 dark:border-white/20 dark:text-white dark:hover:border-white/60">Pesan</a>
                    </div>
                </article>
            @endforeach
        </div>
    </div>
</section>

<section id="alur" class="bg-slate-100/80 px-6 py-16 sm:py-20 dark:bg-white/5">
    <div class="mx-auto max-w-6xl">
        <div class="flex flex-col gap-4 text-center">
            <p class="text-xs font-semibold uppercase tracking-[0.35em] text-slate-500 dark:text-slate-400">End-to-end</p>
            <h2 class="text-3xl font-semibold text-slate-900 dark:text-white">Tiga pilar utama Deadlineku</h2>
            <p class="text-base text-slate-600 dark:text-slate-300">Order → pembayaran → dukungan, semuanya memiliki endpoint publik dan UI khusus.</p>
        </div>
        <div class="mt-12 grid gap-6 md:grid-cols-3">
            @foreach([
                ['title' => 'Order Engine', 'copy' => 'Form order terhubung ke API /api/orders sekaligus memetakan metadata layanan.'],
                ['title' => 'Manual QRIS', 'copy' => 'Service QRIS menghasilkan payload statis yang mudah dipindai dan dikonfirmasi admin.'],
                ['title' => 'Ticketing', 'copy' => 'Pengguna bisa membuka tiket baru, admin bisa takeover langsung di dashboard.'],
            ] as $index => $item)
                <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm dark:border-white/10 dark:bg-slate-950/60">
                    <span class="text-4xl font-semibold text-slate-900 dark:text-white">0{{ $index + 1 }}</span>
                    <h3 class="mt-4 text-xl font-semibold text-slate-900 dark:text-white">{{ $item['title'] }}</h3>
                    <p class="mt-3 text-sm text-slate-600 dark:text-slate-300">{{ $item['copy'] }}</p>
                </div>
            @endforeach
        </div>
    </div>
</section>

<section id="testimoni" class="px-6 py-16 sm:py-20">
    <div class="mx-auto max-w-5xl">
        <div class="flex flex-col gap-3 text-center">
            <p class="text-xs font-semibold uppercase tracking-[0.35em] text-slate-500 dark:text-slate-400">Cerita pengguna</p>
            <h2 class="text-3xl font-semibold text-slate-900 dark:text-white">Kepercayaan yang terus tumbuh</h2>
            <p class="text-base text-slate-600 dark:text-slate-300">Testimoni di bawah ini hanyalah contoh seed untuk UI. Anda bisa menggantinya dengan data asli kapan saja.</p>
        </div>
        <div class="mt-10 grid gap-6 md:grid-cols-3">
            @foreach($testimonials as $testimonial)
                <article class="rounded-3xl border border-slate-200 bg-white p-6 text-left dark:border-white/10 dark:bg-white/5">
                    <p class="text-sm text-slate-600 dark:text-slate-300">“{{ $testimonial['text'] }}”</p>
                    <div class="mt-4">
                        <p class="text-sm font-semibold text-slate-900 dark:text-white">{{ $testimonial['name'] }}</p>
                        <p class="text-xs uppercase tracking-[0.3em] text-slate-500 dark:text-slate-500">{{ $testimonial['role'] }}</p>
                    </div>
                </article>
            @endforeach
        </div>
    </div>
</section>

<section class="px-6 pb-20">
    <div class="mx-auto max-w-4xl rounded-[2rem] border border-slate-200 bg-gradient-to-r from-indigo-100 via-slate-50 to-white p-10 text-center dark:border-white/10 dark:from-indigo-500/20 dark:via-slate-900 dark:to-slate-950">
        <p class="text-xs font-semibold uppercase tracking-[0.35em] text-slate-500 dark:text-slate-200">Siap mulai?</p>
        <h2 class="mt-4 text-3xl font-semibold text-slate-900 dark:text-white">Bangun workflow Deadlineku versi Anda.</h2>
        <p class="mt-3 text-base text-slate-600 dark:text-slate-300">Integrasikan modul-modul ini dengan project internal, atau pakai langsung sebagai portal layanan akademik.</p>
        <div class="mt-8 flex flex-wrap justify-center gap-4">
            <a href="mailto:hello@deadlineku.id" class="rounded-full bg-slate-900 px-6 py-3 text-base font-semibold text-white transition hover:bg-slate-800 dark:bg-white dark:text-slate-950 dark:hover:bg-slate-100">Hubungi tim</a>
            <a href="{{ route('register') }}" class="rounded-full border border-slate-300/80 px-6 py-3 text-base font-semibold text-slate-900 transition hover:border-slate-500 dark:border-white/30 dark:text-white dark:hover:border-white/60">Buat akun</a>
        </div>
    </div>
</section>
@endsection
