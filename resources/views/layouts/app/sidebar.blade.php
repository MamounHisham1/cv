<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen bg-zinc-950 text-zinc-100">
        <header class="sticky top-0 z-50 w-full border-b border-white/10 bg-zinc-950/80 backdrop-blur-xl">
            <div class="mx-auto flex min-h-16 max-w-7xl items-center gap-3 px-4 py-3 sm:px-6 lg:px-8">
                <x-app-logo href="/" />

                <x-ui::navbar class="hidden items-center gap-1 rounded-full border border-white/10 bg-white/5 p-1 backdrop-blur-xl lg:flex">
                    <x-ui::navbar.item href="/" :current="request()->routeIs('home')" wire:navigate class="!rounded-full !px-4 !py-2 {{ request()->routeIs('home') ? '!bg-white/10 !text-white shadow-lg shadow-emerald-500/10' : '!text-zinc-400 hover:!bg-white/10 hover:!text-white' }}">
                        Home
                    </x-ui::navbar.item>
                    <x-ui::navbar.item href="{{ route('home') }}#about" class="!rounded-full !px-4 !py-2 !text-zinc-400 hover:!bg-white/10 hover:!text-white">
                        About
                    </x-ui::navbar.item>
                    <x-ui::navbar.item href="{{ route('home') }}#faq" class="!rounded-full !px-4 !py-2 !text-zinc-400 hover:!bg-white/10 hover:!text-white">
                        FAQ
                    </x-ui::navbar.item>
                    <x-ui::navbar.item href="{{ route('home') }}#contact" class="!rounded-full !px-4 !py-2 !text-zinc-400 hover:!bg-white/10 hover:!text-white">
                        Contact
                    </x-ui::navbar.item>
                </x-ui::navbar>

                <div class="flex-1"></div>

                <x-ui::navbar class="me-1.5 items-center gap-1 rounded-full border border-white/10 bg-white/5 p-1 backdrop-blur-xl rtl:space-x-reverse">
                    <x-ui::navbar.item :href="route('cv.builder')" icon="file-text" :current="request()->routeIs('cv.builder', 'cv.edit')" wire:navigate class="!rounded-full !px-4 !py-2 {{ request()->routeIs('cv.builder', 'cv.edit') ? '!bg-white/10 !text-white shadow-lg shadow-emerald-500/10' : '!text-zinc-300 hover:!bg-white/10 hover:!text-white' }}">
                        {{ __('My CVs') }}
                    </x-ui::navbar.item>

                    <x-desktop-user-menu />
                </x-ui::navbar>
            </div>
        </header>

        {{ $slot }}
    </body>
</html>
