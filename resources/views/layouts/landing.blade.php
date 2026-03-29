<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="antialiased scroll-smooth">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen bg-white dark:bg-zinc-950 text-zinc-900 dark:text-zinc-100">
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
                    @guest
                        <x-ui::navbar.item :href="route('login')" icon="log-in">
                            {{ __('Login') }}
                        </x-ui::navbar.item>
                        <x-ui::button variant="primary" size="sm" :href="route('register')" class="max-lg:hidden">
                            {{ __('Register') }}
                        </x-ui::button>
                    @endguest
                    @auth
                        <x-ui::navbar.item :href="route('cv.builder')" icon="file-text" wire:navigate>
                            {{ __('My CVs') }}
                        </x-ui::navbar.item>
                    @endauth
                </x-ui::navbar>
            </div>
        </header>

        {{ $slot }}

        <footer class="bg-zinc-50 dark:bg-zinc-900 border-t border-zinc-200 dark:border-zinc-800">
            <div class="mx-auto max-w-7xl px-6 lg:px-8 py-12 lg:py-16">
                <div class="grid grid-cols-2 md:grid-cols-4 gap-8 lg:gap-12">
                    <div class="col-span-2 md:col-span-1">
                        <x-app-logo href="/" />
                        <p class="mt-4 text-sm text-zinc-600 dark:text-zinc-400 leading-relaxed">
                            Build professional, ATS-optimized CVs with the power of AI. Land your dream job faster.
                        </p>
                        <div class="flex items-center gap-3 mt-6">
                            <a href="#" class="text-zinc-500 dark:text-zinc-400 hover:text-emerald-600 dark:hover:text-emerald-400 transition-colors">
                                <x-ui::icon name="globe" size="lg" />
                            </a>
                            <a href="#" class="text-zinc-500 dark:text-zinc-400 hover:text-emerald-600 dark:hover:text-emerald-400 transition-colors">
                                <x-ui::icon name="code-2" size="lg" />
                            </a>
                            <a href="#" class="text-zinc-500 dark:text-zinc-400 hover:text-emerald-600 dark:hover:text-emerald-400 transition-colors">
                                <x-ui::icon name="mail" size="lg" />
                            </a>
                        </div>
                    </div>

                    <div>
                        <h3 class="text-sm font-semibold text-zinc-900 dark:text-white mb-4">Product</h3>
                        <ul class="space-y-3">
                            <li><a href="#features" class="text-sm text-zinc-600 dark:text-zinc-400 hover:text-emerald-600 dark:hover:text-emerald-400 transition-colors">Features</a></li>
                            <li><a href="#pricing" class="text-sm text-zinc-600 dark:text-zinc-400 hover:text-emerald-600 dark:hover:text-emerald-400 transition-colors">Pricing</a></li>
                            <li><a href="{{ route('about') }}" class="text-sm text-zinc-600 dark:text-zinc-400 hover:text-emerald-600 dark:hover:text-emerald-400 transition-colors">About</a></li>
                            <li><a href="{{ route('faq') }}" class="text-sm text-zinc-600 dark:text-zinc-400 hover:text-emerald-600 dark:hover:text-emerald-400 transition-colors">FAQ</a></li>
                        </ul>
                    </div>

                    <div>
                        <h3 class="text-sm font-semibold text-zinc-900 dark:text-white mb-4">Resources</h3>
                        <ul class="space-y-3">
                            <li><a href="#" class="text-sm text-zinc-600 dark:text-zinc-400 hover:text-emerald-600 dark:hover:text-emerald-400 transition-colors">Blog</a></li>
                            <li><a href="#" class="text-sm text-zinc-600 dark:text-zinc-400 hover:text-emerald-600 dark:hover:text-emerald-400 transition-colors">CV Tips</a></li>
                            <li><a href="#" class="text-sm text-zinc-600 dark:text-zinc-400 hover:text-emerald-600 dark:hover:text-emerald-400 transition-colors">Templates</a></li>
                            <li><a href="{{ route('contact') }}" class="text-sm text-zinc-600 dark:text-zinc-400 hover:text-emerald-600 dark:hover:text-emerald-400 transition-colors">Support</a></li>
                        </ul>
                    </div>

                    <div>
                        <h3 class="text-sm font-semibold text-zinc-900 dark:text-white mb-4">Legal</h3>
                        <ul class="space-y-3">
                            <li><a href="#" class="text-sm text-zinc-600 dark:text-zinc-400 hover:text-emerald-600 dark:hover:text-emerald-400 transition-colors">Privacy Policy</a></li>
                            <li><a href="#" class="text-sm text-zinc-600 dark:text-zinc-400 hover:text-emerald-600 dark:hover:text-emerald-400 transition-colors">Terms of Service</a></li>
                            <li><a href="#" class="text-sm text-zinc-600 dark:text-zinc-400 hover:text-emerald-600 dark:hover:text-emerald-400 transition-colors">Cookie Policy</a></li>
                        </ul>
                    </div>
                </div>

                <div class="mt-12 pt-8 border-t border-zinc-200 dark:border-zinc-800 flex flex-col sm:flex-row items-center justify-between gap-4">
                    <p class="text-sm text-zinc-500 dark:text-zinc-400">
                        &copy; {{ date('Y') }} SeratyAI. All rights reserved.
                    </p>
                    <p class="text-sm text-zinc-500 dark:text-zinc-400">
                        Built with Laravel, Livewire & Flux
                    </p>
                </div>
            </div>
        </footer>
    </body>
</html>
