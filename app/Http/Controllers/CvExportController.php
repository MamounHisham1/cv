<?php

namespace App\Http\Controllers;

use App\Models\Cv;
use App\Models\CvVersion;
use App\Services\CvExportService;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class CvExportController extends Controller
{
    /**
     * Stream a server-rendered CV download. `{format}` is `pdf` or `docx`.
     * Authorization is enforced inline (owner only) to mirror the existing
     * /preview/{cv} closure rather than relying on route middleware policy
     * resolution. A version snapshot is captured (if content changed) so
     * the owner can revert to this exported state later.
     */
    public function __invoke(Request $request, Cv $cv, string $format): StreamedResponse
    {
        abort_unless($cv->user_id === $request->user()->id, 403);
        abort_unless(in_array($format, ['pdf', 'docx'], true), 404);

        CvVersion::snapshotIfChanged($cv, 'Export '.$format.' · '.$cv->updated_at?->toFormattedDateString());

        $service = app(CvExportService::class);

        $content = $format === 'pdf'
            ? $service->toPdf($cv)
            : $service->toDocx($cv);

        return response()->streamDownload(
            function () use ($content): void {
                echo $content;
            },
            $service->filename($cv, $format),
            [
                'Content-Type' => $format === 'pdf'
                    ? 'application/pdf'
                    : 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            ]
        );
    }
}
