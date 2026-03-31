<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark antialiased scroll-smooth">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen bg-zinc-950 text-zinc-100">
        <x-landing-navbar />

        {{ $slot }}

        <footer class="border-t border-white/10 bg-zinc-950">
            <div class="mx-auto max-w-7xl px-6 lg:px-8 py-12 lg:py-16">
                <div class="grid grid-cols-2 md:grid-cols-4 gap-8 lg:gap-12">
                    <div class="col-span-2 md:col-span-1">
                        <x-app-logo href="/" />
                        <p class="mt-4 text-sm leading-relaxed text-zinc-400">
                            Build professional, ATS-optimized CVs with the power of AI. Land your dream job faster.
                        </p>
                        <div class="flex items-center gap-3 mt-6">
                            <a href="#" class="text-zinc-500 transition-colors hover:text-emerald-400">
                                <x-ui::icon name="globe" size="lg" />
                            </a>
                            <a href="#" class="text-zinc-500 transition-colors hover:text-emerald-400">
                                <x-ui::icon name="code-2" size="lg" />
                            </a>
                            <a href="#" class="text-zinc-500 transition-colors hover:text-emerald-400">
                                <x-ui::icon name="mail" size="lg" />
                            </a>
                        </div>
                    </div>

                    <div>
                        <h3 class="mb-4 text-sm font-semibold text-white">Product</h3>
                        <ul class="space-y-3">
                            <li><a href="{{ route('home') }}#features" class="text-sm text-zinc-400 transition-colors hover:text-emerald-400">Features</a></li>
                            <li><a href="{{ route('home') }}#pricing" class="text-sm text-zinc-400 transition-colors hover:text-emerald-400">Pricing</a></li>
                            <li><a href="{{ route('home') }}#about" class="text-sm text-zinc-400 transition-colors hover:text-emerald-400">About</a></li>
                            <li><a href="{{ route('home') }}#faq" class="text-sm text-zinc-400 transition-colors hover:text-emerald-400">FAQ</a></li>
                        </ul>
                    </div>

                    <div>
                        <h3 class="mb-4 text-sm font-semibold text-white">Resources</h3>
                        <ul class="space-y-3">
                            <li><a href="#" class="text-sm text-zinc-400 transition-colors hover:text-emerald-400">Blog</a></li>
                            <li><a href="#" class="text-sm text-zinc-400 transition-colors hover:text-emerald-400">CV Tips</a></li>
                            <li><a href="#" class="text-sm text-zinc-400 transition-colors hover:text-emerald-400">Templates</a></li>
                            <li><a href="{{ route('home') }}#contact" class="text-sm text-zinc-400 transition-colors hover:text-emerald-400">Support</a></li>
                        </ul>
                    </div>

                    <div>
                        <h3 class="mb-4 text-sm font-semibold text-white">Legal</h3>
                        <ul class="space-y-3">
                            <li><a href="#" class="text-sm text-zinc-400 transition-colors hover:text-emerald-400">Privacy Policy</a></li>
                            <li><a href="#" class="text-sm text-zinc-400 transition-colors hover:text-emerald-400">Terms of Service</a></li>
                            <li><a href="#" class="text-sm text-zinc-400 transition-colors hover:text-emerald-400">Cookie Policy</a></li>
                        </ul>
                    </div>
                </div>

                <div class="mt-12 flex flex-col items-center justify-between gap-4 border-t border-white/10 pt-8 sm:flex-row">
                    <p class="text-sm text-zinc-500">
                        &copy; {{ date('Y') }} SeratyAI. All rights reserved.
                    </p>
                    <p class="text-sm text-zinc-500">
                        Built with Laravel, Livewire & Flux
                    </p>
                </div>
            </div>
        </footer>
        @livewireScripts
    </body>
</html>
