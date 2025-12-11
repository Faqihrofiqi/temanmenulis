@php
    $statusOptions = [
        'open' => 'Open',
        'in_progress' => 'Sedang dikerjakan',
        'resolved' => 'Selesai sementara',
        'closed' => 'Closed',
    ];

    $priorityLabels = [
        'low' => 'Low',
        'normal' => 'Normal',
        'high' => 'High',
        'urgent' => 'Urgent',
    ];
@endphp

<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-2 text-slate-900 dark:text-white">
            <p class="text-xs uppercase tracking-[0.4em] text-muted">Support Desk</p>
            <h1 class="text-3xl font-semibold">Kelola tiket bantuan & briefing revisi</h1>
            <p class="text-sm text-subtle">Monitor status percakapan dengan tim Deadlineku. Buka tiket baru untuk revisi atau bug produksi.</p>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-6xl space-y-8 px-6">
            <div class="grid gap-4 md:grid-cols-4">
                <div class="surface-card p-4">
                    <p class="text-xs uppercase tracking-[0.4em] text-muted">Total Ticket</p>
                    <p class="mt-3 text-3xl font-semibold text-slate-900 dark:text-white">{{ $stats['total'] }}</p>
                </div>
                <div class="rounded-3xl border border-amber-200/70 bg-amber-50 p-4 text-amber-900 shadow-sm dark:border-amber-400/30 dark:bg-amber-400/10 dark:text-amber-100">
                    <p class="text-xs uppercase tracking-[0.4em]">Open</p>
                    <p class="mt-3 text-3xl font-semibold">{{ $stats['open'] }}</p>
                </div>
                <div class="rounded-3xl border border-sky-200/70 bg-sky-50 p-4 text-sky-900 shadow-sm dark:border-sky-400/40 dark:bg-sky-400/10 dark:text-sky-100">
                    <p class="text-xs uppercase tracking-[0.4em]">In Progress</p>
                    <p class="mt-3 text-3xl font-semibold">{{ $stats['in_progress'] }}</p>
                </div>
                <div class="rounded-3xl border border-emerald-200/70 bg-emerald-50 p-4 text-emerald-900 shadow-sm dark:border-emerald-400/30 dark:bg-emerald-400/10 dark:text-emerald-100">
                    <p class="text-xs uppercase tracking-[0.4em]">Resolved</p>
                    <p class="mt-3 text-3xl font-semibold">{{ $stats['resolved'] + $stats['closed'] }}</p>
                </div>
            </div>

            @if (session('success'))
                <div class="rounded-2xl border border-emerald-200/70 bg-emerald-50 px-4 py-3 text-sm text-emerald-900 dark:border-emerald-400/30 dark:bg-emerald-400/10 dark:text-emerald-100">
                    {{ session('success') }}
                </div>
            @endif

            <div class="grid gap-8 lg:grid-cols-[1.45fr_0.9fr]">
                <section class="surface-card space-y-5 p-6">
                    <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
                        <div>
                            <p class="text-xs uppercase tracking-[0.4em] text-muted">Daftar Tiket</p>
                            <h2 class="text-2xl font-semibold text-slate-900 dark:text-white">Percakapan terbaru</h2>
                        </div>
                        <div class="flex flex-wrap gap-2 text-xs">
                            <a href="{{ route('tickets.index') }}" class="rounded-full border px-4 py-2 {{ empty($statusFilter) ? 'border-slate-900 bg-slate-900 text-white dark:border-white/60 dark:bg-white dark:text-slate-900' : 'border-slate-200 text-subtle hover:border-slate-500 hover:text-slate-900 dark:border-white/20 dark:text-slate-400 dark:hover:text-white' }}">Semua</a>
                            @foreach($statusOptions as $value => $label)
                                <a href="{{ route('tickets.index', ['status' => $value]) }}" class="rounded-full border px-4 py-2 {{ $statusFilter === $value ? 'border-slate-900 bg-slate-900 text-white dark:border-white/60 dark:bg-white dark:text-slate-900' : 'border-slate-200 text-subtle hover:border-slate-500 hover:text-slate-900 dark:border-white/20 dark:text-slate-400 dark:hover:text-white' }}">{{ $label }}</a>
                            @endforeach
                        </div>
                    </div>

                    <div class="space-y-4">
                        @forelse($tickets as $ticket)
                            <article class="rounded-2xl border border-slate-200/80 bg-white/80 p-4 dark:border-white/10 dark:bg-white/5">
                                <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
                                    <div>
                                        <p class="text-xs uppercase tracking-[0.35em] text-muted">{{ $ticket->code }}</p>
                                        <h3 class="text-lg font-semibold text-slate-900 dark:text-white">{{ $ticket->subject }}</h3>
                                        <p class="text-sm text-subtle">{{ $ticket->order?->order_number ? 'Order ' . $ticket->order->order_number : 'Tidak terkait order' }}</p>
                                    </div>
                                    <div class="flex flex-wrap gap-2 text-xs text-slate-900 dark:text-white">
                                        <span class="rounded-full border border-slate-200 px-3 py-1 text-subtle dark:border-white/20 dark:text-slate-200">{{ ucfirst(str_replace('_', ' ', $ticket->status)) }}</span>
                                        <span class="rounded-full border border-slate-200 px-3 py-1 text-subtle dark:border-white/20 dark:text-slate-200">Priority: {{ ucfirst($ticket->priority) }}</span>
                                        <span class="rounded-full border border-slate-200 px-3 py-1 text-subtle dark:border-white/20 dark:text-slate-200">Update: {{ $ticket->last_replied_at ? $ticket->last_replied_at->diffForHumans() : 'Baru dibuat' }}</span>
                                    </div>
                                </div>
                                <div class="mt-4 flex flex-col gap-3 text-sm text-muted md:flex-row md:items-center md:justify-between">
                                    <p>Status pembayaran: {{ $ticket->order?->status ? ucfirst($ticket->order->status) : '-' }}</p>
                                    <a href="{{ route('tickets.show', $ticket) }}" class="inline-flex items-center gap-2 rounded-full border border-slate-900 px-4 py-2 text-sm font-semibold text-slate-900 transition hover:bg-slate-900 hover:text-white dark:border-white/40 dark:text-white">Lihat detail →</a>
                                </div>
                            </article>
                        @empty
                            <div class="rounded-2xl border border-dashed border-slate-300 px-4 py-20 text-center text-sm text-subtle dark:border-white/20">
                                Belum ada tiket. Gunakan formulir di samping untuk membuka percakapan baru.
                            </div>
                        @endforelse
                    </div>

                    <div>
                        {{ $tickets->links() }}
                    </div>
                </section>

                <section class="surface-card p-6">
                    <p class="text-xs uppercase tracking-[0.4em] text-muted">Buat tiket baru</p>
                    <h2 class="mt-2 text-2xl font-semibold text-slate-900 dark:text-white">Sampaikan kendala atau revisi</h2>
                    <p class="mt-2 text-sm text-subtle">Tiket akan otomatis terhubung dengan tim support 24/7. Kami biasanya merespons dalam 15 menit.</p>

                    <form method="POST" action="{{ route('tickets.store') }}" enctype="multipart/form-data" class="mt-6 space-y-5">
                        @csrf
                        <div>
                            <label for="subject" class="form-label">Subjek</label>
                            <input id="subject" name="subject" type="text" value="{{ old('subject') }}" class="form-input mt-2" placeholder="Contoh: Revisi bab metode" required>
                            @error('subject')
                                <p class="mt-2 text-sm text-rose-500 dark:text-rose-300">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="order_number" class="form-label">Nomor order (opsional)</label>
                            <input id="order_number" name="order_number" type="text" value="{{ old('order_number') }}" class="form-input mt-2" placeholder="DL-2025xxxx">
                            @error('order_number')
                                <p class="mt-2 text-sm text-rose-500 dark:text-rose-300">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <p class="form-label">Prioritas</p>
                            <div class="mt-3 grid gap-3 sm:grid-cols-2">
                                @foreach($priorityLabels as $value => $label)
                                    <label class="flex items-center gap-3 rounded-2xl border px-4 py-2 transition {{ old('priority', 'normal') === $value ? 'border-indigo-500 bg-indigo-50 text-indigo-900 dark:border-indigo-400 dark:bg-indigo-400/10 dark:text-indigo-100' : 'border-slate-200 bg-white text-slate-700 hover:border-slate-500 dark:border-white/10 dark:bg-white/5 dark:text-slate-200' }}">
                                        <input type="radio" name="priority" value="{{ $value }}" class="h-4 w-4" {{ old('priority', 'normal') === $value ? 'checked' : '' }}>
                                        <span class="text-sm">{{ $label }}</span>
                                    </label>
                                @endforeach
                            </div>
                            @error('priority')
                                <p class="mt-2 text-sm text-rose-500 dark:text-rose-300">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="message" class="form-label">Pesan</label>
                            <textarea id="message" name="message" rows="6" class="form-input mt-2" placeholder="Jelaskan kendala atau revisi yang dibutuhkan" required>{{ old('message') }}</textarea>
                            @error('message')
                                <p class="mt-2 text-sm text-rose-500 dark:text-rose-300">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="attachments" class="form-label">Lampiran (opsional)</label>
                            <input id="attachments" name="attachments[]" type="file" multiple class="form-input-muted mt-2">
                            <p class="mt-2 text-xs text-subtle">Maksimal 2MB per file.</p>
                            @error('attachments.*')
                                <p class="mt-2 text-sm text-rose-500 dark:text-rose-300">{{ $message }}</p>
                            @enderror
                        </div>

                        <button type="submit" class="w-full rounded-3xl bg-slate-900 px-6 py-3 text-center text-lg font-semibold text-white transition hover:bg-slate-700 dark:bg-white dark:text-slate-900">Buat tiket & hubungkan tim →</button>
                    </form>
                </section>
            </div>
        </div>
    </div>
</x-app-layout>
