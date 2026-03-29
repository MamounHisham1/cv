<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen bg-white dark:bg-zinc-800">
        <header class="sticky top-0 z-50 w-full border-b border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900 backdrop-blur-lg">
            <div class="mx-auto flex h-16 max-w-7xl items-center gap-2 px-4 sm:px-6 lg:px-8">
                <button
                    type="button"
                    class="inline-flex items-center justify-center rounded-lg p-2 text-muted-foreground hover:bg-accent hover:text-accent-foreground transition-colors lg:hidden mr-2"
                    x-data
                    x-on:click="$dispatch('toggle-sidebar')"
                >
                    <x-heroicon-c-bars-2 class="size-5" />
                </button>

                <x-app-logo href="{{ route('dashboard') }}" wire:navigate />

                <x-ui::navbar class="-mb-px max-lg:hidden">
                    <x-ui::navbar.item icon="layout-grid" :href="route('dashboard')" :current="request()->routeIs('dashboard')" wire:navigate>
                        {{ __('Dashboard') }}
                    </x-ui::navbar.item>
                </x-ui::navbar>

                <div class="flex-1"></div>

                <x-ui::navbar class="me-1.5 space-x-0.5 rtl:space-x-reverse py-0">
                    <x-ui::tooltip :content="__('Search')" position="bottom">
                        <x-ui::navbar.item icon="search" href="#" :label="__('Search')" />
                    </x-ui::tooltip>
                    <x-ui::tooltip :content="__('Repository')" position="bottom">
                        <x-ui::navbar.item
                            class="max-lg:hidden"
                            icon="git-branch"
                            href="https://github.com/laravel/livewire-starter-kit"
                            target="_blank"
                            :label="__('Repository')"
                        />
                    </x-ui::tooltip>
                    <x-ui::tooltip :content="__('Documentation')" position="bottom">
                        <x-ui::navbar.item
                            class="max-lg:hidden"
                            icon="book-open"
                            href="https://laravel.com/docs/starter-kits#livewire"
                            target="_blank"
                            :label="__('Documentation')"
                        />
                    </x-ui::tooltip>
                </x-ui::navbar>

                <x-desktop-user-menu />
            </div>
        </header>

        <x-ui::sidebar collapsible="mobile" sticky class="lg:hidden border-e border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900">
            <x-ui::sidebar.header>
                <x-app-logo :sidebar="true" href="{{ route('dashboard') }}" wire:navigate />
                <x-ui::sidebar.collapse />
            </x-ui::sidebar.header>

            <x-ui::sidebar.nav>
                <x-ui::sidebar.group :heading="__('Platform')">
                    <x-ui::sidebar.item icon="layout-grid" :href="route('dashboard')" :current="request()->routeIs('dashboard')" wire:navigate>
                        {{ __('Dashboard')  }}
                    </x-ui::sidebar.item>
                </x-ui::sidebar.group>
            </x-ui::sidebar.nav>

            <div class="flex-1"></div>

            <x-ui::sidebar.nav>
                <x-ui::sidebar.item icon="git-branch" href="https://github.com/laravel/livewire-starter-kit" target="_blank">
                    {{ __('Repository') }}
                </x-ui::sidebar.item>
                <x-ui::sidebar.item icon="book-open" href="https://laravel.com/docs/starter-kits#livewire" target="_blank">
                    {{ __('Documentation') }}
                </x-ui::sidebar.item>
            </x-ui::sidebar.nav>
        </x-ui::sidebar>

        {{ $slot }}
    </body>
</html>
