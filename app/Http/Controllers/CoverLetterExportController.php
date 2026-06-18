<?php

namespace App\Http\Controllers;

use App\Models\CoverLetter;
use App\Services\CoverLetterExportService;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class CoverLetterExportController extends Controller
{
    /**
     * Stream a server-rendered cover-letter download (`pdf` or `docx`).
     * Owner-only — mirrors CvExportController's inline auth check.
     */
    public function __invoke(Request $request, CoverLetter $letter, string $format): StreamedResponse
    {
        abort_unless($letter->user_id === $request->user()->id, 403);
        abort_unless(in_array($format, ['pdf', 'docx'], true), 404);

        $service = app(CoverLetterExportService::class);

        $content = $format === 'pdf'
            ? $service->toPdf($letter)
            : $service->toDocx($letter);

        return response()->streamDownload(
            function () use ($content): void {
                echo $content;
            },
            $service->filename($letter, $format),
            [
                'Content-Type' => $format === 'pdf'
                    ? 'application/pdf'
                    : 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            ]
        );
    }
}
