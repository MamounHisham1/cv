<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen bg-zinc-50 dark:bg-zinc-950">
        <flux:header container sticky class="border-b border-zinc-200 dark:border-zinc-800 bg-white/80 dark:bg-zinc-950/80 backdrop-blur-lg">
            <x-app-logo href="/" />

            <flux:navbar class="-mb-px max-lg:hidden">
                <flux:navbar.item href="/" :current="request()->routeIs('home')" wire:navigate>
                    Home
                </flux:navbar.item>
                <flux:navbar.item href="{{ route('about') }}" :current="request()->routeIs('about')" wire:navigate>
                    About
                </flux:navbar.item>
                <flux:navbar.item href="{{ route('faq') }}" :current="request()->routeIs('faq')" wire:navigate>
                    FAQ
                </flux:navbar.item>
                <flux:navbar.item href="{{ route('contact') }}" :current="request()->routeIs('contact')" wire:navigate>
                    Contact
                </flux:navbar.item>
            </flux:navbar>

            <flux:spacer />

            <flux:navbar class="me-1.5 space-x-0.5 rtl:space-x-reverse py-0!">
                <flux:navbar.item :href="route('cv.builder')" icon="document-text" :current="request()->routeIs('cv.builder')" wire:navigate>
                    {{ __('My CVs') }}
                </flux:navbar.item>

                <x-desktop-user-menu />
            </flux:navbar>
        </flux:header>

        {{ $slot }}

        @fluxScripts
    </body>
</html>
