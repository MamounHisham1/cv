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
    <div class="no-print fixed top-4 right-4 z-50 flex gap-2"
         x-data="{ open: false }" @click.outside="open = false">
        <a href="{{ route('cv.builder') }}" class="px-4 py-2 bg-gray-600 text-white rounded-lg shadow hover:bg-gray-700 flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Edit CV
        </a>
        <button onclick="window.print()" class="px-4 py-2 bg-gray-700 text-white rounded-lg shadow hover:bg-gray-800 flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
            Print
        </button>
        <div class="relative">
            <button @click="open = !open" class="px-4 py-2 bg-emerald-600 text-white rounded-lg shadow hover:bg-emerald-700 flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                Download
            </button>
            <div x-show="open" x-transition @keydown.escape.window="open = false"
                 class="absolute right-0 mt-2 w-44 overflow-hidden rounded-xl border border-gray-200 bg-white py-1 shadow-xl" style="display: none;">
                <a href="{{ route('cv.export', [$cv, 'pdf']) }}" class="flex items-center gap-2 px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                    PDF
                </a>
                <a href="{{ route('cv.export', [$cv, 'docx']) }}" class="flex items-center gap-2 px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                    Word (.docx)
                </a>
            </div>
        </div>
    </div>

    <script>
        // Downloads are server-rendered via /cv/{cv}/export/{format};
        // the only remaining client-side action here is window.print().
    </script>

    <!-- CV Content -->
    <div class="max-w-[210mm] mx-auto my-8 bg-white shadow-lg">
        @include('cv.templates.' . $cv->template_id, ['cv' => $cv])
    </div>
</body>
</html>
