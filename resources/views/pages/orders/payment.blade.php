@php
    $bankTransfer = config('payments.bank_transfer');
    $accounts = collect($bankTransfer['accounts'] ?? [])->filter(function ($account) {
        return filled($account['bank'] ?? null) && filled($account['number'] ?? null);
    })->values();
    $qrisConfig = config('qris');
@endphp

<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-2 text-white">
            <p class="text-xs uppercase tracking-[0.4em] text-slate-400">Pembayaran Pesanan</p>
            <div class="flex flex-col gap-2 lg:flex-row lg:items-center lg:justify-between">
                <div>
                    <h1 class="text-3xl font-semibold">Order {{ $order->order_number }}</h1>
                    <p class="text-sm text-slate-400">Layanan {{ $order->service->name ?? 'Tanpa layanan' }} • Rp{{ number_format($order->amount, 0, ',', '.') }}</p>
                </div>
                <div class="flex flex-wrap gap-2 text-xs">
                    <span class="rounded-full border border-white/20 px-3 py-1">Status Order: {{ ucfirst($order->status) }}</span>
                    <span class="rounded-full border border-white/20 px-3 py-1">Pembayaran: {{ ucfirst($order->payment_status ?? 'pending') }}</span>
                    <span class="rounded-full border border-emerald-400/50 px-3 py-1 text-emerald-300">Metode: {{ $selectedChannel === 'bank_transfer' ? 'Transfer Semua Bank' : 'QRIS' }}</span>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-6xl space-y-8 px-6">
            @if (session('success'))
                <div class="rounded-2xl border border-emerald-500/60 bg-emerald-500/10 px-4 py-3 text-sm text-emerald-200">
                    {{ session('success') }}
                </div>
            @endif

            <div class="grid gap-8 lg:grid-cols-[1.4fr_0.8fr]">
                <div class="space-y-6">
                    <section class="rounded-3xl border {{ $selectedChannel === 'bank_transfer' ? 'border-indigo-400/80 bg-slate-950/70' : 'border-white/10 bg-slate-950/40' }} p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-xs uppercase tracking-[0.4em] text-slate-400">Transfer Semua Bank</p>
                                <h2 class="mt-1 text-2xl font-semibold text-white">Virtual account Midtrans</h2>
                                <p class="mt-2 text-sm text-slate-400">Gunakan mobile banking, ATM, atau teller. Sistem kami menerima konfirmasi otomatis maupun manual.</p>
                            </div>
                            <span class="rounded-full border border-white/20 px-3 py-1 text-xs text-slate-300">Midtrans</span>
                        </div>

                        <div class="mt-4 space-y-4 text-sm text-slate-200">
                            @forelse($accounts as $account)
                                <div class="flex flex-wrap items-center justify-between rounded-2xl border border-white/10 bg-slate-900/50 px-4 py-3">
                                    <div>
                                        <p class="text-xs uppercase tracking-[0.3em] text-slate-400">{{ $account['bank'] }}</p>
                                        <p class="text-lg font-semibold text-white">{{ $account['number'] }}</p>
                                        <p class="text-xs text-slate-400">a.n {{ $account['name'] }}</p>
                                    </div>
                                    <button type="button" class="rounded-full border border-white/20 px-4 py-2 text-xs font-semibold text-white transition hover:border-white/60" onclick="copyToClipboard('{{ $account['number'] }}')">Salin</button>
                                </div>
                            @empty
                                <p class="rounded-2xl border border-dashed border-white/20 px-4 py-4 text-center text-slate-400">Lengkapi data rekening pada konfigurasi pembayaran.</p>
                            @endforelse
                        </div>

                        <div class="mt-4 grid gap-3 text-sm text-slate-300 md:grid-cols-3">
                            @foreach($bankTransfer['instructions'] ?? [] as $instruction)
                                <div class="rounded-2xl border border-white/10 bg-slate-900/40 px-4 py-3">
                                    {{ $instruction }}
                                </div>
                            @endforeach
                        </div>
                    </section>

                    <section class="rounded-3xl border {{ $selectedChannel === 'qris' ? 'border-indigo-400/80 bg-slate-950/70' : 'border-white/10 bg-slate-950/40' }} p-6">
                        <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
                            <div>
                                <p class="text-xs uppercase tracking-[0.4em] text-slate-400">QRIS API</p>
                                <h2 class="mt-1 text-2xl font-semibold text-white">Scan QR & selesai</h2>
                                <p class="mt-2 text-sm text-slate-400">Cocok untuk pembayaran instan melalui OVO, GoPay, DANA, ShopeePay, dan mobile banking.</p>
                            </div>
                            <div class="text-right text-xs text-slate-400">
                                <p>Nomor: {{ $qrisConfig['number'] }}</p>
                                <p>Nama: {{ $qrisConfig['name'] }}</p>
                                <p>Bank: {{ $qrisConfig['bank'] }}</p>
                            </div>
                        </div>

                        <div class="mt-6 rounded-3xl border border-white/10 bg-white/90 p-6 text-center">
                            <div id="qris-qr-code" data-qr-string="{{ $paymentData['qr_string'] }}" class="mx-auto flex h-64 w-64 items-center justify-center rounded-2xl bg-white"></div>
                            <p class="mt-4 text-sm text-slate-600">Nominal otomatis: Rp{{ number_format($order->amount, 0, ',', '.') }}</p>
                            <button type="button" onclick="refreshStatus()" class="mt-4 inline-flex items-center gap-2 rounded-full bg-slate-900/90 px-4 py-2 text-xs font-semibold text-white transition hover:bg-slate-900">Cek status terbaru</button>
                        </div>

                        <div class="mt-4 text-sm text-slate-300">
                            @if ($qrisConfig['manual_confirmation'] ?? true)
                                Setelah scan, unggah bukti ke dashboard atau WA agar tim memverifikasi & mengubah status pembayaran.
                            @else
                                Status akan diperbarui otomatis setelah pembayaran berhasil.
                            @endif
                        </div>
                    </section>
                </div>

                <div class="space-y-6">
                    <div class="rounded-3xl border border-white/10 bg-slate-950/60 p-6 text-sm text-slate-300">
                        <p class="text-xs uppercase tracking-[0.4em] text-slate-500">Ringkasan Pesanan</p>
                        <h3 class="mt-2 text-2xl font-semibold text-white">{{ $order->service->name ?? 'Tanpa layanan' }}</h3>
                        <div class="mt-4 space-y-3">
                            <div class="flex items-center justify-between">
                                <span>ID Order</span>
                                <span class="font-semibold text-white">{{ $order->order_number }}</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span>Tanggal</span>
                                <span>{{ $order->created_at?->translatedFormat('d M Y H:i') }}</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span>Nominal</span>
                                <span class="text-xl font-semibold text-white">Rp{{ number_format($order->amount, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span>Status pembayaran</span>
                                <span class="rounded-full border border-white/20 px-3 py-1 text-xs">{{ ucfirst($order->payment_status ?? 'pending') }}</span>
                            </div>
                        </div>
                        <hr class="my-4 border-white/10">
                        <p class="text-xs text-slate-500">Catatan:</p>
                        <p class="mt-1 text-slate-300">{{ $order->order_details ?: 'Belum ada catatan tambahan.' }}</p>
                    </div>

                    <div class="rounded-3xl border border-white/10 bg-gradient-to-br from-emerald-500/10 via-slate-950 to-slate-950 p-6 text-sm text-slate-200">
                        <p class="text-xs uppercase tracking-[0.4em] text-emerald-200">After payment</p>
                        <ul class="mt-3 space-y-2">
                            <li>1. Sistem kami mengecek pembayaran setiap 30 detik.</li>
                            <li>2. Kamu bisa cek manual lewat tombol di atas atau halaman cek pesanan.</li>
                            <li>3. Begitu lunas, order otomatis bergeser ke tahap produksi.</li>
                        </ul>
                        <div class="mt-4 flex flex-col gap-3 text-sm">
                            <a href="{{ route('orders.check') }}" class="inline-flex items-center justify-center rounded-full border border-white/20 px-4 py-2 font-semibold text-white transition hover:border-white/60">Cek status lain</a>
                            <a href="mailto:{{ $bankTransfer['support_contact'] ?? 'support@deadlineku.id' }}" class="inline-flex items-center justify-center rounded-full bg-white/10 px-4 py-2 font-semibold text-white transition hover:bg-white/20">Butuh bantuan admin?</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/qrcode@1.5.3/build/qrcode.min.js"></script>
    <script>
        const qrElement = document.getElementById('qris-qr-code');
        const qrString = qrElement?.dataset?.qrString;

        if (qrElement && qrString && window.QRCode) {
            QRCode.toCanvas(qrElement, qrString, {
                width: 240,
                margin: 2,
                color: {
                    dark: '#0f172a',
                    light: '#ffffff'
                }
            });
        }

        function copyToClipboard(value) {
            navigator.clipboard.writeText(value).then(() => {
                alert('Nomor rekening tersalin.');
            }).catch(() => {
                alert('Gagal menyalin nomor, silakan copy manual.');
            });
        }

        function refreshStatus() {
            window.location.reload();
        }
    </script>
</x-app-layout>
