@props([
    'label' => null,
    'checked' => null,
    'disabled' => false,
    'id' => null,
])

@php
$checkboxId = $id ?? ($attributes->get('name') ? 'field-' . $attributes->get('name') : null);
@endphp

<div class="flex items-center gap-2">
    <input
        type="checkbox"
        id="{{ $checkboxId }}"
        @if($checked !== null) {{ $checked ? 'checked' : '' }} @endif
        @if($disabled) disabled @endif
        {{ $attributes->merge(['class' => 'h-4 w-4 rounded border-input text-primary accent-primary focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50']) }}
    />
    @if($label)
        <label for="{{ $checkboxId }}" class="text-sm font-medium leading-none @if($disabled) cursor-not-allowed opacity-70 @endif">
            {{ $label }}
        </label>
    @endif
</div>
