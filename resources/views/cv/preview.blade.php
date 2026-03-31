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
        <button onclick="downloadPDF()" class="px-4 py-2 bg-emerald-600 text-white rounded-lg shadow hover:bg-emerald-700 flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
            Download PDF
        </button>
    </div>

    <script>
        function downloadPDF() {
            const controls = document.querySelector('.no-print');
            controls.style.display = 'none';

            const fileName = '{{ str_replace(' ', '_', $cv->title ?? 'cv') }}.pdf';
            document.title = fileName.replace('.pdf', '');

            window.print();

            controls.style.display = '';
        }

        // Auto-trigger download when opened with ?download=1
        if (new URLSearchParams(window.location.search).has('download')) {
            window.addEventListener('load', () => {
                setTimeout(downloadPDF, 300);
            });
        }
    </script>

    <!-- CV Content -->
    <div class="max-w-[210mm] mx-auto my-8 bg-white shadow-lg">
        @include('cv.templates.' . $cv->template_id, ['cv' => $cv])
    </div>
</body>
</html>
