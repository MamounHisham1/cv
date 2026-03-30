<header class="sticky top-0 z-50 w-full border-b border-white/10 bg-zinc-950/80 backdrop-blur-xl">
    <div class="mx-auto flex min-h-16 max-w-7xl items-center gap-3 px-4 py-3 sm:px-6 lg:px-8">
        <x-app-logo href="/" class="shrink-0" />

        <x-ui::navbar class="hidden items-center gap-1 rounded-full border border-white/10 bg-white/5 p-1 backdrop-blur-xl lg:flex" x-data="{ activeSection: window.location.hash || '#home', scrolling: false }" x-init="$nextTick(() => { if (!window.location.hash && window.location.pathname === '/') activeSection = '#home'; }); window.addEventListener('hashchange', () => activeSection = window.location.hash || '#home'); window.addEventListener('scroll', () => { if (scrolling) return; const sections = ['home', 'features', 'about', 'pricing', 'faq']; for (const id of sections) { const el = document.getElementById(id); if (el) { const rect = el.getBoundingClientRect(); if (rect.top <= 100 && rect.bottom >= 100) { activeSection = '#' + id; break; } } } })">
            <x-ui::navbar.item href="/" @click.prevent="activeSection = '#home'; window.location.hash = 'home'; scrolling = true; setTimeout(() => scrolling = false, 1000)" class="!rounded-full !px-4 !py-2" x-bind:class="activeSection === '#home' ? '!bg-white/10 !text-white shadow-lg shadow-emerald-500/10' : '!text-zinc-400 hover:!bg-white/10 hover:!text-white'">
                Home
            </x-ui::navbar.item>
            <x-ui::navbar.item href="{{ route('home') }}#features" @click="activeSection = '#features'; scrolling = true; setTimeout(() => scrolling = false, 1000)" class="!rounded-full !px-4 !py-2" x-bind:class="activeSection === '#features' ? '!bg-white/10 !text-white shadow-lg shadow-emerald-500/10' : '!text-zinc-400 hover:!bg-white/10 hover:!text-white'">
                Features
            </x-ui::navbar.item>
            <x-ui::navbar.item href="{{ route('home') }}#about" @click="activeSection = '#about'; scrolling = true; setTimeout(() => scrolling = false, 1000)" class="!rounded-full !px-4 !py-2" x-bind:class="activeSection === '#about' ? '!bg-white/10 !text-white shadow-lg shadow-emerald-500/10' : '!text-zinc-400 hover:!bg-white/10 hover:!text-white'">
                About
            </x-ui::navbar.item>
            <x-ui::navbar.item href="{{ route('home') }}#pricing" @click="activeSection = '#pricing'; scrolling = true; setTimeout(() => scrolling = false, 1000)" class="!rounded-full !px-4 !py-2" x-bind:class="activeSection === '#pricing' ? '!bg-white/10 !text-white shadow-lg shadow-emerald-500/10' : '!text-zinc-400 hover:!bg-white/10 hover:!text-white'">
                Pricing
            </x-ui::navbar.item>
            <x-ui::navbar.item href="{{ route('home') }}#faq" @click="activeSection = '#faq'; scrolling = true; setTimeout(() => scrolling = false, 1000)" class="!rounded-full !px-4 !py-2" x-bind:class="activeSection === '#faq' ? '!bg-white/10 !text-white shadow-lg shadow-emerald-500/10' : '!text-zinc-400 hover:!bg-white/10 hover:!text-white'">
                FAQ
            </x-ui::navbar.item>
        </x-ui::navbar>

        <div class="flex-1"></div>

        <x-ui::navbar class="me-1.5 items-center gap-1 rounded-full border border-white/10 bg-white/5 p-1 backdrop-blur-xl rtl:space-x-reverse">
            @guest
                <x-ui::navbar.item :href="route('login')" icon="log-in" class="!rounded-full !px-4 !py-2 !text-zinc-400 hover:!bg-white/10 hover:!text-white">
                    {{ __('Login') }}
                </x-ui::navbar.item>
                <x-ui::button variant="primary" size="sm" :href="route('register')" class="max-lg:hidden border border-white/10 bg-gradient-to-r from-emerald-500 to-emerald-600 text-white shadow-lg shadow-emerald-500/20 hover:from-emerald-400 hover:to-emerald-500">
                    {{ __('Register') }}
                </x-ui::button>
            @endguest
            @auth
                <x-ui::navbar.item :href="route('drafts')" icon="file-text" :current="request()->routeIs('drafts')" wire:navigate class="!rounded-full !px-4 !py-2 {{ request()->routeIs('drafts') ? '!bg-white/10 !text-white shadow-lg shadow-emerald-500/10' : '!text-zinc-400 hover:!bg-white/10 hover:!text-white' }}">
                    {{ __('My CVs') }}
                </x-ui::navbar.item>
                <x-desktop-user-menu />
            @endauth
        </x-ui::navbar>
    </div>
</header>
