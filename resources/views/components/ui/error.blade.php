@props([
    'name' => null,
    'message' => null,
])

@php
$errorMessage = $message ?? null;
@endphp

@if($errorMessage)
    <p {{ $attributes->merge(['class' => 'text-sm text-destructive']) }}>
        {{ $errorMessage }}
    </p>
@endif
