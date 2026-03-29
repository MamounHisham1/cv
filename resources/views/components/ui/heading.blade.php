@props([
    'size' => 'md',
    'level' => 'h2',
])

@php
$sizeClasses = [
    'sm' => 'text-sm font-semibold',
    'md' => 'text-lg font-semibold',
    'lg' => 'text-xl font-bold',
    'xl' => 'text-2xl font-bold',
];

$tag = in_array($level, ['h1', 'h2', 'h3', 'h4', 'h5', 'h6']) ? $level : 'h2';
$classes = ($sizeClasses[$size] ?? $sizeClasses['md']) . ' tracking-tight';
@endphp

<{{ $tag }} {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</{{ $tag }}>
