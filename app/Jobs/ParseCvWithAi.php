<?php

namespace App\Jobs;

use App\Ai\Agents\CvParser;
use App\Models\Cv;
use App\Notifications\CvParsedNotification;
use App\Services\CreditManager;
use App\Services\CvDataHydrator;
use App\Services\CvTextExtractor;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ParseCvWithAi implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable;

    public $backoff = [10, 30];

    public $tries = 2;

    public $timeout = 300;

    public function __construct(
        public int $userId,
        public string $filePath,
        public string $extension,
        public string $filename,
        public int $fileSize,
        public int $cvId,
    ) {}

    public function handle(): void
    {
        try {
            $text = app(CvTextExtractor::class)->extractFromPath(
                $this->filePath,
                $this->extension,
                $this->filename,
                $this->fileSize,
            );

            if (empty(trim($text))) {
                $this->failImport('Could not extract text from the file.');

                return;
            }

            $agent = new CvParser;
            $response = $agent->prompt("Extract all data from this CV into the exact schema fields (first_name, last_name, email, phone, location, linkedin, github, website, title, summary, experiences, skills, educations, certifications, projects, languages). Return ONLY valid JSON with these exact keys:\n\n{$text}");

            $data = $response->structured;
            $data = app(CvDataHydrator::class)->normalize($data);

            $cv = Cv::find($this->cvId);

            if (! $cv) {
                $this->failImport('CV not found.');

                return;
            }

            $personalInfo = [
                'first_name' => $data['first_name'] ?? '',
                'last_name' => $data['last_name'] ?? '',
                'email' => $data['email'] ?? $cv->personal_info['email'] ?? '',
                'phone' => $data['phone'] ?? '',
                'location' => $data['location'] ?? '',
                'linkedin' => $data['linkedin'] ?? '',
                'github' => $data['github'] ?? '',
                'website' => $data['website'] ?? '',
            ];

            $cv->update([
                'title' => $data['title'] ?? 'Imported CV',
                'personal_info' => $personalInfo,
                'summary' => $data['summary'] ?? '',
            ]);

            $cv->experiences()->delete();
            $cv->skills()->delete();
            $cv->educations()->delete();
            $cv->certifications()->delete();
            $cv->projects()->delete();
            $cv->languages()->delete();

            app(CvDataHydrator::class)->import($cv, $data);

            Log::info('ParseCvWithAi: Import completed', ['cv_id' => $cv->id, 'title' => $cv->title]);

            $user = $cv->user;
            if ($user) {
                $user->notify(new CvParsedNotification($cv->fresh()));
                $creditManager = app(CreditManager::class);
                $credits = $creditManager->calculateFromUsage($response->usage, 'ai_parse');

                // Retry deduction with backoff to handle SQLite locking
                $attempt = 0;
                $maxAttempts = 3;
                $deducted = false;

                while ($attempt < $maxAttempts && ! $deducted) {
                    try {
                        $creditManager->deduct($user, $credits, 'ai_parse', $cv, [
                            'prompt_tokens' => $response->usage->promptTokens,
                            'completion_tokens' => $response->usage->completionTokens,
                        ]);
                        $deducted = true;
                    } catch (QueryException $e) {
                        $attempt++;
                        if (str_contains($e->getMessage(), 'database is locked') && $attempt < $maxAttempts) {
                            usleep(100000 * $attempt);
                        } else {
                            throw $e;
                        }
                    }
                }
            }
        } catch (\Throwable $e) {
            Log::error('ParseCvWithAi: Import failed', [
                'cv_id' => $this->cvId,
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            $this->failImport($e->getMessage());
        } finally {
            $relativePath = str_replace(storage_path('app/private/'), '', $this->filePath);
            Storage::delete($relativePath);
        }
    }

    private function failImport(string $message): void
    {
        $cv = Cv::find($this->cvId);
        if ($cv) {
            $cv->update(['title' => 'Import failed']);
        }

        Log::warning('ParseCvWithAi: Import failed', ['cv_id' => $this->cvId, 'message' => $message]);
    }
}
