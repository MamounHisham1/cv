@php
    // Browsershot renders headlessly with no HTTP origin, so external
    // stylesheets/fonts fail to load. Inline the built Tailwind CSS and
    // the local Instrument Sans woff2 files as data URIs to make the
    // render deterministic and offline-safe.
    $manifestPath = public_path('build/manifest.json');
    $css = '';
    if (file_exists($manifestPath)) {
        $manifest = json_decode((string) file_get_contents($manifestPath), true);
        $cssFile = $manifest['resources/css/app.css']['file'] ?? null;
        if ($cssFile) {
            $absolute = public_path('build/'.$cssFile);
            $css = file_exists($absolute) ? file_get_contents($absolute) : '';
        }
    }

    $fontBase = function (int $weight) {
        $file = resource_path("fonts/instrument-sans-latin-{$weight}.woff2");
        if (! file_exists($file)) {
            return '';
        }

        return 'data:font/woff2;base64,'.base64_encode(file_get_contents($file));
    };
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>{{ $cv->title ?? 'CV' }}</title>
    <style>
        @font-face {
            font-family: 'Instrument Sans';
            font-style: normal;
            font-weight: 400;
            font-display: block;
            src: url({{ $fontBase(400) }}) format('woff2');
        }
        @font-face {
            font-family: 'Instrument Sans';
            font-style: normal;
            font-weight: 500;
            font-display: block;
            src: url({{ $fontBase(500) }}) format('woff2');
        }
        @font-face {
            font-family: 'Instrument Sans';
            font-style: normal;
            font-weight: 600;
            font-display: block;
            src: url({{ $fontBase(600) }}) format('woff2');
        }

        {!! $css !!}

        @page {
            size: A4;
            margin: 0;
        }

        html, body {
            margin: 0;
            padding: 0;
            background: #ffffff;
            font-family: 'Instrument Sans', system-ui, sans-serif;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        /* Content flows naturally across page breaks at line/paragraph
           boundaries. Entries, sections, and headers are all allowed to
           split — only truly atomic units are protected. */
        .cv-export-content {
            /* Don't strand a single line at the top/bottom of a page. */
            orphans: 2;
            widows: 2;
        }

        /* A heading must never be the last thing on a page — keep it with
           the line of content that follows it. */
        .cv-export-content h1,
        .cv-export-content h2,
        .cv-export-content h3,
        .cv-export-content h4 {
            break-after: avoid;
            page-break-after: avoid;
        }

        /* Single-line atomic units that look broken if split. */
        .cv-export-content li,
        .cv-export-content tr {
            break-inside: avoid;
            page-break-inside: avoid;
        }

        .cv-export-page {
            width: 210mm;
            min-height: 297mm;
            background: #ffffff;
        }
    </style>
</head>
<body>
    <div class="cv-export-page cv-export-content">
        @include('cv.templates.' . $cv->template_id, ['cv' => $cv])
    </div>
</body>
</html>
