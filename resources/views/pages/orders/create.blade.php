<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
            <div>
                <p class="text-xs uppercase tracking-[0.45em] text-slate-400">Menu Pesan</p>
                <h1 class="mt-2 text-3xl font-semibold text-white">Mulai pesanan baru</h1>
                <p class="mt-1 text-sm text-slate-400">Lengkapi detail pesanan dan lanjutkan ke pembayaran Midtrans Snap untuk pengalaman checkout yang lebih baik.</p>
            </div>
            <a href="{{ route('orders.check') }}" class="inline-flex items-center gap-2 rounded-full border border-white/20 px-4 py-2 text-sm font-semibold text-white transition hover:border-white/60">
                <span class="size-2 rounded-full bg-emerald-400"></span>
                Cek status pesanan
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-6xl space-y-8 px-6">
            <div class="grid gap-8 lg:grid-cols-3">
                <form method="POST" action="{{ route('orders.store') }}" class="space-y-6 rounded-3xl border border-white/10 bg-slate-950/60 p-6 shadow-2xl shadow-black/40 lg:col-span-2">
                    @csrf
                    <div>
                        <label for="service_id" class="text-sm font-semibold uppercase tracking-[0.3em] text-slate-400">Pilih layanan</label>
                        <select id="service_id" name="service_id" class="mt-2 w-full rounded-2xl border border-white/10 bg-slate-900/60 px-4 py-3 text-white focus:border-indigo-400 focus:outline-none" required>
                            <option value="" disabled {{ old('service_id', optional($selectedService)->id) ? '' : 'selected' }}>Pilih paket Deadlineku</option>
                            @foreach($services as $service)
                                <option value="{{ $service->id }}" @selected(old('service_id', optional($selectedService)->id) == $service->id)>
                                    {{ $service->name }} — Rp{{ number_format($service->effective_price, 0, ',', '.') }}@if($service->has_active_discount) (-{{ number_format($service->discount_percentage, (floor($service->discount_percentage) == $service->discount_percentage) ? 0 : 1) }}%)@endif
                                </option>
                            @endforeach
                        </select>
                        @error('service_id')
                            <p class="mt-2 text-sm text-rose-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid gap-6 md:grid-cols-2">
                        <div>
                            <label for="customer_name" class="text-sm font-semibold uppercase tracking-[0.3em] text-slate-400">Nama lengkap</label>
                            <input id="customer_name" name="customer_name" type="text" value="{{ old('customer_name', auth()->user()->name) }}" class="mt-2 w-full rounded-2xl border border-white/10 bg-slate-900/60 px-4 py-3 text-white placeholder:text-slate-500 focus:border-indigo-400 focus:outline-none" required>
                            @error('customer_name')
                                <p class="mt-2 text-sm text-rose-400">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="customer_email" class="text-sm font-semibold uppercase tracking-[0.3em] text-slate-400">Email aktif</label>
                            <input id="customer_email" name="customer_email" type="email" value="{{ old('customer_email', auth()->user()->email) }}" class="mt-2 w-full rounded-2xl border border-white/10 bg-slate-900/60 px-4 py-3 text-white placeholder:text-slate-500 focus:border-indigo-400 focus:outline-none" required>
                            @error('customer_email')
                                <p class="mt-2 text-sm text-rose-400">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="grid gap-6 md:grid-cols-2">
                        <div>
                            <label for="customer_phone" class="text-sm font-semibold uppercase tracking-[0.3em] text-slate-400">Nomor WhatsApp</label>
                            <input id="customer_phone" name="customer_phone" type="text" value="{{ old('customer_phone') }}" placeholder="08xxxxxxxxxx" class="mt-2 w-full rounded-2xl border border-white/10 bg-slate-900/60 px-4 py-3 text-white placeholder:text-slate-500 focus:border-indigo-400 focus:outline-none" required>
                            @error('customer_phone')
                                <p class="mt-2 text-sm text-rose-400">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="order_details" class="text-sm font-semibold uppercase tracking-[0.3em] text-slate-400">Catatan order</label>
                            <textarea id="order_details" name="order_details" rows="3" placeholder="Tuliskan brief singkat atau deadline khusus" class="mt-2 w-full rounded-2xl border border-white/10 bg-slate-900/60 px-4 py-3 text-white placeholder:text-slate-500 focus:border-indigo-400 focus:outline-none">{{ old('order_details', optional($selectedService)->description) }}</textarea>
                            @error('order_details')
                                <p class="mt-2 text-sm text-rose-400">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    @php
                        $bankTransfer = config('payments.bank_transfer');
                    @endphp

                    <div class="flex flex-col gap-3 rounded-3xl border border-white/10 bg-slate-900/40 p-4 text-sm text-slate-400">
                        <p class="text-white">Pembayaran:</p>
                        <p>Setelah submit, kamu akan diarahkan ke halaman pembayaran Midtrans Snap yang mendukung semua metode pembayaran (transfer bank, QRIS, e-wallet).</p>
                        <p>Butuh bantuan? Hubungi kami di <span class="text-white">{{ $bankTransfer['support_contact'] ?? 'support@deadlineku.id' }}</span>.</p>
                    </div>

                    <button type="submit" class="w-full rounded-3xl bg-gradient-to-r from-indigo-500 to-sky-500 px-6 py-3 text-center text-lg font-semibold text-white transition hover:opacity-90">
                        Kirim pesanan & lanjutkan pembayaran →
                    </button>
                </form>

                <div class="space-y-6">
                    <div class="rounded-3xl border border-white/10 bg-slate-950/60 p-6">
                        <p class="text-xs uppercase tracking-[0.4em] text-slate-400">Kenapa Deadlineku?</p>
                        <h3 class="mt-2 text-2xl font-semibold text-white">Workflow akademik yang luwes.</h3>
                        <p class="mt-3 text-sm text-slate-400">Tim research, desain, dan penulisan kami siap menangani revisi cepat dengan SLA yang jelas.</p>
                        <ul class="mt-4 space-y-3 text-sm text-slate-300">
                            <li class="flex items-center gap-3">
                                <span class="size-2 rounded-full bg-emerald-400"></span>
                                Integrasi dashboard + update status real-time
                            </li>
                            <li class="flex items-center gap-3">
                                <span class="size-2 rounded-full bg-sky-400"></span>
                                Support 24/7 melalui WA & email
                            </li>
                            <li class="flex items-center gap-3">
                                <span class="size-2 rounded-full bg-indigo-400"></span>
                                Catatan progress dan file delivery rapi
                            </li>
                        </ul>
                    </div>

                    <div class="rounded-3xl border border-white/10 bg-gradient-to-br from-indigo-950/80 via-slate-950 to-slate-950 p-6 text-white">
                        <p class="text-xs uppercase tracking-[0.4em] text-slate-400">Butuh konsultasi dulu?</p>
                        <h3 class="mt-3 text-2xl font-semibold">Jadwalkan discovery call 15 menit.</h3>
                        <p class="mt-2 text-sm text-slate-300">Kami bantu breakdown kebutuhan, budgeting, dan timeline pengerjaan.</p>
                        <a href="mailto:hello@deadlineku.id" class="mt-5 inline-flex items-center justify-center rounded-full bg-white/10 px-5 py-3 text-sm font-semibold text-white transition hover:bg-white/20">
                            Hubungi tim Deadlineku
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
