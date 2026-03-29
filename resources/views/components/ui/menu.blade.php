@props([
    'separator' => false,
    'as' => 'button',
    'disabled' => false,
])

@if($separator)
    <div class="-mx-1 my-1 h-px bg-border"></div>
@else
    <{{ $as }}
        {{ $attributes->merge(['class' => 'relative flex w-full cursor-pointer select-none items-center gap-2 rounded-md px-2 py-1.5 text-sm outline-none transition-colors hover:bg-accent hover:text-accent-foreground focus:bg-accent focus:text-accent-foreground']) }}
        @if($disabled) disabled @endif
    >
        {{ $slot }}
    </{{ $as }}>
@endif
