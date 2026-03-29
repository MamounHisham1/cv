<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CV Preview - {{ $cv->title }}</title>
    @vite(['resources/css/app.css'])
    <style>
        /* Print styles */
        @media print {
            body { margin: 0; }
            .no-print { display: none !important; }
        }
    </style>
</head>
<body class="bg-gray-100">
    <!-- Preview Controls -->
    <div class="no-print fixed top-4 right-4 z-50 flex gap-2">
        <a href="{{ route('cv.builder') }}" class="px-4 py-2 bg-gray-600 text-white rounded-lg shadow hover:bg-gray-700 flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Edit CV
        </a>
        <button onclick="window.print()" class="px-4 py-2 bg-indigo-600 text-white rounded-lg shadow hover:bg-indigo-700 flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
            Print / Save as PDF
        </button>
    </div>

    <!-- CV Content -->
    <div class="max-w-[210mm] mx-auto my-8 bg-white shadow-lg">
        @include('cv.templates.' . $cv->template_id, ['cv' => $cv])
    </div>
</body>
</html>
