@php
    use Illuminate\Support\Facades\Crypt;
    use Illuminate\Support\Facades\Storage;

    $statusColors = [
        'open' => 'border-amber-200 bg-amber-50 text-amber-900 dark:border-amber-400/40 dark:bg-amber-400/10 dark:text-amber-100',
        'in_progress' => 'border-sky-200 bg-sky-50 text-sky-900 dark:border-sky-400/40 dark:bg-sky-400/10 dark:text-sky-100',
        'resolved' => 'border-emerald-200 bg-emerald-50 text-emerald-900 dark:border-emerald-400/40 dark:bg-emerald-400/10 dark:text-emerald-100',
        'closed' => 'border-slate-200 bg-slate-50 text-slate-900 dark:border-white/20 dark:bg-white/10 dark:text-white',
    ];
@endphp

<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-2 text-slate-900 dark:text-white">
            <p class="text-xs uppercase tracking-[0.4em] text-muted">Ticket Detail</p>
            <div class="flex flex-col gap-3 lg:flex-row lg:items-center lg:justify-between">
                <div>
                    <h1 class="text-3xl font-semibold">{{ $ticket->subject }}</h1>
                    <p class="text-sm text-subtle">Kode {{ $ticket->code }} • Prioritas {{ ucfirst($ticket->priority) }}</p>
                </div>
                <div class="flex flex-wrap gap-2 text-xs">
                    <span class="rounded-full border px-3 py-1 font-semibold {{ $statusColors[$ticket->status] ?? 'border-slate-200 text-slate-900 dark:border-white/20 dark:text-white' }}">
                        {{ ucfirst(str_replace('_', ' ', $ticket->status)) }}
                    </span>
                    @if($ticket->last_replied_at)
                        <span class="rounded-full border border-slate-200 px-3 py-1 text-subtle dark:border-white/20 dark:text-slate-300">Terakhir update {{ $ticket->last_replied_at->diffForHumans() }}</span>
                    @endif
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-5xl space-y-8 px-6">
            @if (session('success'))
                <div class="rounded-2xl border border-emerald-200/70 bg-emerald-50 px-4 py-3 text-sm text-emerald-900 dark:border-emerald-400/30 dark:bg-emerald-400/10 dark:text-emerald-100">
                    {{ session('success') }}
                </div>
            @endif

            <div class="grid gap-6 lg:grid-cols-[1.35fr_0.65fr]">
                <section class="surface-card space-y-4 p-6">
                    <p class="text-xs uppercase tracking-[0.4em] text-muted">Timeline percakapan</p>
                    <div class="mt-4 space-y-6">
                        @foreach($ticket->messages as $message)
                            <article class="rounded-2xl border border-slate-200/80 bg-white/80 p-4 dark:border-white/10 dark:bg-white/5">
                                <div class="flex flex-col gap-2 md:flex-row md:items-center md:justify-between">
                                    <div>
                                        <p class="text-sm font-semibold text-slate-900 dark:text-white">
                                            {{ $message->user?->name ?? $message->sender_name ?? 'Klien' }}
                                            <span class="text-xs text-subtle">{{ $message->created_at->translatedFormat('d M Y H:i') }}</span>
                                        </p>
                                        <p class="text-xs uppercase tracking-[0.3em] text-muted">{{ $message->user?->email ?? $message->sender_email ?? $ticket->user->email }}</p>
                                    </div>
                                    @if($message->is_internal)
                                        <span class="rounded-full border border-rose-200/70 bg-rose-50 px-3 py-1 text-xs text-rose-800 dark:border-rose-400/40 dark:bg-rose-400/10 dark:text-rose-100">Internal note</span>
                                    @endif
                                </div>
                                <p class="mt-3 whitespace-pre-line text-sm text-muted">{{ $message->message }}</p>

                                @if(! empty($message->attachments))
                                    <div class="mt-3 space-y-2">
                                        <p class="text-xs font-semibold uppercase tracking-[0.3em] text-muted">Lampiran</p>
                                        <div class="flex flex-col gap-2">
                                            @foreach($message->attachments as $path)
                                                <a href="{{ route('tickets.attachments.download', ['ticket' => $ticket, 'attachment' => Crypt::encryptString($path)]) }}" class="inline-flex items-center gap-2 rounded-xl border border-slate-200 px-3 py-2 text-xs text-slate-700 hover:border-slate-500 dark:border-white/20 dark:text-slate-200">
                                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                    </svg>
                                                    {{ basename($path) }}
                                                </a>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                            </article>
                        @endforeach
                    </div>
                </section>

                <section class="space-y-6">
                    <div class="surface-card p-6 text-sm text-muted">
                        <p class="text-xs uppercase tracking-[0.4em] text-muted">Ringkasan</p>
                        <div class="mt-4 space-y-3 text-slate-900 dark:text-white">
                            <div class="flex items-center justify-between text-sm">
                                <span class="text-subtle">Order Terkait</span>
                                <span class="font-semibold">{{ $ticket->order?->order_number ?? 'Tidak ada' }}</span>
                            </div>
                            <div class="flex items-center justify-between text-sm">
                                <span class="text-subtle">Status Order</span>
                                <span>{{ $ticket->order?->status ? ucfirst($ticket->order->status) : '-' }}</span>
                            </div>
                            <div class="flex items-center justify-between text-sm">
                                <span class="text-subtle">Dibuat</span>
                                <span>{{ $ticket->created_at->translatedFormat('d M Y H:i') }}</span>
                            </div>
                            <div class="flex items-center justify-between text-sm">
                                <span class="text-subtle">Channel</span>
                                <span>{{ ucfirst($ticket->channel) }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="surface-card p-6">
                        <p class="text-xs uppercase tracking-[0.4em] text-muted">Balas tiket</p>
                        <form method="POST" action="{{ route('tickets.reply', $ticket) }}" enctype="multipart/form-data" class="mt-4 space-y-4">
                            @csrf
                            <div>
                                <label for="message" class="form-label">Pesan</label>
                                <textarea id="message" name="message" rows="6" class="form-input mt-2" placeholder="Tulis update terbaru" required>{{ old('message') }}</textarea>
                                @error('message')
                                    <p class="mt-2 text-sm text-rose-500 dark:text-rose-300">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="attachments" class="form-label">Lampiran</label>
                                <input id="attachments" name="attachments[]" type="file" multiple class="form-input-muted mt-2">
                                <p class="mt-2 text-xs text-subtle">Maksimal 2MB per file.</p>
                                @error('attachments.*')
                                    <p class="mt-2 text-sm text-rose-500 dark:text-rose-300">{{ $message }}</p>
                                @enderror
                            </div>

                            <button type="submit" class="w-full rounded-3xl bg-slate-900 px-6 py-3 text-center text-sm font-semibold text-white transition hover:bg-slate-700 dark:bg-white dark:text-slate-900">Kirim balasan →</button>
                        </form>
                    </div>

                    <div class="rounded-3xl border border-slate-200/80 bg-gradient-to-br from-white via-slate-50 to-indigo-50 p-6 text-sm text-slate-700 dark:border-white/10 dark:from-slate-900/80 dark:via-indigo-950 dark:to-slate-950 dark:text-slate-200">
                        <p class="text-xs uppercase tracking-[0.4em] text-muted">Bantuan cepat</p>
                        <p class="mt-3">Hubungi tim support di <span class="font-semibold text-slate-900 dark:text-white">{{ config('payments.bank_transfer.support_contact', 'support@deadlineku.id') }}</span> untuk eskalasi urgensi.</p>
                        <a href="mailto:{{ config('payments.bank_transfer.support_contact', 'support@deadlineku.id') }}" class="mt-4 inline-flex items-center justify-center rounded-full border border-slate-900 px-4 py-2 text-slate-900 transition hover:bg-slate-900 hover:text-white dark:border-white/40 dark:text-white">Email support</a>
                    </div>
                </section>
            </div>
        </div>
    </div>
</x-app-layout>
