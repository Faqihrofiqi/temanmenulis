<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Deadlineku') }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-['Space_Grotesk'] bg-slate-950 text-white antialiased">
    <div class="pointer-events-none fixed inset-0 -z-10 overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-b from-slate-950 via-slate-900 to-slate-950"></div>
        <div class="absolute -top-20 -left-10 h-72 w-72 rounded-full bg-indigo-500/30 blur-3xl"></div>
        <div class="absolute bottom-0 right-0 h-[28rem] w-[28rem] rounded-full bg-sky-400/20 blur-[200px]"></div>
    </div>

    <div class="relative flex min-h-screen items-center justify-center px-4 py-12">
        <div class="grid w-full max-w-5xl gap-8 lg:grid-cols-[0.9fr_1.1fr]">
            <div class="rounded-3xl border border-white/10 bg-white/5 p-8 text-slate-100">
                <p class="text-sm uppercase tracking-[0.35em] text-slate-400">Deadlineku Access</p>
                <h1 class="mt-4 text-3xl font-semibold leading-tight">Kembali ke workspace riset kamu dalam hitungan detik.</h1>
                <p class="mt-4 text-sm text-slate-300">Dashboard Deadlineku menggabungkan pemesanan layanan, progres tim, dan notifikasi pembayaran di satu tempat.</p>
                <dl class="mt-8 grid grid-cols-2 gap-4 text-sm text-slate-200">
                    <div class="rounded-2xl border border-white/10 p-4">
                        <dt class="text-xs uppercase tracking-[0.35em] text-slate-400">Jam Support</dt>
                        <dd class="mt-2 text-2xl font-semibold text-white">24/7</dd>
                    </div>
                    <div class="rounded-2xl border border-white/10 p-4">
                        <dt class="text-xs uppercase tracking-[0.35em] text-slate-400">Order Aktif</dt>
                        <dd class="mt-2 text-2xl font-semibold text-white">1.200+</dd>
                    </div>
                </dl>
                <a href="{{ route('home') }}" class="mt-8 inline-flex items-center gap-2 text-sm font-semibold text-sky-300 hover:text-white">
                    Lihat landing page utama
                    <span aria-hidden="true">→</span>
                </a>
            </div>

            <div class="rounded-3xl border border-white/10 bg-white/90 p-8 text-slate-900 shadow-2xl">
                <div class="flex items-center gap-3 text-slate-500">
                    <span class="flex h-10 w-10 items-center justify-center rounded-2xl bg-slate-900 text-white">DK</span>
                    <div>
                        <p class="text-sm font-semibold text-slate-900">Deadlineku</p>
                        <p class="text-xs">Secure access portal</p>
                    </div>
                </div>
                <div class="mt-6">
                    {{ $slot }}
                </div>
            </div>
        </div>
    </div>
</body>
</html>
