<?php

namespace App\Ai\Tools;

use App\Services\ResumeVectorStore;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Ai\Contracts\Tool;
use Laravel\Ai\Tools\Request;
use Stringable;

class SearchResumes implements Tool
{
    public function __construct(
        private readonly ResumeVectorStore $vectorStore,
    ) {}

    public function description(): Stringable|string
    {
        return 'Search a database of 17,000+ real resume samples across 45+ job roles to find examples relevant to the user\'s career field. Includes resumes with hiring decisions (select/reject) and reasons. Use this to provide real-world resume writing examples, skill keywords, and industry-specific language.';
    }

    public function handle(Request $request): Stringable|string
    {
        $query = $request['query'] ?? '';
        $role = $request['role'] ?? null;
        $source = $request['source'] ?? null;
        $limit = $request['limit'] ?? 3;

        $this->log('info', 'SearchResumes: Tool invoked by AI agent', [
            'query' => $query,
            'role' => $role,
            'source' => $source,
            'limit' => $limit,
        ]);

        if (empty(trim($query))) {
            $this->log('warning', 'SearchResumes: Empty query received');

            return 'Error: Please provide a search query describing what kind of resume examples you want to find.';
        }

        $results = $this->vectorStore->search($query, (int) $limit, $role, $source);

        $this->log('info', 'SearchResumes: Qdrant returned results', [
            'query' => $query,
            'result_count' => count($results),
            'scores' => array_column($results, 'score'),
            'roles' => array_column($results, 'role'),
        ]);

        if (empty($results)) {
            $this->log('info', 'SearchResumes: No results found, returning fallback message');

            return "No similar resume samples found for: \"{$query}\". Try a broader search or different keywords.";
        }

        $output = "=== Resume Samples Found ({$limit} results) ===\n\n";

        foreach ($results as $i => $result) {
            $this->log('info', 'SearchResumes: Returning result to AI', [
                'index' => $i + 1,
                'role' => $result['role'],
                'source' => $result['source'],
                'relevance' => $result['score'],
                'content_preview' => mb_substr($result['content'], 0, 200),
            ]);

            $output .= '--- Sample '.($i + 1)." (Role: {$result['role']}, Source: {$result['source']}, Relevance: {$result['score']}) ---\n";
            $output .= mb_substr($result['content'], 0, 2000)."\n\n";
        }

        $output .= 'Use these real-world resume examples to provide specific, industry-relevant advice to the user. Extract common skills, keywords, phrasing patterns, and section structures that would strengthen their CV.';

        $this->log('info', 'SearchResumes: Returning context to AI agent', [
            'total_results' => count($results),
            'output_length' => strlen($output),
        ]);

        return $output;
    }

    public function schema(JsonSchema $schema): array
    {
        return [
            'query' => $schema->string()
                ->description('Search query describing the type of resume examples to find (e.g., "senior software engineer with AWS experience", "marketing manager resume")'),
            'role' => $schema->string()
                ->description('Optional: Filter by job role (e.g., "Software Engineer", "Data Scientist", "HR Manager")'),
            'source' => $schema->string()
                ->description('Optional: Filter by dataset source. Options: resume_csv (2,484 resumes, 24 categories), master_jsonl (4,817 structured resumes), decisions_csv (10,174 resumes with select/reject decisions)'),
            'limit' => $schema->integer()
                ->description('Number of resume samples to return (default: 3, max: 5)'),
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
