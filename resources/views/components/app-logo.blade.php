@props([
    'sidebar' => false,
])

@if($sidebar)
    <a {{ $attributes->merge(['href' => $attributes->get('href', '/'), 'class' => 'flex items-center gap-2 px-4 py-4']) }}>
        <img src="{{ asset('storage/images/logo.png') }}" alt="{{ config('app.name') }}" class="h-12 w-auto" />
        <span class="font-semibold">{{ config('app.name', 'Laravel') }}</span>
    </a>
@else
    <a {{ $attributes->merge(['href' => $attributes->get('href', '/'), 'class' => 'flex items-center gap-2']) }}>
        <img src="{{ asset('storage/images/logo.png') }}" alt="{{ config('app.name') }}" class="h-12 w-auto" />
        <span class="font-semibold">{{ config('app.name', 'Laravel') }}</span>
    </a>
@endif
