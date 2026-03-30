@php
    $siteName = config('app.name', 'SeratyAI');
    $defaultTitle = $siteName . ' — AI-Powered CV Builder';
    $defaultDescription = 'Build professional, ATS-optimized CVs with AI. Create standout resumes that land interviews faster with SeratyAI.';
    $pageTitle = filled($title ?? null) ? $title . ' - ' . $siteName : $defaultTitle;
    $pageDescription = $description ?? $defaultDescription;
    $pageImage = asset('storage/images/logo.png');
    $canonicalUrl = url()->current();
@endphp

<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />

<title>{{ $pageTitle }}</title>
<meta name="description" content="{{ $pageDescription }}" />
<meta name="robots" content="index, follow" />
<link rel="canonical" href="{{ $canonicalUrl }}" />

{{-- Favicons --}}
<link rel="icon" href="/favicon.ico" sizes="any">
<link rel="icon" href="/favicon.svg" type="image/svg+xml">
<link rel="apple-touch-icon" href="/apple-touch-icon.png">
<link rel="manifest" href="/site.webmanifest">
<meta name="theme-color" content="#18181b" />
<meta name="msapplication-TileColor" content="#18181b" />
<meta name="msapplication-TileImage" content="/icons/mstile-150.png" />

{{-- Open Graph --}}
<meta property="og:type" content="website" />
<meta property="og:url" content="{{ $canonicalUrl }}" />
<meta property="og:title" content="{{ $pageTitle }}" />
<meta property="og:description" content="{{ $pageDescription }}" />
<meta property="og:image" content="{{ $pageImage }}" />
<meta property="og:site_name" content="{{ $siteName }}" />
<meta property="og:locale" content="{{ str_replace('_', '-', app()->getLocale()) }}" />

{{-- Twitter Card --}}
<meta name="twitter:card" content="summary_large_image" />
<meta name="twitter:title" content="{{ $pageTitle }}" />
<meta name="twitter:description" content="{{ $pageDescription }}" />
<meta name="twitter:image" content="{{ $pageImage }}" />

{{-- Preconnect for fonts --}}
<link rel="preconnect" href="https://fonts.bunny.net">
<link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

{{-- Structured Data --}}
<script type="application/ld+json">
{!! json_encode([
    '@context' => 'https://schema.org',
    '@type' => 'WebApplication',
    'name' => $siteName,
    'url' => url('/'),
    'description' => $defaultDescription,
    'applicationCategory' => 'BusinessApplication',
    'operatingSystem' => 'Web',
    'logo' => $pageImage,
    'offers' => [
        '@type' => 'Offer',
        'price' => '0',
        'priceCurrency' => 'USD',
    ],
], JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) !!}
</script>

@vite(['resources/css/app.css', 'resources/js/app.js'])
