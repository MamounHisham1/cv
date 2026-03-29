@props([
    'size' => 'base',
    'muted' => false,
    'color' => 'inherit',
    'as' => null,
])

@php
$sizeClasses = [
    'sm' => 'text-sm',
    'base' => 'text-base',
    'lg' => 'text-lg',
];

$colorClasses = [
    'inherit' => '',
    'primary' => 'text-primary',
    'muted' => 'text-muted-foreground',
    'destructive' => 'text-destructive',
];

$classes = ($sizeClasses[$size] ?? $sizeClasses['base']);
if ($muted) {
    $classes .= ' text-muted-foreground';
} elseif ($color !== 'inherit') {
    $classes .= ' ' . ($colorClasses[$color] ?? '');
}
$tag = $as ?? 'p';
@endphp

<{{ $tag }} {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</{{ $tag }}>
