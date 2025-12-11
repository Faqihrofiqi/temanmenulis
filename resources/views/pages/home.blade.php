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
            <div class="rounded-3xl border border-slate-200/60 bg-white/90 p-6 shadow-2xl shadow-indigo-200/40 dark:border-white/5 dark:bg-white/5">
                <div class="flex flex-col gap-2 rounded-2xl bg-slate-100/70 px-6 py-5 text-slate-900 dark:bg-slate-900/80 dark:text-white">
                    <p class="text-xs font-semibold uppercase tracking-[0.35em] text-indigo-500 dark:text-indigo-200">Studio visual</p>
                    <h3 class="text-3xl font-semibold">Moodboard orkestrasi</h3>
                <div class="mt-8 space-y-4">
                    <figure class="group relative overflow-hidden rounded-[28px] border border-slate-200 bg-slate-900/5 shadow-lg dark:border-white/10 dark:bg-white/5">
                        <img src="https://images.unsplash.com/photo-1521737604893-d14cc237f11d?auto=format&fit=crop&w=1200&q=80" alt="Tim sedang melakukan stand-up meeting di depan layar analitik" class="h-64 w-full object-cover transition duration-700 group-hover:scale-105">
                        <figcaption class="absolute inset-0 flex flex-col justify-end bg-gradient-to-t from-slate-950/80 via-slate-900/0 to-transparent p-6 text-white">
                            <p class="text-xs font-semibold tracking-[0.4em] text-white/70">Ops Center</p>
                            <p class="mt-2 text-2xl font-semibold">Layer monitoring live</p>
                            <p class="mt-1 text-sm text-white/80">Snapshot pipeline akademik lengkap dengan status integrasi TemanMenulis API.</p>
                        </figcaption>
                    </figure>
                    <div class="grid gap-4 sm:grid-cols-2">
                        <figure class="group relative overflow-hidden rounded-[24px] border border-slate-200 bg-white shadow-lg dark:border-white/10 dark:bg-white/10">
                            <img src="https://images.unsplash.com/photo-1529333166437-7750a6dd5a70?auto=format&fit=crop&w=800&q=80" alt="Workspace dengan perangkat keras dan layar kode" class="h-48 w-full object-cover transition duration-700 group-hover:scale-105">
                            <figcaption class="absolute inset-0 flex flex-col justify-end bg-gradient-to-t from-slate-950/80 via-slate-900/0 to-transparent p-5 text-white">
                                <p class="text-xs uppercase tracking-[0.35em] text-white/70">Pipeline node</p>
                                <p class="mt-1 text-lg font-semibold">Worker & queue</p>
                            </figcaption>
                        </figure>
                        <figure class="group relative overflow-hidden rounded-[24px] border border-slate-200 bg-white shadow-lg dark:border-white/10 dark:bg-white/10">
                            <img src="https://images.unsplash.com/photo-1498050108023-c5249f4df085?auto=format&fit=crop&w=800&q=80" alt="Dashboard cloud dengan grafik infrastruktur" class="h-48 w-full object-cover transition duration-700 group-hover:scale-105">
                            <figcaption class="absolute inset-0 flex flex-col justify-end bg-gradient-to-t from-slate-950/80 via-slate-900/0 to-transparent p-5 text-white">
                                <p class="text-xs uppercase tracking-[0.35em] text-white/70">Client view</p>
                                <p class="mt-1 text-lg font-semibold">Status tiket & SLA</p>
                            </figcaption>
                        </figure>
                    </div>
                </div>
                <div class="mt-6 flex flex-wrap gap-2 text-xs text-slate-600 dark:text-slate-200">
                    <span class="rounded-full border border-slate-200/70 px-3 py-1 dark:border-white/20">TemanMenulis API</span>
                    <span class="rounded-full border border-slate-200/70 px-3 py-1 dark:border-white/20">QRIS manual</span>
                    <span class="rounded-full border border-slate-200/70 px-3 py-1 dark:border-white/20">Ticket orchestration</span>
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
                        <div class="mt-3 inline-flex items-center gap-3 rounded-full border-2 border-rose-400/70 bg-gradient-to-r from-rose-600 to-orange-500 px-4 py-2 text-xs font-semibold uppercase tracking-[0.35em] text-white shadow-lg shadow-rose-500/30">
                            <span>-{{ number_format($service->discount_percentage, (floor($service->discount_percentage) == $service->discount_percentage) ? 0 : 1) }}%</span>
                            <span>{{ $service->discount_label ?? 'Promo aktif' }}</span>
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
                        @auth
                            <a href="{{ route('orders.create', ['service' => $service->slug]) }}" class="rounded-2xl border border-slate-300/80 px-4 py-3 text-center font-semibold text-slate-900 transition hover:border-slate-500 dark:border-white/20 dark:text-white dark:hover:border-white/60">Pesan</a>
                        @else
                            <a href="{{ route('register') }}?service={{ $service->slug }}" class="rounded-2xl border border-slate-300/80 px-4 py-3 text-center font-semibold text-slate-900 transition hover:border-slate-500 dark:border-white/20 dark:text-white dark:hover:border-white/60">Pesan</a>
                        @endauth
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
