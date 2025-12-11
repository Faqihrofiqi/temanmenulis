@php
    $statusColors = [
        'pending' => 'border-amber-200 bg-amber-50 text-amber-700 dark:border-amber-400/40 dark:bg-amber-400/10 dark:text-amber-100',
        'processing' => 'border-sky-200 bg-sky-50 text-sky-700 dark:border-sky-400/40 dark:bg-sky-400/10 dark:text-sky-100',
        'paid' => 'border-emerald-200 bg-emerald-50 text-emerald-700 dark:border-emerald-400/40 dark:bg-emerald-400/10 dark:text-emerald-100',
        'completed' => 'border-slate-200 bg-slate-100 text-slate-700 dark:border-white/10 dark:bg-white/10 dark:text-white',
        'cancelled' => 'border-rose-200 bg-rose-50 text-rose-700 dark:border-rose-400/40 dark:bg-rose-400/10 dark:text-rose-100',
    ];

    $formatPercent = fn ($value) => number_format($value, (floor($value) == $value) ? 0 : 1);
@endphp

<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm uppercase tracking-[0.3em] text-slate-400 dark:text-slate-500">{{ $user->isAdmin() ? 'Admin Control Center' : 'Selamat Datang Kembali' }}</p>
                <h2 class="mt-2 text-3xl font-semibold text-slate-900 dark:text-white">{{ $user->name }}</h2>
            </div>
            <a href="{{ route('profile.edit') }}" class="inline-flex items-center gap-2 rounded-full border border-slate-200 px-4 py-2 text-sm font-medium text-slate-700 transition hover:border-slate-500 dark:border-white/20 dark:text-white dark:hover:border-white/40">
                <span class="size-2 rounded-full bg-emerald-400"></span>
                Profil & Pengaturan
            </a>
        </div>
    </x-slot>

    @if($user->isAdmin())
        <div class="py-12">
            <div class="mx-auto max-w-7xl space-y-8 px-4 sm:px-6 lg:px-8">
                <section class="rounded-3xl bg-gradient-to-r from-white via-slate-50 to-indigo-50 px-8 py-8 text-slate-900 shadow-xl dark:from-slate-900 dark:via-slate-800 dark:to-indigo-900 dark:text-white">
                    <div class="flex flex-col gap-6 lg:flex-row lg:items-center lg:justify-between">
                        <div class="max-w-2xl">
                            <p class="text-sm tracking-[0.35em] text-slate-500 dark:text-slate-300">ADMIN CONTROL CENTER</p>
                            <h3 class="mt-4 text-2xl font-semibold leading-tight">Kelola seluruh sistem Deadlineku dari dashboard terpusat.</h3>
                            <p class="mt-2 text-slate-600 dark:text-slate-200">Monitor order, tiket support, dan performa bisnis secara real-time.</p>
                        </div>
                        <div class="grid w-full max-w-2xl grid-cols-2 gap-3 text-slate-900 sm:grid-cols-4 dark:text-white">
                            <div class="surface-card border border-white/20 bg-white/90 p-4 shadow-sm dark:border-white/10 dark:bg-white/5">
                                <p class="text-xs font-semibold uppercase tracking-wide text-muted">Total Order</p>
                                <p class="mt-2 text-3xl font-semibold text-slate-900 dark:text-white">{{ number_format($stats['totalOrders']) }}</p>
                            </div>
                            <div class="surface-card border border-white/20 bg-white/90 p-4 shadow-sm dark:border-white/10 dark:bg-white/5">
                                <p class="text-xs font-semibold uppercase tracking-wide text-muted">Active</p>
                                <p class="mt-2 text-3xl font-semibold text-slate-900 dark:text-white">{{ number_format($stats['activeOrders']) }}</p>
                            </div>
                            <div class="surface-card border border-white/20 bg-white/90 p-4 shadow-sm dark:border-white/10 dark:bg-white/5">
                                <p class="text-xs font-semibold uppercase tracking-wide text-muted">Revenue</p>
                                <p class="mt-2 text-3xl font-semibold text-slate-900 dark:text-white">Rp{{ number_format($stats['totalRevenue'], 0, ',', '.') }}</p>
                            </div>
                            <div class="surface-card border border-white/20 bg-white/90 p-4 shadow-sm dark:border-white/10 dark:bg-white/5">
                                <p class="text-xs font-semibold uppercase tracking-wide text-muted">Open Tickets</p>
                                <p class="mt-2 text-3xl font-semibold text-slate-900 dark:text-white">{{ number_format($stats['openTickets']) }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="mt-6 flex flex-wrap gap-3 text-sm">
                        <a href="{{ route('admin.orders.index') }}" class="inline-flex items-center gap-2 rounded-full border border-slate-300 px-5 py-2 font-semibold text-slate-900 transition hover:border-slate-900 hover:text-slate-900 dark:border-white/20 dark:text-white dark:hover:border-white/50">
                            <span>📋</span>
                            Kelola Order
                        </a>
                        <a href="{{ route('admin.services.index') }}" class="inline-flex items-center gap-2 rounded-full border border-slate-300 px-5 py-2 font-semibold text-slate-900 transition hover:border-slate-900 hover:text-slate-900 dark:border-white/20 dark:text-white dark:hover:border-white/50">
                            <span>⚙️</span>
                            Kelola Layanan
                        </a>
                        <a href="{{ route('admin.tickets.index') }}" class="inline-flex items-center gap-2 rounded-full border border-slate-300 px-5 py-2 font-semibold text-slate-900 transition hover:border-slate-900 hover:text-slate-900 dark:border-white/20 dark:text-white dark:hover:border-white/50">
                            <span>🎫</span>
                            Ticket Console
                        </a>
                    </div>
                </section>

                @if(session('success'))
                    <div class="rounded-3xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800 dark:border-emerald-400/40 dark:bg-emerald-400/10 dark:text-emerald-100">
                        {{ session('success') }}
                    </div>
                @endif

                <section class="surface-card p-6 text-slate-900 dark:text-white">
                    <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                        <div>
                            <p class="text-xs uppercase tracking-[0.4em] text-muted">Layanan & Pricing</p>
                            <h3 class="mt-2 text-xl font-semibold">Kelola paket, harga, dan diskon</h3>
                            <p class="mt-1 text-sm text-subtle">Panel terpisah untuk mengelola seluruh katalog layanan dan promosi.</p>
                        </div>
                        <a href="{{ route('admin.services.index') }}" class="inline-flex items-center gap-2 rounded-full border border-slate-300 px-5 py-2 text-sm font-semibold text-slate-900 transition hover:border-slate-900 hover:text-slate-900 dark:border-white/30 dark:text-white">
                            <span>⚙️</span>
                            Kelola Layanan →
                        </a>
                    </div>
                    @if($serviceSnapshots)
                        <div class="mt-6 grid gap-4 sm:grid-cols-3">
                            <div class="rounded-2xl border border-slate-200/80 bg-white/80 p-4 text-center dark:border-white/10 dark:bg-white/5">
                                <p class="text-xs uppercase tracking-[0.35em] text-muted">Aktif</p>
                                <p class="mt-2 text-3xl font-semibold text-slate-900 dark:text-white">{{ number_format($serviceSnapshots['active']) }}</p>
                            </div>
                            <div class="rounded-2xl border border-amber-200/70 bg-amber-50 p-4 text-center text-amber-900 dark:border-amber-400/30 dark:bg-amber-400/10 dark:text-amber-100">
                                <p class="text-xs uppercase tracking-[0.35em]">Promo</p>
                                <p class="mt-2 text-3xl font-semibold">{{ number_format($serviceSnapshots['promo']) }}</p>
                            </div>
                            <div class="rounded-2xl border border-slate-200/80 bg-white/80 p-4 text-center dark:border-white/10 dark:bg-white/5">
                                <p class="text-xs uppercase tracking-[0.35em] text-muted">Draft / Nonaktif</p>
                                <p class="mt-2 text-3xl font-semibold text-slate-900 dark:text-white">{{ number_format($serviceSnapshots['inactive']) }}</p>
                            </div>
                        </div>
                    @endif
                </section>

                <section class="grid gap-6 lg:grid-cols-2">
                    <div class="surface-card p-6 text-slate-900 dark:text-white">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-xs uppercase tracking-[0.4em] text-muted">Ticket Monitor</p>
                                <h3 class="mt-2 text-xl font-semibold">{{ $adminTickets->count() }} tiket terbaru</h3>
                                <p class="mt-1 text-sm text-subtle">Tiket support yang perlu perhatian</p>
                            </div>
                            <a href="{{ route('admin.tickets.index') }}" class="text-sm font-semibold text-indigo-600 hover:text-indigo-500 dark:text-indigo-300">Semua tiket →</a>
                        </div>
                        <div class="mt-4 space-y-4">
                            @forelse($adminTickets as $ticket)
                                <div class="rounded-2xl border border-slate-200/80 bg-white/90 p-4 text-sm dark:border-white/10 dark:bg-white/5">
                                    <div class="flex items-center justify-between">
                                        <p class="font-semibold text-slate-900 dark:text-white">{{ $ticket->code }} · {{ $ticket->subject }}</p>
                                        <span class="text-xs text-subtle">{{ $ticket->updated_at->diffForHumans() }}</span>
                                    </div>
                                    <p class="mt-1 text-xs text-subtle">{{ $ticket->user->name }} — {{ $ticket->order->order_number ?? 'No order' }}</p>
                                    <div class="mt-3 flex items-center justify-between text-xs">
                                        <span class="rounded-full border border-slate-200 px-3 py-1 text-subtle dark:border-white/20">{{ ucfirst($ticket->status) }}</span>
                                        <a href="{{ route('admin.tickets.show', $ticket) }}" class="text-emerald-600 dark:text-emerald-300">Balas tiket →</a>
                                    </div>
                                </div>
                            @empty
                                <p class="rounded-2xl border border-dashed border-slate-200 p-6 text-center text-sm text-subtle dark:border-white/20">Belum ada tiket.</p>
                            @endforelse
                        </div>
                    </div>

                    <div class="surface-card p-6 text-slate-900 dark:text-white">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-xs uppercase tracking-[0.4em] text-muted">Order Monitor</p>
                                <h3 class="mt-2 text-xl font-semibold">{{ $adminOrders->count() }} transaksi terakhir</h3>
                                <p class="mt-1 text-sm text-subtle">Order terbaru yang perlu monitoring</p>
                            </div>
                            <a href="{{ route('admin.orders.index') }}" class="text-sm font-semibold text-indigo-600 hover:text-indigo-500 dark:text-indigo-300">Semua order →</a>
                        </div>
                        <div class="mt-4 space-y-4">
                            @forelse($adminOrders as $order)
                                <div class="rounded-2xl border border-slate-200/80 bg-white/90 p-4 text-sm dark:border-white/10 dark:bg-white/5">
                                    <div class="flex items-center justify-between">
                                        <p class="font-semibold text-slate-900 dark:text-white">{{ $order->order_number }}</p>
                                        <span class="text-xs text-subtle">{{ $order->created_at->diffForHumans() }}</span>
                                    </div>
                                    <p class="text-xs text-subtle">{{ $order->user->name ?? $order->customer_name }} · {{ $order->service->name ?? '-' }}</p>
                                    <div class="mt-3 flex items-center justify-between">
                                        <div>
                                            <p class="text-base font-semibold text-slate-900 dark:text-white">Rp{{ number_format($order->amount, 0, ',', '.') }}</p>
                                            <p class="text-xs text-subtle">Payment {{ ucfirst($order->payment_status ?? 'pending') }}</p>
                                        </div>
                                        <a href="{{ route('admin.orders.show', $order) }}" class="text-sky-600 dark:text-sky-300">Detail transaksi →</a>
                                    </div>
                                </div>
                            @empty
                                <p class="rounded-2xl border border-dashed border-slate-200 p-6 text-center text-sm text-subtle dark:border-white/20">Belum ada order.</p>
                            @endforelse
                        </div>
                    </div>
                </section>
            </div>
        </div>
    @else
        <div class="py-12">
            <div class="mx-auto max-w-7xl space-y-8 px-4 sm:px-6 lg:px-8">
                <section class="rounded-3xl bg-gradient-to-r from-white via-slate-50 to-indigo-50 px-8 py-8 text-slate-900 shadow-xl dark:from-slate-900 dark:via-slate-800 dark:to-indigo-900 dark:text-white">
                    <div class="flex flex-col gap-6 lg:flex-row lg:items-center lg:justify-between">
                        <div class="max-w-2xl">
                            <p class="text-sm tracking-[0.35em] text-slate-500 dark:text-slate-300">DASHBOARD PELANGGAN</p>
                            <h3 class="mt-4 text-2xl font-semibold leading-tight">Pantau pesanan, pembayaran, dan progress kerja Anda.</h3>
                            <p class="mt-2 text-slate-600 dark:text-slate-200">Semua update real-time dari sistem Deadlineku.</p>
                        </div>
                        <div class="grid w-full max-w-lg grid-cols-2 gap-3 text-slate-900 dark:text-white">
                            <div class="surface-card border border-white/20 bg-white/90 p-4 shadow-sm dark:border-white/10 dark:bg-white/5">
                                <p class="text-xs font-semibold uppercase tracking-wide text-muted">Total Order</p>
                                <p class="mt-2 text-3xl font-semibold text-slate-900 dark:text-white">{{ number_format($stats['totalOrders']) }}</p>
                            </div>
                            <div class="surface-card border border-white/20 bg-white/90 p-4 shadow-sm dark:border-white/10 dark:bg-white/5">
                                <p class="text-xs font-semibold uppercase tracking-wide text-muted">Active</p>
                                <p class="mt-2 text-3xl font-semibold text-slate-900 dark:text-white">{{ number_format($stats['activeOrders']) }}</p>
                            </div>
                            <div class="surface-card border border-white/20 bg-white/90 p-4 shadow-sm dark:border-white/10 dark:bg-white/5">
                                <p class="text-xs font-semibold uppercase tracking-wide text-muted">Revenue</p>
                                <p class="mt-2 text-3xl font-semibold text-slate-900 dark:text-white">Rp{{ number_format($stats['totalRevenue'], 0, ',', '.') }}</p>
                            </div>
                            <div class="surface-card border border-white/20 bg-white/90 p-4 shadow-sm dark:border-white/10 dark:bg-white/5">
                                <p class="text-xs font-semibold uppercase tracking-wide text-muted">Open Tickets</p>
                                <p class="mt-2 text-3xl font-semibold text-slate-900 dark:text-white">{{ number_format($stats['openTickets']) }}</p>
                            </div>
                        </div>
                    </div>
                </section>

                @if (session('review_success'))
                    <div class="rounded-3xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-900 dark:border-emerald-400/30 dark:bg-emerald-400/10 dark:text-emerald-100">
                        {{ session('review_success') }}
                    </div>
                @endif

                @if (session('review_notice'))
                    <div class="rounded-3xl border border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-900 dark:border-amber-400/40 dark:bg-amber-400/10 dark:text-amber-100">
                        {{ session('review_notice') }}
                    </div>
                @endif

                @if($pendingReviewOrders->isNotEmpty())
                    <section class="surface-card p-6">
                        <div class="flex flex-col gap-2 lg:flex-row lg:items-center lg:justify-between">
                            <div>
                                <p class="text-xs uppercase tracking-[0.4em] text-muted">Berikan review</p>
                                <h4 class="mt-2 text-2xl font-semibold text-slate-900 dark:text-white">Bagikan pengalaman setelah pesanan selesai</h4>
                                <p class="mt-1 text-sm text-subtle">Review kamu membantu tim meningkatkan kualitas prioritas proyek berikutnya.</p>
                            </div>
                        </div>

                        <div class="mt-6 space-y-5">
                            @foreach($pendingReviewOrders as $order)
                                <article class="rounded-2xl border border-slate-200/80 bg-white/95 p-5 dark:border-white/10 dark:bg-white/5">
                                    <div class="flex flex-col gap-2 md:flex-row md:items-center md:justify-between">
                                        <div>
                                            <p class="text-xs font-semibold text-subtle">Order {{ $order->order_number }}</p>
                                            <p class="text-lg font-semibold text-slate-900 dark:text-white">{{ $order->service->name ?? 'Layanan custom' }}</p>
                                        </div>
                                        <span class="rounded-full border border-emerald-200 bg-emerald-50 px-3 py-1 text-xs font-semibold text-emerald-700 dark:border-emerald-400/30 dark:bg-emerald-400/10 dark:text-emerald-100">Selesai {{ $order->updated_at->diffForHumans() }}</span>
                                    </div>
                                    <form method="POST" action="{{ route('orders.review.store', $order) }}" class="mt-4 space-y-4">
                                        @csrf
                                        <input type="hidden" name="order_context" value="{{ $order->order_number }}">
                                        <div>
                                            <label for="rating-{{ $order->id }}" class="form-label">Rating</label>
                                            <select id="rating-{{ $order->id }}" name="rating" class="form-input mt-2">
                                                <option value="">Pilih rating</option>
                                                @for($rating = 5; $rating >= 1; $rating--)
                                                    <option value="{{ $rating }}" {{ old('rating') && old('order_context') === $order->order_number && (int) old('rating') === $rating ? 'selected' : '' }}>{{ $rating }} / 5</option>
                                                @endfor
                                            </select>
                                            @error('rating', 'review_'.$order->id)
                                                <p class="mt-2 text-sm text-rose-500 dark:text-rose-300">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        <div>
                                            <label for="headline-{{ $order->id }}" class="form-label">Judul singkat (opsional)</label>
                                            <input id="headline-{{ $order->id }}" name="headline" type="text" value="{{ old('order_context') === $order->order_number ? old('headline') : '' }}" class="form-input mt-2" placeholder="Contoh: Tim responsif banget">
                                            @error('headline', 'review_'.$order->id)
                                                <p class="mt-2 text-sm text-rose-500 dark:text-rose-300">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        <div>
                                            <label for="message-{{ $order->id }}" class="form-label">Ceritakan pengalamanmu</label>
                                            <textarea id="message-{{ $order->id }}" name="message" rows="4" class="form-input mt-2" placeholder="Tuliskan hal yang bikin kamu puas atau ide perbaikan">{{ old('order_context') === $order->order_number ? old('message') : '' }}</textarea>
                                            @error('message', 'review_'.$order->id)
                                                <p class="mt-2 text-sm text-rose-500 dark:text-rose-300">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        <button type="submit" class="w-full rounded-2xl bg-slate-900 px-5 py-3 text-sm font-semibold text-white transition hover:bg-slate-700 dark:bg-white dark:text-slate-900">Kirim review</button>
                                    </form>
                                </article>
                            @endforeach
                        </div>
                    </section>
                @endif

                @if($recentOrders->isNotEmpty())
                    <section class="surface-card p-6">
                        <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                            <div>
                                <p class="text-xs uppercase tracking-[0.4em] text-muted">Order & Pembayaran</p>
                                <h4 class="mt-2 text-2xl font-semibold text-slate-900 dark:text-white">Detail pesanan terbaru</h4>
                                <p class="mt-1 text-sm text-subtle">Pantau status pembayaran dan progress order Anda.</p>
                            </div>
                            <a href="{{ route('orders.check') }}" class="inline-flex items-center rounded-full border border-slate-200 px-4 py-2 text-sm font-semibold text-slate-700 transition hover:border-slate-900 hover:text-slate-900 dark:border-white/20 dark:text-white">Cek semua order →</a>
                        </div>

                        <div class="mt-6 space-y-6">
                            @foreach($recentOrders as $order)
                                <article class="rounded-2xl border border-slate-200/80 bg-white/95 p-6 dark:border-white/10 dark:bg-white/5">
                                    <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
                                        <div class="flex-1">
                                            <div class="flex flex-col gap-2 md:flex-row md:items-center md:justify-between">
                                                <div>
                                                    <p class="text-xs font-semibold text-subtle">Order {{ $order->order_number }}</p>
                                                    <h5 class="text-xl font-semibold text-slate-900 dark:text-white">{{ $order->service->name ?? 'Layanan custom' }}</h5>
                                                    <p class="mt-1 text-sm text-subtle">{{ $order->order_details ?: 'Tidak ada catatan tambahan' }}</p>
                                                </div>
                                                <div class="text-right">
                                                    <p class="text-2xl font-semibold text-slate-900 dark:text-white">Rp{{ number_format($order->amount, 0, ',', '.') }}</p>
                                                    <p class="text-xs text-subtle">{{ $order->created_at->translatedFormat('d M Y H:i') }}</p>
                                                </div>
                                            </div>

                                            <div class="mt-4 flex flex-wrap items-center gap-3">
                                                <span class="inline-flex items-center rounded-full border px-3 py-1 text-xs font-semibold {{ $statusColors[$order->status] ?? 'bg-slate-100 text-slate-600 border-slate-200' }}">
                                                    {{ ucfirst($order->status) }}
                                                </span>
                                                <span class="inline-flex items-center rounded-full border px-3 py-1 text-xs font-semibold
                                                    @if($order->payment_status === 'settlement') border-emerald-200 bg-emerald-50 text-emerald-700 dark:border-emerald-400/40 dark:bg-emerald-400/10 dark:text-emerald-100
                                                    @elseif($order->payment_status === 'pending') border-amber-200 bg-amber-50 text-amber-700 dark:border-amber-400/40 dark:bg-amber-400/10 dark:text-amber-100
                                                    @elseif($order->payment_status === 'cancelled') border-rose-200 bg-rose-50 text-rose-700 dark:border-rose-400/40 dark:bg-rose-400/10 dark:text-rose-100
                                                    @else border-slate-200 bg-slate-50 text-slate-600 dark:border-white/10 dark:bg-white/5 dark:text-slate-300
                                                    @endif">
                                                    Pembayaran: {{ ucfirst($order->payment_status ?? 'pending') }}
                                                </span>
                                                @if($order->paid_at)
                                                    <span class="inline-flex items-center rounded-full border border-emerald-200 bg-emerald-50 px-3 py-1 text-xs font-semibold text-emerald-700 dark:border-emerald-400/40 dark:bg-emerald-400/10 dark:text-emerald-100">
                                                        ✓ Dibayar {{ $order->paid_at->diffForHumans() }}
                                                    </span>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="flex flex-col gap-2 lg:min-w-[200px]">
                                            @if(in_array($order->payment_status, ['pending', 'challenge']))
                                                <a href="{{ route('orders.payment', $order) }}" class="inline-flex items-center justify-center rounded-full bg-slate-900 px-4 py-2 text-sm font-semibold text-white transition hover:bg-slate-800 dark:bg-white dark:text-slate-900">
                                                    Bayar Sekarang
                                                </a>
                                            @endif
                                            <a href="{{ route('orders.check') }}?order_number={{ $order->order_number }}" class="inline-flex items-center justify-center rounded-full border border-slate-200 px-4 py-2 text-sm font-semibold text-slate-700 transition hover:border-slate-900 hover:text-slate-900 dark:border-white/20 dark:text-white">
                                                Detail Order
                                            </a>
                                            @if($order->status === 'completed' && !$order->review)
                                                <a href="{{ route('orders.create') }}?service={{ $order->service->slug ?? '' }}" class="inline-flex items-center justify-center rounded-full border border-slate-200 px-4 py-2 text-sm font-semibold text-slate-700 transition hover:border-slate-900 hover:text-slate-900 dark:border-white/20 dark:text-white">
                                                    Pesan Lagi
                                                </a>
                                            @endif
                                        </div>
                                    </div>

                                    @if($order->payments->isNotEmpty())
                                        <div class="mt-4 border-t border-slate-200/50 pt-4 dark:border-white/10">
                                            <p class="text-xs font-semibold uppercase tracking-[0.3em] text-muted">Riwayat Pembayaran</p>
                                            <div class="mt-2 space-y-2">
                                                @foreach($order->payments->sortByDesc('created_at')->take(2) as $payment)
                                                    <div class="flex items-center justify-between rounded-lg border border-slate-200/50 bg-slate-50/50 px-3 py-2 text-sm dark:border-white/10 dark:bg-white/5">
                                                        <div>
                                                            <p class="font-semibold text-slate-900 dark:text-white">{{ $payment->provider }} • {{ $payment->reference }}</p>
                                                            <p class="text-xs text-subtle">{{ $payment->paid_at?->translatedFormat('d M Y H:i') ?: $payment->created_at->translatedFormat('d M Y H:i') }}</p>
                                                        </div>
                                                        <div class="text-right">
                                                            <p class="font-semibold text-slate-900 dark:text-white">Rp{{ number_format($payment->amount, 0, ',', '.') }}</p>
                                                            <span class="inline-flex items-center rounded-full border px-2 py-1 text-xs
                                                                @if($payment->status === 'settlement') border-emerald-200 bg-emerald-50 text-emerald-700 dark:border-emerald-400/40 dark:bg-emerald-400/10 dark:text-emerald-100
                                                                @elseif($payment->status === 'pending') border-amber-200 bg-amber-50 text-amber-700 dark:border-amber-400/40 dark:bg-amber-400/10 dark:text-amber-100
                                                                @else border-slate-200 bg-slate-50 text-slate-600 dark:border-white/10 dark:bg-white/5 dark:text-slate-300
                                                                @endif">
                                                                {{ ucfirst($payment->status) }}
                                                            </span>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif
                                </article>
                            @endforeach
                        </div>
                    </section>
                @endif

                @if($spotlightServices->isNotEmpty())
                    <section class="rounded-3xl border border-slate-100 bg-white/95 p-6 shadow-sm dark:border-white/10 dark:bg-white/5">
                        <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                            <div>
                                <p class="text-xs uppercase tracking-[0.4em] text-muted">Layanan unggulan</p>
                                <h4 class="mt-2 text-2xl font-semibold text-slate-900 dark:text-white">Pesan paket langsung dari dashboard</h4>
                                <p class="mt-1 text-sm text-subtle">Paket prioritas dengan SLA cepat, terhubung otomatis ke order kamu.</p>
                            </div>
                            <a href="{{ route('services.index') }}" class="inline-flex items-center rounded-full border border-slate-200 px-4 py-2 text-sm font-semibold text-slate-700 transition hover:border-slate-900 hover:text-slate-900 dark:border-white/20 dark:text-white">Lihat semua layanan →</a>
                        </div>

                        <div class="mt-6 grid gap-4 md:grid-cols-3">
                            @foreach($spotlightServices as $service)
                                <article class="rounded-3xl border border-slate-100 p-5 dark:border-white/10">
                                    <div class="flex items-center justify-between text-xs uppercase tracking-[0.3em] text-muted">
                                        <span>{{ $service->category }}</span>
                                        @if($service->is_featured)
                                            <span class="font-semibold text-emerald-600 dark:text-emerald-300">Favorit</span>
                                        @endif
                                    </div>
                                    <h5 class="mt-3 text-xl font-semibold text-slate-900 dark:text-white">{{ $service->name }}</h5>
                                    @if($service->has_active_discount)
                                        <span class="mt-3 inline-flex items-center gap-2 rounded-full border-2 border-rose-400/70 bg-gradient-to-r from-rose-600 to-orange-500 px-3 py-1 text-[11px] font-semibold uppercase tracking-[0.35em] text-white shadow-lg shadow-rose-500/30">
                                            -{{ $formatPercent($service->discount_percentage) }}%
                                            <span>{{ $service->discount_label ?? 'Promo aktif' }}</span>
                                        </span>
                                    @endif
                                    <p class="mt-2 text-sm text-subtle overflow-hidden text-ellipsis">{{ $service->description }}</p>
                                    <div class="mt-4 flex items-center justify-between text-sm text-subtle">
                                        <span>± {{ $service->delivery_days ?? 14 }} hari kerja</span>
                                        <div class="text-right">
                                            <p class="text-2xl font-semibold text-slate-900 dark:text-white">Rp{{ number_format($service->effective_price, 0, ',', '.') }}</p>
                                            @if($service->has_active_discount)
                                                <p class="text-xs text-subtle line-through">Rp{{ number_format($service->price, 0, ',', '.') }}</p>
                                            @endif
                                        </div>
                                    </div>
                                    <ul class="mt-4 space-y-2 text-sm text-muted">
                                        @foreach(array_slice($service->features ?? [], 0, 3) as $feature)
                                            <li class="flex items-start gap-2">
                                                <span class="mt-1 h-2 w-2 rounded-full bg-slate-900 dark:bg-white"></span>
                                                <span>{{ $feature }}</span>
                                            </li>
                                        @endforeach
                                    </ul>
                                    <div class="mt-6 flex gap-3 text-sm">
                                        <a href="{{ route('services.show', $service) }}" class="flex-1 rounded-2xl bg-slate-900 px-4 py-2 text-center font-semibold text-white transition hover:bg-slate-800 dark:bg-white dark:text-slate-900">Detail</a>
                                        <a href="{{ route('orders.create', ['service' => $service->slug]) }}" class="rounded-2xl border border-slate-200 px-4 py-2 text-center font-semibold text-slate-700 transition hover:border-slate-900 hover:text-slate-900 dark:border-white/30 dark:text-white">Pesan</a>
                                    </div>
                                </article>
                            @endforeach
                        </div>
                    </section>
                @endif

                <section class="rounded-3xl border border-slate-100 bg-white/95 p-6 shadow-sm dark:border-white/10 dark:bg-white/5">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs uppercase tracking-[0.4em] text-muted">Ringkasan Pesanan</p>
                            <h4 class="mt-2 text-xl font-semibold text-slate-900 dark:text-white">Overview pesanan terbaru</h4>
                        </div>
                        <a href="{{ route('orders.check') }}" class="text-sm font-semibold text-indigo-600 hover:text-indigo-500 dark:text-indigo-300">Lihat semua →</a>
                    </div>

                    <div class="mt-6 overflow-x-auto">
                        <table class="min-w-full divide-y divide-slate-100 text-sm dark:divide-white/5">
                            <thead>
                                <tr class="text-left text-muted">
                                    <th class="py-3 pr-4 font-medium">Order</th>
                                    <th class="py-3 pr-4 font-medium">Layanan</th>
                                    <th class="py-3 pr-4 font-medium">Status</th>
                                    <th class="py-3 pr-4 font-medium">Pembayaran</th>
                                    <th class="py-3 font-medium text-right">Tanggal</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 text-slate-700 dark:divide-white/5 dark:text-slate-200">
                                @forelse($recentOrders as $order)
                                    <tr class="align-top hover:bg-slate-50/50 dark:hover:bg-white/5">
                                        <td class="py-4 pr-4 font-semibold text-slate-900 dark:text-white">{{ $order->order_number }}</td>
                                        <td class="py-4 pr-4 max-w-[200px] truncate">{{ $order->service->name ?? 'N/A' }}</td>
                                        <td class="py-4 pr-4">
                                            <span class="inline-flex items-center rounded-full border px-2 py-1 text-xs font-semibold {{ $statusColors[$order->status] ?? 'bg-slate-100 text-slate-600 border-slate-200' }}">
                                                {{ ucfirst($order->status) }}
                                            </span>
                                        </td>
                                        <td class="py-4 pr-4">
                                            <span class="inline-flex items-center rounded-full border px-2 py-1 text-xs font-semibold
                                                @if($order->payment_status === 'settlement') border-emerald-200 bg-emerald-50 text-emerald-700 dark:border-emerald-400/40 dark:bg-emerald-400/10 dark:text-emerald-100
                                                @elseif($order->payment_status === 'pending') border-amber-200 bg-amber-50 text-amber-700 dark:border-amber-400/40 dark:bg-amber-400/10 dark:text-amber-100
                                                @elseif($order->payment_status === 'cancelled') border-rose-200 bg-rose-50 text-rose-700 dark:border-rose-400/40 dark:bg-rose-400/10 dark:text-rose-100
                                                @else border-slate-200 bg-slate-50 text-slate-600 dark:border-white/10 dark:bg-white/5 dark:text-slate-300
                                                @endif">
                                                {{ ucfirst($order->payment_status ?? 'pending') }}
                                            </span>
                                        </td>
                                        <td class="py-4 text-right text-subtle">{{ $order->created_at->translatedFormat('d M Y') }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="py-8 text-center text-subtle">
                                            <div class="flex flex-col items-center gap-2">
                                                <span class="text-2xl">📦</span>
                                                <p>Belum ada pesanan.</p>
                                                <a href="{{ route('orders.create') }}" class="text-sm font-semibold text-indigo-600 hover:text-indigo-500 dark:text-indigo-300">Buat pesanan pertama →</a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </section>

                    <div class="rounded-3xl border border-slate-100 bg-white/95 p-6 shadow-sm dark:border-white/10 dark:bg-white/5">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-xs uppercase tracking-[0.4em] text-muted">Support Center</p>
                                <h4 class="mt-2 text-xl font-semibold text-slate-900 dark:text-white">Tiket bantuan aktif</h4>
                            </div>
                            <a href="{{ route('tickets.index') }}" class="text-sm font-semibold text-indigo-600 hover:text-indigo-500 dark:text-indigo-300">Kelola tiket →</a>
                        </div>

                        <div class="mt-6 space-y-4">
                            @forelse($openTickets as $ticket)
                                <div class="rounded-2xl border border-slate-100 p-4 hover:border-slate-200 transition dark:border-white/10 dark:hover:border-white/20">
                                    <div class="flex items-start justify-between">
                                        <div class="flex-1">
                                            <p class="text-xs font-semibold text-muted">{{ $ticket->code }}</p>
                                            <p class="text-base font-semibold text-slate-900 dark:text-white">{{ $ticket->subject }}</p>
                                            <p class="mt-1 text-sm text-subtle">Order: {{ $ticket->order->order_number ?? 'Tidak terhubung' }}</p>
                                        </div>
                                        <div class="flex flex-col items-end gap-2">
                                            <span class="rounded-full border px-2 py-1 text-xs font-semibold
                                                @if($ticket->priority === 'high') border-rose-200 bg-rose-50 text-rose-700 dark:border-rose-400/40 dark:bg-rose-400/10 dark:text-rose-100
                                                @elseif($ticket->priority === 'medium') border-amber-200 bg-amber-50 text-amber-700 dark:border-amber-400/40 dark:bg-amber-400/10 dark:text-amber-100
                                                @else border-slate-200 bg-slate-50 text-slate-600 dark:border-white/10 dark:bg-white/5 dark:text-slate-300
                                                @endif">
                                                {{ ucfirst($ticket->priority) }}
                                            </span>
                                            <span class="text-xs text-subtle">{{ $ticket->updated_at->diffForHumans() }}</span>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="rounded-2xl border border-dashed border-slate-200 p-8 text-center text-subtle dark:border-white/20">
                                    <div class="flex flex-col items-center gap-2">
                                        <span class="text-2xl">🎫</span>
                                        <p>Belum ada tiket aktif.</p>
                                        <p class="text-xs">Butuh bantuan? <a href="{{ route('tickets.index') }}" class="font-semibold text-indigo-600 hover:text-indigo-500 dark:text-indigo-300">Buat tiket baru</a></p>
                                    </div>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </section>
            </div>
        </div>
    @endif
</x-app-layout>
