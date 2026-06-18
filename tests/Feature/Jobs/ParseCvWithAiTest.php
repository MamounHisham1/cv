<?php

use App\Ai\Agents\CvParser;
use App\Jobs\ParseCvWithAi;
use App\Models\Cv;
use App\Models\CvSkill;
use App\Models\User;
use App\Notifications\CvParsedNotification;
use App\Services\CvTextExtractor;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;

uses(RefreshDatabase::class);

function writeTempCvFile(string $contents = 'John Doe - Software Engineer'): array
{
    // Write under storage/app/private/temp/uploads so the job's finally
    // block (which calls Storage::delete on the relative path) actually
    // removes the file on disk.
    $relative = 'temp/uploads/test_'.uniqid().'.txt';
    $absolute = storage_path('app/private/'.$relative);
    @mkdir(dirname($absolute), 0777, true);
    file_put_contents($absolute, $contents);

    return [$absolute, 'txt'];
}

beforeEach(function () {
    Notification::fake();

    $this->user = User::factory()->create();
    $this->cv = Cv::factory()->create([
        'user_id' => $this->user->id,
        'title' => 'Importing...',
    ]);
});

it('extracts text, parses via AI, hydrates the CV, deducts credits, and notifies the user', function () {
    [$fullPath, $extension] = writeTempCvFile();

    // Stub the text extractor so we don't need pdftotext / tesseract on CI.
    app()->singleton(CvTextExtractor::class, fn () => new class extends CvTextExtractor
    {
        public function extractFromPath(string $path, string $extension, string $filename = 'unknown', int $size = 0): string
        {
            return 'Extracted CV text';
        }
    });

    // Stub the CvParser agent response. The fake expects a *list* of responses
    // (one per prompt), so wrap the structured payload in an outer array.
    CvParser::fake([[
        'first_name' => 'Jane',
        'last_name' => 'Doe',
        'email' => 'jane@example.com',
        'phone' => '+1234567890',
        'location' => 'Cairo, Egypt',
        'linkedin' => '',
        'github' => '',
        'website' => '',
        'title' => 'Backend Engineer',
        'summary' => 'A capable engineer.',
        'experiences' => [],
        'skills' => [['name' => 'PHP', 'category' => 'general', 'level' => 'advanced']],
        'educations' => [],
        'certifications' => [],
        'languages' => [],
        'projects' => [],
    ]]);

    $initialBalance = 50;
    $this->user->creditBalance()->create(['balance' => $initialBalance, 'plan' => 'pro']);

    (new ParseCvWithAi(
        userId: $this->user->id,
        filePath: $fullPath,
        extension: $extension,
        filename: 'jane-doe.txt',
        fileSize: 100,
        cvId: $this->cv->id,
    ))->handle();

    // CV was updated with parsed personal info + skills.
    expect($this->cv->fresh()->title)->toBe('Backend Engineer')
        ->and($this->cv->fresh()->skills)->toHaveCount(1)
        ->and(CvSkill::first()->name)->toBe('PHP');

    // Notification was sent.
    Notification::assertSentTo($this->user, CvParsedNotification::class);

    // Credits were deducted (at least the minimum charge).
    expect($this->user->fresh()->creditBalance->balance)->toBeLessThan($initialBalance);

    // Temp file was cleaned up by the `finally` block.
    expect(file_exists($fullPath))->toBeFalse();
});

it('marks the CV as "Import failed" when extraction yields no text', function () {
    [$fullPath, $extension] = writeTempCvFile('');

    app()->singleton(CvTextExtractor::class, fn () => new class extends CvTextExtractor
    {
        public function extractFromPath(string $path, string $extension, string $filename = 'unknown', int $size = 0): string
        {
            return '';
        }
    });

    (new ParseCvWithAi(
        userId: $this->user->id,
        filePath: $fullPath,
        extension: $extension,
        filename: 'empty.txt',
        fileSize: 0,
        cvId: $this->cv->id,
    ))->handle();

    expect($this->cv->fresh()->title)->toBe('Import failed')
        ->and(CvSkill::count())->toBe(0);
});

it('marks the CV as "Import failed" when the AI parser throws', function () {
    [$fullPath, $extension] = writeTempCvFile();

    app()->singleton(CvTextExtractor::class, fn () => new class extends CvTextExtractor
    {
        public function extractFromPath(string $path, string $extension, string $filename = 'unknown', int $size = 0): string
        {
            return 'Some text';
        }
    });

    // Force the parser to throw on every prompt.
    CvParser::fake(fn () => throw new RuntimeException('AI gateway down'));

    (new ParseCvWithAi(
        userId: $this->user->id,
        filePath: $fullPath,
        extension: $extension,
        filename: 'broken.txt',
        fileSize: 10,
        cvId: $this->cv->id,
    ))->handle();

    expect($this->cv->fresh()->title)->toBe('Import failed');
});
