@props([
    'orientation' => 'horizontal',
    'variant' => 'default',
])

@php
$orientationClasses = $orientation === 'vertical'
    ? 'inline-block h-full w-px'
    : 'h-px w-full';

$variantClasses = $variant === 'subtle'
    ? 'bg-border/50'
    : 'bg-border';

$classes = 'shrink-0 ' . $orientationClasses . ' ' . $variantClasses;
$tag = $orientation === 'vertical' ? 'div' : 'hr';
@endphp

<{{ $tag }}
    role="separator"
    @if($orientation === 'vertical') aria-orientation="vertical" @endif
    {{ $attributes->merge(['class' => $classes]) }}
/>
