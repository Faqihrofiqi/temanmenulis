@php
    use Illuminate\Support\Facades\Crypt;
    use Illuminate\Support\Facades\Storage;

    $statusColors = [
        'open' => 'border-amber-400/40 bg-amber-400/10 text-amber-200',
        'in_progress' => 'border-sky-400/40 bg-sky-400/10 text-sky-100',
        'resolved' => 'border-emerald-400/40 bg-emerald-400/10 text-emerald-100',
        'closed' => 'border-slate-400/40 bg-slate-400/10 text-slate-200',
    ];
@endphp

<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-3 text-white">
            <p class="text-xs uppercase tracking-[0.4em] text-slate-400">Admin · Ticket Detail</p>
            <div class="flex flex-col gap-2 lg:flex-row lg:items-center lg:justify-between">
                <div>
                    <h1 class="text-3xl font-semibold">{{ $ticket->subject }}</h1>
                    <p class="text-sm text-slate-400">{{ $ticket->code }} · Prioritas {{ ucfirst($ticket->priority) }}</p>
                </div>
                <div class="flex flex-wrap gap-2 text-xs">
                    <span class="rounded-full border px-3 py-1 font-semibold {{ $statusColors[$ticket->status] ?? 'border-white/20' }}">
                        {{ ucfirst(str_replace('_', ' ', $ticket->status)) }}
                    </span>
                    <a href="{{ route('admin.tickets.index') }}" class="rounded-full border border-white/20 px-4 py-2 font-semibold text-white">Kembali ke tiket</a>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-5xl space-y-6 px-6">
            @if (session('success'))
                <div class="rounded-2xl border border-emerald-400/40 bg-emerald-400/10 px-4 py-3 text-sm text-emerald-100">
                    {{ session('success') }}
                </div>
            @endif

            <div class="grid gap-6 lg:grid-cols-[1.35fr_0.65fr]">
                <section class="space-y-4 rounded-3xl border border-white/10 bg-slate-950/60 p-6">
                    <p class="text-xs uppercase tracking-[0.4em] text-slate-400">Percakapan</p>
                    <div class="space-y-6">
                        @foreach($ticket->messages as $message)
                            <article class="rounded-2xl border border-white/10 bg-slate-900/40 p-4">
                                <div class="flex flex-col gap-2 md:flex-row md:items-center md:justify-between">
                                    <div>
                                        <p class="text-sm font-semibold text-white">{{ $message->user->name ?? $message->sender_name ?? 'Klien' }}</p>
                                        <p class="text-xs uppercase tracking-[0.3em] text-slate-500">{{ $message->user->email ?? $message->sender_email ?? $ticket->user->email }}</p>
                                    </div>
                                    <p class="text-xs text-slate-500">{{ $message->created_at->translatedFormat('d M Y H:i') }}</p>
                                </div>
                                @if($message->is_internal)
                                    <span class="mt-2 inline-flex items-center rounded-full border border-rose-400/40 px-3 py-1 text-xs text-rose-200">Catatan internal</span>
                                @endif
                                <p class="mt-3 text-sm text-slate-200 whitespace-pre-line">{{ $message->message }}</p>
                                @if(! empty($message->attachments))
                                    <div class="mt-3 space-y-2">
                                        <p class="text-xs uppercase tracking-[0.3em] text-slate-500">Lampiran</p>
                                        <div class="flex flex-col gap-2">
                                            @foreach($message->attachments as $path)
                                                <a href="{{ route('tickets.attachments.download', ['ticket' => $ticket, 'attachment' => Crypt::encryptString($path)]) }}" class="inline-flex items-center gap-2 rounded-xl border border-white/10 px-3 py-2 text-xs text-white hover:border-white/40">
                                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16v4h4M20 8V4h-4m0 16l4-4m-4-12L4 20"></path>
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
                        <p class="text-xs uppercase tracking-[0.4em] text-slate-500">Ringkasan klien</p>
                        <div class="mt-4 space-y-3">
                            <div class="flex items-center justify-between">
                                <span>Nama</span>
                                <span class="font-semibold text-white">{{ $ticket->user->name }}</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span>Email</span>
                                <span>{{ $ticket->user->email }}</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span>Order</span>
                                <span>{{ $ticket->order->order_number ?? 'Tidak ada' }}</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span>Dibuat</span>
                                <span>{{ $ticket->created_at->translatedFormat('d M Y H:i') }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="rounded-3xl border border-white/10 bg-slate-950/60 p-6">
                        <p class="text-xs uppercase tracking-[0.4em] text-slate-500">Balas / update status</p>
                        <form method="POST" action="{{ route('admin.tickets.reply', $ticket) }}" enctype="multipart/form-data" class="mt-4 space-y-4">
                            @csrf
                            <div>
                                <label for="status" class="text-xs font-semibold uppercase tracking-[0.3em] text-slate-400">Status</label>
                                <select id="status" name="status" class="mt-2 w-full rounded-2xl border border-white/10 bg-slate-900/30 px-3 py-2 text-white">
                                    @foreach($statusOptions as $status)
                                        <option value="{{ $status }}" @selected($ticket->status === $status)>{{ ucfirst(str_replace('_', ' ', $status)) }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label for="message" class="text-xs font-semibold uppercase tracking-[0.3em] text-slate-400">Pesan</label>
                                <textarea id="message" name="message" rows="5" class="mt-2 w-full rounded-2xl border border-white/10 bg-slate-900/30 px-3 py-3 text-white placeholder:text-slate-500" placeholder="Tulis update terbaru" required>{{ old('message') }}</textarea>
                                @error('message')
                                    <p class="mt-2 text-sm text-rose-400">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="flex items-center gap-2 text-xs text-slate-400">
                                <input id="is_internal" type="checkbox" name="is_internal" value="1" class="h-4 w-4 rounded border-white/20 bg-slate-900/30">
                                <label for="is_internal">Tandai sebagai catatan internal</label>
                            </div>
                            <div>
                                <label for="attachments" class="text-xs font-semibold uppercase tracking-[0.3em] text-slate-400">Lampiran</label>
                                <input id="attachments" name="attachments[]" type="file" multiple class="mt-2 w-full rounded-2xl border border-dashed border-white/20 bg-slate-900/10 px-3 py-2 text-sm text-slate-300">
                            </div>
                            <button type="submit" class="w-full rounded-3xl bg-gradient-to-r from-indigo-500 to-sky-500 px-5 py-3 text-sm font-semibold text-white">Kirim balasan</button>
                        </form>
                        <form method="POST" action="{{ route('admin.tickets.close', $ticket) }}" class="mt-4">
                            @csrf
                            <button type="submit" class="w-full rounded-3xl border border-white/20 px-5 py-3 text-sm font-semibold text-white hover:border-white/60">Tutup tiket</button>
                        </form>
                    </div>
                </section>
            </div>
        </div>
    </div>
</x-app-layout>
