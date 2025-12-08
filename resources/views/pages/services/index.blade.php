@extends('layouts.site')

@section('content')
<section class="px-6 pt-16 pb-10">
    <div class="mx-auto max-w-5xl text-center">
        <p class="text-xs font-semibold uppercase tracking-[0.35em] text-slate-400">Katalog Deadlineku</p>
        <h1 class="mt-4 text-4xl font-semibold text-white">Semua layanan disusun per tahap akademik.</h1>
        <p class="mt-3 text-base text-slate-300">Gunakan filter kategori untuk menemukan paket proposal, penelitian, atau disertasi yang sesuai.</p>
    </div>

    <div class="mx-auto mt-10 flex max-w-4xl flex-wrap items-center gap-4 rounded-3xl border border-white/10 bg-white/5 p-6 text-sm text-white">
        <form method="GET" class="flex flex-1 flex-col gap-3 sm:flex-row sm:items-center">
            <label class="text-xs uppercase tracking-[0.3em] text-slate-400">Kategori</label>
            <div class="flex flex-1 flex-wrap gap-2">
                <button type="submit" name="category" value="" class="rounded-full border px-4 py-2 transition {{ $category ? 'border-white/10 text-slate-400' : 'border-white/60 bg-white/10 text-white' }}">
                    Semua
                </button>
                @foreach($categories as $option)
                    <button type="submit" name="category" value="{{ $option }}" class="rounded-full border px-4 py-2 transition {{ $category === $option ? 'border-white/60 bg-white/10 text-white' : 'border-white/10 text-slate-400 hover:border-white/40 hover:text-white' }}">
                        {{ ucfirst($option) }}
                    </button>
                @endforeach
            </div>
        </form>
        <a href="{{ route('services.index') }}" class="rounded-full border border-white/10 px-4 py-2 text-xs font-semibold uppercase tracking-[0.3em] text-slate-300 transition hover:border-white/40 hover:text-white">Reset</a>
    </div>
</section>

<section class="px-6 pb-16">
    <div class="mx-auto max-w-6xl">
        <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
            @forelse($services as $service)
                <article class="rounded-3xl border border-white/10 bg-white/5 p-6">
                    <div class="flex items-center justify-between text-xs uppercase tracking-[0.3em] text-slate-400">
                        <span>{{ $service->category }}</span>
                        @if($service->is_featured)
                            <span class="text-emerald-300">Favorit</span>
                        @endif
                    </div>
                    <h2 class="mt-4 text-2xl font-semibold text-white">{{ $service->name }}</h2>
                    @if($service->has_active_discount)
                        <div class="mt-2 inline-flex items-center gap-2 rounded-full border border-rose-400/40 bg-rose-400/10 px-3 py-1 text-xs font-semibold text-rose-100">
                            <span>-{{ number_format($service->discount_percentage, (floor($service->discount_percentage) == $service->discount_percentage) ? 0 : 1) }}%</span>
                            @if($service->discount_label)
                                <span>{{ $service->discount_label }}</span>
                            @endif
                        </div>
                    @endif
                    <p class="mt-3 text-sm text-slate-300">{{ $service->description }}</p>
                    <div class="mt-6 flex items-center justify-between text-sm text-slate-300">
                        <span>Pengerjaan ± {{ $service->delivery_days ?? 14 }} hari</span>
                        <div class="text-right">
                            <p class="text-3xl font-semibold text-white">Rp{{ number_format($service->effective_price, 0, ',', '.') }}</p>
                            @if($service->has_active_discount)
                                <p class="text-xs text-slate-500 line-through">Rp{{ number_format($service->price, 0, ',', '.') }}</p>
                            @endif
                        </div>
                    </div>
                    <ul class="mt-6 space-y-2 text-sm text-slate-200">
                        @foreach(array_slice($service->features ?? [], 0, 4) as $feature)
                            <li class="flex items-start gap-2">
                                <span class="mt-1 h-2 w-2 rounded-full bg-emerald-300"></span>
                                <span>{{ $feature }}</span>
                            </li>
                        @endforeach
                    </ul>
                    <div class="mt-8 flex flex-wrap gap-3 text-sm">
                        <a href="{{ route('services.show', $service) }}" class="flex-1 rounded-2xl bg-white px-4 py-3 text-center font-semibold text-slate-950 transition hover:bg-slate-100">Detail</a>
                        <a href="{{ route('register') }}?service={{ $service->slug }}" class="rounded-2xl border border-white/20 px-4 py-3 text-center font-semibold text-white transition hover:border-white/60">Pesan</a>
                    </div>
                </article>
            @empty
                <p class="col-span-full rounded-3xl border border-white/10 bg-white/5 p-6 text-center text-slate-300">Belum ada layanan untuk kategori ini.</p>
            @endforelse
        </div>
        <div class="mt-10 text-center text-white">
            {{ $services->links() }}
        </div>
    </div>
</section>
@endsection
