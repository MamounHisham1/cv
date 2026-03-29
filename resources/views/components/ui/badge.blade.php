@props([
    'variant' => 'default',
])

@php
$variantClasses = [
    'default' => 'bg-primary text-primary-foreground',
    'secondary' => 'bg-secondary text-secondary-foreground',
    'outline' => 'border border-border text-foreground',
    'destructive' => 'bg-destructive text-destructive-foreground',
    'brand' => 'bg-brand text-brand-foreground',
];

$classes = 'inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-semibold transition-colors ' . ($variantClasses[$variant] ?? $variantClasses['default']);
@endphp

<span {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</span>
