<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="antialiased scroll-smooth">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen bg-white dark:bg-zinc-950 text-zinc-900 dark:text-zinc-100">
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
                @guest
                    <flux:navbar.item :href="route('login')" icon="arrow-right-to-bracket">
                        {{ __('Login') }}
                    </flux:navbar.item>
                    <flux:button variant="primary" size="sm" :href="route('register')" class="max-lg:hidden">
                        {{ __('Register') }}
                    </flux:button>
                @endguest
                @auth
                    <flux:navbar.item :href="route('cv.builder')" icon="document-text" wire:navigate>
                        {{ __('My CVs') }}
                    </flux:navbar.item>
                @endauth
            </flux:navbar>
        </flux:header>

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
                                <flux:icon name="globe" class="size-5" />
                            </a>
                            <a href="#" class="text-zinc-500 dark:text-zinc-400 hover:text-emerald-600 dark:hover:text-emerald-400 transition-colors">
                                <flux:icon name="code-bracket" class="size-5" />
                            </a>
                            <a href="#" class="text-zinc-500 dark:text-zinc-400 hover:text-emerald-600 dark:hover:text-emerald-400 transition-colors">
                                <flux:icon name="mail" class="size-5" />
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
                        &copy; {{ date('Y') }} CVForge. All rights reserved.
                    </p>
                    <p class="text-sm text-zinc-500 dark:text-zinc-400">
                        Built with Laravel, Livewire & Flux
                    </p>
                </div>
            </div>
        </footer>

        @fluxScripts
    </body>
</html>
