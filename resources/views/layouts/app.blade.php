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
<body class="font-['Space_Grotesk'] bg-slate-50 text-slate-900 antialiased transition-colors duration-300 dark:bg-slate-950 dark:text-slate-100">
    <div class="pointer-events-none fixed inset-0 -z-10 overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-b from-slate-50 via-white to-slate-100 dark:from-slate-950 dark:via-slate-900 dark:to-slate-950"></div>
        <div class="absolute -top-32 -left-24 h-96 w-96 rounded-full bg-indigo-300/30 blur-3xl dark:bg-indigo-600/20"></div>
        <div class="absolute top-24 right-0 h-[26rem] w-[26rem] rounded-full bg-cyan-200/30 blur-[200px] dark:bg-cyan-400/10"></div>
    </div>

    <div class="relative min-h-screen flex flex-col">
        @include('layouts.navigation')

        @isset($header)
            <header class="border-b border-white/10 bg-slate-950/70 backdrop-blur">
                <div class="mx-auto w-full max-w-6xl px-6 py-6">
                    {{ $header }}
                </div>
            </header>
        @endisset

        <main class="flex-1">
            {{ $slot }}
        </main>
    </div>
</body>
</html>
