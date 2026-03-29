<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen bg-zinc-950 text-zinc-100 antialiased">
        <div class="relative flex min-h-svh flex-col items-center justify-center overflow-hidden px-6 py-10 md:px-10">
            <div class="pointer-events-none absolute inset-0">
                <div class="absolute left-1/2 top-0 h-80 w-80 -translate-x-1/2 rounded-full bg-emerald-500/15 blur-3xl"></div>
                <div class="absolute bottom-10 left-10 h-56 w-56 rounded-full bg-cyan-500/10 blur-3xl"></div>
                <div class="absolute right-0 top-1/3 h-72 w-72 rounded-full bg-emerald-400/10 blur-3xl"></div>
            </div>

            <div class="relative z-10 flex w-full max-w-md flex-col gap-4">
                <a href="{{ route('home') }}" class="mx-auto inline-flex items-center gap-3 rounded-full border border-white/10 bg-white/5 px-4 py-2 text-sm font-medium text-zinc-200 backdrop-blur-xl transition hover:bg-white/10 hover:text-white" wire:navigate>
                    <span class="flex h-9 w-9 items-center justify-center rounded-full border border-white/10 bg-zinc-950/80 shadow-lg shadow-black/30">
                        <x-app-logo-icon class="size-5 fill-current text-white" />
                    </span>
                    <span>{{ config('app.name', 'Laravel') }}</span>
                </a>

                <div class="rounded-3xl border border-white/10 bg-zinc-950/80 px-6 py-7 shadow-2xl shadow-black/40 backdrop-blur-xl sm:px-8 sm:py-8">
                    <div class="flex flex-col gap-6">
                        {{ $slot }}
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
