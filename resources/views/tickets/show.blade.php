@php
    use Illuminate\Support\Facades\Crypt;
    use Illuminate\Support\Facades\Storage;

    $statusColors = [
        'open' => 'bg-amber-400/10 text-amber-200 border-amber-400/40',
        'in_progress' => 'bg-sky-400/10 text-sky-100 border-sky-400/40',
        'resolved' => 'bg-emerald-400/10 text-emerald-100 border-emerald-400/40',
        'closed' => 'bg-slate-400/10 text-slate-100 border-slate-400/40',
    ];
@endphp

<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-2 text-white">
            <p class="text-xs uppercase tracking-[0.4em] text-slate-400">Ticket Detail</p>
            <div class="flex flex-col gap-3 lg:flex-row lg:items-center lg:justify-between">
                <div>
                    <h1 class="text-3xl font-semibold">{{ $ticket->subject }}</h1>
                    <p class="text-sm text-slate-400">Kode {{ $ticket->code }} • Prioritas {{ ucfirst($ticket->priority) }}</p>
                </div>
                <div class="flex flex-wrap gap-2 text-xs">
                    <span class="rounded-full border px-3 py-1 font-semibold {{ $statusColors[$ticket->status] ?? 'border-white/20 text-white' }}">
                        {{ ucfirst(str_replace('_', ' ', $ticket->status)) }}
                    </span>
                    @if($ticket->last_replied_at)
                        <span class="rounded-full border border-white/20 px-3 py-1">Terakhir update {{ $ticket->last_replied_at->diffForHumans() }}</span>
                    @endif
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-5xl space-y-8 px-6">
            @if (session('success'))
                <div class="rounded-2xl border border-emerald-400/40 bg-emerald-400/10 px-4 py-3 text-sm text-emerald-100">
                    {{ session('success') }}
                </div>
            @endif

            <div class="grid gap-6 lg:grid-cols-[1.35fr_0.65fr]">
                <section class="space-y-4 rounded-3xl border border-white/10 bg-slate-950/60 p-6">
                    <p class="text-xs uppercase tracking-[0.4em] text-slate-400">Timeline percakapan</p>
                    <div class="mt-4 space-y-6">
                        @foreach($ticket->messages as $message)
                            <article class="rounded-2xl border border-white/10 bg-slate-900/40 p-4">
                                <div class="flex flex-col gap-2 md:flex-row md:items-center md:justify-between">
                                    <div>
                                        <p class="text-sm font-semibold text-white">
                                            {{ $message->user?->name ?? $message->sender_name ?? 'Klien' }}
                                            <span class="text-xs text-slate-400">{{ $message->created_at->translatedFormat('d M Y H:i') }}</span>
                                        </p>
                                        <p class="text-xs uppercase tracking-[0.3em] text-slate-500">{{ $message->user?->email ?? $message->sender_email ?? $ticket->user->email }}</p>
                                    </div>
                                    @if($message->is_internal)
                                        <span class="rounded-full border border-rose-400/40 px-3 py-1 text-xs text-rose-200">Internal note</span>
                                    @endif
                                </div>
                                <p class="mt-3 text-sm text-slate-200 whitespace-pre-line">{{ $message->message }}</p>

                                @if(! empty($message->attachments))
                                    <div class="mt-3 space-y-2">
                                        <p class="text-xs font-semibold uppercase tracking-[0.3em] text-slate-400">Lampiran</p>
                                        <div class="flex flex-col gap-2">
                                            @foreach($message->attachments as $path)
                                                <a href="{{ route('tickets.attachments.download', ['ticket' => $ticket, 'attachment' => Crypt::encryptString($path)]) }}" class="inline-flex items-center gap-2 rounded-xl border border-white/10 px-3 py-2 text-xs text-white hover:border-white/40">
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
                    <div class="rounded-3xl border border-white/10 bg-slate-950/60 p-6 text-sm text-slate-300">
                        <p class="text-xs uppercase tracking-[0.4em] text-slate-400">Ringkasan</p>
                        <div class="mt-4 space-y-3">
                            <div class="flex items-center justify-between">
                                <span>Order Terkait</span>
                                <span class="font-semibold text-white">{{ $ticket->order?->order_number ?? 'Tidak ada' }}</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span>Status Order</span>
                                <span>{{ $ticket->order?->status ? ucfirst($ticket->order->status) : '-' }}</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span>Dibuat</span>
                                <span>{{ $ticket->created_at->translatedFormat('d M Y H:i') }}</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span>Channel</span>
                                <span>{{ ucfirst($ticket->channel) }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="rounded-3xl border border-white/10 bg-slate-950/60 p-6">
                        <p class="text-xs uppercase tracking-[0.4em] text-slate-400">Balas tiket</p>
                        <form method="POST" action="{{ route('tickets.reply', $ticket) }}" enctype="multipart/form-data" class="mt-4 space-y-4">
                            @csrf
                            <div>
                                <label for="message" class="text-xs font-semibold uppercase tracking-[0.3em] text-slate-400">Pesan</label>
                                <textarea id="message" name="message" rows="6" class="mt-2 w-full rounded-2xl border border-white/10 bg-slate-900/40 px-4 py-3 text-white placeholder:text-slate-500 focus:border-indigo-400 focus:outline-none" placeholder="Tulis update terbaru" required>{{ old('message') }}</textarea>
                                @error('message')
                                    <p class="mt-2 text-sm text-rose-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="attachments" class="text-xs font-semibold uppercase tracking-[0.3em] text-slate-400">Lampiran</label>
                                <input id="attachments" name="attachments[]" type="file" multiple class="mt-2 w-full rounded-2xl border border-dashed border-white/20 bg-slate-900/20 px-4 py-3 text-sm text-slate-300">
                                <p class="mt-2 text-xs text-slate-500">Maksimal 2MB per file.</p>
                                @error('attachments.*')
                                    <p class="mt-2 text-sm text-rose-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <button type="submit" class="w-full rounded-3xl bg-gradient-to-r from-emerald-500 to-sky-500 px-6 py-3 text-center text-sm font-semibold text-white transition hover:opacity-90">Kirim balasan →</button>
                        </form>
                    </div>

                    <div class="rounded-3xl border border-white/10 bg-gradient-to-br from-slate-900/80 via-indigo-950 to-slate-950 p-6 text-sm text-slate-200">
                        <p class="text-xs uppercase tracking-[0.4em] text-slate-400">Bantuan cepat</p>
                        <p class="mt-3">Hubungi tim support di <span class="font-semibold text-white">{{ config('payments.bank_transfer.support_contact', 'support@deadlineku.id') }}</span> untuk eskalasi urgensi.</p>
                        <a href="mailto:{{ config('payments.bank_transfer.support_contact', 'support@deadlineku.id') }}" class="mt-4 inline-flex items-center justify-center rounded-full border border-white/20 px-4 py-2 text-white transition hover:border-white/60">Email support</a>
                    </div>
                </section>
            </div>
        </div>
    </div>
</x-app-layout>
