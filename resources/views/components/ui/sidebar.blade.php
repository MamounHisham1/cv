@props([
    'collapsible' => 'mobile',
    'sticky' => true,
])

@php
$stickyClass = $sticky ? 'sticky top-0' : '';
$containerClass = $stickyClass . ' flex h-full flex-col border-r border-border bg-background';
@endphp

<aside
    x-data="{ collapsed: false }"
    {{ $attributes->merge(['class' => $containerClass]) }}
    @class([ 'max-md:hidden' => $collapsible === 'desktop' && !$sticky])
>
    <div class="flex flex-1 flex-col overflow-y-auto overflow-x-hidden">
        {{ $slot }}
    </div>
</aside>
