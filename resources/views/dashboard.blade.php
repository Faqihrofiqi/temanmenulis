@php
    $statusColors = [
        'pending' => 'bg-amber-50 text-amber-700 border-amber-200',
        'processing' => 'bg-sky-50 text-sky-700 border-sky-200',
        'paid' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
        'completed' => 'bg-slate-100 text-slate-700 border-slate-200',
        'cancelled' => 'bg-rose-50 text-rose-700 border-rose-200',
    ];

    $formatPercent = fn ($value) => number_format($value, (floor($value) == $value) ? 0 : 1);
@endphp

<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm uppercase tracking-[0.3em] text-slate-400">{{ $user->isAdmin() ? 'Admin Control Center' : 'Selamat Datang Kembali' }}</p>
                <h2 class="mt-2 text-3xl font-semibold text-slate-900">{{ $user->name }}</h2>
            </div>
            <a href="{{ route('profile.edit') }}" class="inline-flex items-center gap-2 rounded-full border border-slate-200 px-4 py-2 text-sm font-medium text-slate-700 hover:border-slate-300">
                <span class="size-2 rounded-full bg-emerald-400"></span>
                Profil & Pengaturan
            </a>
        </div>
    </x-slot>

    @if($user->isAdmin())
        <div class="py-12">
            <div class="mx-auto max-w-7xl space-y-8 px-4 sm:px-6 lg:px-8">
                <section class="rounded-3xl bg-gradient-to-r from-slate-900 via-slate-800 to-indigo-900 px-8 py-10 text-white shadow-xl">
                    <div class="flex flex-col gap-6 lg:flex-row lg:items-center lg:justify-between">
                        <div class="max-w-2xl">
                            <p class="text-sm tracking-[0.35em] text-slate-300">CONTROL ROOM</p>
                            <h3 class="mt-4 text-3xl font-semibold leading-tight">Kelola paket, diskon event, order, dan tiket support di satu layar.</h3>
                            <p class="mt-3 text-slate-200">Statistik real-time membantu prioritas harga dan follow-up pembayaran.</p>
                        </div>
                        <div class="grid w-full max-w-2xl grid-cols-2 gap-4 text-slate-900 sm:grid-cols-4">
                            <div class="rounded-2xl bg-white/95 p-4 shadow">
                                <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">Total Order</p>
                                <p class="mt-2 text-3xl font-semibold text-slate-900">{{ number_format($stats['totalOrders']) }}</p>
                            </div>
                            <div class="rounded-2xl bg-white/95 p-4 shadow">
                                <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">Active</p>
                                <p class="mt-2 text-3xl font-semibold text-slate-900">{{ number_format($stats['activeOrders']) }}</p>
                            </div>
                            <div class="rounded-2xl bg-white/95 p-4 shadow">
                                <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">Revenue</p>
                                <p class="mt-2 text-3xl font-semibold text-slate-900">Rp{{ number_format($stats['totalRevenue'], 0, ',', '.') }}</p>
                            </div>
                            <div class="rounded-2xl bg-white/95 p-4 shadow">
                                <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">Open Tickets</p>
                                <p class="mt-2 text-3xl font-semibold text-slate-900">{{ number_format($stats['openTickets']) }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="mt-6 flex flex-wrap gap-3 text-sm">
                        <a href="{{ route('admin.orders.index') }}" class="inline-flex items-center rounded-full border border-white/20 px-4 py-2 font-semibold text-white hover:border-white/60">Kelola order</a>
                        <a href="{{ route('admin.tickets.index') }}" class="inline-flex items-center rounded-full border border-white/20 px-4 py-2 font-semibold text-white hover:border-white/60">Buka ticket console</a>
                    </div>
                </section>

                @if(session('success'))
                    <div class="rounded-3xl border border-emerald-400/50 bg-emerald-400/10 px-4 py-3 text-sm text-emerald-100">
                        {{ session('success') }}
                    </div>
                @endif

                <section class="rounded-3xl border border-white/10 bg-white/5 p-6 text-white">
                    <div class="flex flex-col gap-3 lg:flex-row lg:items-center lg:justify-between">
                        <div>
                            <p class="text-xs uppercase tracking-[0.4em] text-slate-400">Paket & Harga</p>
                            <h3 class="mt-2 text-2xl font-semibold text-white">Kelola pricing + diskon event</h3>
                            <p class="mt-1 text-sm text-slate-300">Aktifkan badge promo Ramadhan, thesis week, atau event apapun tanpa keluar dashboard.</p>
                        </div>
                        <p class="text-xs text-slate-400">Isi diskon & tanggal habis untuk memunculkan badge otomatis.</p>
                    </div>

                    <div class="mt-6 grid gap-4 lg:grid-cols-2">
                        @foreach($adminServices as $service)
                            <form method="POST" action="{{ route('admin.services.update', $service) }}" class="rounded-3xl border border-white/10 bg-slate-950/70 p-5 text-sm text-slate-200">
                                @csrf
                                @method('PATCH')
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="text-xs uppercase tracking-[0.35em] text-slate-500">{{ $service->category ?? 'Tanpa kategori' }}</p>
                                        <h4 class="text-xl font-semibold text-white">{{ $service->name }}</h4>
                                    </div>
                                    <span class="text-xs text-slate-400">{{ $service->is_active ? 'Aktif' : 'Nonaktif' }}</span>
                                </div>
                                <div class="mt-4 grid gap-4 sm:grid-cols-2">
                                    <div>
                                        <label class="text-xs uppercase tracking-[0.3em] text-slate-500">Harga dasar</label>
                                        <input type="number" step="1000" min="0" name="price" value="{{ old('price', $service->price) }}" class="mt-2 w-full rounded-2xl border border-white/10 bg-slate-900/40 px-3 py-2 text-white" required>
                                        <p class="mt-1 text-xs text-slate-500">Harga efektif: Rp{{ number_format($service->effective_price, 0, ',', '.') }}</p>
                                    </div>
                                    <div>
                                        <label class="text-xs uppercase tracking-[0.3em] text-slate-500">Durasi (hari)</label>
                                        <input type="number" min="1" max="90" name="delivery_days" value="{{ old('delivery_days', $service->delivery_days) }}" class="mt-2 w-full rounded-2xl border border-white/10 bg-slate-900/40 px-3 py-2 text-white" required>
                                    </div>
                                </div>
                                <div class="mt-4 grid gap-4 sm:grid-cols-3">
                                    <div>
                                        <label class="text-xs uppercase tracking-[0.3em] text-slate-500">Diskon (%)</label>
                                        <input type="number" step="0.5" min="0" max="95" name="discount_percentage" value="{{ old('discount_percentage', $service->discount_percentage) }}" class="mt-2 w-full rounded-2xl border border-white/10 bg-slate-900/40 px-3 py-2 text-white" placeholder="0">
                                    </div>
                                    <div class="sm:col-span-2">
                                        <label class="text-xs uppercase tracking-[0.3em] text-slate-500">Label event</label>
                                        <input type="text" name="discount_label" value="{{ old('discount_label', $service->discount_label) }}" class="mt-2 w-full rounded-2xl border border-white/10 bg-slate-900/40 px-3 py-2 text-white" placeholder="Ramadhan Sprint">
                                    </div>
                                </div>
                                <div class="mt-4">
                                    <label class="text-xs uppercase tracking-[0.3em] text-slate-500">Diskon berakhir</label>
                                    <input type="datetime-local" name="discount_ends_at" value="{{ optional($service->discount_ends_at)->format('Y-m-d\TH:i') }}" class="mt-2 w-full rounded-2xl border border-white/10 bg-slate-900/40 px-3 py-2 text-white">
                                </div>
                                <div class="mt-4 flex flex-wrap gap-4 text-xs text-slate-300">
                                    <label class="inline-flex items-center gap-2">
                                        <input type="hidden" name="is_active" value="0">
                                        <input type="checkbox" name="is_active" value="1" class="h-4 w-4 rounded border-white/20 bg-slate-900/40" @checked($service->is_active)>
                                        <span>Aktifkan paket</span>
                                    </label>
                                    <label class="inline-flex items-center gap-2">
                                        <input type="hidden" name="is_featured" value="0">
                                        <input type="checkbox" name="is_featured" value="1" class="h-4 w-4 rounded border-white/20 bg-slate-900/40" @checked($service->is_featured)>
                                        <span>Jadikan unggulan</span>
                                    </label>
                                </div>
                                <button type="submit" class="mt-5 w-full rounded-2xl bg-gradient-to-r from-indigo-500 to-sky-500 px-4 py-2 text-sm font-semibold text-white">Simpan perubahan</button>
                            </form>
                        @endforeach
                    </div>
                </section>

                <section class="grid gap-6 lg:grid-cols-2">
                    <div class="rounded-3xl border border-white/10 bg-white/5 p-6 text-white">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-xs uppercase tracking-[0.4em] text-slate-400">Ticket monitor</p>
                                <h3 class="mt-2 text-2xl font-semibold text-white">{{ $adminTickets->count() }} tiket terbaru</h3>
                            </div>
                            <a href="{{ route('admin.tickets.index') }}" class="text-xs font-semibold text-slate-200 underline">Semua tiket</a>
                        </div>
                        <div class="mt-4 space-y-4">
                            @forelse($adminTickets as $ticket)
                                <div class="rounded-2xl border border-white/10 bg-slate-950/60 p-4 text-sm">
                                    <div class="flex items-center justify-between">
                                        <p class="font-semibold text-white">{{ $ticket->code }} · {{ $ticket->subject }}</p>
                                        <span class="text-xs text-slate-400">{{ $ticket->updated_at->diffForHumans() }}</span>
                                    </div>
                                    <p class="mt-1 text-xs text-slate-400">{{ $ticket->user->name }} — {{ $ticket->order->order_number ?? 'No order' }}</p>
                                    <div class="mt-3 flex items-center justify-between text-xs">
                                        <span class="rounded-full border border-white/20 px-3 py-1">{{ ucfirst($ticket->status) }}</span>
                                        <a href="{{ route('admin.tickets.show', $ticket) }}" class="text-emerald-300">Balas tiket →</a>
                                    </div>
                                </div>
                            @empty
                                <p class="rounded-2xl border border-dashed border-white/20 p-6 text-center text-sm text-slate-300">Belum ada tiket.</p>
                            @endforelse
                        </div>
                    </div>

                    <div class="rounded-3xl border border-white/10 bg-white/5 p-6 text-white">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-xs uppercase tracking-[0.4em] text-slate-400">Order monitor</p>
                                <h3 class="mt-2 text-2xl font-semibold text-white">{{ $adminOrders->count() }} transaksi terakhir</h3>
                            </div>
                            <a href="{{ route('admin.orders.index') }}" class="text-xs font-semibold text-slate-200 underline">Semua order</a>
                        </div>
                        <div class="mt-4 space-y-4">
                            @forelse($adminOrders as $order)
                                <div class="rounded-2xl border border-white/10 bg-slate-950/60 p-4 text-sm">
                                    <div class="flex items-center justify-between">
                                        <p class="font-semibold text-white">{{ $order->order_number }}</p>
                                        <span class="text-xs text-slate-400">{{ $order->created_at->diffForHumans() }}</span>
                                    </div>
                                    <p class="text-xs text-slate-400">{{ $order->user->name ?? $order->customer_name }} · {{ $order->service->name ?? '-' }}</p>
                                    <div class="mt-3 flex items-center justify-between">
                                        <div>
                                            <p class="text-base font-semibold text-white">Rp{{ number_format($order->amount, 0, ',', '.') }}</p>
                                            <p class="text-xs text-slate-400">Payment {{ ucfirst($order->payment_status ?? 'pending') }}</p>
                                        </div>
                                        <a href="{{ route('admin.orders.show', $order) }}" class="text-sky-300">Detail transaksi →</a>
                                    </div>
                                </div>
                            @empty
                                <p class="rounded-2xl border border-dashed border-white/20 p-6 text-center text-sm text-slate-300">Belum ada order.</p>
                            @endforelse
                        </div>
                    </div>
                </section>
            </div>
        </div>
    @else
        <div class="py-12">
            <div class="mx-auto max-w-7xl space-y-10 px-4 sm:px-6 lg:px-8">
                <section class="rounded-3xl bg-gradient-to-r from-slate-900 via-slate-800 to-indigo-900 px-8 py-10 text-white shadow-xl">
                    <div class="flex flex-col gap-6 lg:flex-row lg:items-center lg:justify-between">
                        <div class="max-w-2xl">
                            <p class="text-sm tracking-[0.35em] text-slate-300">PROJECT OVERVIEW</p>
                            <h3 class="mt-4 text-3xl font-semibold leading-tight">Pantau pesanan, pembayaran, dan tiket support dalam satu layar.</h3>
                            <p class="mt-3 text-slate-200">Data diperbarui secara real-time dari order management Deadlineku.</p>
                        </div>
                        <div class="grid w-full max-w-xl grid-cols-2 gap-4 text-slate-900">
                            <div class="rounded-2xl bg-white/95 p-4 shadow">
                                <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">Total Order</p>
                                <p class="mt-2 text-3xl font-semibold text-slate-900">{{ number_format($stats['totalOrders']) }}</p>
                            </div>
                            <div class="rounded-2xl bg-white/95 p-4 shadow">
                                <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">Active</p>
                                <p class="mt-2 text-3xl font-semibold text-slate-900">{{ number_format($stats['activeOrders']) }}</p>
                            </div>
                            <div class="rounded-2xl bg-white/95 p-4 shadow">
                                <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">Revenue</p>
                                <p class="mt-2 text-3xl font-semibold text-slate-900">Rp{{ number_format($stats['totalRevenue'], 0, ',', '.') }}</p>
                            </div>
                            <div class="rounded-2xl bg-white/95 p-4 shadow">
                                <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">Open Tickets</p>
                                <p class="mt-2 text-3xl font-semibold text-slate-900">{{ number_format($stats['openTickets']) }}</p>
                            </div>
                        </div>
                    </div>
                </section>

                @if($spotlightServices->isNotEmpty())
                    <section class="rounded-3xl bg-white/95 p-6 shadow-sm">
                        <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                            <div>
                                <p class="text-xs uppercase tracking-[0.4em] text-slate-400">Layanan unggulan</p>
                                <h4 class="mt-2 text-2xl font-semibold text-slate-900">Pesan paket langsung dari dashboard</h4>
                                <p class="mt-1 text-sm text-slate-500">Paket prioritas dengan SLA cepat, terhubung otomatis ke order kamu.</p>
                            </div>
                            <a href="{{ route('services.index') }}" class="inline-flex items-center rounded-full border border-slate-200 px-4 py-2 text-sm font-semibold text-slate-700 hover:border-slate-900 hover:text-slate-900">Lihat semua layanan →</a>
                        </div>

                        <div class="mt-6 grid gap-4 md:grid-cols-3">
                            @foreach($spotlightServices as $service)
                                <article class="rounded-3xl border border-slate-100 p-5">
                                    <div class="flex items-center justify-between text-xs uppercase tracking-[0.3em] text-slate-400">
                                        <span>{{ $service->category }}</span>
                                        @if($service->is_featured)
                                            <span class="text-emerald-500">Favorit</span>
                                        @endif
                                    </div>
                                    <h5 class="mt-3 text-xl font-semibold text-slate-900">{{ $service->name }}</h5>
                                    @if($service->has_active_discount)
                                        <span class="mt-2 inline-flex items-center gap-2 rounded-full border border-rose-200/60 bg-rose-50 px-3 py-1 text-xs font-semibold text-rose-500">
                                            -{{ $formatPercent($service->discount_percentage) }}%
                                            @if($service->discount_label)
                                                <span>{{ $service->discount_label }}</span>
                                            @endif
                                        </span>
                                    @endif
                                    <p class="mt-2 text-sm text-slate-500 overflow-hidden text-ellipsis">{{ $service->description }}</p>
                                    <div class="mt-4 flex items-center justify-between text-sm text-slate-500">
                                        <span>± {{ $service->delivery_days ?? 14 }} hari kerja</span>
                                        <div class="text-right">
                                            <p class="text-2xl font-semibold text-slate-900">Rp{{ number_format($service->effective_price, 0, ',', '.') }}</p>
                                            @if($service->has_active_discount)
                                                <p class="text-xs text-slate-400 line-through">Rp{{ number_format($service->price, 0, ',', '.') }}</p>
                                            @endif
                                        </div>
                                    </div>
                                    <ul class="mt-4 space-y-2 text-sm text-slate-600">
                                        @foreach(array_slice($service->features ?? [], 0, 3) as $feature)
                                            <li class="flex items-start gap-2">
                                                <span class="mt-1 h-2 w-2 rounded-full bg-slate-900"></span>
                                                <span>{{ $feature }}</span>
                                            </li>
                                        @endforeach
                                    </ul>
                                    <div class="mt-6 flex gap-3 text-sm">
                                        <a href="{{ route('services.show', $service) }}" class="flex-1 rounded-2xl bg-slate-900 px-4 py-2 text-center font-semibold text-white hover:bg-slate-800">Detail</a>
                                        <a href="{{ route('register') }}?service={{ $service->slug }}" class="rounded-2xl border border-slate-200 px-4 py-2 text-center font-semibold text-slate-700 hover:border-slate-900 hover:text-slate-900">Pesan</a>
                                    </div>
                                </article>
                            @endforeach
                        </div>
                    </section>
                @endif

                <section class="grid gap-6 lg:grid-cols-3">
                    <div class="lg:col-span-2 rounded-3xl bg-white/90 p-6 shadow-sm">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-xs uppercase tracking-[0.4em] text-slate-400">Pesanan Terbaru</p>
                                <h4 class="mt-2 text-xl font-semibold text-slate-900">Timeline pesanan terakhir kamu</h4>
                            </div>
                        </div>

                        <div class="mt-6 overflow-x-auto">
                            <table class="min-w-full divide-y divide-slate-100 text-sm">
                                <thead>
                                    <tr class="text-left text-slate-400">
                                        <th class="py-3 pr-4 font-medium">Order</th>
                                        <th class="py-3 pr-4 font-medium">Layanan</th>
                                        <th class="py-3 pr-4 font-medium">Status</th>
                                        <th class="py-3 pr-4 font-medium">Nominal</th>
                                        <th class="py-3 font-medium text-right">Tanggal</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100 text-slate-700">
                                    @forelse($recentOrders as $order)
                                        <tr class="align-top">
                                            <td class="py-4 pr-4 font-semibold text-slate-900">{{ $order->order_number }}</td>
                                            <td class="py-4 pr-4">{{ $order->service->name ?? 'N/A' }}</td>
                                            <td class="py-4 pr-4">
                                                <span class="inline-flex items-center rounded-full border px-3 py-1 text-xs font-semibold {{ $statusColors[$order->status] ?? 'bg-slate-100 text-slate-600 border-slate-200' }}">
                                                    {{ ucfirst($order->status) }}
                                                </span>
                                            </td>
                                            <td class="py-4 pr-4 font-semibold">Rp{{ number_format($order->amount, 0, ',', '.') }}</td>
                                            <td class="py-4 text-right text-slate-500">{{ $order->created_at->translatedFormat('d M Y') }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="py-6 text-center text-slate-400">Belum ada pesanan.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="rounded-3xl bg-white/90 p-6 shadow-sm">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-xs uppercase tracking-[0.4em] text-slate-400">Ticket Support</p>
                                <h4 class="mt-2 text-xl font-semibold text-slate-900">Percakapan terbaru</h4>
                            </div>
                            <a href="{{ route('tickets.index') }}" class="text-sm font-semibold text-indigo-600 hover:text-indigo-500">Buka tiket →</a>
                        </div>

                        <div class="mt-6 space-y-5">
                            @forelse($openTickets as $ticket)
                                <div class="rounded-2xl border border-slate-100 p-4">
                                    <div class="flex items-start justify-between">
                                        <div>
                                            <p class="text-xs font-semibold text-slate-400">{{ $ticket->code }}</p>
                                            <p class="text-base font-semibold text-slate-900">{{ $ticket->subject }}</p>
                                        </div>
                                        <span class="rounded-full bg-indigo-50 px-3 py-1 text-xs font-semibold text-indigo-500">{{ ucfirst($ticket->priority) }}</span>
                                    </div>
                                    <p class="mt-2 text-sm text-slate-500">Order: {{ $ticket->order->order_number ?? 'Tidak terhubung' }}</p>
                                    <p class="mt-1 text-xs uppercase tracking-wide text-slate-400">{{ $ticket->updated_at->diffForHumans() }}</p>
                                </div>
                            @empty
                                <div class="rounded-2xl border border-dashed border-slate-200 p-6 text-center text-slate-400">
                                    Belum ada tiket aktif.
                                </div>
                            @endforelse
                        </div>
                    </div>
                </section>
            </div>
        </div>
    @endif
</x-app-layout>
