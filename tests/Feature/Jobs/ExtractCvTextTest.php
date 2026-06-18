<?php

use App\Jobs\ExtractCvText;
use App\Services\CvTextExtractor;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;

uses(RefreshDatabase::class);

it('writes the extracted text and a "completed" status under the cache key', function () {
    // Stub the extractor so the test does not depend on system binaries.
    app()->singleton(CvTextExtractor::class, fn () => new class extends CvTextExtractor
    {
        public function extractFromPath(string $path, string $extension, string $filename = 'unknown', int $size = 0): string
        {
            return 'Hello from the extracted file';
        }
    });

    $key = 'cv_extract_test_'.uniqid();

    // The job's handle() type-hints CvTextExtractor for DI — resolve it
    // from the container the same way the queue worker would.
    (new ExtractCvText(
        filePath: '/tmp/does-not-matter.txt',
        extension: 'txt',
        cacheKey: $key,
        filename: 'sample.txt',
        fileSize: 100,
    ))->handle(app(CvTextExtractor::class));

    expect(Cache::get($key))->toBe('Hello from the extracted file')
        ->and(Cache::get($key.'_status'))->toBe('completed');
});

it('records a "failed" status when the extractor throws', function () {
    app()->singleton(CvTextExtractor::class, fn () => new class extends CvTextExtractor
    {
        public function extractFromPath(string $path, string $extension, string $filename = 'unknown', int $size = 0): string
        {
            throw new RuntimeException('boom');
        }
    });

    $key = 'cv_extract_fail_'.uniqid();

    (new ExtractCvText(
        filePath: '/tmp/x.txt',
        extension: 'pdf',
        cacheKey: $key,
        filename: 'bad.pdf',
        fileSize: 5,
    ))->handle(app(CvTextExtractor::class));

    expect(Cache::get($key.'_status'))->toBe('failed')
        ->and(Cache::get($key.'_error'))->toBe('boom')
        ->and(Cache::get($key))->toBe('');
});
