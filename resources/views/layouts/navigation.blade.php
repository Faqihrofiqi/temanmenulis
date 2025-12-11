@php
    $user = Auth::user();
@endphp

<nav x-data="{ open: false }" @keydown.window.escape="open = false" class="relative border-b border-slate-200/80 bg-white/80 backdrop-blur dark:border-white/10 dark:bg-slate-950/70">
    <div class="mx-auto flex w-full max-w-6xl items-center justify-between px-6 py-4">
        <div class="flex items-center gap-6">
            <a href="{{ route('home') }}" class="flex items-center gap-3 text-slate-900 dark:text-white">
                <span class="flex h-11 w-11 items-center justify-center rounded-2xl bg-slate-900/10 text-lg font-semibold tracking-tight text-slate-900 dark:bg-white/10 dark:text-white">DK</span>
                <div>
                    <p class="text-base font-semibold">Deadlineku</p>
                    <p class="text-xs text-slate-500 dark:text-slate-400">Academic workflow OS</p>
                </div>
            </a>

            <div class="hidden items-center gap-5 text-sm text-slate-500 md:flex dark:text-slate-300">
                <a href="{{ route('dashboard') }}" class="transition {{ request()->routeIs('dashboard') ? 'text-slate-900 dark:text-white' : 'hover:text-slate-900 dark:hover:text-white' }}">Dashboard</a>
                <a href="{{ route('services.index') }}" class="transition {{ request()->routeIs('services.*') ? 'text-slate-900 dark:text-white' : 'hover:text-slate-900 dark:hover:text-white' }}">Layanan</a>
                <a href="{{ route('orders.create') }}" class="transition {{ request()->routeIs('orders.*') ? 'text-slate-900 dark:text-white' : 'hover:text-slate-900 dark:hover:text-white' }}">Pesan</a>
                <a href="{{ route('tickets.index') }}" class="transition {{ request()->routeIs('tickets.*') ? 'text-slate-900 dark:text-white' : 'hover:text-slate-900 dark:hover:text-white' }}">Tiket</a>
                <a href="{{ route('profile.edit') }}" class="transition {{ request()->routeIs('profile.*') ? 'text-slate-900 dark:text-white' : 'hover:text-slate-900 dark:hover:text-white' }}">Profil</a>
            </div>
        </div>

        <div class="hidden items-center gap-4 md:flex">
            <div class="text-right">
                <p class="text-sm font-semibold text-slate-900 dark:text-white">{{ $user->name }}</p>
                <p class="text-xs text-slate-500 dark:text-slate-400">{{ $user->email }}</p>
            </div>
            <button type="button" data-theme-toggle class="inline-flex items-center gap-2 rounded-full border border-slate-300/80 px-3 py-2 text-xs font-semibold text-slate-700 transition hover:border-slate-500 hover:text-slate-900 dark:border-white/20 dark:text-white dark:hover:border-white/60">
                <svg class="h-4 w-4 text-amber-500 dark:hidden" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v2m0 14v2m9-9h-2M5 12H3m15.364 6.364-1.414-1.414M8.05 8.05 6.636 6.636m10.728 0-1.414 1.414M8.05 15.95l-1.414 1.414M16 12a4 4 0 1 1-8 0 4 4 0 0 1 8 0Z" />
                </svg>
                <svg class="hidden h-4 w-4 text-indigo-200 dark:block" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79Z" />
                </svg>
                <span data-theme-toggle-label>Mode Gelap</span>
            </button>
            <form method="POST" action="{{ route('logout') }}" class="inline-flex">
                @csrf
                <button class="inline-flex items-center rounded-full border border-slate-300/80 px-4 py-2 text-sm font-semibold text-slate-900 transition hover:border-slate-500 dark:border-white/20 dark:text-white dark:hover:border-white/50">
                    Keluar
                </button>
            </form>
        </div>

        <button @click="open = !open" :aria-expanded="open" class="inline-flex items-center justify-center rounded-2xl border border-slate-300/80 p-2 text-slate-900 transition hover:border-slate-500 md:hidden dark:border-white/30 dark:text-white">
            <span class="sr-only">Toggle menu</span>
            <svg x-show="!open" class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
            <svg x-show="open" x-cloak class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </div>

    <div x-cloak x-show="open" x-transition.opacity class="fixed inset-0 z-30 bg-slate-900/40 backdrop-blur-sm md:hidden" @click="open = false"></div>

    <div x-cloak x-show="open" x-transition class="fixed inset-x-0 top-0 z-40 mt-[72px] border-t border-slate-200/80 bg-white/95 px-6 py-6 text-slate-600 shadow-2xl dark:border-white/10 dark:bg-slate-950/95 md:hidden">
        <div class="flex flex-col gap-3 text-sm">
            <a href="{{ route('dashboard') }}" class="rounded-xl px-4 py-2 {{ request()->routeIs('dashboard') ? 'bg-slate-100 text-slate-900 dark:bg-white/10 dark:text-white' : 'hover:text-slate-900 dark:hover:text-white' }}">Dashboard</a>
            <a href="{{ route('services.index') }}" class="rounded-xl px-4 py-2 {{ request()->routeIs('services.*') ? 'bg-slate-100 text-slate-900 dark:bg-white/10 dark:text-white' : 'hover:text-slate-900 dark:hover:text-white' }}">Layanan</a>
            <a href="{{ route('orders.create') }}" class="rounded-xl px-4 py-2 {{ request()->routeIs('orders.*') ? 'bg-slate-100 text-slate-900 dark:bg-white/10 dark:text-white' : 'hover:text-slate-900 dark:hover:text-white' }}">Pesan</a>
            <a href="{{ route('tickets.index') }}" class="rounded-xl px-4 py-2 {{ request()->routeIs('tickets.*') ? 'bg-slate-100 text-slate-900 dark:bg-white/10 dark:text-white' : 'hover:text-slate-900 dark:hover:text-white' }}">Tiket</a>
            <a href="{{ route('profile.edit') }}" class="rounded-xl px-4 py-2 {{ request()->routeIs('profile.*') ? 'bg-slate-100 text-slate-900 dark:bg-white/10 dark:text-white' : 'hover:text-slate-900 dark:hover:text-white' }}">Profil</a>
            @if ($user->isAdmin())
                <a href="{{ route('admin.services.index') }}" class="rounded-xl px-4 py-2 {{ request()->routeIs('admin.services.*') ? 'bg-indigo-50 text-indigo-900 dark:bg-indigo-500/10 dark:text-indigo-100' : 'hover:text-slate-900 dark:hover:text-white' }}">Kelola Layanan</a>
            @endif
        </div>
        <div class="mt-4 rounded-2xl border border-slate-200/80 p-4 text-sm text-slate-600 dark:border-white/10 dark:text-slate-300">
            <p class="font-semibold text-slate-900 dark:text-white">{{ $user->name }}</p>
            <p class="text-xs text-slate-500 dark:text-slate-400">{{ $user->email }}</p>
            <button type="button" data-theme-toggle class="mt-3 inline-flex w-full items-center justify-center gap-2 rounded-xl border border-slate-300/80 px-4 py-2 text-xs font-semibold text-slate-800 transition hover:border-slate-500 dark:border-white/20 dark:text-white dark:hover:border-white/60">
                <svg class="h-4 w-4 text-amber-500 dark:hidden" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v2m0 14v2m9-9h-2M5 12H3m15.364 6.364-1.414-1.414M8.05 8.05 6.636 6.636m10.728 0-1.414 1.414M8.05 15.95l-1.414 1.414M16 12a4 4 0 1 1-8 0 4 4 0 0 1 8 0Z" />
                </svg>
                <svg class="hidden h-4 w-4 text-indigo-200 dark:block" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79Z" />
                </svg>
                <span data-theme-toggle-label>Mode Gelap</span>
            </button>
            <form method="POST" action="{{ route('logout') }}" class="mt-4">
                @csrf
                <button class="inline-flex w-full items-center justify-center rounded-xl border border-slate-300/80 px-4 py-2 font-semibold text-slate-900 transition hover:border-slate-500 dark:border-white/20 dark:bg-white/10 dark:text-white dark:hover:bg-white/20">Keluar</button>
            </form>
        </div>
    </div>
</nav>
