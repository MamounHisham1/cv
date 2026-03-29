@props([
    'variant' => 'default',
    'size' => 'default',
    'type' => 'button',
    'href' => null,
    'icon' => null,
    'loading' => false,
    'disabled' => false,
])

@php
$variantClasses = [
    'default' => 'bg-background text-foreground hover:bg-accent hover:text-accent-foreground border border-input',
    'primary' => 'bg-brand text-brand-foreground hover:bg-brand/90 shadow-sm',
    'secondary' => 'bg-secondary text-secondary-foreground hover:bg-secondary/80 shadow-sm',
    'ghost' => 'hover:bg-accent hover:text-accent-foreground',
    'outline' => 'border border-input bg-background hover:bg-accent hover:text-accent-foreground',
    'danger' => 'bg-destructive text-destructive-foreground hover:bg-destructive/90 shadow-sm',
    'link' => 'text-primary underline-offset-4 hover:underline',
];

$sizeClasses = [
    'sm' => 'h-8 px-3 text-xs gap-1.5',
    'default' => 'h-10 px-4 py-2 text-sm gap-2',
    'lg' => 'h-12 px-6 text-base gap-2.5',
    'icon' => 'size-10',
];

$baseClasses = 'inline-flex items-center justify-center rounded-lg font-medium transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 active:scale-[0.98]';
$classes = $baseClasses . ' ' . ($variantClasses[$variant] ?? $variantClasses['default']) . ' ' . ($sizeClasses[$size] ?? $sizeClasses['default']);

$heroiconMap = [
    'sparkles' => 'sparkles',
    'shield-check' => 'shield-check',
    'file-text' => 'document-text',
    'eye' => 'eye',
    'lightbulb' => 'light-bulb',
    'download' => 'arrow-down-tray',
    'star' => 'star',
    'check' => 'check',
    'check-circle' => 'check-circle',
    'x' => 'x-mark',
    'arrow-right' => 'arrow-right',
    'graduation-cap' => 'academic-cap',
    'briefcase' => 'briefcase',
    'zap' => 'bolt',
    'trophy' => 'trophy',
    'mail' => 'envelope',
    'globe' => 'globe-alt',
    'code-2' => 'code-bracket',
    'code' => 'code-bracket',
    'user' => 'user',
    'send' => 'paper-airplane',
    'trash-2' => 'trash',
    'trash' => 'trash',
    'pencil' => 'pencil',
    'plus' => 'plus',
    'folder' => 'folder',
    'clock' => 'clock',
    'arrow-path' => 'arrow-path',
    'refresh-cw' => 'arrow-path',
    'loader-2' => 'arrow-path',
    'chevron-up' => 'chevron-up',
    'chevron-down' => 'chevron-down',
    'phone' => 'phone',
    'map-pin' => 'map-pin',
    'users' => 'users',
    'heart' => 'heart',
    'lock' => 'lock-closed',
    'qr-code' => 'qr-code',
    'copy' => 'clipboard-document',
    'building-2' => 'building-office',
    'key' => 'key',
    'log-in' => 'arrow-right-end-on-rectangle',
    'log-out' => 'arrow-right-start-on-rectangle',
    'menu' => 'bars-2',
    'search' => 'magnifying-glass',
    'git-branch' => 'folder',
    'folder-git-2' => 'folder',
    'book-open' => 'book-open',
    'book-open-text' => 'book-open',
    'settings' => 'cog-6-tooth',
    'layout-grid' => 'squares-2x2',
    'external-link' => 'arrow-top-right-on-square',
    'x-circle' => 'x-circle',
    'eye-off' => 'eye-slash',
    'monitor' => 'computer-desktop',
    'moon' => 'moon',
    'sun' => 'sun',
];

$heroiconName = $heroiconMap[$icon] ?? $icon;
$iconComponent = $icon ? "heroicon-c-{$heroiconName}" : null;

$isDisabled = $disabled || $loading;
$spinner = '<svg class="size-4 animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>';
@endphp

@if($href)
    <a
        href="{{ $href }}"
        {{ $attributes->merge(['class' => $classes]) }}
        @if($isDisabled) disabled @endif
    >
        {!! $loading ? $spinner : '' !!}
        @if($iconComponent && !$loading)
            <x-dynamic-component :component="$iconComponent" class="size-4" />
        @endif
        {{ $slot }}
    </a>
@else
    <button
        type="{{ $type }}"
        {{ $attributes->merge(['class' => $classes]) }}
        @if($isDisabled) disabled @endif
    >
        {!! $loading ? $spinner : '' !!}
        @if($iconComponent && !$loading)
            <x-dynamic-component :component="$iconComponent" class="size-4" />
        @endif
        {{ $slot }}
    </button>
@endif
