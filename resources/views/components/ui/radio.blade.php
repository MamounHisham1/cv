@props([
    'value' => null,
    'label' => null,
    'name' => null,
    'checked' => null,
    'disabled' => false,
])

@php
$radioName = $name ?? $attributes->get('name');
$radioId = $radioName ? 'field-' . $radioName . '-' . ($value ?? str()->random(6)) : null;
@endphp

<div class="flex items-center gap-2">
    <input
        type="radio"
        name="{{ $radioName }}"
        value="{{ $value }}"
        id="{{ $radioId }}"
        @if($checked !== null) {{ $checked ? 'checked' : '' }} @endif
        @if($disabled) disabled @endif
        {{ $attributes->merge(['class' => 'h-4 w-4 border-input text-primary accent-primary focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50']) }}
    />
    @if($label)
        <label for="{{ $radioId }}" class="text-sm font-medium leading-none @if($disabled) cursor-not-allowed opacity-70 @endif">
            {{ $label }}
        </label>
    @endif
</div>
