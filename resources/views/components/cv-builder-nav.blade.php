@php
    $navItems = [
        ['route' => 'drafts', 'label' => 'My CVs', 'icon' => 'file-text', 'routeIs' => ['cv.builder', 'cv.edit', 'drafts']],
        ['route' => 'cv.evaluator', 'label' => 'AI Evaluator', 'icon' => 'sparkles', 'routeIs' => ['cv.evaluator']],
        ['route' => 'ai.interview', 'label' => 'AI Interviewer', 'icon' => 'microphone', 'routeIs' => ['ai.interview']],
        ['route' => 'referrals', 'label' => 'Referrals', 'icon' => 'gift', 'routeIs' => ['referrals']],
    ];
@endphp

<header class="sticky top-0 z-50 w-full border-b border-white/10 bg-zinc-950/80 backdrop-blur-xl">
    @if(app(\App\Services\ImpersonateService::class)->isImpersonating())
        <div class="bg-amber-600 px-4 py-2 text-center text-sm font-medium text-white">
            You are impersonating <strong>{{ auth()->user()->name }}</strong> ({{ auth()->user()->email }})
            <form method="POST" action="{{ route('impersonate.stop') }}" class="inline ml-3">
                @csrf
                <button type="submit" class="rounded bg-white/20 px-3 py-0.5 text-sm font-medium text-white hover:bg-white/30 transition-colors">
                    Stop Impersonating
                </button>
            </form>
        </div>
    @endif
    <div class="mx-auto flex min-h-16 max-w-7xl items-center gap-3 px-4 py-3 sm:px-6 lg:px-8">

        <x-app-logo href="/" class="shrink-0" />

        {{-- Desktop nav links --}}
        <x-ui::navbar class="hidden items-center gap-1 rounded-full border border-white/10 bg-white/5 p-1 backdrop-blur-xl lg:flex">
            @foreach ($navItems as $item)
                @php
                    $isActive = request()->routeIs(...$item['routeIs']);
                @endphp
                <x-ui::navbar.item :href="route($item['route'])" icon="{{ $item['icon'] }}" :current="$isActive" wire:navigate class="!rounded-full !px-4 !py-2 {{ $isActive ? '!bg-white/10 !text-white shadow-lg shadow-emerald-500/10' : '!text-zinc-400 hover:!bg-white/10 hover:!text-white' }}">
                    {{ $item['label'] }}
                </x-ui::navbar.item>
            @endforeach
        </x-ui::navbar>

        <div class="flex-1"></div>

        {{-- Mobile: credits + bell next to hamburger --}}
        <div class="flex items-center gap-2 lg:hidden">
            <livewire:credit-balance-indicator />
            <livewire:notification-bell />
            <div x-data="{ open: false }">
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
                        @foreach ($navItems as $item)
                            @php
                                $isActive = request()->routeIs(...$item['routeIs']);
                            @endphp
                            <a href="{{ route($item['route']) }}" wire:navigate
                                @click="open = false"
                                class="flex items-center gap-3 rounded-lg px-4 py-3 text-sm font-medium transition-colors {{ $isActive ? 'bg-white/10 text-white' : 'text-zinc-400 hover:bg-white/10 hover:text-white' }}">
                                <x-ui::icon name="{{ $item['icon'] }}" class="h-5 w-5" />
                                {{ $item['label'] }}
                            </a>
                        @endforeach

                        <div class="my-2 border-t border-white/10"></div>

                        <a href="{{ route('home') }}" wire:navigate
                            @click="open = false"
                            class="flex items-center gap-3 rounded-lg px-4 py-3 text-sm font-medium text-zinc-400 hover:bg-white/10 hover:text-white transition-colors">
                            <x-ui::icon name="arrow-left" class="h-5 w-5" />
                            {{ __('Back to site') }}
                        </a>

                        <div class="my-2 border-t border-white/10"></div>

                        {{-- User account links --}}
                        <div class="flex items-center gap-2 px-4 py-2 text-start text-sm">
                            <x-ui::avatar :name="auth()->user()->name" :initials="auth()->user()->initials()" size="sm" />
                            <div class="grid flex-1 text-start text-sm leading-tight">
                                <span class="font-medium text-white">{{ auth()->user()->name }}</span>
                                <span class="text-zinc-400">{{ auth()->user()->email }}</span>
                            </div>
                        </div>
                        <a href="{{ route('profile.edit') }}" wire:navigate
                            @click="open = false"
                            class="flex items-center gap-3 rounded-lg px-4 py-3 text-sm font-medium text-zinc-400 hover:bg-white/10 hover:text-white transition-colors">
                            <x-ui::icon name="settings" class="h-5 w-5" />
                            {{ __('Settings') }}
                        </a>
                        <a href="{{ route('credits.history') }}" wire:navigate
                            @click="open = false"
                            class="flex items-center gap-3 rounded-lg px-4 py-3 text-sm font-medium text-zinc-400 hover:bg-white/10 hover:text-white transition-colors">
                            <x-ui::icon name="clock" class="h-5 w-5" />
                            {{ __('Credit History') }}
                        </a>
                        <form method="POST" action="{{ route('logout') }}" class="w-full">
                            @csrf
                            <button type="submit"
                                class="flex w-full items-center gap-3 rounded-lg px-4 py-3 text-sm font-medium text-zinc-400 hover:bg-white/10 hover:text-white transition-colors">
                                <x-ui::icon name="log-out" class="h-5 w-5" />
                                {{ __('Log out') }}
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        {{-- Desktop right side --}}
        <div class="max-lg:hidden items-center gap-2 flex">
            <livewire:credit-balance-indicator />
            <livewire:notification-bell />

            <x-ui::navbar class="me-1.5 items-center gap-1 rounded-full border border-white/10 bg-white/5 p-1 backdrop-blur-xl rtl:space-x-reverse">
                <x-ui::navbar.item :href="route('home')" icon="arrow-left" class="!rounded-full !px-4 !py-2 !text-zinc-300 hover:!bg-white/10 hover:!text-white">
                    {{ __('Back to site') }}
                </x-ui::navbar.item>

                <x-desktop-user-menu />
            </x-ui::navbar>
        </div>
    </div>
</header>
