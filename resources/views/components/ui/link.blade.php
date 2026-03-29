@props([
    'variant' => 'default',
])

@php
$variantClasses = [
    'default' => 'text-primary underline-offset-4 hover:underline',
    'muted' => 'text-muted-foreground hover:text-foreground underline-offset-4 hover:underline',
];

$classes = 'inline-flex items-center gap-1 text-sm font-medium transition-colors ' . ($variantClasses[$variant] ?? $variantClasses['default']);
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
