@php
    $bankTransfer = config('payments.bank_transfer');
    $accounts = collect($bankTransfer['accounts'] ?? [])->filter(function ($account) {
        return filled($account['bank'] ?? null) && filled($account['number'] ?? null);
    })->values();
    $snapJsUrl = config('midtrans.snap_js_url');
    $midtransClientKey = config('midtrans.client_key');
    $enabledPayments = collect(data_get($snapData ?? [], 'enabled_payments', []))->filter()->values();
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
                    <span class="rounded-full border border-emerald-400/50 px-3 py-1 text-emerald-300">Metode: Midtrans Snap</span>
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
                    <section class="rounded-3xl border border-indigo-400/80 bg-slate-950/70 p-6">
                        <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
                            <div>
                                <p class="text-xs uppercase tracking-[0.4em] text-slate-400">Midtrans Snap</p>
                                <h2 class="mt-1 text-2xl font-semibold text-white">Bayar instan tanpa input manual</h2>
                                <p class="mt-2 text-sm text-slate-400">Snap menangani semua metode pembayaran: transfer bank, QRIS, dan e-wallet. Setelah berhasil, status order otomatis diperbarui.</p>
                            </div>
                            <div class="text-right text-xs text-slate-400">
                                <p>Status sistem: {{ ucfirst($order->payment_status ?? 'pending') }}</p>
                                @if($snapExpiry)
                                    <p>Kedaluwarsa token: {{ $snapExpiry->timezone('Asia/Jakarta')->translatedFormat('d M Y H:i') }} WIB</p>
                                @endif
                            </div>
                        </div>

                        @if ($snapToken && $midtransClientKey)
                            <div class="mt-6 rounded-3xl border border-white/10 bg-white/95 p-6 text-center text-slate-900">
                                <p class="text-lg font-semibold">Lanjutkan pembayaran via Midtrans</p>
                                <p class="mt-2 text-sm text-slate-600">Pastikan popup tidak diblokir oleh browser.</p>
                                <div class="mt-4 flex flex-wrap items-center justify-center gap-3">
                                    <button type="button" data-action="snap-pay" class="inline-flex items-center gap-2 rounded-full bg-slate-900 px-6 py-3 text-sm font-semibold text-white transition hover:bg-slate-800">
                                        Buka Snap & bayar
                                    </button>
                                    @if($snapRedirectUrl)
                                        <a href="{{ $snapRedirectUrl }}" target="_blank" rel="noreferrer" class="inline-flex items-center gap-2 rounded-full border border-slate-900/20 px-5 py-3 text-sm font-semibold text-slate-900 transition hover:border-slate-900">
                                            Alternatif: buka tab baru
                                        </a>
                                    @endif
                                    <button type="button" onclick="refreshStatus()" class="inline-flex items-center gap-2 rounded-full border border-slate-900/10 px-5 py-3 text-xs font-semibold text-slate-700 transition hover:border-slate-900">
                                        Perbarui status
                                    </button>
                                </div>
                            </div>
                        @else
                            <div class="mt-6 rounded-3xl border border-dashed border-amber-400/30 bg-amber-500/10 p-6 text-center text-sm">
                                <div class="flex flex-col items-center gap-3">
                                    <span class="text-2xl">🔑</span>
                                    <p class="text-amber-800 dark:text-amber-200 font-semibold">Konfigurasi Midtrans Diperlukan</p>
                                    <div class="text-amber-700 dark:text-amber-300 space-y-2">
                                        <p>Kunci Midtrans belum dikonfigurasi dengan benar.</p>
                                        <div class="text-xs space-y-1">
                                            <p><strong>Untuk mendapatkan kunci valid:</strong></p>
                                            <p>1. Buka <a href="https://dashboard.midtrans.com/" target="_blank" class="underline hover:text-amber-900">dashboard.midtrans.com</a></p>
                                            <p>2. Login dengan akun Anda</p>
                                            <p>3. Pergi ke: <strong>Settings → Access Keys</strong></p>
                                            <p>4. Copy Server Key dan Client Key</p>
                                            <p>5. Update file <code class="bg-amber-100 px-1 rounded">.env</code> di project</p>
                                        </div>
                                    </div>
                                    <div class="flex gap-2 mt-2">
                                        <button type="button" onclick="window.location.reload()" class="inline-flex items-center gap-2 rounded-full bg-amber-600 px-4 py-2 text-xs font-semibold text-white transition hover:bg-amber-700">
                                            Coba lagi
                                        </button>
                                        <a href="https://dashboard.midtrans.com/" target="_blank" class="inline-flex items-center gap-2 rounded-full border border-amber-600 px-4 py-2 text-xs font-semibold text-amber-700 transition hover:bg-amber-800">
                                            Buka Dashboard Midtrans
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <div class="mt-6 grid gap-4 text-xs text-slate-300 sm:grid-cols-2">
                            <div class="rounded-2xl border border-white/10 bg-slate-900/50 p-4">
                                <p class="text-[0.65rem] uppercase tracking-[0.3em] text-slate-500">Token</p>
                                <p class="mt-1 text-base font-semibold text-white">{{ $snapToken ? substr($snapToken, 0, 8).'•••' : '-' }}</p>
                            </div>
                            <div class="rounded-2xl border border-white/10 bg-slate-900/50 p-4">
                                <p class="text-[0.65rem] uppercase tracking-[0.3em] text-slate-500">Merchant</p>
                                <p class="mt-1 text-base font-semibold text-white">{{ config('midtrans.merchant_id') ?: 'Midtrans' }}</p>
                            </div>
                            <div class="rounded-2xl border border-white/10 bg-slate-900/50 p-4">
                                <p class="text-[0.65rem] uppercase tracking-[0.3em] text-slate-500">Order ID</p>
                                <p class="mt-1 text-base font-semibold text-white">{{ $order->order_number }}</p>
                            </div>
                            <div class="rounded-2xl border border-white/10 bg-slate-900/50 p-4">
                                <p class="text-[0.65rem] uppercase tracking-[0.3em] text-slate-500">Metode aktif</p>
                                <p class="mt-1 text-base font-semibold text-white">{{ $enabledPayments->isNotEmpty() ? $enabledPayments->map(fn ($method) => strtoupper(str_replace('_', ' ', $method)))->implode(', ') : 'Semua metode Snap' }}</p>
                            </div>
                        </div>

                        <div class="mt-4 text-sm text-slate-300">
                            Snap akan menandai pembayaran otomatis. Kamu juga bisa mengunggah bukti di dashboard jika butuh intervensi manual.
                        </div>
                    </section>

                    <section class="rounded-3xl border border-white/10 bg-slate-950/40 p-6">
                        <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
                            <div>
                                <p class="text-xs uppercase tracking-[0.4em] text-slate-500">Referensi transfer</p>
                                <h3 class="mt-1 text-2xl font-semibold text-white">Perlu bayar manual?</h3>
                                <p class="mt-2 text-sm text-slate-400">Gunakan data di bawah ini bila tim meminta bukti transfer manual.</p>
                            </div>
                            <span class="rounded-full border border-white/15 px-3 py-1 text-xs text-slate-300">Optional</span>
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

    @if ($snapToken && $midtransClientKey && $snapJsUrl)
        <script src="{{ $snapJsUrl }}" data-client-key="{{ $midtransClientKey }}"></script>
    @endif
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const payButton = document.querySelector('[data-action="snap-pay"]');
            if (!payButton) {
                return;
            }

            const snapToken = @json($snapToken);
            const fallbackUrl = @json($snapRedirectUrl);

            const fallback = () => {
                if (fallbackUrl) {
                    window.location.href = fallbackUrl;
                } else {
                    window.location.reload();
                }
            };

            const handleResult = () => window.location.reload();

            payButton.addEventListener('click', () => {
                payButton.disabled = true;
                payButton.classList.add('opacity-60');

                if (!snapToken || !window.snap) {
                    fallback();
                    return;
                }

                window.snap.pay(snapToken, {
                    onSuccess: handleResult,
                    onPending: handleResult,
                    onError: () => {
                        alert('Terjadi kendala saat membuka Snap. Kami arahkan ke halaman pembayaran.');
                        fallback();
                    },
                    onClose: () => {
                        payButton.disabled = false;
                        payButton.classList.remove('opacity-60');
                    },
                });
            });
        });

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
