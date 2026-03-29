@props([
    'name' => null,
    'show' => false,
    'focusable' => true,
])

@php
$dialogId = 'dialog-' . ($name ?? str()->random(8));
$baseClasses = 'fixed inset-0 z-50 flex items-center justify-center';
@endphp

<div
    x-data="{ open: @js($show) }"
    x-show="open"
    x-transition:enter="transition ease-out duration-200"
    x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100"
    x-transition:leave="transition ease-in duration-150"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
    x-on:open-dialog-{{ $name ?? 'default' }}.window="open = true"
    x-on:close-dialog-{{ $name ?? 'default' }}.window="open = false"
    x-on:keydown.escape.window="open = false; $dispatch('close', { name: '{{ $name ?? 'default' }}' })"
    class="{{ $baseClasses }}"
    {{ $attributes->merge(['class' => '']) }}
    role="dialog"
    aria-modal="true"
    aria-labelledby="{{ $dialogId }}-title"
>
    <div
        class="fixed inset-0 bg-black/80"
        x-on:click="open = false; $dispatch('close', { name: '{{ $name ?? 'default' }}' })"
    ></div>

    <div
        class="relative z-50 w-full max-w-lg rounded-xl border border-border bg-background p-6 shadow-lg"
        x-show="open"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
        x-on:click.stop
    >
        {{ $slot }}
    </div>
</div>
