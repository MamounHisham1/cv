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

        if (empty(trim($query))) {
            return 'Error: Please provide a search query describing the CV to find similar past evaluations.';
        }

        $results = $this->vectorStore->search($query, (int) $limit);

        if (empty($results)) {
            return "No similar past evaluations found for: \"{$query}\". Proceed with standard evaluation based on your expertise.";
        }

        $output = "=== Past CV Evaluations ({$limit} results) ===\n\n";

        foreach ($results as $i => $result) {
            $output .= '--- Evaluation '.($i + 1)." (Grade: {$result['grade']}, Overall Score: {$result['overall_score']}/100, Similarity: {$result['score']}) ---\n";
            $output .= mb_substr($result['content'], 0, 2000)."\n\n";
        }

        $output .= 'Use these past evaluations as benchmarks. Pay attention to common weaknesses found in similar CVs and strengths that earned high scores. Adjust your scoring accordingly.';

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
}
