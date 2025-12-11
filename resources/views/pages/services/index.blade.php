@extends('layouts.site')

@section('content')
<section class="px-6 pt-16 pb-10">
    <div class="mx-auto max-w-5xl text-center">
        <p class="text-xs font-semibold uppercase tracking-[0.35em] text-muted">Katalog Deadlineku</p>
        <h1 class="mt-4 text-4xl font-semibold text-slate-900 dark:text-white">Semua layanan disusun per tahap akademik.</h1>
        <p class="mt-3 text-base text-subtle">Gunakan filter kategori untuk menemukan paket proposal, penelitian, atau disertasi yang sesuai.</p>
    </div>

    <div class="surface-card mx-auto mt-10 flex max-w-4xl flex-wrap items-center gap-4 p-6 text-sm">
        <form method="GET" class="flex flex-1 flex-col gap-3 sm:flex-row sm:items-center">
            <label class="text-xs uppercase tracking-[0.3em] text-muted">Kategori</label>
            <div class="flex flex-1 flex-wrap gap-2">
                <button type="submit" name="category" value="" class="rounded-full border px-4 py-2 transition {{ $category ? 'border-slate-200 text-subtle dark:border-white/20 dark:text-slate-400' : 'border-slate-900 bg-slate-900 text-white dark:border-white/60 dark:bg-white dark:text-slate-900' }}">
                    Semua
                </button>
                @foreach($categories as $option)
                    <button type="submit" name="category" value="{{ $option }}" class="rounded-full border px-4 py-2 transition {{ $category === $option ? 'border-slate-900 bg-slate-900 text-white dark:border-white/60 dark:bg-white dark:text-slate-900' : 'border-slate-200 text-subtle hover:border-slate-500 hover:text-slate-900 dark:border-white/20 dark:text-slate-400 dark:hover:text-white' }}">
                        {{ ucfirst($option) }}
                    </button>
                @endforeach
            </div>
        </form>
        <a href="{{ route('services.index') }}" class="rounded-full border border-slate-200 px-4 py-2 text-xs font-semibold uppercase tracking-[0.3em] text-subtle transition hover:border-slate-500 hover:text-slate-900 dark:border-white/20 dark:text-slate-400 dark:hover:text-white">Reset</a>
    </div>
</section>

@if($promoHighlights->isNotEmpty())
<section class="px-6 pb-16">
    <div class="mx-auto max-w-6xl rounded-[32px] border border-rose-200 bg-gradient-to-r from-rose-600 via-orange-500 to-amber-400 p-1 shadow-xl shadow-rose-500/30 dark:border-rose-400/40">
        <div class="rounded-[30px] bg-white/95 p-6 dark:bg-slate-950/70">
            <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-[0.4em] text-rose-500">Promo Aktif</p>
                    <h2 class="mt-2 text-3xl font-semibold text-slate-900 dark:text-white">Diskon spesial tampil lebih dulu.</h2>
                    <p class="mt-1 text-sm text-subtle dark:text-slate-300">Badge besar memastikan klien langsung melihat campaign Ramadhan, thesis week, atau flash sale lainnya.</p>
                </div>
                <span class="inline-flex items-center gap-2 rounded-full bg-rose-100 px-4 py-2 text-sm font-semibold text-rose-700 shadow-sm dark:bg-rose-500/20 dark:text-rose-100">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-4 w-4">
                        <path d="M12 2a1 1 0 0 1 .9.56l2.52 5.11 5.64.82a1 1 0 0 1 .55 1.7l-4.08 3.98.96 5.61a1 1 0 0 1-1.45 1.05L12 18.89l-5.04 2.64a1 1 0 0 1-1.45-1.05l.96-5.61-4.08-3.98a1 1 0 0 1 .55-1.7l5.64-.82L11.1 2.56A1 1 0 0 1 12 2Z"/>
                    </svg>
                    {{ $promoHighlights->count() }} paket
                </span>
            </div>

            <div class="mt-6 grid gap-4 md:grid-cols-2 lg:grid-cols-4">
                @foreach($promoHighlights as $promo)
                    <article class="rounded-3xl border border-rose-100/70 bg-white p-4 dark:border-white/10 dark:bg-white/5">
                        <div class="flex items-center justify-between text-xs uppercase tracking-[0.3em] text-muted">
                            <span>{{ $promo->category }}</span>
                            <span class="text-emerald-600 dark:text-emerald-300">Hemat {{ number_format($promo->discount_percentage, (floor($promo->discount_percentage) == $promo->discount_percentage) ? 0 : 1) }}%</span>
                        </div>
                        <h3 class="mt-3 text-lg font-semibold text-slate-900 dark:text-white">{{ $promo->name }}</h3>
                        <div class="mt-3 inline-flex items-center gap-2 rounded-full border-2 border-rose-400/70 bg-rose-500/10 px-3 py-1 text-xs font-semibold uppercase tracking-[0.4em] text-rose-600 dark:border-rose-300/40 dark:text-rose-200">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-4 w-4">
                                <path d="M12.66 2.1a.75.75 0 0 0-1.2.33c-.76 2.4-2.27 3.8-3.66 4.82-1.03.75-2 .95-2.73.95a.75.75 0 0 0-.7.98c1.1 3.35 2.62 5.2 3.93 6.18 1.32.99 2.52 1.19 3.31 1.19.79 0 1.99-.2 3.3-1.19 1.32-.98 2.85-2.83 3.94-6.18a.75.75 0 0 0-.7-.98c-.72 0-1.7-.2-2.73-.95-1.4-1.02-2.9-2.42-3.66-4.82a.75.75 0 0 0-.2-.33Z"/>
                            </svg>
                            {{ $promo->discount_label ?? 'Promo aktif' }}
                        </div>
                        <p class="mt-3 text-2xl font-semibold text-slate-900 dark:text-white">Rp{{ number_format($promo->effective_price, 0, ',', '.') }}</p>
                        <p class="text-xs text-subtle line-through">Rp{{ number_format($promo->price, 0, ',', '.') }}</p>
                    </article>
                @endforeach
            </div>
        </div>
    </div>
