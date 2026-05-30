<?php

namespace App\Jobs;

use App\Ai\Agents\InterviewEvaluatorAgent;
use App\Models\InterviewEvaluation;
use App\Models\InterviewSession;
use App\Models\User;
use App\Notifications\InterviewEvaluationCompletedNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

class EvaluateInterview implements ShouldQueue
{
    use Queueable;

    public $backoff = [30, 60, 120];

    public $tries = 3;

    public $timeout = 300;

    public function __construct(
        public int $userId,
        public int $sessionId,
    ) {}

    public function handle(): void
    {
        $session = InterviewSession::with(['messages', 'cv', 'user'])->find($this->sessionId);

        if (! $session) {
            Log::error('EvaluateInterview: Session not found', ['session_id' => $this->sessionId]);

            return;
        }

        $evaluation = $session->evaluation;

        if (! $evaluation) {
            Log::error('EvaluateInterview: Evaluation not found', ['session_id' => $this->sessionId]);

            return;
        }

        $evaluation->update(['status' => InterviewEvaluation::STATUS_PROCESSING]);

        try {
            $transcript = $session->messages()
                ->orderBy('sort_order')
                ->get()
                ->map(fn ($msg) => [
                    'role' => $msg->role,
                    'content' => $msg->content,
                ])
                ->all();

            if (empty($transcript)) {
                throw new \RuntimeException('No transcript messages found for this session.');
            }

            $agent = new InterviewEvaluatorAgent(
                $session->cv,
                $transcript,
                $session->job_description
            );

            try {
                $agentResponse = $agent->prompt('Evaluate the interview transcript.');

                // Agent no longer uses structured output, so we get plain text
                $raw = method_exists($agentResponse, 'text')
                    ? $agentResponse->text()
                    : (string) $agentResponse;

                $response = $this->parseResponse($raw);
            } catch (\Throwable $structuredError) {
                // If the agent call itself failed with a structured output error, extract raw text
                $raw = $structuredError->getMessage();

                if (str_starts_with($raw, 'Structured object could not be decoded. Received:')) {
                    $raw = substr($raw, strlen('Structured object could not be decoded. Received: '));
                }

                $response = $this->parseResponse($raw);

                if (empty($response)) {
                    throw $structuredError;
                }
            }

            $evaluationData = $this->buildEvaluationData($response);

            $evaluation->update([
                'status' => InterviewEvaluation::STATUS_COMPLETED,
                'overall_score' => $evaluationData['overall_score'],
                'grade' => $evaluationData['grade'],
                'summary' => $evaluationData['summary'],
                'criteria' => $evaluationData['criteria'],
                'strengths' => $evaluationData['strengths'],
                'improvements' => $evaluationData['improvements'],
            ]);

            $user = User::find($this->userId);

            if ($user) {
                $user->notify(new InterviewEvaluationCompletedNotification($evaluation));
            }
        } catch (\Throwable $e) {
            Log::error('EvaluateInterview: Evaluation failed', [
                'session_id' => $this->sessionId,
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            $evaluation->update([
                'status' => InterviewEvaluation::STATUS_FAILED,
                'error_message' => $e->getMessage(),
            ]);
        }
    }

    protected function buildEvaluationData(array $response): array
    {
        $criteriaKeys = [
            'communication_clarity' => 'Communication Clarity',
            'technical_depth' => 'Technical Depth',
            'confidence_composure' => 'Confidence & Composure',
            'star_method' => 'STAR Method',
            'relevance_role' => 'Relevance to Role',
            'specificity_examples' => 'Specificity of Examples',
        ];

        $criteria = [];
        foreach ($criteriaKeys as $key => $label) {
            $criteria[$key] = [
                'score' => (int) ($response["{$key}_score"] ?? 0),
                'reason' => (string) ($response["{$key}_reason"] ?? ''),
            ];
        }

        $strengths = [];
        if (isset($response['strengths'])) {
            if (is_array($response['strengths'])) {
                $strengths = $response['strengths'];
            } else {
                $strengths = array_filter(array_map('trim', explode('||', (string) $response['strengths'])));
            }
        }

        $improvements = [];
        if (isset($response['improvements'])) {
            if (is_array($response['improvements'])) {
                $improvements = $response['improvements'];
            } else {
                $improvements = array_filter(array_map('trim', explode('||', (string) $response['improvements'])));
            }
        }

        $overallScore = (int) ($response['overall_score'] ?? 0);
        if ($overallScore === 0 && ! empty($criteria)) {
            $total = array_sum(array_column($criteria, 'score'));
            $nonNaScores = array_filter(array_column($criteria, 'score'), fn ($s) => $s > 0);
            $overallScore = count($nonNaScores) > 0
                ? (int) round((array_sum($nonNaScores) / count($nonNaScores)) * 10)
                : 0;
        }

        return [
            'overall_score' => min(100, $overallScore),
            'grade' => (string) ($response['grade'] ?? 'F'),
            'summary' => (string) ($response['summary'] ?? ''),
            'criteria' => $criteria,
            'strengths' => array_values($strengths),
            'improvements' => array_values($improvements),
        ];
    }

    protected function parseResponse(string $raw): array
    {
        // Try JSON first
        $json = trim($raw);
        if (str_starts_with($json, '```')) {
            $json = preg_replace('/^```(?:json)?\s*\n?/', '', $json);
            $json = preg_replace('/\n?```\s*$/', '', $json);
            $json = trim($json);
        }

        $decoded = json_decode($json, true);
        if (is_array($decoded) && isset($decoded['grade'])) {
            return $decoded;
        }

        // Fallback to markdown parsing
        return $this->parseMarkdownEvaluation($raw);
    }

    protected function parseMarkdownEvaluation(string $raw): array
    {
        $response = [];

        // Extract grade: "## Overall Grade: **F**"
        if (preg_match('/Overall Grade:\s*\*{0,2}([A-F][+-]?)\*{0,2}/', $raw, $m)) {
            $response['grade'] = $m[1];
        }

        // Extract summary section
        if (preg_match('/## Summary\s*\n+(.*?)(?=\n##|\n---|\*\*Main|\Z)/s', $raw, $m)) {
            $response['summary'] = trim(preg_replace('/\*\*[^*]+\*\*:?\s*/', '', $m[1]));
        }

        // Extract scores from markdown table: | Criterion | Score | Notes |
        $criteriaMap = [
            'communication clarity' => 'communication_clarity',
            'technical depth' => 'technical_depth',
            'confidence' => 'confidence_composure',
            'composure' => 'confidence_composure',
            'star method' => 'star_method',
            'star' => 'star_method',
            'relevance' => 'relevance_role',
            'role relevance' => 'relevance_role',
            'specificity' => 'specificity_examples',
            'specificity of examples' => 'specificity_examples',
        ];

        if (preg_match_all('/\|\s*([^|]+?)\s*\|\s*(\d+|N\/A)\s*\|\s*([^|]+?)\s*\|/', $raw, $rows, PREG_SET_ORDER)) {
            foreach ($rows as $row) {
                $criterion = strtolower(trim($row[1]));
                $score = trim($row[2]);
                $reason = trim($row[3]);

                foreach ($criteriaMap as $keyword => $key) {
                    if (str_contains($criterion, $keyword)) {
                        $response["{$key}_score"] = $score === 'N/A' ? 0 : (int) $score;
                        $response["{$key}_reason"] = $reason;
                        break;
                    }
                }
            }
        }

        // Extract strengths from bullet points
        if (preg_match('/\*\*Main Strengths?\*\*:?\s*\n((?:\s*-\s*.+\n?)+)/', $raw, $m)) {
            $items = array_filter(array_map('trim', preg_split('/\n\s*-\s*/', trim($m[1]))));
            $response['strengths'] = implode('||', $items) ?: null;
        }

        // Extract improvements from bullet points
        if (preg_match('/\*\*Areas? for Improvement\*\*:?\s*\n((?:\s*[-*]\s*.+\n?)+)/', $raw, $m)) {
            $items = array_filter(array_map('trim', preg_split('/\n\s*[-*]\s*/', trim($m[1]))));
            $items = array_map(fn ($s) => preg_replace('/\*\*[^*]+\*\*:\s*/', '', $s), $items);
            $response['improvements'] = implode('||', array_filter($items)) ?: null;
        }

        return $response;
    }
}
