<header class="sticky top-0 z-50 w-full border-b border-white/10 bg-zinc-950/80 backdrop-blur-xl">
    <div class="mx-auto flex min-h-16 max-w-7xl items-center gap-3 px-4 py-3 sm:px-6 lg:px-8">
        <x-app-logo href="/" class="shrink-0" />

        {{-- Alpine scope wrapper to ensure x-data is on the correct element --}}
        <div
            x-data="{
                activeSection: '#home',
                ignoreScroll: false,
                scrollToSection(id) {
                    this.activeSection = '#' + id;
                    this.ignoreScroll = true;
                    document.getElementById(id).scrollIntoView({ behavior: 'smooth' });
                    setTimeout(() => this.ignoreScroll = false, 1000);
                }
            }"
            @scroll.window="
                if (ignoreScroll) return;
                const sections = ['home', 'features', 'about', 'pricing', 'faq'];
                let current = 'home';
                for (const id of sections) {
                    const el = document.getElementById(id);
                    if (el && el.getBoundingClientRect().top <= 120) current = id;
                }
                activeSection = '#' + current;
            "
            x-init="activeSection = window.location.hash || '#home'"
            class="hidden items-center gap-1 rounded-full border border-white/10 bg-white/5 p-1 backdrop-blur-xl lg:flex"
        >
            <a href="#home"
                @click.prevent="scrollToSection('home')"
                class="inline-flex items-center gap-2 rounded-lg px-3 py-2 text-sm font-medium transition-colors !rounded-full !px-4 !py-2"
                :class="activeSection === '#home' ? '!bg-white/10 !text-white shadow-lg shadow-emerald-500/10' : '!text-zinc-400 hover:!bg-white/10 hover:!text-white'">
                Home
            </a>
            <a href="#features"
                @click.prevent="scrollToSection('features')"
                class="inline-flex items-center gap-2 rounded-lg px-3 py-2 text-sm font-medium transition-colors !rounded-full !px-4 !py-2"
                :class="activeSection === '#features' ? '!bg-white/10 !text-white shadow-lg shadow-emerald-500/10' : '!text-zinc-400 hover:!bg-white/10 hover:!text-white'">
                Features
            </a>
            <a href="#about"
                @click.prevent="scrollToSection('about')"
                class="inline-flex items-center gap-2 rounded-lg px-3 py-2 text-sm font-medium transition-colors !rounded-full !px-4 !py-2"
                :class="activeSection === '#about' ? '!bg-white/10 !text-white shadow-lg shadow-emerald-500/10' : '!text-zinc-400 hover:!bg-white/10 hover:!text-white'">
                About
            </a>
            <a href="#pricing"
                @click.prevent="scrollToSection('pricing')"
                class="inline-flex items-center gap-2 rounded-lg px-3 py-2 text-sm font-medium transition-colors !rounded-full !px-4 !py-2"
                :class="activeSection === '#pricing' ? '!bg-white/10 !text-white shadow-lg shadow-emerald-500/10' : '!text-zinc-400 hover:!bg-white/10 hover:!text-white'">
                Pricing
            </a>
            <a href="#faq"
                @click.prevent="scrollToSection('faq')"
                class="inline-flex items-center gap-2 rounded-lg px-3 py-2 text-sm font-medium transition-colors !rounded-full !px-4 !py-2"
                :class="activeSection === '#faq' ? '!bg-white/10 !text-white shadow-lg shadow-emerald-500/10' : '!text-zinc-400 hover:!bg-white/10 hover:!text-white'">
                FAQ
            </a>
        </div>

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
