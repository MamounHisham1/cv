@props([
    'type' => 'text',
    'label' => null,
    'viewable' => false,
    'placeholder' => null,
    'required' => false,
    'disabled' => false,
    'id' => null,
    'error' => null,
])

@php
$inputId = $id ?? ($attributes->get('name') ? 'field-' . $attributes->get('name') : null);
$hasError = !empty($error);
$baseClasses = 'flex h-10 w-full rounded-lg border bg-background px-3 py-2 text-sm transition-colors placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-1 disabled:cursor-not-allowed disabled:opacity-50';
$borderClass = $hasError ? 'border-destructive' : 'border-input';
$classes = $baseClasses . ' ' . $borderClass;
@endphp

@if($label)
    <label for="{{ $inputId }}" class="text-sm font-medium leading-none mb-1.5 block">
        {{ $label }}
        @if($required)
            <span class="text-destructive">*</span>
        @endif
    </label>
@endif

<div class="relative">
    <input
        type="{{ $type }}"
        id="{{ $inputId }}"
        @if($placeholder) placeholder="{{ $placeholder }}" @endif
        @if($required) required @endif
        @if($disabled) disabled @endif
        {{ $attributes->merge(['class' => $classes]) }}
    />
    @if($viewable && $type === 'password')
        <button
            type="button"
            x-data="{ show: false }"
            @click="show = !show"
            class="absolute right-2.5 top-1/2 -translate-y-1/2 text-muted-foreground hover:text-foreground transition-colors"
        >
            <x-heroicon-c-eye x-show="!show" class="size-4" />
            <x-heroicon-c-eye-slash x-show="show" class="size-4" />
        </button>
    @endif
</div>

@if($hasError)
    <p class="text-sm text-destructive mt-1.5">{{ $error }}</p>
@endif
