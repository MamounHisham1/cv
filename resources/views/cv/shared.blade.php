<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $cv->title ?? $cv->full_name }} — Shared CV</title>
    @vite(['resources/css/app.css'])
    <style>
        @media print {
            body { margin: 0; }
            .no-print { display: none !important; }
        }
    </style>
</head>
<body class="bg-gray-100"
      x-data="{ open: false }" @click.outside="open = false">
    <!-- Public controls -->
    <div class="no-print fixed top-4 right-4 z-50 flex gap-2">
        <div class="relative">
            <button @click="open = !open" class="px-4 py-2 bg-emerald-600 text-white rounded-lg shadow hover:bg-emerald-700 flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                Download
            </button>
            <div x-show="open" x-transition @keydown.escape.window="open = false"
                 class="absolute right-0 mt-2 w-44 overflow-hidden rounded-xl border border-gray-200 bg-white py-1 shadow-xl" style="display: none;">
                <a href="{{ route('cv.export.public', [$cv, 'pdf']) }}" class="flex items-center gap-2 px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">PDF</a>
                <a href="{{ route('cv.export.public', [$cv, 'docx']) }}" class="flex items-center gap-2 px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Word (.docx)</a>
            </div>
        </div>
    </div>

    <!-- CV Content -->
    <div class="max-w-[210mm] mx-auto my-8 bg-white shadow-lg">
        @include('cv.templates.' . $cv->template_id, ['cv' => $cv])
    </div>
</body>
</html>
