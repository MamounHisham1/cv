@props([])

<nav
    {{ $attributes->merge(['class' => 'flex items-center gap-1']) }}
    role="navigation"
>
    {{ $slot }}
</nav>
