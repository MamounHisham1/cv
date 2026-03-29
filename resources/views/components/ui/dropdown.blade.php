@props([
    'position' => 'bottom-start',
])

@php
$positionClasses = [
    'bottom-start' => 'top-full left-0 mt-1',
    'bottom-end' => 'top-full right-0 mt-1',
    'top-start' => 'bottom-full left-0 mb-1',
    'top-end' => 'bottom-full right-0 mb-1',
];

$positionClass = $positionClasses[$position] ?? $positionClasses['bottom-start'];
@endphp

<div x-data="{ open: false }" class="relative inline-block">
    <div @click="open = !open">
        {{ $slot }}
    </div>

    <div
        x-show="open"
        x-transition:enter="transition ease-out duration-100"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-75"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
        x-on:click.outside="open = false"
        class="absolute z-50 {{ $positionClass }} min-w-[8rem] overflow-hidden rounded-lg border border-border bg-popover p-1 text-popover-foreground shadow-lg"
    >
        {{ $items }}
    </div>
</div>
