@props([
    'ariaLabel' => 'Navigation',
])

<nav
    {{ $attributes->merge(['class' => 'flex flex-col gap-1']) }}
    aria-label="{{ $ariaLabel }}"
>
    {{ $slot }}
</nav>
