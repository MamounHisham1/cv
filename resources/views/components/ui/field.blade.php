@props([
    'label' => null,
    'description' => null,
    'error' => null,
    'name' => null,
])

@php
$fieldId = $attributes->get('id') ?? ($name ? 'field-' . $name : null);
@endphp

<div class="grid gap-2">
    @if($label)
        <x-ui::label for="{{ $fieldId }}">
            {{ $label }}
        </x-ui::label>
    @endif

    {{ $slot }}

    @if($description)
        <x-ui::description>{{ $description }}</x-ui::description>
    @endif

    @if($error)
        <x-ui::error :message="$error" />
    @endif
</div>
