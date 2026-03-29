@props([
    'name' => null,
    'initials' => null,
    'src' => null,
    'size' => 'md',
])

@php
$sizeClasses = [
    'sm' => 'size-8 text-xs',
    'md' => 'size-10 text-sm',
    'lg' => 'size-14 text-base',
];

$displayInitials = $initials;
if (!$displayInitials && $name) {
    $parts = explode(' ', trim($name));
    if (count($parts) >= 2) {
        $displayInitials = strtoupper($parts[0][0] . $parts[count($parts) - 1][0]);
    } else {
        $displayInitials = strtoupper(mb_substr($name, 0, 2));
    }
}

$classes = ($sizeClasses[$size] ?? $sizeClasses['md']) . ' rounded-full overflow-hidden flex items-center justify-center bg-secondary text-secondary-foreground font-medium shrink-0';
@endphp

<div {{ $attributes->merge(['class' => $classes]) }}>
    @if($src)
        <img src="{{ $src }}" alt="{{ $name ?? 'Avatar' }}" class="size-full object-cover" />
    @else
        {{ $displayInitials }}
    @endif
</div>
