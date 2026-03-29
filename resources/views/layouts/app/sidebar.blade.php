<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen bg-zinc-50 dark:bg-zinc-950">
        <header class="sticky top-0 z-50 w-full border-b border-zinc-200 dark:border-zinc-800 bg-white/80 dark:bg-zinc-950/80 backdrop-blur-lg">
            <div class="mx-auto flex h-16 max-w-7xl items-center gap-2 px-4 sm:px-6 lg:px-8">
                <x-app-logo href="/" />

                <x-ui::navbar class="-mb-px max-lg:hidden">
                    <x-ui::navbar.item href="/" :current="request()->routeIs('home')" wire:navigate>
                        Home
                    </x-ui::navbar.item>
                    <x-ui::navbar.item href="{{ route('about') }}" :current="request()->routeIs('about')" wire:navigate>
                        About
                    </x-ui::navbar.item>
                    <x-ui::navbar.item href="{{ route('faq') }}" :current="request()->routeIs('faq')" wire:navigate>
                        FAQ
                    </x-ui::navbar.item>
                    <x-ui::navbar.item href="{{ route('contact') }}" :current="request()->routeIs('contact')" wire:navigate>
                        Contact
                    </x-ui::navbar.item>
                </x-ui::navbar>

                <div class="flex-1"></div>

                <x-ui::navbar class="me-1.5 space-x-0.5 rtl:space-x-reverse py-0">
                    <x-ui::navbar.item :href="route('cv.builder')" icon="file-text" :current="request()->routeIs('cv.builder')" wire:navigate>
                        {{ __('My CVs') }}
                    </x-ui::navbar.item>

                    <x-desktop-user-menu />
                </x-ui::navbar>
            </div>
        </header>

        {{ $slot }}
    </body>
</html>
