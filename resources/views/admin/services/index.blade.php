<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-2 text-slate-900 dark:text-white">
            <p class="text-xs uppercase tracking-[0.4em] text-muted">Admin · Layanan</p>
            <h1 class="text-3xl font-semibold">Kelola paket & promo deadlineku</h1>
            <p class="text-sm text-subtle">Percepat pengaturan harga, SLA, dan badge promo dari satu halaman khusus.</p>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl space-y-8 px-4 sm:px-6 lg:px-8">
            <section class="rounded-3xl border border-slate-100 bg-white/95 p-6 shadow-sm dark:border-white/10 dark:bg-white/5">
                <form method="GET" class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                    <div>
                        <p class="text-xs uppercase tracking-[0.35em] text-muted">Filter</p>
                        <h2 class="mt-1 text-xl font-semibold text-slate-900 dark:text-white">Prioritaskan kategori atau promo aktif</h2>
                    </div>
                    <div class="flex flex-wrap items-center gap-3">
                        <select name="category" class="form-input text-sm">
                            <option value="">Semua kategori</option>
                            @foreach($categories as $option)
                                <option value="{{ $option }}" @selected($category === $option)>{{ ucfirst($option) }}</option>
                            @endforeach
                        </select>
                        <label class="inline-flex items-center gap-2 rounded-full border px-4 py-2 text-sm font-semibold {{ $promoOnly ? 'border-slate-900 bg-slate-900 text-white dark:border-white/60 dark:bg-white dark:text-slate-900' : 'border-slate-200 text-subtle hover:border-slate-500 hover:text-slate-900 dark:border-white/20 dark:text-slate-300 dark:hover:text-white' }}">
                            <input type="checkbox" name="promo" value="1" class="h-4 w-4" {{ $promoOnly ? 'checked' : '' }}>
                            Promo aktif dulu
                        </label>
                        <button type="submit" class="rounded-full bg-slate-900 px-4 py-2 text-sm font-semibold text-white transition hover:bg-slate-800 dark:bg-white dark:text-slate-900">Terapkan</button>
                        <a href="{{ route('admin.services.index') }}" class="text-sm font-semibold text-slate-600 underline dark:text-slate-300">Reset</a>
                    </div>
                </form>
            </section>

            @if (session('success'))
                <div class="rounded-3xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800 dark:border-emerald-400/40 dark:bg-emerald-400/10 dark:text-emerald-100">
                    {{ session('success') }}
                </div>
            @endif

            <section class="space-y-6">
                <div class="flex flex-col gap-2 lg:flex-row lg:items-center lg:justify-between">
                    <div>
                        <p class="text-xs uppercase tracking-[0.35em] text-muted">Daftar layanan</p>
                        <h2 class="text-2xl font-semibold text-slate-900 dark:text-white">{{ $services->count() }} paket siap diterbitkan</h2>
                        <p class="text-sm text-subtle">Atur harga dasar, SLA pengerjaan, dan badge promo tanpa kembali ke dashboard utama.</p>
                    </div>
                    <div class="flex gap-3 text-xs text-subtle">
                        <span class="rounded-full border border-slate-200 px-4 py-2 dark:border-white/20">Promo aktif: {{ $services->filter->has_active_discount->count() }}</span>
                        <span class="rounded-full border border-slate-200 px-4 py-2 dark:border-white/20">Featured: {{ $services->where('is_featured', true)->count() }}</span>
                    </div>
                </div>

                <div class="grid gap-5 lg:grid-cols-2">
                    @forelse($services as $service)
                        <form method="POST" action="{{ route('admin.services.update', $service) }}" class="rounded-3xl border border-slate-200/80 bg-white/95 p-5 text-sm text-slate-700 shadow-sm dark:border-white/10 dark:bg-slate-950/60 dark:text-slate-200">
                            @csrf
                            @method('PATCH')
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-xs uppercase tracking-[0.35em] text-muted">{{ $service->category ?? 'Tanpa kategori' }}</p>
                                    <h3 class="text-xl font-semibold text-slate-900 dark:text-white">{{ $service->name }}</h3>
                                </div>
                                <span class="text-xs text-subtle">{{ $service->is_active ? 'Aktif' : 'Nonaktif' }}</span>
                            </div>
                            <div class="mt-4 grid gap-4 sm:grid-cols-2">
                                <div>
                                    <label class="form-label">Harga dasar</label>
                                    <input type="number" step="1000" min="0" name="price" value="{{ old('price', $service->price) }}" class="form-input mt-2" required>
                                    <p class="mt-1 text-xs text-subtle">Harga efektif: Rp{{ number_format($service->effective_price, 0, ',', '.') }}</p>
                                </div>
                                <div>
                                    <label class="form-label">Durasi (hari)</label>
                                    <input type="number" min="1" max="90" name="delivery_days" value="{{ old('delivery_days', $service->delivery_days) }}" class="form-input mt-2" required>
                                </div>
                            </div>
                            <div class="mt-4 grid gap-4 sm:grid-cols-3">
                                <div>
                                    <label class="form-label">Diskon (%)</label>
                                    <input type="number" step="0.5" min="0" max="95" name="discount_percentage" value="{{ old('discount_percentage', $service->discount_percentage) }}" class="form-input mt-2" placeholder="0">
                                </div>
                                <div class="sm:col-span-2">
                                    <label class="form-label">Label event</label>
                                    <input type="text" name="discount_label" value="{{ old('discount_label', $service->discount_label) }}" class="form-input mt-2" placeholder="Ramadhan Sprint">
                                </div>
                            </div>
                            <div class="mt-4">
                                <label class="form-label">Diskon berakhir</label>
                                <input type="datetime-local" name="discount_ends_at" value="{{ optional($service->discount_ends_at)->format('Y-m-d\TH:i') }}" class="form-input mt-2">
                            </div>
                            <div class="mt-4 flex flex-wrap gap-4 text-xs text-subtle dark:text-slate-300">
                                <label class="inline-flex items-center gap-2">
                                    <input type="hidden" name="is_active" value="0">
                                    <input type="checkbox" name="is_active" value="1" class="h-4 w-4 rounded border-slate-300 bg-white text-indigo-600 focus:ring-indigo-500 dark:border-white/30 dark:bg-slate-900" @checked($service->is_active)>
                                    <span>Aktifkan paket</span>
                                </label>
                                <label class="inline-flex items-center gap-2">
                                    <input type="hidden" name="is_featured" value="0">
                                    <input type="checkbox" name="is_featured" value="1" class="h-4 w-4 rounded border-slate-300 bg-white text-indigo-600 focus:ring-indigo-500 dark:border-white/30 dark:bg-slate-900" @checked($service->is_featured)>
                                    <span>Jadikan unggulan</span>
                                </label>
                            </div>
                            <button type="submit" class="mt-5 w-full rounded-2xl bg-slate-900 px-4 py-2 text-sm font-semibold text-white transition hover:bg-slate-700 dark:bg-white dark:text-slate-900">Simpan perubahan</button>
                        </form>
                    @empty
                        <div class="rounded-3xl border border-dashed border-slate-200 px-6 py-20 text-center text-sm text-subtle dark:border-white/20">Belum ada layanan.</div>
                    @endforelse
                </div>
            </section>
        </div>
    </div>
</x-app-layout>
