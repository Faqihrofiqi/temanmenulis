@php
    $statusColors = [
        'open' => 'bg-amber-400/10 text-amber-200 border-amber-400/40',
        'in_progress' => 'bg-sky-400/10 text-sky-100 border-sky-400/40',
        'resolved' => 'bg-emerald-400/10 text-emerald-100 border-emerald-400/40',
        'closed' => 'bg-slate-400/10 text-slate-200 border-slate-400/40',
    ];

    $priorityColors = [
        'low' => 'bg-slate-400/10 text-slate-100 border-slate-400/30',
        'normal' => 'bg-indigo-400/10 text-indigo-100 border-indigo-400/30',
        'high' => 'bg-rose-400/10 text-rose-100 border-rose-400/30',
        'urgent' => 'bg-rose-500/20 text-rose-100 border-rose-500/40',
    ];
@endphp

<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-2 text-white">
            <p class="text-xs uppercase tracking-[0.4em] text-slate-400">Admin · Ticket Console</p>
            <h1 class="text-3xl font-semibold">Monitor & balas tiket support</h1>
            <p class="text-sm text-slate-400">Semua percakapan pelanggan, terpusat dalam satu meja kerja.</p>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-6xl space-y-6 px-6">
            <form method="GET" class="grid gap-4 rounded-3xl border border-white/10 bg-slate-950/60 p-6 text-sm text-slate-300 md:grid-cols-4">
                <div>
                    <label for="status" class="text-xs uppercase tracking-[0.3em] text-slate-500">Status</label>
                    <select id="status" name="status" class="mt-2 w-full rounded-xl border border-white/10 bg-slate-900/50 px-3 py-2 text-white">
                        <option value="">Semua</option>
                        @foreach(array_keys($statusColors) as $status)
                            <option value="{{ $status }}" @selected($selectedStatus === $status)>{{ ucfirst(str_replace('_', ' ', $status)) }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="priority" class="text-xs uppercase tracking-[0.3em] text-slate-500">Prioritas</label>
                    <select id="priority" name="priority" class="mt-2 w-full rounded-xl border border-white/10 bg-slate-900/50 px-3 py-2 text-white">
                        <option value="">Semua</option>
                        @foreach(array_keys($priorityColors) as $priority)
                            <option value="{{ $priority }}" @selected($selectedPriority === $priority)>{{ ucfirst($priority) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="md:col-span-2 flex items-end gap-3">
                    <button type="submit" class="flex-1 rounded-2xl bg-white/90 px-4 py-3 text-center font-semibold text-slate-900">Terapkan filter</button>
                    <a href="{{ route('admin.tickets.index') }}" class="rounded-2xl border border-white/20 px-4 py-3 text-center font-semibold text-white">Reset</a>
                </div>
            </form>

            <section class="rounded-3xl border border-white/10 bg-slate-950/60 p-6">
                <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
                    <div>
                        <p class="text-xs uppercase tracking-[0.3em] text-slate-500">Daftar tiket</p>
                        <h2 class="text-2xl font-semibold text-white">{{ $tickets->total() }} tiket</h2>
                    </div>
                    <p class="text-xs text-slate-400">Klik baris untuk membuka detail.</p>
                </div>

                <div class="mt-6 overflow-x-auto">
                    <table class="min-w-full divide-y divide-white/10 text-sm text-slate-200">
                        <thead class="text-left text-xs uppercase tracking-[0.3em] text-slate-500">
                            <tr>
                                <th class="py-3 pr-4">Tiket</th>
                                <th class="py-3 pr-4">Klien</th>
                                <th class="py-3 pr-4">Order</th>
                                <th class="py-3 pr-4">Prioritas</th>
                                <th class="py-3 pr-4">Status</th>
                                <th class="py-3 text-right">Update</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-white/5">
                            @forelse($tickets as $ticket)
                                <tr class="align-top text-sm">
                                    <td class="py-4 pr-4">
                                        <a href="{{ route('admin.tickets.show', $ticket) }}" class="font-semibold text-white hover:underline">
                                            {{ $ticket->code }} · {{ $ticket->subject }}
                                        </a>
                                        <p class="text-xs text-slate-400">{{ \Illuminate\Support\Str::limit(optional($ticket->messages->first())->message ?? '', 70) }}</p>
                                    </td>
                                    <td class="py-4 pr-4">
                                        <p class="font-semibold">{{ $ticket->user->name ?? 'Guest' }}</p>
                                        <p class="text-xs text-slate-400">{{ $ticket->user->email ?? '-' }}</p>
                                    </td>
                                    <td class="py-4 pr-4">
                                        @if($ticket->order)
                                            <span class="rounded-full border border-white/20 px-3 py-1 text-xs">{{ $ticket->order->order_number }}</span>
                                        @else
                                            <span class="text-xs text-slate-500">Tidak terkait</span>
                                        @endif
                                    </td>
                                    <td class="py-4 pr-4">
                                        <span class="inline-flex items-center rounded-full border px-3 py-1 text-xs font-semibold {{ $priorityColors[$ticket->priority] ?? 'border-white/20' }}">
                                            {{ ucfirst($ticket->priority) }}
                                        </span>
                                    </td>
                                    <td class="py-4 pr-4">
                                        <span class="inline-flex items-center rounded-full border px-3 py-1 text-xs font-semibold {{ $statusColors[$ticket->status] ?? 'border-white/20' }}">
                                            {{ ucfirst(str_replace('_', ' ', $ticket->status)) }}
                                        </span>
                                    </td>
                                    <td class="py-4 text-right text-xs text-slate-400">{{ $ticket->updated_at->diffForHumans() }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="py-8 text-center text-slate-400">Belum ada tiket.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-6">
                    {{ $tickets->links() }}
                </div>
            </section>
        </div>
    </div>
</x-app-layout>
