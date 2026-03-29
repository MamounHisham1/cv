@props([
    'label' => null,
    'placeholder' => null,
    'required' => false,
    'disabled' => false,
    'id' => null,
    'error' => null,
    'rows' => 3,
])

@php
$inputId = $id ?? ($attributes->get('name') ? 'field-' . $attributes->get('name') : null);
$hasError = !empty($error);
$baseClasses = 'flex min-h-[80px] w-full rounded-lg border bg-background px-3 py-2 text-sm transition-colors placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-1 disabled:cursor-not-allowed disabled:opacity-50';
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

<textarea
    id="{{ $inputId }}"
    rows="{{ $rows }}"
    @if($placeholder) placeholder="{{ $placeholder }}" @endif
    @if($required) required @endif
    @if($disabled) disabled @endif
    {{ $attributes->merge(['class' => $classes]) }}
>{{ $slot }}</textarea>

@if($hasError)
    <p class="text-sm text-destructive mt-1.5">{{ $error }}</p>
@endif
