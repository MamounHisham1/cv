<header class="sticky top-0 z-50 w-full border-b border-white/10 bg-zinc-950/80 backdrop-blur-xl">
    <div class="mx-auto flex min-h-16 max-w-7xl items-center gap-3 px-4 py-3 sm:px-6 lg:px-8">
        <x-app-logo href="/" class="shrink-0" />

        {{-- Desktop nav links --}}
        <div
            x-data="{
                activeSection: '#home',
                ignoreScroll: false,
                scrollToSection(id) {
                    this.activeSection = '#' + id;
                    this.ignoreScroll = true;
                    const el = document.getElementById(id);
                    const offset = 80;
                    const top = el.getBoundingClientRect().top + window.pageYOffset - offset;
                    window.scrollTo({ top: top, behavior: 'smooth' });
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

        {{-- Mobile hamburger --}}
        <div class="lg:hidden"
            x-data="{ open: false, activeSection: '#home', ignoreScroll: false,
                scrollToSection(id) {
                    this.activeSection = '#' + id;
                    this.ignoreScroll = true;
                    this.open = false;
                    const el = document.getElementById(id);
                    const offset = 80;
                    const top = el.getBoundingClientRect().top + window.pageYOffset - offset;
                    window.scrollTo({ top: top, behavior: 'smooth' });
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
        >
            <button @click="open = !open" class="inline-flex h-9 w-9 items-center justify-center rounded-lg text-zinc-400 hover:bg-white/10 hover:text-white transition-colors">
                <svg x-show="!open" class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5"/>
                </svg>
                <svg x-show="open" x-cloak class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>

            {{-- Mobile dropdown --}}
            <div x-show="open" x-cloak
                @click.outside="open = false"
                x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 -translate-y-2"
                x-transition:enter-end="opacity-100 translate-y-0"
                x-transition:leave="transition ease-in duration-150"
                x-transition:leave-start="opacity-100 translate-y-0"
                x-transition:leave-end="opacity-0 -translate-y-2"
                class="absolute left-0 right-0 top-16 z-50 mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="rounded-xl border border-white/10 bg-zinc-950/95 backdrop-blur-xl p-2 shadow-2xl shadow-black/50">
                    <a href="#home" @click.prevent="scrollToSection('home')"
                        class="flex items-center gap-3 rounded-lg px-4 py-3 text-sm font-medium transition-colors"
                        :class="activeSection === '#home' ? 'bg-white/10 text-white' : 'text-zinc-400 hover:bg-white/10 hover:text-white'">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="m2.25 12 8.954-8.955a1.126 1.126 0 0 1 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25"/></svg>
                        Home
                    </a>
                    <a href="#features" @click.prevent="scrollToSection('features')"
                        class="flex items-center gap-3 rounded-lg px-4 py-3 text-sm font-medium transition-colors"
                        :class="activeSection === '#features' ? 'bg-white/10 text-white' : 'text-zinc-400 hover:bg-white/10 hover:text-white'">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9.813 15.904 9 18.75l-.813-2.846a4.5 4.5 0 0 0-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 0 0 3.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 0 0 3.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 0 0-3.09 3.09ZM18.259 8.715 18 9.75l-.259-1.035a3.375 3.375 0 0 0-2.455-2.456L14.25 6l1.036-.259a3.375 3.375 0 0 0 2.455-2.456L18 2.25l.259 1.035a3.375 3.375 0 0 0 2.455 2.456L21.75 6l-1.036.259a3.375 3.375 0 0 0-2.455 2.456ZM16.894 20.567 16.5 21.75l-.394-1.183a2.25 2.25 0 0 0-1.423-1.423L13.5 18.75l1.183-.394a2.25 2.25 0 0 0 1.423-1.423l.394-1.183.394 1.183a2.25 2.25 0 0 0 1.423 1.423l1.183.394-1.183.394a2.25 2.25 0 0 0-1.423 1.423Z"/></svg>
                        Features
                    </a>
                    <a href="#about" @click.prevent="scrollToSection('about')"
                        class="flex items-center gap-3 rounded-lg px-4 py-3 text-sm font-medium transition-colors"
                        :class="activeSection === '#about' ? 'bg-white/10 text-white' : 'text-zinc-400 hover:bg-white/10 hover:text-white'">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="m11.25 11.25.041-.02a.75.75 0 0 1 1.063.852l-.708 2.836a.75.75 0 0 0 1.063.853l.041-.021M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9-3.75h.008v.008H12V8.25Z"/></svg>
                        About
                    </a>
                    <a href="#pricing" @click.prevent="scrollToSection('pricing')"
                        class="flex items-center gap-3 rounded-lg px-4 py-3 text-sm font-medium transition-colors"
                        :class="activeSection === '#pricing' ? 'bg-white/10 text-white' : 'text-zinc-400 hover:bg-white/10 hover:text-white'">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 0 0 2.25-2.25V6.75A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25v10.5A2.25 2.25 0 0 0 4.5 19.5Z"/></svg>
                        Pricing
                    </a>
                    <a href="#faq" @click.prevent="scrollToSection('faq')"
                        class="flex items-center gap-3 rounded-lg px-4 py-3 text-sm font-medium transition-colors"
                        :class="activeSection === '#faq' ? 'bg-white/10 text-white' : 'text-zinc-400 hover:bg-white/10 hover:text-white'">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9.879 7.519c1.171-1.025 3.071-1.025 4.242 0 1.172 1.025 1.172 2.687 0 3.712-.203.179-.43.326-.67.442-.745.361-1.45.999-1.45 1.827v.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 5.25h.008v.008H12v-.008Z"/></svg>
                        FAQ
                    </a>

                    {{-- Mobile auth items --}}
                    <div class="my-2 border-t border-white/10"></div>
                    @guest
                        <a href="{{ route('login') }}" class="flex items-center gap-3 rounded-lg px-4 py-3 text-sm font-medium text-zinc-400 hover:bg-white/10 hover:text-white transition-colors">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15m3 0 3-3m0 0-3-3m3 3H9"/></svg>
                            {{ __('Login') }}
                        </a>
                        <a href="{{ route('register') }}" class="flex items-center justify-center gap-2 rounded-lg bg-gradient-to-r from-emerald-500 to-emerald-600 px-4 py-3 text-sm font-medium text-white shadow-lg shadow-emerald-500/20 mx-2">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7.5v3m0 0v3m0-3h3m-3 0h-3m-2.25-4.125a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0ZM4 19.235v-.11a6.375 6.375 0 0 1 12.75 0v.109A12.318 12.318 0 0 1 10.374 21c-2.331 0-4.512-.645-6.374-1.766Z"/></svg>
                            {{ __('Register') }}
                        </a>
                    @endguest
                    @auth
                        <div class="my-2 border-t border-white/10"></div>
                        <div class="flex items-center gap-2 px-4 py-2 text-start text-sm">
                            <x-ui::avatar :name="auth()->user()->name" :initials="auth()->user()->initials()" size="sm" />
                            <div class="grid flex-1 text-start text-sm leading-tight">
                                <span class="font-medium text-white">{{ auth()->user()->name }}</span>
                                <span class="text-zinc-400">{{ auth()->user()->email }}</span>
                            </div>
                        </div>
                        <a href="{{ route('drafts') }}" wire:navigate class="flex items-center gap-3 rounded-lg px-4 py-3 text-sm font-medium {{ request()->routeIs('drafts') ? 'bg-white/10 text-white' : 'text-zinc-400 hover:bg-white/10 hover:text-white' }} transition-colors">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z"/></svg>
                            {{ __('My CVs') }}
                        </a>
                        <a href="{{ route('profile.edit') }}" wire:navigate class="flex items-center gap-3 rounded-lg px-4 py-3 text-sm font-medium text-zinc-400 hover:bg-white/10 hover:text-white transition-colors">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.325.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 0 1 1.37.49l1.296 2.247a1.125 1.125 0 0 1-.145 1.374l-.885.885c-.283.283-.402.69-.308 1.089l.308 1.281c.127.53-.266 1.038-.807 1.038H13.5a1.125 1.125 0 0 1-.94-.56l-.213-1.281a1.125 1.125 0 0 0-1.11-.94h-2.593a1.125 1.125 0 0 0-1.11.94l-.213 1.281a1.125 1.125 0 0 1-.807.56H4.34a1.125 1.125 0 0 1-.94-.56l-.213-1.281a1.125 1.125 0 0 0-1.11-.94H.78a1.125 1.125 0 0 1-1.125-1.125v-2.25c0-.621.504-1.125 1.125-1.125h1.281c.53 0 .94-.266 1.038-.807l.213-1.281a1.125 1.125 0 0 0-.308-.807l-.885-.885a1.125 1.125 0 0 1-.145-1.374l1.296-2.247a1.125 1.125 0 0 1 1.37-.49l1.217.456c.355.133.75.072 1.075-.124.073-.044.146-.087.22-.127a1.125 1.125 0 0 0 .645-.87l.213-1.281Z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/></svg>
                            {{ __('Settings') }}
                        </a>
                        <a href="{{ route('credits.history') }}" wire:navigate class="flex items-center gap-3 rounded-lg px-4 py-3 text-sm font-medium text-zinc-400 hover:bg-white/10 hover:text-white transition-colors">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/></svg>
                            {{ __('Credit History') }}
                        </a>
                        <form method="POST" action="{{ route('logout') }}" class="w-full">
                            @csrf
                            <button type="submit" class="flex w-full items-center gap-3 rounded-lg px-4 py-3 text-sm font-medium text-zinc-400 hover:bg-white/10 hover:text-white transition-colors">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15m3 0 3-3m0 0-3-3m3 3H9"/></svg>
                                {{ __('Log out') }}
                            </button>
                        </form>
                    @endauth
                </div>
            </div>
        </div>

        {{-- Desktop auth items --}}
        <x-ui::navbar class="me-1.5 items-center gap-1 rounded-full border border-white/10 bg-white/5 p-1 backdrop-blur-xl rtl:space-x-reverse max-lg:hidden">
            @guest
                <x-ui::navbar.item :href="route('login')" icon="log-in" class="!rounded-full !px-4 !py-2 !text-zinc-400 hover:!bg-white/10 hover:!text-white">
                    {{ __('Login') }}
                </x-ui::navbar.item>
                <x-ui::button variant="primary" size="sm" :href="route('register')" class="border border-white/10 bg-gradient-to-r from-emerald-500 to-emerald-600 text-white shadow-lg shadow-emerald-500/20 hover:from-emerald-400 hover:to-emerald-500">
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
