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
        <div class="flex flex-col gap-2 text-white">
            <p class="text-xs uppercase tracking-[0.4em] text-slate-400">Support Desk</p>
            <h1 class="text-3xl font-semibold">Kelola tiket bantuan & briefing revisi</h1>
            <p class="text-sm text-slate-400">Monitor status percakapan dengan tim Deadlineku. Buka tiket baru untuk revisi atau bug produksi.</p>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-6xl space-y-8 px-6">
            <div class="grid gap-4 md:grid-cols-4">
                <div class="rounded-3xl border border-white/10 bg-slate-950/50 p-4 text-white">
                    <p class="text-xs uppercase tracking-[0.4em] text-slate-400">Total Ticket</p>
                    <p class="mt-3 text-3xl font-semibold">{{ $stats['total'] }}</p>
                </div>
                <div class="rounded-3xl border border-amber-400/30 bg-amber-400/10 p-4 text-amber-50">
                    <p class="text-xs uppercase tracking-[0.4em]">Open</p>
                    <p class="mt-3 text-3xl font-semibold">{{ $stats['open'] }}</p>
                </div>
                <div class="rounded-3xl border border-sky-400/30 bg-sky-400/10 p-4 text-sky-50">
                    <p class="text-xs uppercase tracking-[0.4em]">In Progress</p>
                    <p class="mt-3 text-3xl font-semibold">{{ $stats['in_progress'] }}</p>
                </div>
                <div class="rounded-3xl border border-emerald-400/30 bg-emerald-400/10 p-4 text-emerald-50">
                    <p class="text-xs uppercase tracking-[0.4em]">Resolved</p>
                    <p class="mt-3 text-3xl font-semibold">{{ $stats['resolved'] + $stats['closed'] }}</p>
                </div>
            </div>

            @if (session('success'))
                <div class="rounded-2xl border border-emerald-400/40 bg-emerald-400/10 px-4 py-3 text-sm text-emerald-50">
                    {{ session('success') }}
                </div>
            @endif

            <div class="grid gap-8 lg:grid-cols-[1.45fr_0.9fr]">
                <section class="space-y-5 rounded-3xl border border-white/10 bg-slate-950/60 p-6">
                    <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
                        <div>
                            <p class="text-xs uppercase tracking-[0.4em] text-slate-400">Daftar Tiket</p>
                            <h2 class="text-2xl font-semibold text-white">Percakapan terbaru</h2>
                        </div>
                        <div class="flex flex-wrap gap-2 text-xs">
                            <a href="{{ route('tickets.index') }}" class="rounded-full border px-4 py-2 {{ empty($statusFilter) ? 'border-white/60 text-white' : 'border-white/20 text-slate-400 hover:border-white/40 hover:text-white' }}">Semua</a>
                            @foreach($statusOptions as $value => $label)
                                <a href="{{ route('tickets.index', ['status' => $value]) }}" class="rounded-full border px-4 py-2 {{ $statusFilter === $value ? 'border-white/60 text-white' : 'border-white/20 text-slate-400 hover:border-white/40 hover:text-white' }}">{{ $label }}</a>
                            @endforeach
                        </div>
                    </div>

                    <div class="space-y-4">
                        @forelse($tickets as $ticket)
                            <article class="rounded-2xl border border-white/10 bg-slate-900/40 p-4">
                                <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
                                    <div>
                                        <p class="text-xs uppercase tracking-[0.35em] text-slate-500">{{ $ticket->code }}</p>
                                        <h3 class="text-lg font-semibold text-white">{{ $ticket->subject }}</h3>
                                        <p class="text-sm text-slate-400">{{ $ticket->order?->order_number ? 'Order ' . $ticket->order->order_number : 'Tidak terkait order' }}</p>
                                    </div>
                                    <div class="flex flex-wrap gap-2 text-xs text-white">
                                        <span class="rounded-full border border-white/20 px-3 py-1">{{ ucfirst(str_replace('_', ' ', $ticket->status)) }}</span>
                                        <span class="rounded-full border border-white/20 px-3 py-1">Priority: {{ ucfirst($ticket->priority) }}</span>
                                        <span class="rounded-full border border-white/20 px-3 py-1">Update: {{ $ticket->last_replied_at ? $ticket->last_replied_at->diffForHumans() : 'Baru dibuat' }}</span>
                                    </div>
                                </div>
                                <div class="mt-4 flex flex-col gap-3 text-sm text-slate-300 md:flex-row md:items-center md:justify-between">
                                    <p>Status pembayaran: {{ $ticket->order?->status ? ucfirst($ticket->order->status) : '-' }}</p>
                                    <a href="{{ route('tickets.show', $ticket) }}" class="inline-flex items-center gap-2 rounded-full border border-white/20 px-4 py-2 text-sm font-semibold text-white transition hover:border-white/60">Lihat detail →</a>
                                </div>
                            </article>
                        @empty
                            <div class="rounded-2xl border border-dashed border-white/20 px-4 py-20 text-center text-sm text-slate-400">
                                Belum ada tiket. Gunakan formulir di samping untuk membuka percakapan baru.
                            </div>
                        @endforelse
                    </div>

                    <div>
                        {{ $tickets->links() }}
                    </div>
                </section>

                <section class="rounded-3xl border border-white/10 bg-slate-950/60 p-6">
                    <p class="text-xs uppercase tracking-[0.4em] text-slate-400">Buat tiket baru</p>
                    <h2 class="mt-2 text-2xl font-semibold text-white">Sampaikan kendala atau revisi</h2>
                    <p class="mt-2 text-sm text-slate-400">Tiket akan otomatis terhubung dengan tim support 24/7. Kami biasanya merespons dalam 15 menit.</p>

                    <form method="POST" action="{{ route('tickets.store') }}" enctype="multipart/form-data" class="mt-6 space-y-5">
                        @csrf
                        <div>
                            <label for="subject" class="text-xs font-semibold uppercase tracking-[0.3em] text-slate-400">Subjek</label>
                            <input id="subject" name="subject" type="text" value="{{ old('subject') }}" class="mt-2 w-full rounded-2xl border border-white/10 bg-slate-900/50 px-4 py-3 text-white placeholder:text-slate-600 focus:border-indigo-400 focus:outline-none" placeholder="Contoh: Revisi bab metode" required>
                            @error('subject')
                                <p class="mt-2 text-sm text-rose-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="order_number" class="text-xs font-semibold uppercase tracking-[0.3em] text-slate-400">Nomor order (opsional)</label>
                            <input id="order_number" name="order_number" type="text" value="{{ old('order_number') }}" class="mt-2 w-full rounded-2xl border border-white/10 bg-slate-900/50 px-4 py-3 text-white placeholder:text-slate-600 focus:border-indigo-400 focus:outline-none" placeholder="DL-2025xxxx">
                            @error('order_number')
                                <p class="mt-2 text-sm text-rose-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <p class="text-xs font-semibold uppercase tracking-[0.3em] text-slate-400">Prioritas</p>
                            <div class="mt-3 grid gap-3 sm:grid-cols-2">
                                @foreach($priorityLabels as $value => $label)
                                    <label class="flex items-center gap-3 rounded-2xl border px-4 py-2 {{ old('priority', 'normal') === $value ? 'border-indigo-400/70 bg-slate-900/70' : 'border-white/10 bg-slate-900/30 hover:border-white/40' }}">
                                        <input type="radio" name="priority" value="{{ $value }}" class="h-4 w-4" {{ old('priority', 'normal') === $value ? 'checked' : '' }}>
                                        <span class="text-sm text-white">{{ $label }}</span>
                                    </label>
                                @endforeach
                            </div>
                            @error('priority')
                                <p class="mt-2 text-sm text-rose-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="message" class="text-xs font-semibold uppercase tracking-[0.3em] text-slate-400">Pesan</label>
                            <textarea id="message" name="message" rows="6" class="mt-2 w-full rounded-2xl border border-white/10 bg-slate-900/50 px-4 py-3 text-white placeholder:text-slate-600 focus:border-indigo-400 focus:outline-none" placeholder="Jelaskan kendala atau revisi yang dibutuhkan" required>{{ old('message') }}</textarea>
                            @error('message')
                                <p class="mt-2 text-sm text-rose-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="attachments" class="text-xs font-semibold uppercase tracking-[0.3em] text-slate-400">Lampiran (opsional)</label>
                            <input id="attachments" name="attachments[]" type="file" multiple class="mt-2 w-full rounded-2xl border border-dashed border-white/20 bg-slate-900/20 px-4 py-3 text-sm text-slate-300">
                            <p class="mt-2 text-xs text-slate-500">Maksimal 2MB per file.</p>
                            @error('attachments.*')
                                <p class="mt-2 text-sm text-rose-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <button type="submit" class="w-full rounded-3xl bg-gradient-to-r from-indigo-500 to-sky-500 px-6 py-3 text-center text-lg font-semibold text-white transition hover:opacity-90">Buat tiket & hubungkan tim →</button>
                    </form>
                </section>
            </div>
        </div>
    </div>
</x-app-layout>
