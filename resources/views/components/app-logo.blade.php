@props([
    'sidebar' => false,
])

@if($sidebar)
    <a {{ $attributes->merge(['href' => $attributes->get('href', '/'), 'class' => 'flex items-center gap-2 px-4 py-4']) }}>
        <span class="flex aspect-square size-8 items-center justify-center rounded-md bg-accent-content text-accent-foreground">
            <x-app-logo-icon class="size-5 fill-current text-white dark:text-black" />
        </span>
        <span class="font-semibold">{{ config('app.name', 'Laravel') }}</span>
    </a>
@else
    <a {{ $attributes->merge(['href' => $attributes->get('href', '/'), 'class' => 'flex items-center gap-2']) }}>
        <span class="flex aspect-square size-8 items-center justify-center rounded-md bg-accent-content text-accent-foreground">
            <x-app-logo-icon class="size-5 fill-current text-white dark:text-black" />
        </span>
        <span class="font-semibold">{{ config('app.name', 'Laravel') }}</span>
    </a>
@endif
