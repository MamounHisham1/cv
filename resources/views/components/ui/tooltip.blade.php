@props([
    'content' => null,
    'position' => 'top',
])

@php
$positionClasses = [
    'top' => 'bottom-full left-1/2 -translate-x-1/2 mb-2',
    'bottom' => 'top-full left-1/2 -translate-x-1/2 mt-2',
    'left' => 'right-full top-1/2 -translate-y-1/2 mr-2',
    'right' => 'left-full top-1/2 -translate-y-1/2 ml-2',
];

$positionClass = $positionClasses[$position] ?? $positionClasses['top'];
@endphp

<div
    class="relative inline-flex"
    x-data="{ show: false }"
    @mouseenter="show = true"
    @mouseleave="show = false"
>
    {{ $slot }}

    <div
        x-show="show"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
        class="absolute z-50 {{ $positionClass }} pointer-events-none"
        @if($position === 'top') x-anchor.offset.8="top start" @endif
    >
        <div class="rounded-lg bg-popover px-3 py-1.5 text-sm text-popover-foreground shadow-md border border-border">
            {{ $content }}
        </div>
    </div>
</div>
