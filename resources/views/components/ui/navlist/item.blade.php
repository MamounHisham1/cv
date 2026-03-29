@props([
    'href' => null,
    'icon' => null,
    'current' => false,
])

@php
$heroiconMap = [
    'sparkles' => 'sparkles',
    'file-text' => 'document-text',
    'eye' => 'eye',
    'eye-off' => 'eye-slash',
    'search' => 'magnifying-glass',
    'log-in' => 'arrow-right-end-on-rectangle',
    'log-out' => 'arrow-right-start-on-rectangle',
    'settings' => 'cog-6-tooth',
    'menu' => 'bars-2',
    'briefcase' => 'briefcase',
    'layout-grid' => 'squares-2x2',
    'book-open' => 'book-open',
    'code-2' => 'code-bracket',
    'code' => 'code-bracket',
    'git-branch' => 'folder',
    'folder-git-2' => 'folder',
    'star' => 'star',
    'check' => 'check',
    'check-circle' => 'check-circle',
    'x' => 'x-mark',
    'arrow-right' => 'arrow-right',
    'download' => 'arrow-down-tray',
    'lightbulb' => 'light-bulb',
    'graduation-cap' => 'academic-cap',
    'zap' => 'bolt',
    'shield-check' => 'shield-check',
    'mail' => 'envelope',
    'globe' => 'globe-alt',
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
    'trophy' => 'trophy',
    'x-circle' => 'x-circle',
    'monitor' => 'computer-desktop',
    'moon' => 'moon',
    'sun' => 'sun',
    'external-link' => 'arrow-top-right-on-square',
];
$heroiconName = $heroiconMap[$icon] ?? $icon;
$component = 'heroicon-c-' . $heroiconName;
@endphp

<a
    href="{{ $href }}"
    {{ $attributes->merge(['class' => 'flex items-center gap-3 rounded-lg px-3 py-2 text-sm font-medium transition-colors hover:bg-accent hover:text-accent-foreground ' . ($current ? 'bg-accent text-accent-foreground' : 'text-muted-foreground')]) }}
    @if($current) aria-current="page" @endif
>
    @if($icon)
        <x-dynamic-component :component="$component" class="size-4" />
    @endif
    {{ $slot }}
</a>
