@php
    $statusColors = [
        'pending' => 'bg-amber-400/10 text-amber-100 border-amber-400/30',
        'processing' => 'bg-sky-400/10 text-sky-100 border-sky-400/30',
        'paid' => 'bg-emerald-400/10 text-emerald-100 border-emerald-400/30',
        'completed' => 'bg-slate-400/10 text-slate-100 border-slate-400/30',
        'cancelled' => 'bg-rose-400/10 text-rose-100 border-rose-400/30',
    ];
@endphp

<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-2 text-white">
            <p class="text-xs uppercase tracking-[0.4em] text-slate-400">Admin · Orders</p>
            <h1 class="text-3xl font-semibold">Kelola transaksi, harga & status produksi</h1>
            <p class="text-sm text-slate-400">Klik transaksi untuk melihat detail pembayaran.</p>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-6xl space-y-6 px-6">
            <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
                @foreach($stats as $label => $value)
                    <div class="rounded-3xl border border-white/10 bg-slate-950/60 p-4 text-white">
                        <p class="text-xs uppercase tracking-[0.35em] text-slate-500">{{ ucfirst($label) }}</p>
                        <p class="mt-3 text-3xl font-semibold">{{ number_format($value) }}</p>
                    </div>
                @endforeach
            </div>

            <form method="GET" class="grid gap-4 rounded-3xl border border-white/10 bg-slate-950/60 p-6 text-sm text-slate-300 md:grid-cols-3">
                <div>
                    <label for="status" class="text-xs uppercase tracking-[0.3em] text-slate-500">Status order</label>
                    <select id="status" name="status" class="mt-2 w-full rounded-2xl border border-white/10 bg-slate-900/40 px-3 py-2 text-white">
                        <option value="">Semua</option>
                        @foreach(array_keys($statusColors) as $status)
                            <option value="{{ $status }}" @selected($selectedStatus === $status)>{{ ucfirst($status) }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="payment_status" class="text-xs uppercase tracking-[0.3em] text-slate-500">Status pembayaran</label>
                    <select id="payment_status" name="payment_status" class="mt-2 w-full rounded-2xl border border-white/10 bg-slate-900/40 px-3 py-2 text-white">
                        <option value="">Semua</option>
                        @foreach(['pending', 'waiting', 'settlement', 'cancel', 'expire', 'failed'] as $payment)
                            <option value="{{ $payment }}" @selected($selectedPaymentStatus === $payment)>{{ ucfirst($payment) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="md:flex md:items-end md:gap-3">
                    <button type="submit" class="flex-1 rounded-2xl bg-white/90 px-4 py-3 text-center font-semibold text-slate-900">Filter</button>
                    <a href="{{ route('admin.orders.index') }}" class="mt-3 block rounded-2xl border border-white/20 px-4 py-3 text-center font-semibold text-white md:mt-0">Reset</a>
                </div>
            </form>

            <section class="rounded-3xl border border-white/10 bg-slate-950/60 p-6">
                <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
                    <div>
                        <p class="text-xs uppercase tracking-[0.3em] text-slate-500">Daftar order</p>
                        <h2 class="text-2xl font-semibold text-white">{{ $orders->total() }} transaksi</h2>
                    </div>
                    <p class="text-xs text-slate-400">Klik baris untuk membuka detail.</p>
                </div>

                <div class="mt-6 overflow-x-auto">
                    <table class="min-w-full divide-y divide-white/10 text-sm text-slate-200">
                        <thead class="text-left text-xs uppercase tracking-[0.3em] text-slate-500">
                            <tr>
                                <th class="py-3 pr-4">Order</th>
                                <th class="py-3 pr-4">Klien</th>
                                <th class="py-3 pr-4">Layanan</th>
                                <th class="py-3 pr-4">Nominal</th>
                                <th class="py-3 pr-4">Status</th>
                                <th class="py-3 pr-4">Payment</th>
                                <th class="py-3 text-right">Tanggal</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-white/5">
                            @forelse($orders as $order)
                                <tr class="align-top">
                                    <td class="py-4 pr-4">
                                        <a href="{{ route('admin.orders.show', $order) }}" class="font-semibold text-white hover:underline">{{ $order->order_number }}</a>
                                        <p class="text-xs text-slate-400">{{ $order->metadata['source'] ?? 'website' }}</p>
                                    </td>
                                    <td class="py-4 pr-4">
                                        <p class="font-semibold">{{ $order->user->name ?? $order->customer_name }}</p>
                                        <p class="text-xs text-slate-400">{{ $order->user->email ?? $order->customer_email }}</p>
                                    </td>
                                    <td class="py-4 pr-4 text-sm">{{ $order->service->name ?? '-' }}</td>
                                    <td class="py-4 pr-4 font-semibold">Rp{{ number_format($order->amount, 0, ',', '.') }}</td>
                                    <td class="py-4 pr-4">
                                        <span class="inline-flex items-center rounded-full border px-3 py-1 text-xs font-semibold {{ $statusColors[$order->status] ?? 'border-white/20' }}">{{ ucfirst($order->status) }}</span>
                                    </td>
                                    <td class="py-4 pr-4 text-xs text-slate-400">{{ ucfirst($order->payment_status ?? 'pending') }}</td>
                                    <td class="py-4 text-right text-xs text-slate-500">{{ $order->created_at->translatedFormat('d M Y H:i') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="py-8 text-center text-slate-400">Belum ada order.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-6">
                    {{ $orders->links() }}
                </div>
            </section>
        </div>
    </div>
</x-app-layout>
