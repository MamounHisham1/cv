<?php

namespace App\Jobs;

use App\Services\CvTextExtractor;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class ExtractCvText implements ShouldQueue
{
    use Queueable;

    public $backoff = [10, 30];

    public $tries = 2;

    public $timeout = 120;

    public function __construct(
        public string $filePath,
        public string $extension,
        public string $cacheKey,
        public string $filename = 'unknown',
        public int $fileSize = 0,
    ) {}

    public function handle(CvTextExtractor $extractor): void
    {
        try {
            $text = $extractor->extractFromPath($this->filePath, $this->extension, $this->filename, $this->fileSize);

            Cache::put($this->cacheKey, $text, now()->addMinutes(10));
            Cache::put($this->cacheKey.'_status', 'completed', now()->addMinutes(10));
        } catch (\Throwable $e) {
            Log::error('ExtractCvText: Extraction failed', [
                'file' => $this->filePath,
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            Cache::put($this->cacheKey, '', now()->addMinutes(10));
            Cache::put($this->cacheKey.'_status', 'failed', now()->addMinutes(10));
            Cache::put($this->cacheKey.'_error', $e->getMessage(), now()->addMinutes(10));
        }
    }
}
