@props([
    'variant' => 'default',
])

@php
$variantClasses = [
    'default' => 'flex flex-col gap-3',
    'segmented' => 'inline-flex rounded-lg border border-input p-1 gap-1',
];

$containerClass = $variantClasses[$variant] ?? $variantClasses['default'];
@endphp

<div
    {{ $attributes->merge(['class' => $containerClass]) }}
    role="radiogroup"
>
    {{ $slot }}
</div>
