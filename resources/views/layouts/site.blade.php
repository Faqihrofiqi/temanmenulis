<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Deadlineku membantu mempercepat pengerjaan skripsi, thesis, dan layanan akademik secara profesional.">

    <title>{{ $title ?? config('app.name', 'Deadlineku') }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;600;700&display=swap" rel="stylesheet">

    <script>
        (function () {
            const storageKey = 'deadlineku-theme';
            const stored = localStorage.getItem(storageKey);
            const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
            const theme = stored === 'light' || stored === 'dark' ? stored : (prefersDark ? 'dark' : 'light');
            document.documentElement.classList.toggle('dark', theme === 'dark');
            document.documentElement.dataset.theme = theme;
        })();
    </script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-slate-50 text-slate-900 antialiased font-['Space_Grotesk'] transition-colors duration-300 dark:bg-slate-950 dark:text-slate-100">
    <div class="relative min-h-screen overflow-hidden">
        <div class="pointer-events-none absolute inset-0 -z-10">
            <div class="absolute inset-0 bg-gradient-to-br from-slate-50 via-white to-slate-100 dark:from-slate-950 dark:via-slate-900 dark:to-slate-950"></div>
            <div class="absolute -top-40 left-0 h-96 w-96 rounded-full bg-indigo-300/40 blur-[120px] dark:bg-indigo-500/30"></div>
            <div class="absolute bottom-0 right-0 h-[28rem] w-[28rem] rounded-full bg-sky-200/30 blur-[140px] dark:bg-sky-400/20"></div>
        </div>

        <header x-data="{ mobileNavOpen: false }" @keydown.window.escape="mobileNavOpen = false" class="sticky top-0 z-40 border-b border-slate-200/80 bg-white/80 backdrop-blur dark:border-white/5 dark:bg-slate-950/70">
            <div class="mx-auto flex max-w-6xl items-center justify-between px-6 py-4">
                <a href="{{ route('home') }}" class="flex items-center gap-3">
                    <span class="flex h-11 w-11 items-center justify-center rounded-2xl bg-slate-900/10 text-lg font-semibold text-slate-900 dark:bg-white/10 dark:text-white">DK</span>
                    <div>
                        <p class="text-base font-semibold text-slate-900 tracking-tight dark:text-white">Deadlineku</p>
                        <p class="text-xs text-slate-500 dark:text-slate-400">Eksperimen produktivitas akademik</p>
                    </div>
                </a>

                <nav class="hidden items-center gap-6 text-sm text-slate-600 md:flex dark:text-slate-300">
                    <a href="{{ route('home') }}#layanan" class="transition hover:text-slate-900 dark:hover:text-white">Layanan</a>
                    <a href="{{ route('home') }}#alur" class="transition hover:text-slate-900 dark:hover:text-white">Alur</a>
                    <a href="{{ route('home') }}#testimoni" class="transition hover:text-slate-900 dark:hover:text-white">Testimoni</a>
                    <a href="{{ route('services.index') }}" class="transition hover:text-slate-900 dark:hover:text-white">Katalog</a>
                </nav>

                <div class="flex items-center gap-3 text-sm">
                    <button type="button" data-theme-toggle class="hidden items-center gap-2 rounded-full border border-slate-300/80 px-4 py-2 font-semibold text-slate-800 transition hover:border-slate-500 md:inline-flex dark:border-white/20 dark:text-white dark:hover:border-white/60">
                        <svg class="h-4 w-4 text-amber-500 dark:hidden" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v2m0 14v2m9-9h-2M5 12H3m15.364 6.364-1.414-1.414M8.05 8.05 6.636 6.636m10.728 0-1.414 1.414M8.05 15.95l-1.414 1.414M16 12a4 4 0 1 1-8 0 4 4 0 0 1 8 0Z" />
                        </svg>
                        <svg class="hidden h-4 w-4 text-indigo-200 dark:block" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79Z" />
                        </svg>
                        <span data-theme-toggle-label>Mode Gelap</span>
                    </button>
                    @auth
                        <a href="{{ route('dashboard') }}" class="hidden rounded-full border border-slate-300/80 px-4 py-2 font-semibold text-slate-900 transition hover:border-slate-500 md:inline-flex dark:border-white/20 dark:text-white dark:hover:border-white/60">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="hidden rounded-full border border-slate-300/80 px-4 py-2 text-slate-900 transition hover:border-slate-500 md:inline-flex dark:border-white/20 dark:text-white dark:hover:border-white/60">Masuk</a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="hidden rounded-full bg-slate-900 px-4 py-2 font-semibold text-white transition hover:bg-slate-800 md:inline-flex dark:bg-white dark:text-slate-900 dark:hover:bg-slate-100">Daftar</a>
                        @endif
                    @endauth
                    <button type="button" @click="mobileNavOpen = !mobileNavOpen" :aria-expanded="mobileNavOpen" class="inline-flex items-center justify-center rounded-2xl border border-slate-300/70 p-2 text-slate-900 transition hover:border-slate-500 hover:text-slate-900 md:hidden dark:border-white/20 dark:text-white dark:hover:border-white/60">
                        <span class="sr-only">Toggle navigation</span>
                        <svg x-show="!mobileNavOpen" class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                        <svg x-show="mobileNavOpen" x-cloak class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>
            <div x-cloak x-show="mobileNavOpen" x-transition.opacity class="md:hidden">
                <div class="border-t border-slate-200/80 bg-white/95 px-6 py-6 text-sm text-slate-700 dark:border-white/10 dark:bg-slate-950/95 dark:text-slate-200">
                    <div class="flex flex-col gap-3">
                        <a href="{{ route('home') }}#layanan" class="rounded-2xl border border-slate-200 px-4 py-2 font-semibold text-slate-900 transition hover:border-slate-900 dark:border-white/10 dark:text-white">Layanan</a>
                        <a href="{{ route('home') }}#alur" class="rounded-2xl border border-slate-200 px-4 py-2 font-semibold text-slate-900 transition hover:border-slate-900 dark:border-white/10 dark:text-white">Alur</a>
                        <a href="{{ route('home') }}#testimoni" class="rounded-2xl border border-slate-200 px-4 py-2 font-semibold text-slate-900 transition hover:border-slate-900 dark:border-white/10 dark:text-white">Testimoni</a>
                        <a href="{{ route('services.index') }}" class="rounded-2xl border border-slate-200 px-4 py-2 font-semibold text-slate-900 transition hover:border-slate-900 dark:border-white/10 dark:text-white">Katalog</a>
                    </div>
                    <div class="mt-4 flex flex-col gap-3">
                        <button type="button" data-theme-toggle class="inline-flex items-center justify-center gap-2 rounded-2xl border border-slate-300/80 px-4 py-2 font-semibold text-slate-800 transition hover:border-slate-500 dark:border-white/20 dark:text-white">
                            <svg class="h-4 w-4 text-amber-500 dark:hidden" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v2m0 14v2m9-9h-2M5 12H3m15.364 6.364-1.414-1.414M8.05 8.05 6.636 6.636m10.728 0-1.414 1.414M8.05 15.95l-1.414 1.414M16 12a4 4 0 1 1-8 0 4 4 0 0 1 8 0Z" />
                            </svg>
                            <svg class="hidden h-4 w-4 text-indigo-200 dark:block" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79Z" />
                            </svg>
                            <span data-theme-toggle-label>Mode Gelap</span>
                        </button>
                        @auth
                            <a href="{{ route('dashboard') }}" class="inline-flex items-center justify-center rounded-2xl bg-slate-900 px-4 py-3 text-base font-semibold text-white transition hover:bg-slate-800 dark:bg-white dark:text-slate-900">Buka Dashboard</a>
                        @else
                            <a href="{{ route('login') }}" class="inline-flex items-center justify-center rounded-2xl border border-slate-300/80 px-4 py-3 font-semibold text-slate-900 transition hover:border-slate-500 dark:border-white/20 dark:text-white">Masuk</a>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="inline-flex items-center justify-center rounded-2xl bg-slate-900 px-4 py-3 text-base font-semibold text-white transition hover:bg-slate-800 dark:bg-white dark:text-slate-900">Daftar</a>
                            @endif
                        @endauth
                    </div>
                </div>
            </div>
        </header>

        <main>
            @yield('content')
        </main>

        <footer class="border-t border-slate-200/80 py-8 text-center text-xs text-slate-500 dark:border-white/5 dark:text-slate-400">
            © {{ now()->year }} {{ config('app.name', 'Deadlineku') }} · Dibangun dengan Laravel 12 + Tailwind
        </footer>
    </div>

    @stack('scripts')
</body>
</html>
