<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen bg-zinc-950 text-zinc-100">
        @if (request()->routeIs('cv.builder', 'cv.edit', 'cv.evaluator', 'drafts'))
            <x-cv-builder-nav />
        @else
            <x-landing-navbar />
        @endif

        {{ $slot }}
    </body>
</html>
