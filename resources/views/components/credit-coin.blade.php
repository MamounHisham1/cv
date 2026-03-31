@props([
    'size' => 'md',
])

@php
$uid = uniqid('cc');
$sizeClass = match ($size) {
    'xs' => 'w-3.5 h-3.5',
    'sm' => 'w-4 h-4',
    'md' => 'w-5 h-5',
    'lg' => 'w-6 h-6',
    'xl' => 'w-8 h-8',
    default => $size,
};
@endphp

<svg {{ $attributes->merge(['class' => $sizeClass]) }} viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
    <circle cx="12" cy="12" r="10.5" stroke="url(#{{ $uid }}-coin-ring)" stroke-width="1.5" opacity="0.4"/>
    <circle cx="12" cy="12" r="9.5" stroke="url(#{{ $uid }}-coin-edge)" stroke-width="0.5" opacity="0.25" stroke-dasharray="2 2"/>
    <circle cx="12" cy="12" r="8" stroke="url(#{{ $uid }}-coin-face)" stroke-width="1.5" opacity="0.8"/>
    <circle cx="12" cy="12" r="7" stroke="url(#{{ $uid }}-coin-edge)" stroke-width="0.5" opacity="0.15"/>
    <path d="M13.5 2.5L8.5 12.5H12L10.5 21.5L16 10.5H12.5L13.5 2.5Z" fill="url(#{{ $uid }}-spark-gradient)" opacity="0.9"/>
    <path d="M13.5 2.5L8.5 12.5H12L10.5 21.5L16 10.5H12.5L13.5 2.5Z" fill="url(#{{ $uid }}-spark-glow)" opacity="0.3" filter="url(#{{ $uid }}-blur)"/>

    <defs>
        <linearGradient id="{{ $uid }}-coin-ring" x1="2" y1="2" x2="22" y2="22">
            <stop offset="0%" stop-color="#F6D365"/>
            <stop offset="50%" stop-color="#FBBF24"/>
            <stop offset="100%" stop-color="#D4A017"/>
        </linearGradient>
        <linearGradient id="{{ $uid }}-coin-edge" x1="2" y1="2" x2="22" y2="22">
            <stop offset="0%" stop-color="#FDE68A"/>
            <stop offset="100%" stop-color="#B8860B"/>
        </linearGradient>
        <linearGradient id="{{ $uid }}-coin-face" x1="4" y1="4" x2="20" y2="20">
            <stop offset="0%" stop-color="#F6D365"/>
            <stop offset="50%" stop-color="#FBBF24"/>
            <stop offset="100%" stop-color="#D4A017"/>
        </linearGradient>
        <linearGradient id="{{ $uid }}-spark-gradient" x1="8.5" y1="2.5" x2="16" y2="21.5">
            <stop offset="0%" stop-color="#FDE68A"/>
            <stop offset="40%" stop-color="#FBBF24"/>
            <stop offset="100%" stop-color="#D4A017"/>
        </linearGradient>
        <linearGradient id="{{ $uid }}-spark-glow" x1="8.5" y1="2.5" x2="16" y2="21.5">
            <stop offset="0%" stop-color="#FEF3C7"/>
            <stop offset="100%" stop-color="#F6D365"/>
        </linearGradient>
        <filter id="{{ $uid }}-blur">
            <feGaussianBlur stdDeviation="1"/>
        </filter>
    </defs>
</svg>
