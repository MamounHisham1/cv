@php
    $navItems = [
        ['route' => 'drafts', 'label' => 'My CVs', 'icon' => 'file-text', 'routeIs' => ['cv.builder', 'cv.edit', 'drafts']],
        ['route' => 'cv.evaluator', 'label' => 'AI Evaluator', 'icon' => 'sparkles', 'routeIs' => ['cv.evaluator']],
    ];
@endphp

<header class="sticky top-0 z-50 w-full border-b border-white/10 bg-zinc-950/80 backdrop-blur-xl">
    <div class="mx-auto flex min-h-16 max-w-7xl items-center gap-3 px-4 py-3 sm:px-6 lg:px-8">

        <x-app-logo href="/" class="shrink-0" />

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

        <x-ui::navbar class="me-1.5 items-center gap-1 rounded-full border border-white/10 bg-white/5 p-1 backdrop-blur-xl rtl:space-x-reverse">
            <x-ui::navbar.item :href="route('home')" icon="arrow-left" wire:navigate class="!rounded-full !px-4 !py-2 !text-zinc-300 hover:!bg-white/10 hover:!text-white">
                {{ __('Back to site') }}
            </x-ui::navbar.item>

            <x-desktop-user-menu />
        </x-ui::navbar>
    </div>
</header>
