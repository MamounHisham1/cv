<x-ui::dropdown position="bottom-start" class="dark">
    <button
        type="button"
        data-test="sidebar-menu-button"
        class="inline-flex items-center gap-2 rounded-full px-4 py-2 text-sm font-medium text-zinc-300 transition-colors hover:bg-white/10 hover:text-white"
    >
        <x-ui::avatar :name="auth()->user()->name" :initials="auth()->user()->initials()" size="sm" />
        <x-heroicon-c-chevron-down class="size-4 text-zinc-500" />
    </button>

    <x-slot:items>
        <div class="flex items-center gap-2 px-2 py-2 text-start text-sm">
            <x-ui::avatar :name="auth()->user()->name" :initials="auth()->user()->initials()" size="sm" />
            <div class="grid flex-1 text-start text-sm leading-tight">
                <x-ui::heading size="sm">{{ auth()->user()->name }}</x-ui::heading>
                <x-ui::text size="sm" muted>{{ auth()->user()->email }}</x-ui::text>
            </div>
        </div>
        <x-ui::menu separator />
        <a href="{{ route('profile.edit') }}" wire:navigate class="relative flex w-full cursor-pointer select-none items-center gap-2 rounded-md px-2 py-1.5 text-sm text-zinc-300 outline-none transition-colors hover:bg-white/10 hover:text-white focus:bg-white/10 focus:text-white">
            <x-heroicon-c-cog-6-tooth class="size-4" />
            {{ __('Settings') }}
        </a>
        <form method="POST" action="{{ route('logout') }}" class="w-full">
            @csrf
            <button
                type="submit"
                class="relative flex w-full cursor-pointer select-none items-center gap-2 rounded-md px-2 py-1.5 text-sm text-zinc-300 outline-none transition-colors hover:bg-white/10 hover:text-white focus:bg-white/10 focus:text-white"
                data-test="logout-button"
            >
                <x-heroicon-c-arrow-right-start-on-rectangle class="size-4" />
                {{ __('Log out') }}
            </button>
        </form>
    </x-slot:items>
</x-ui::dropdown>
