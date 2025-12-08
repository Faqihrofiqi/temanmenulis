@php
    $statusColors = [
        'pending' => 'border-amber-400/30 bg-amber-400/10 text-amber-200',
        'processing' => 'border-sky-400/40 bg-sky-400/10 text-sky-100',
        'paid' => 'border-emerald-400/40 bg-emerald-400/10 text-emerald-100',
        'completed' => 'border-slate-400/40 bg-slate-400/10 text-slate-100',
        'cancelled' => 'border-rose-400/40 bg-rose-400/10 text-rose-100',
    ];
@endphp

<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-2 text-white">
            <p class="text-xs uppercase tracking-[0.4em] text-slate-400">Cek Pesanan</p>
            <h1 class="text-3xl font-semibold">Pantau status order & pembayaran</h1>
            <p class="text-sm text-slate-400">Masukkan nomor order (contoh: DL-20240501-AB12CD) untuk melihat progres terbaru.</p>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-4xl space-y-8 px-6">
            <form method="POST" action="{{ route('orders.check.perform') }}" class="rounded-3xl border border-white/10 bg-slate-950/60 p-6">
                @csrf
                <label for="order_number" class="text-sm font-semibold uppercase tracking-[0.3em] text-slate-400">Nomor order</label>
                <div class="mt-3 flex flex-col gap-3 md:flex-row md:items-center">
                    <input id="order_number" name="order_number" type="text" placeholder="DL-20240501-XXXXXX" value="{{ old('order_number', $orderNumber ?? '') }}" class="flex-1 rounded-2xl border border-white/10 bg-slate-900/50 px-4 py-3 text-white placeholder:text-slate-500 focus:border-indigo-400 focus:outline-none" required>
                    <button type="submit" class="rounded-2xl bg-gradient-to-r from-indigo-500 to-sky-500 px-6 py-3 text-sm font-semibold text-white transition hover:opacity-90">Cek status →</button>
                </div>
                @error('order_number')
                    <p class="mt-2 text-sm text-rose-400">{{ $message }}</p>
                @enderror
            </form>

            @if ($order)
                <div class="space-y-4 rounded-3xl border border-white/10 bg-slate-950/60 p-6">
                    <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
                        <div>
                            <p class="text-xs uppercase tracking-[0.4em] text-slate-400">Order ditemukan</p>
                            <h2 class="text-2xl font-semibold text-white">{{ $order->order_number }}</h2>
                            <p class="text-sm text-slate-400">{{ $order->service->name ?? 'Tanpa layanan' }}</p>
                        </div>
                        <div class="flex flex-wrap gap-2 text-xs">
                            <span class="rounded-full border border-white/20 px-3 py-1">Order: {{ ucfirst($order->status) }}</span>
                            <span class="rounded-full border border-white/20 px-3 py-1">Pembayaran: {{ ucfirst($order->payment_status ?? 'pending') }}</span>
                        </div>
                    </div>

                    <div class="grid gap-4 md:grid-cols-2">
                        <div class="rounded-2xl border border-white/10 bg-slate-900/40 p-4 text-sm text-slate-300">
                            <p class="text-xs uppercase tracking-[0.3em] text-slate-500">Detail</p>
                            <div class="mt-3 space-y-2">
                                <div class="flex items-center justify-between">
                                    <span>Nominal</span>
                                    <span class="text-white">Rp{{ number_format($order->amount, 0, ',', '.') }}</span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span>Tanggal</span>
                                    <span>{{ $order->created_at?->translatedFormat('d M Y H:i') }}</span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span>Metode</span>
                                    <span>{{ ucfirst(data_get($order->metadata, 'payment_channel', 'qris')) }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="rounded-2xl border border-white/10 bg-slate-900/40 p-4 text-sm text-slate-300">
                            <p class="text-xs uppercase tracking-[0.3em] text-slate-500">Catatan</p>
                            <p class="mt-3 text-slate-200">{{ $order->order_details ?: 'Belum ada catatan tambahan.' }}</p>
                        </div>
                    </div>

                    <div class="rounded-2xl border border-white/10 bg-slate-900/40 p-4">
                        <p class="text-xs uppercase tracking-[0.3em] text-slate-500">Langkah berikutnya</p>
                        <div class="mt-3 grid gap-3 md:grid-cols-3">
                            <div class="rounded-2xl border border-white/10 bg-slate-900/50 px-4 py-3 text-sm text-slate-300">Tim finance memvalidasi pembayaran.</div>
                            <div class="rounded-2xl border border-white/10 bg-slate-900/50 px-4 py-3 text-sm text-slate-300">Project manager membuat brief eksekusi.</div>
                            <div class="rounded-2xl border border-white/10 bg-slate-900/50 px-4 py-3 text-sm text-slate-300">Update dikirim ke dashboard & email.</div>
                        </div>
                    </div>

                    <div class="flex flex-col gap-3 text-sm text-slate-300 md:flex-row md:items-center md:justify-between">
                        <a href="{{ route('orders.payment', $order->order_number) }}" class="inline-flex items-center justify-center rounded-full border border-white/20 px-5 py-2 font-semibold text-white transition hover:border-white/60">Buka halaman pembayaran</a>
                        <div class="text-xs text-slate-500">Butuh bantuan? Hubungi {{ config('payments.bank_transfer.support_contact', 'support@deadlineku.id') }}</div>
                    </div>
                </div>
            @elseif(! empty($orderNumber))
                <div class="rounded-3xl border border-rose-400/30 bg-rose-400/10 p-6 text-sm text-rose-100">
                    Order dengan nomor <span class="font-semibold">{{ $orderNumber }}</span> belum ditemukan. Pastikan formatnya benar atau hubungi admin untuk verifikasi manual.
                </div>
            @else
                <div class="rounded-3xl border border-dashed border-white/10 p-6 text-center text-sm text-slate-400">
                    Masukkan nomor order untuk menampilkan progres pembayaran dan produksi.
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
