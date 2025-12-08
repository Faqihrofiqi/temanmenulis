<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-2 text-white">
            <p class="text-xs uppercase tracking-[0.4em] text-slate-400">Admin · Order Detail</p>
            <div class="flex flex-col gap-2 lg:flex-row lg:items-center lg:justify-between">
                <div>
                    <h1 class="text-3xl font-semibold">{{ $order->order_number }}</h1>
                    <p class="text-sm text-slate-400">{{ $order->service->name ?? 'Tanpa layanan' }} · {{ ucfirst($order->status) }}</p>
                </div>
                <a href="{{ route('admin.orders.index') }}" class="rounded-full border border-white/20 px-4 py-2 text-sm font-semibold text-white">Kembali ke order</a>
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

            <div class="grid gap-6 lg:grid-cols-[1.3fr_0.7fr]">
                <section class="space-y-6 rounded-3xl border border-white/10 bg-slate-950/60 p-6">
                    <div class="grid gap-4 text-sm text-slate-200 md:grid-cols-2">
                        <div>
                            <p class="text-xs uppercase tracking-[0.3em] text-slate-500">Klien</p>
                            <p class="mt-2 font-semibold text-white">{{ $order->user->name ?? $order->customer_name }}</p>
                            <p class="text-xs text-slate-400">{{ $order->user->email ?? $order->customer_email }}</p>
                        </div>
                        <div>
                            <p class="text-xs uppercase tracking-[0.3em] text-slate-500">Nominal</p>
                            <p class="mt-2 text-3xl font-semibold text-white">Rp{{ number_format($order->amount, 0, ',', '.') }}</p>
                            @if(data_get($order->metadata, 'applied_discount'))
                                <p class="text-xs text-slate-400">Diskon {{ data_get($order->metadata, 'applied_discount.percentage') }}% · {{ data_get($order->metadata, 'applied_discount.label') }}</p>
                            @endif
                        </div>
                        <div>
                            <p class="text-xs uppercase tracking-[0.3em] text-slate-500">Pembayaran</p>
                            <p class="mt-2 font-semibold text-white">{{ ucfirst($order->payment_status ?? 'pending') }}</p>
                            <p class="text-xs text-slate-400">Channel: {{ data_get($order->metadata, 'payment_channel', 'qris') }}</p>
                        </div>
                        <div>
                            <p class="text-xs uppercase tracking-[0.3em] text-slate-500">Timeline</p>
                            <p class="mt-2">Dibuat {{ $order->created_at->translatedFormat('d M Y H:i') }}</p>
                            <p class="text-xs text-slate-400">Paid at: {{ $order->paid_at?->translatedFormat('d M Y H:i') ?? '-' }}</p>
                        </div>
                    </div>

                    <div class="rounded-2xl border border-white/10 bg-slate-900/30 p-4">
                        <p class="text-xs uppercase tracking-[0.3em] text-slate-500">Metadata</p>
                        <pre class="mt-2 overflow-x-auto text-xs text-slate-300">{{ json_encode($order->metadata, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) }}</pre>
                    </div>

                    <div class="rounded-2xl border border-white/10 bg-slate-900/30 p-4">
                        <p class="text-xs uppercase tracking-[0.3em] text-slate-500">Riwayat pembayaran</p>
                        <div class="mt-4 space-y-4 text-sm text-slate-200">
                            @forelse($order->payments as $payment)
                                <div class="rounded-xl border border-white/10 bg-slate-900/40 p-3">
                                    <div class="flex items-center justify-between">
                                        <p class="font-semibold">{{ $payment->provider }}</p>
                                        <span class="text-xs text-slate-400">{{ $payment->paid_at?->translatedFormat('d M Y H:i') }}</span>
                                    </div>
                                    <p class="text-sm">Rp{{ number_format($payment->amount, 0, ',', '.') }} — {{ ucfirst($payment->status) }}</p>
                                    <p class="text-xs text-slate-500">Ref: {{ $payment->reference }}</p>
                                </div>
                            @empty
                                <p class="text-sm text-slate-400">Belum ada payment log.</p>
                            @endforelse
                        </div>
                    </div>
                </section>

                <section class="space-y-6">
                    <div class="rounded-3xl border border-white/10 bg-slate-950/60 p-6">
                        <p class="text-xs uppercase tracking-[0.3em] text-slate-500">Update status</p>
                        <form method="POST" action="{{ route('admin.orders.update', $order) }}" class="mt-4 space-y-4">
                            @csrf
                            @method('PATCH')
                            <div>
                                <label for="status" class="text-xs font-semibold uppercase tracking-[0.3em] text-slate-400">Status order</label>
                                <select id="status" name="status" class="mt-2 w-full rounded-2xl border border-white/10 bg-slate-900/30 px-3 py-2 text-white">
                                    @foreach($statusOptions as $status)
                                        <option value="{{ $status }}" @selected($order->status === $status)>{{ ucfirst($status) }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label for="payment_status" class="text-xs font-semibold uppercase tracking-[0.3em] text-slate-400">Status pembayaran</label>
                                <select id="payment_status" name="payment_status" class="mt-2 w-full rounded-2xl border border-white/10 bg-slate-900/30 px-3 py-2 text-white">
                                    <option value="">--</option>
                                    @foreach($paymentOptions as $payment)
                                        <option value="{{ $payment }}" @selected($order->payment_status === $payment)>{{ ucfirst($payment) }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="flex items-center gap-2 text-xs text-slate-400">
                                <input id="requires_followup" type="checkbox" name="requires_followup" value="1" class="h-4 w-4 rounded border-white/20 bg-slate-900/40" @checked($order->requires_followup)>
                                <label for="requires_followup">Butuh follow-up lanjutan</label>
                            </div>
                            <div>
                                <label for="internal_notes" class="text-xs font-semibold uppercase tracking-[0.3em] text-slate-400">Catatan internal</label>
                                <textarea id="internal_notes" name="internal_notes" rows="5" class="mt-2 w-full rounded-2xl border border-white/10 bg-slate-900/30 px-3 py-3 text-white" placeholder="Masukkan update atau instruksi">{{ old('internal_notes', $order->internal_notes) }}</textarea>
                            </div>
                            <button type="submit" class="w-full rounded-3xl bg-gradient-to-r from-emerald-500 to-sky-500 px-5 py-3 text-sm font-semibold text-white">Simpan perubahan</button>
                        </form>
                    </div>

                    <div class="rounded-3xl border border-white/10 bg-slate-950/60 p-6 text-sm text-slate-300">
                        <p class="text-xs uppercase tracking-[0.3em] text-slate-500">Kontak cepat</p>
                        <p class="mt-3">WA: {{ $order->customer_phone ?? '-' }}</p>
                        <p>Email: {{ $order->customer_email }}</p>
                        <p class="mt-4 text-xs text-slate-500">Gunakan kode order saat follow-up untuk memastikan konteks tetap sinkron.</p>
                    </div>
                </section>
            </div>
        </div>
    </div>
</x-app-layout>
