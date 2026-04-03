<?php

namespace App\Jobs;

use App\Ai\Agents\CvEvaluatorAgent;
use App\Models\CvEvaluation;
use App\Models\User;
use App\Notifications\EvaluationCompletedNotification;
use App\Services\CreditManager;
use App\Services\EvaluationVectorStore;
use App\Services\ResumeVectorStore;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

class ProcessCvEvaluation implements ShouldQueue
{
    use Queueable;

    public $backoff = [30, 60, 120];

    public $tries = 3;

    public $timeout = 300;

    public function __construct(
        public int $userId,
        public string $cvText,
        public ?string $filename,
        public string $inputMode,
        public ?int $cvId = null,
        public ?int $evaluationId = null,
    ) {}

    public function handle(): void
    {
        if ($this->evaluationId) {
            $evaluation = CvEvaluation::find($this->evaluationId);
            if (! $evaluation) {
                Log::error('ProcessCvEvaluation: Evaluation not found', ['evaluation_id' => $this->evaluationId]);

                return;
            }
            $evaluation->update(['status' => 'processing']);
        } else {
            $evaluation = CvEvaluation::create([
                'user_id' => $this->userId,
                'cv_id' => $this->cvId,
                'filename' => $this->filename,
                'status' => 'processing',
                'cv_text' => $this->cvText,
            ]);
        }

        try {
            $ragContext = $this->gatherRagContext($this->cvText);
            $prompt = $this->buildEvaluationPrompt($this->cvText, $ragContext);

            $agent = new CvEvaluatorAgent;
            $response = $agent->prompt($prompt);

            $data = $response->toArray();

            if (isset($data['evaluation']) && is_array($data['evaluation'])) {
                $data = $data['evaluation'];
            }

            if (empty(array_filter($data))) {
                $rawText = trim((string) $response->text);
                $decoded = json_decode($rawText, true);
                if (json_last_error() !== JSON_ERROR_NONE) {
                    throw new \RuntimeException('AI returned invalid JSON: ' . json_last_error_msg());
                }
                $data = $decoded;
            }

            $criterionNameMap = [
                'Contact Information' => 'contact_information',
                '1. Contact Information' => 'contact_information',
                'Professional Summary' => 'professional_summary',
                '2. Professional Summary' => 'professional_summary',
                'Work Experience' => 'work_experience',
                '3. Work Experience' => 'work_experience',
                'Skills Section' => 'skills_section',
                '4. Skills Section' => 'skills_section',
                'Education' => 'education',
                '5. Education' => 'education',
                'ATS Compatibility' => 'ats_compatibility',
                '6. ATS Compatibility' => 'ats_compatibility',
                'Formatting and Readability' => 'formatting_readability',
                '7. Formatting and Readability' => 'formatting_readability',
                'Achievements and Impact' => 'achievements_impact',
                '8. Achievements and Impact' => 'achievements_impact',
                'Keyword Optimisation' => 'keyword_optimisation',
                '9. Keyword Optimisation' => 'keyword_optimisation',
                'Overall Completeness' => 'overall_completeness',
                '10. Overall Completeness' => 'overall_completeness',
            ];

            $criteria = [];

            if (isset($data[0]) && is_array($data[0]) && isset($data[0]['criterion'])) {
                foreach ($data as $item) {
                    $criterionName = $item['criterion'] ?? '';
                    $ourKey = $criterionNameMap[$criterionName] ?? null;

                    if ($ourKey) {
                        $criteria[$ourKey] = [
                            'score' => (int) ($item['score'] ?? 0),
                            'reason' => (string) ($item['reason'] ?? ''),
                        ];
                    }
                }
            } else {
                foreach ($criterionNameMap as $aiKey => $ourKey) {
                    if (isset($data[$aiKey]) && is_array($data[$aiKey])) {
                        $criteria[$ourKey] = [
                            'score' => (int) ($data[$aiKey]['score'] ?? 0),
                            'reason' => (string) ($data[$aiKey]['reason'] ?? ''),
                        ];
                    } elseif (isset($data["{$ourKey}_score"])) {
                        $criteria[$ourKey] = [
                            'score' => (int) ($data["{$ourKey}_score"] ?? 0),
                            'reason' => (string) ($data["{$ourKey}_reason"] ?? ''),
                        ];
                    }
                }
            }

            $allKeys = ['contact_information', 'professional_summary', 'work_experience', 'skills_section', 'education', 'ats_compatibility', 'formatting_readability', 'achievements_impact', 'keyword_optimisation', 'overall_completeness'];
            foreach ($allKeys as $key) {
                if (! isset($criteria[$key])) {
                    $criteria[$key] = ['score' => 0, 'reason' => ''];
                }
            }

            $strengths = [];
            if (isset($data['top_strengths'])) {
                if (is_array($data['top_strengths'])) {
                    $strengths = $data['top_strengths'];
                } else {
                    $strengths = array_filter(array_map('trim', explode('||', (string) $data['top_strengths'])));
                }
            }

            $improvements = [];
            if (isset($data['critical_improvements'])) {
                if (is_array($data['critical_improvements'])) {
                    $improvements = $data['critical_improvements'];
                } else {
                    $improvements = array_filter(array_map('trim', explode('||', (string) $data['critical_improvements'])));
                }
            }

            if (empty($strengths) && ! empty($criteria)) {
                $sortedCriteria = $criteria;
                arsort($sortedCriteria);
                $topThree = array_slice($sortedCriteria, 0, 3, true);
                foreach ($topThree as $key => $item) {
                    if ($item['score'] >= 8) {
                        $strengths[] = ucwords(str_replace('_', ' ', $key)) . ': ' . $item['reason'];
                    }
                }
            }

            if (empty($improvements) && ! empty($criteria)) {
                $sortedCriteria = $criteria;
                asort($sortedCriteria);
                $bottomThree = array_slice($sortedCriteria, 0, 3, true);
                foreach ($bottomThree as $key => $item) {
                    if ($item['score'] <= 7) {
                        $improvements[] = ucwords(str_replace('_', ' ', $key)) . ': ' . $item['reason'];
                    }
                }
            }

            $overallScore = (int) ($data['overall_score'] ?? 0);
            if ($overallScore === 0 && ! empty($criteria)) {
                $total = array_sum(array_column($criteria, 'score'));
                $overallScore = (int) round(($total / count($criteria)) * 10);
            }

            $grade = (string) ($data['grade'] ?? '');
            if (empty($grade) || $grade === 'N/A') {
                $grade = match (true) {
                    $overallScore >= 90 => 'A+',
                    $overallScore >= 85 => 'A',
                    $overallScore >= 80 => 'B+',
                    $overallScore >= 75 => 'B',
                    $overallScore >= 70 => 'C+',
                    $overallScore >= 65 => 'C',
                    $overallScore >= 50 => 'D',
                    default => 'F',
                };
            }

            $result = [
                'overall_score' => $overallScore,
                'grade' => $grade,
                'summary' => (string) ($data['summary'] ?? 'AI evaluation completed.'),
                'criteria' => $criteria,
                'top_strengths' => $strengths,
                'critical_improvements' => $improvements,
            ];

            $evaluation->update([
                'status' => 'completed',
                'overall_score' => $result['overall_score'],
                'grade' => $result['grade'],
                'summary' => $result['summary'],
                'criteria' => $result['criteria'],
                'top_strengths' => $result['top_strengths'],
                'critical_improvements' => $result['critical_improvements'],
            ]);

            $user = User::find($this->userId);
            if ($user) {
                $creditManager = app(CreditManager::class);
                $credits = $creditManager->calculateFromUsage($response->usage, 'ai_evaluation');

                // Retry deduction with backoff to handle SQLite locking
                $attempt = 0;
                $maxAttempts = 3;
                $deducted = false;

                while ($attempt < $maxAttempts && ! $deducted) {
                    try {
                        $creditManager->deduct(
                            $user,
                            $credits,
                            'ai_evaluation',
                            $evaluation,
                            [
                                'prompt_tokens' => $response->usage->promptTokens,
                                'completion_tokens' => $response->usage->completionTokens,
                                'model' => 'mistral-large-3',
                            ]
                        );
                        $deducted = true;
                    } catch (QueryException $e) {
                        $attempt++;
                        if (str_contains($e->getMessage(), 'database is locked') && $attempt < $maxAttempts) {
                            usleep(100000 * $attempt); // 100ms, 200ms, 300ms backoff
                        } else {
                            throw $e;
                        }
                    }
                }
            }

            try {
                $vectorStore = app(EvaluationVectorStore::class);
                $vectorStore->ensureCollectionExists();
                $embedding = $vectorStore->generateEmbedding($this->cvText);
                $vectorStore->store(
                    "eval_{$evaluation->id}",
                    $this->userId,
                    $result['grade'],
                    $result['overall_score'],
                    $this->cvText,
                    $embedding,
                );
            } catch (\Throwable $e) {
                Log::warning('ProcessCvEvaluation: Failed to store evaluation in Qdrant', [
                    'message' => $e->getMessage(),
                ]);
            }

            if ($user) {
                $user->notify(new EvaluationCompletedNotification($evaluation));
            }
        } catch (\Throwable $e) {
            Log::error('ProcessCvEvaluation: Evaluation failed', [
                'evaluation_id' => $evaluation->id,
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            $evaluation->update([
                'status' => 'failed',
                'error_message' => $e->getMessage(),
            ]);
        }
    }

    private function gatherRagContext(string $cvText): array
    {
        $resumes = [];
        $evaluations = [];

        $searchQuery = mb_substr($cvText, 0, 1500);

        try {
            $embedding = app(EvaluationVectorStore::class)->generateEmbedding($searchQuery);
        } catch (\Throwable $e) {
            Log::warning('ProcessCvEvaluation: RAG — embedding generation failed', [
                'message' => $e->getMessage(),
            ]);

            return compact('resumes', 'evaluations');
        }

        try {
            $resumeStore = app(ResumeVectorStore::class);
            $resumes = $resumeStore->searchByEmbedding($embedding, 3);
        } catch (\Throwable $e) {
            Log::warning('ProcessCvEvaluation: RAG — resume search failed', [
                'message' => $e->getMessage(),
            ]);
        }

        try {
            $evaluationStore = app(EvaluationVectorStore::class);
            $evaluations = $evaluationStore->searchByEmbedding($embedding, 3);
        } catch (\Throwable $e) {
            Log::warning('ProcessCvEvaluation: RAG — evaluation search failed', [
                'message' => $e->getMessage(),
            ]);
        }

        return compact('resumes', 'evaluations');
    }

    private function buildEvaluationPrompt(string $cvText, array $ragContext): string
    {
        $prompt = '';

        if (! empty($ragContext['resumes'])) {
            $prompt .= '=== REFERENCE RESUME SAMPLES (' . count($ragContext['resumes']) . " results from 17,000+ database) ===\n\n";

            foreach ($ragContext['resumes'] as $i => $resume) {
                $prompt .= '--- Sample ' . ($i + 1) . " (Role: {$resume['role']}, Source: {$resume['source']}, Relevance: {$resume['score']}) ---\n";
                $prompt .= mb_substr($resume['content'], 0, 2000) . "\n\n";
            }

            $prompt .= "Use these real-world resume samples as benchmarks. Compare the CV against the strongest samples found.\n\n";
        }

        if (! empty($ragContext['evaluations'])) {
            $prompt .= '=== PAST CV EVALUATIONS (' . count($ragContext['evaluations']) . " results) ===\n\n";

            foreach ($ragContext['evaluations'] as $i => $eval) {
                $prompt .= '--- Evaluation ' . ($i + 1) . " (Grade: {$eval['grade']}, Overall Score: {$eval['overall_score']}/100, Similarity: {$eval['score']}) ---\n";
                $prompt .= mb_substr($eval['content'], 0, 2000) . "\n\n";
            }

            $prompt .= "Use these past evaluations as benchmarks. Pay attention to common weaknesses found in similar CVs and strengths that earned high scores. Adjust your scoring accordingly.\n\n";
        }

        $prompt .= "=== CV TO EVALUATE ===\n\n{$cvText}";

        return $prompt;
    }
}
