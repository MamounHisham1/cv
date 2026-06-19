<?php

namespace App\Jobs;

use App\Ai\Agents\JobMatchAgent;
use App\Models\Cv;
use App\Models\CvJobMatch;
use App\Models\User;
use App\Services\CreditManager;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

class ProcessJobMatch implements ShouldQueue
{
    use Queueable;

    public $backoff = [30, 60, 120];

    public $tries = 3;

    public $timeout = 300;

    public function __construct(public int $jobMatchId) {}

    public function handle(): void
    {
        $match = CvJobMatch::find($this->jobMatchId);
        if (! $match) {
            Log::error('ProcessJobMatch: match not found', ['job_match_id' => $this->jobMatchId]);

            return;
        }

        $cv = Cv::find($match->cv_id);
        if (! $cv) {
            $match->update(['status' => CvJobMatch::STATUS_FAILED, 'error_message' => 'CV not found.']);

            return;
        }

        $match->update(['status' => CvJobMatch::STATUS_PROCESSING]);

        try {
            $cvText = $cv->toText();
            $prompt = $this->buildPrompt($cvText, $match->job_description, $match->job_title);

            $response = (new JobMatchAgent)->prompt($prompt);

            $result = $this->normalize($response->toArray(), (string) $response);

            $match->update([
                'status' => CvJobMatch::STATUS_COMPLETED,
                'compatibility_score' => $result['compatibility_score'],
                'grade' => $result['grade'],
                'summary' => $result['summary'],
                'matched_keywords' => $result['matched_keywords'],
                'missing_keywords' => $result['missing_keywords'],
                'gap_analysis' => $result['gap_analysis'],
                'suggestions' => $result['suggestions'],
            ]);

            $this->charge($match, $response->usage);
        } catch (\Throwable $e) {
            Log::error('ProcessJobMatch failed', [
                'job_match_id' => $this->jobMatchId,
                'error' => $e->getMessage(),
            ]);
            $match->update([
                'status' => CvJobMatch::STATUS_FAILED,
                'error_message' => mb_substr($e->getMessage(), 0, 500),
            ]);
        }
    }

    private function buildPrompt(string $cvText, string $jobDescription, ?string $jobTitle): string
    {
        $header = $jobTitle ? "Target role: {$jobTitle}" : 'Target role: (not specified)';

        return <<<PROMPT
{$header}

=== CANDIDATE CV ===
{$cvText}

=== TARGET JOB DESCRIPTION ===
{$jobDescription}

Produce the structured compatibility report now.
PROMPT;
    }

    /**
     * Map the flat structured-output fields onto the model's columns.
     * Pipe-delimited list strings are split into arrays.
     *
     * @param  array<string, mixed>  $json
     * @return array<string, mixed>
     */
    private function normalize(array $json, string $fallbackText): array
    {
        $split = static function (mixed $value): array {
            if (is_array($value)) {
                return $value;
            }
            if (! is_string($value)) {
                return [];
            }

            return array_values(array_filter(array_map('trim', explode('||', $value)), fn ($v) => $v !== ''));
        };

        $score = isset($json['compatibility_score']) ? max(0, min(100, (int) $json['compatibility_score'])) : null;

        return [
            'compatibility_score' => $score,
            'grade' => $json['grade'] ?? $this->gradeFromScore($score),
            'summary' => $json['summary'] ?? null,
            'matched_keywords' => $split($json['matched_keywords'] ?? null),
            'missing_keywords' => $split($json['missing_keywords'] ?? null),
            'gap_analysis' => $split($json['gap_analysis'] ?? null),
            'suggestions' => $split($json['suggestions'] ?? null),
        ];
    }

    private function gradeFromScore(?int $score): string
    {
        return match (true) {
            $score === null => '?',
            $score >= 80 => 'A',
            $score >= 60 => 'B',
            $score >= 40 => 'C',
            $score >= 20 => 'D',
            default => 'F',
        };
    }

    /**
     * Charge based on real token usage, with SQLite-lock retry backoff
     * mirroring ProcessCvEvaluation.
     */
    private function charge(CvJobMatch $match, $usage): void
    {
        $user = User::find($match->user_id);
        if (! $user) {
            return;
        }

        $creditManager = app(CreditManager::class);
        $credits = $creditManager->calculateFromUsage($usage, 'ai_jd_match');

        $attempt = 0;
        while ($attempt < 3) {
            try {
                $creditManager->deduct($user, $credits, 'ai_jd_match', $match, [
                    'prompt_tokens' => $usage->promptTokens ?? 0,
                    'completion_tokens' => $usage->completionTokens ?? 0,
                    'model' => config('services.ollama.model'),
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
