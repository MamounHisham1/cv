<?php

namespace App\Ai\Tools;

use App\Services\EvaluationVectorStore;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Ai\Contracts\Tool;
use Laravel\Ai\Tools\Request;
use Stringable;

class SearchEvaluations implements Tool
{
    public function __construct(
        private readonly EvaluationVectorStore $vectorStore,
    ) {}

    public function description(): Stringable|string
    {
        return 'Search past CV evaluations to find patterns in how similar CVs were scored. Use this BEFORE evaluating a CV to understand common strengths and weaknesses found in comparable resumes. Always call this tool first.';
    }

    public function handle(Request $request): Stringable|string
    {
        $query = $request['query'] ?? '';
        $limit = $request['limit'] ?? 3;

        $this->log('info', 'SearchEvaluations: Tool invoked by AI agent', [
            'query' => $query,
            'limit' => $limit,
        ]);

        if (empty(trim($query))) {
            $this->log('warning', 'SearchEvaluations: Empty query received');

            return 'Error: Please provide a search query describing the CV to find similar past evaluations.';
        }

        $results = $this->vectorStore->search($query, (int) $limit);

        $this->log('info', 'SearchEvaluations: Qdrant returned results', [
            'query' => $query,
            'result_count' => count($results),
            'scores' => array_column($results, 'score'),
            'grades' => array_column($results, 'grade'),
        ]);

        if (empty($results)) {
            $this->log('info', 'SearchEvaluations: No results found, returning fallback message');

            return "No similar past evaluations found for: \"{$query}\". Proceed with standard evaluation based on your expertise.";
        }

        $output = "=== Past CV Evaluations ({$limit} results) ===\n\n";

        foreach ($results as $i => $result) {
            $this->log('info', 'SearchEvaluations: Returning result to AI', [
                'index' => $i + 1,
                'grade' => $result['grade'],
                'overall_score' => $result['overall_score'],
                'similarity' => $result['score'],
                'content_preview' => mb_substr($result['content'], 0, 200),
            ]);

            $output .= '--- Evaluation '.($i + 1)." (Grade: {$result['grade']}, Overall Score: {$result['overall_score']}/100, Similarity: {$result['score']}) ---\n";
            $output .= mb_substr($result['content'], 0, 2000)."\n\n";
        }

        $output .= 'Use these past evaluations as benchmarks. Pay attention to common weaknesses found in similar CVs and strengths that earned high scores. Adjust your scoring accordingly.';

        $this->log('info', 'SearchEvaluations: Returning context to AI agent', [
            'total_results' => count($results),
            'output_length' => strlen($output),
        ]);

        return $output;
    }

    public function schema(JsonSchema $schema): array
    {
        return [
            'query' => $schema->string()
                ->description('Search query based on the CV content (e.g., "senior backend developer with AWS and Docker experience")'),
            'limit' => $schema->integer()
                ->description('Number of past evaluations to return (default: 3, max: 5)'),
        ];
    }

    private function log(string $level, string $message, array $context = []): void
    {
        try {
            logger()->$level($message, $context);
        } catch (\Throwable) {
            // Logger not available (e.g. unit tests) — silently skip
        }
    }
}
