@php
    // Inline the built Tailwind CSS + local Instrument Sans fonts as data
    // URIs so headless Chromium renders deterministically (no HTTP origin).
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
    <title>{{ $letter->title ?? 'Cover Letter' }}</title>
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

        .cv-export-content p { break-inside: avoid; page-break-inside: avoid; }

        .cv-export-page {
            width: 210mm;
            min-height: 297mm;
            background: #ffffff;
        }
    </style>
</head>
<body>
    <div class="cv-export-page">
        @include('cover-letter.templates.' . $letter->template_id, ['letter' => $letter])
    </div>
</body>
</html>
