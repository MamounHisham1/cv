@props([
    'variant' => 'default',
    'heading' => null,
])

@php
$variantClasses = [
    'default' => 'border border-border bg-card text-card-foreground',
    'destructive' => 'border border-destructive/50 bg-destructive/10 text-destructive',
    'warning' => 'border border-yellow-500/50 bg-yellow-500/10 text-yellow-700 dark:text-yellow-400',
];

$iconMap = [
    'default' => 'information-circle',
    'destructive' => 'exclamation-circle',
    'warning' => 'exclamation-triangle',
];

$classes = 'rounded-lg p-4 ' . ($variantClasses[$variant] ?? $variantClasses['default']);
$component = 'heroicon-c-' . ($iconMap[$variant] ?? $iconMap['default']);
@endphp

<div {{ $attributes->merge(['class' => $classes]) }}>
    <div class="flex gap-3">
        <x-dynamic-component :component="$component" class="size-5 shrink-0 mt-0.5" />
        <div class="flex-1 space-y-1">
            @if($heading)
                <h5 class="font-semibold text-sm leading-none">{{ $heading }}</h5>
            @endif
            <div class="text-sm">
                {{ $slot }}
            </div>
        </div>
    </div>
</div>