</section>
@endif

<section class="px-6 pb-16">
    <div class="mx-auto max-w-6xl">
        <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
            @forelse($services as $service)
                <article class="surface-card p-6">
                    <div class="flex items-center justify-between text-xs uppercase tracking-[0.3em] text-muted">
                        <span>{{ $service->category }}</span>
                        @if($service->is_featured)
                            <span class="font-semibold text-emerald-600 dark:text-emerald-300">Favorit</span>
                        @endif
                    </div>
                    <h2 class="mt-4 text-2xl font-semibold text-slate-900 dark:text-white">{{ $service->name }}</h2>
                    @if($service->has_active_discount)
                        <div class="mt-3 inline-flex items-center gap-3 rounded-full border-2 border-rose-400/70 bg-gradient-to-r from-rose-600 to-orange-500 px-4 py-2 text-xs font-semibold uppercase tracking-[0.4em] text-white shadow-lg shadow-rose-500/30">
                            <span>-{{ number_format($service->discount_percentage, (floor($service->discount_percentage) == $service->discount_percentage) ? 0 : 1) }}%</span>
                            <span>{{ $service->discount_label ?? 'Promo aktif' }}</span>
                        </div>
                    @endif
                    <p class="mt-3 text-sm text-muted">{{ $service->description }}</p>
                    <div class="mt-6 flex items-center justify-between text-sm text-muted">
                        <span>Pengerjaan ± {{ $service->delivery_days ?? 14 }} hari</span>
                        <div class="text-right">
                            <p class="text-3xl font-semibold text-slate-900 dark:text-white">Rp{{ number_format($service->effective_price, 0, ',', '.') }}</p>
                            @if($service->has_active_discount)
                                <p class="text-xs text-subtle line-through">Rp{{ number_format($service->price, 0, ',', '.') }}</p>
                            @endif
                        </div>
                    </div>
                    <ul class="mt-6 space-y-2 text-sm text-muted">
                        @foreach(array_slice($service->features ?? [], 0, 4) as $feature)
                            <li class="flex items-start gap-2">
                                <span class="mt-1 h-2 w-2 rounded-full bg-emerald-500/80 dark:bg-emerald-300"></span>
                                <span>{{ $feature }}</span>
                            </li>
                        @endforeach
                    </ul>
                    <div class="mt-8 flex flex-wrap gap-3 text-sm">
                        <a href="{{ route('services.show', $service) }}" class="flex-1 rounded-2xl bg-slate-900 px-4 py-3 text-center font-semibold text-white transition hover:bg-slate-800 dark:bg-white dark:text-slate-900 dark:hover:bg-slate-100">Detail</a>
                        @auth
                            <a href="{{ route('orders.create', ['service' => $service->slug]) }}" class="rounded-2xl border border-slate-900 px-4 py-3 text-center font-semibold text-slate-900 transition hover:bg-slate-900 hover:text-white dark:border-white/40 dark:text-white">Pesan</a>
                        @else
                            <a href="{{ route('register') }}?service={{ $service->slug }}" class="rounded-2xl border border-slate-900 px-4 py-3 text-center font-semibold text-slate-900 transition hover:bg-slate-900 hover:text-white dark:border-white/40 dark:text-white">Pesan</a>
                        @endauth
                    </div>
                </article>
            @empty
                <p class="surface-card col-span-full p-6 text-center text-muted">Belum ada layanan untuk kategori ini.</p>
            @endforelse
        </div>
        <div class="mt-10 text-center text-slate-900 dark:text-white">
            {{ $services->links() }}
        </div>
    </div>
</section>
@endsection
