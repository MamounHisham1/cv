<?php

namespace App\Jobs;

use App\Ai\Agents\CoverLetterAgent;
use App\Models\CoverLetter;
use App\Models\Cv;
use App\Models\User;
use App\Notifications\CoverLetterGeneratedNotification;
use App\Services\CreditManager;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

/**
 * Generates an AI cover-letter draft asynchronously. Mirrors
 * ProcessCvEvaluation: marks the CoverLetter row "generating", runs the
 * agent, stores the body + marks "generated", charges credits by real
 * token usage, and notifies the user.
 */
class ProcessCoverLetter implements ShouldQueue
{
    use Queueable;

    public $backoff = [30, 60, 120];

    public $tries = 3;

    public $timeout = 300;

    public function __construct(public int $coverLetterId) {}

    public function handle(): void
    {
        $letter = CoverLetter::find($this->coverLetterId);
        if (! $letter) {
            Log::error('ProcessCoverLetter: letter not found', ['cover_letter_id' => $this->coverLetterId]);

            return;
        }

        $cv = Cv::find($letter->cv_id);
        if (! $cv) {
            $letter->update([
                'status' => CoverLetter::STATUS_FAILED,
                'error_message' => 'Source CV not found.',
            ]);

            return;
        }

        $letter->update(['status' => CoverLetter::STATUS_GENERATING]);

        try {
            $cv->load(['experiences', 'educations', 'skills', 'certifications', 'projects', 'languages']);
            $prompt = $this->buildPrompt($cv->toText(), $letter->job_description);

            $response = (new CoverLetterAgent)->prompt($prompt);
            [$title, $body] = $this->splitTitleAndBody((string) $response);

            $update = [
                'status' => CoverLetter::STATUS_GENERATED,
                'body' => trim(preg_replace('/\R{3,}/', "\n\n", $body)),
            ];
            // Only override the title if the AI produced a valid short one.
            if ($title !== '') {
                $update['title'] = $title;
            }

            $letter->update($update);

            $this->charge($letter, $response->usage);

            $user = User::find($letter->user_id);
            if ($user) {
                $user->notify(new CoverLetterGeneratedNotification($letter));
            }
        } catch (\Throwable $e) {
            Log::error('ProcessCoverLetter failed', [
                'cover_letter_id' => $this->coverLetterId,
                'error' => $e->getMessage(),
            ]);
            $letter->update([
                'status' => CoverLetter::STATUS_FAILED,
                'error_message' => mb_substr($e->getMessage(), 0, 500),
            ]);
        }
    }

    /**
     * Split the agent output into a short title (first line) and the
     * letter body (everything after the first blank line). The title is
     * clamped to 3 words; if parsing fails we return an empty title and
     * treat the whole output as the body.
     *
     * @return array{0: string, 1: string} [title, body]
     */
    private function splitTitleAndBody(string $output): array
    {
        $output = trim($output);
        if ($output === '') {
            return ['', ''];
        }

        // First non-empty line = candidate title; rest = body.
        $lines = preg_split('/\r\n|\r|\n/', $output);
        $titleLine = '';
        $bodyStartIndex = 0;
        foreach ($lines as $i => $line) {
            if (trim($line) !== '') {
                $titleLine = trim($line);
                $bodyStartIndex = $i + 1;

                break;
            }
        }

        $body = trim(implode("\n", array_slice($lines, $bodyStartIndex)));

        // Strip quotes/punctuation, drop standalone punctuation tokens
        // (em/en dashes, hyphens the model uses as separators), then clamp
        // to 3 words. "Acme — Senior Engineer" → ["Acme","Senior","Engineer"].
        $titleLine = trim($titleLine, " \t\n\r\0\x0B\"'….,");
        $words = array_values(array_filter(
            preg_split('/\s+/', $titleLine),
            fn ($w) => $w !== '' && preg_match('/[\p{L}\p{N}]/u', $w)
        ));
        if (count($words) > 3) {
            $words = array_slice($words, 0, 3);
        }
        $titleLine = implode(' ', $words);

        // Heuristic: if the first line looks like prose (>= 4 words AND
        // ends with sentence punctuation), treat the whole output as body.
        $firstLine = trim($lines[$bodyStartIndex - 1] ?? '');
        $firstLineWords = array_values(array_filter(preg_split('/\s+/', $firstLine), fn ($w) => $w !== ''));
        if (count($firstLineWords) >= 4 && preg_match('/[.!?]$/', $firstLine)) {
            return ['', $output];
        }

        return [$titleLine, $body];
    }

    private function buildPrompt(string $cvText, ?string $jobDescription): string
    {
        $prompt = "=== CANDIDATE CV ===\n{$cvText}\n\n";

        if ($jobDescription && trim($jobDescription) !== '') {
            $prompt .= "=== TARGET JOB DESCRIPTION ===\n".trim($jobDescription)."\n\n";
        }

        $prompt .= 'Write the cover-letter body now (paragraphs only, no headers/salutation).';

        return $prompt;
    }

    /**
     * Charge based on real token usage, with SQLite-lock retry backoff
     * mirroring ProcessCvEvaluation.
     */
    private function charge(CoverLetter $letter, $usage): void
    {
        $user = User::find($letter->user_id);
        if (! $user) {
            return;
        }

        $creditManager = app(CreditManager::class);
        $credits = $creditManager->calculateFromUsage($usage, 'ai_cover_letter');

        $attempt = 0;
        while ($attempt < 3) {
            try {
                $creditManager->deduct($user, $credits, 'ai_cover_letter', $letter, [
                    'prompt_tokens' => $usage->promptTokens ?? 0,
                    'completion_tokens' => $usage->completionTokens ?? 0,
                ]);

                return;
            } catch (QueryException $e) {
                $attempt++;
                if (str_contains($e->getMessage(), 'database is locked') && $attempt < 3) {
                    usleep(100000 * $attempt);

                    continue;
                }
                throw $e;
            }
        }
    }
}
