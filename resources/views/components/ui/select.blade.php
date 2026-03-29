@props([
    'label' => null,
    'options' => [],
    'placeholder' => null,
    'disabled' => false,
    'required' => false,
    'id' => null,
])

@php
$selectId = $id ?? ($attributes->get('name') ? 'field-' . $attributes->get('name') : null);
$classes = 'flex h-10 w-full rounded-lg border border-input bg-background px-3 py-2 text-sm transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-1 disabled:cursor-not-allowed disabled:opacity-50 appearance-none bg-[url("data:image/svg+xml;charset=utf-8,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20width%3D%2224%22%20height%3D%2224%22%20viewBox%3D%220%200%2024%2024%22%20fill%3D%22none%22%20stroke%3D%22%23888%22%20stroke-width%3D%222%22%20stroke-linecap%3D%22round%22%20stroke-linejoin%3D%22round%22%3E%3Cpath%20d%3D%22m6%209%206%206%206-6%22%2F%3E%3C%2Fsvg%3E")] bg-[length:1rem] bg-[right_0.5rem_center] bg-no-repeat pr-8';
@endphp

@if($label)
    <label for="{{ $selectId }}" class="text-sm font-medium leading-none mb-1.5 block">
        {{ $label }}
        @if($required)
            <span class="text-destructive">*</span>
        @endif
    </label>
@endif

<select
    id="{{ $selectId }}"
    @if($required) required @endif
    @if($disabled) disabled @endif
    {{ $attributes->merge(['class' => $classes]) }}
>
    @if($placeholder)
        <option value="" disabled selected>{{ $placeholder }}</option>
    @endif
    @foreach($options as $value => $display)
        <option value="{{ $value }}">{{ $display }}</option>
    @endforeach
</select>
