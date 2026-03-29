@php
    $navItems = [
        ['route' => 'cv.builder', 'label' => 'My CVs', 'icon' => 'file-text', 'routeIs' => ['cv.builder', 'cv.edit']],
        ['route' => 'cv.evaluator', 'label' => 'AI Evaluator', 'icon' => 'sparkles', 'routeIs' => ['cv.evaluator']],
    ];
@endphp

<nav class="sticky top-0 z-50 w-full border-b border-white/10 bg-zinc-950/80 backdrop-blur-xl">
    <div class="mx-auto flex min-h-14 max-w-7xl items-center gap-3 px-4 py-2 sm:px-6 lg:px-8">

        {{-- Logo --}}
        <x-app-logo href="/" class="shrink-0" />

        {{-- Module pill nav --}}
        <div class="hidden items-center gap-1 rounded-full border border-white/10 bg-white/5 p-1 backdrop-blur-xl sm:flex">
            @foreach ($navItems as $item)
                @php
                    $isActive = request()->routeIs(...$item['routeIs']);
                @endphp
                <a
                    href="{{ route($item['route']) }}"
                    wire:navigate
                    class="inline-flex items-center gap-1.5 rounded-full px-4 py-1.5 text-sm font-medium transition-all duration-200
                        {{ $isActive
                            ? 'bg-white/10 text-white shadow-lg shadow-emerald-500/10'
                            : 'text-zinc-400 hover:bg-white/10 hover:text-white' }}"
                >
                    <x-ui::icon name="{{ $item['icon'] }}" class="h-3.5 w-3.5" />
                    {{ $item['label'] }}
                </a>
            @endforeach
        </div>

        {{-- Mobile: compact icon links --}}
        <div class="flex items-center gap-1 sm:hidden">
            @foreach ($navItems as $item)
                @php $isActive = request()->routeIs(...$item['routeIs']); @endphp
                <a
                    href="{{ route($item['route']) }}"
                    wire:navigate
                    class="inline-flex h-9 w-9 items-center justify-center rounded-full transition-all duration-200
                        {{ $isActive ? 'bg-white/10 text-white' : 'text-zinc-400 hover:bg-white/10 hover:text-white' }}"
                >
                    <x-ui::icon name="{{ $item['icon'] }}" class="h-4 w-4" />
                </a>
            @endforeach
        </div>

        <div class="flex-1"></div>

        {{-- Right: Back to site + user menu --}}
        <a
            href="{{ route('home') }}"
            wire:navigate
            class="hidden items-center gap-1.5 rounded-full border border-white/10 bg-white/5 px-3 py-1.5 text-xs font-medium text-zinc-400 transition-all duration-200 hover:bg-white/10 hover:text-white sm:inline-flex"
        >
            <x-ui::icon name="arrow-left" class="h-3 w-3" />
            Back to site
        </a>

        <x-desktop-user-menu />
    </div>
</nav>

