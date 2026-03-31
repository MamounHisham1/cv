# RAG-Powered CV Evaluation Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Give the CvEvaluatorAgent two vector search tools so it can benchmark CVs against real resume samples and past evaluations before scoring.

**Architecture:** The CvEvaluatorAgent implements `HasTools` and receives `SearchResumes` (existing) and `SearchEvaluations` (new) tools. A new `EvaluationVectorStore` service manages the `cv_evaluations` Qdrant collection. After each evaluation, the Livewire component stores the embedding in Qdrant. An artisan command backfills existing evaluations.

**Tech Stack:** Laravel AI SDK (agents, tools, embeddings), Qdrant (`hkulekci/qdrant`), Ollama (`mxbai-embed-large`, 768-dim cosine), Livewire 4, Pest 4

---

## File Structure

| Action | File | Responsibility |
|--------|------|----------------|
| Create | `app/Services/EvaluationVectorStore.php` | Qdrant client for `cv_evaluations` collection (ensure, store, search, embed) |
| Create | `app/Ai/Tools/SearchEvaluations.php` | AI tool wrapping `EvaluationVectorStore::search()` |
| Create | `app/Console/Commands/VectorizeEvaluations.php` | Artisan command to backfill existing evaluations into Qdrant |
| Modify | `app/Ai/Agents/CvEvaluatorAgent.php` | Add `HasTools`, implement `tools()`, update `instructions()` |
| Modify | `app/Livewire/CvEvaluator.php` | After saving evaluation, embed and store in Qdrant |
| Create | `tests/Unit/EvaluationVectorStoreTest.php` | Unit tests for the vector store |
| Create | `tests/Unit/SearchEvaluationsToolTest.php` | Unit test for the search tool |
| Create | `tests/Feature/VectorizeEvaluationsCommandTest.php` | Feature test for the artisan command |

---

### Task 1: EvaluationVectorStore Service

**Files:**
- Create: `app/Services/EvaluationVectorStore.php`
- Test: `tests/Unit/EvaluationVectorStoreTest.php`

This service mirrors the structure of `ResumeVectorStore` but for the `cv_evaluations` collection. It handles collection creation, storing evaluation embeddings, and semantic search over past evaluations.

- [ ] **Step 1: Write the failing test**

```php
<?php

// tests/Unit/EvaluationVectorStoreTest.php

use App\Services\EvaluationVectorStore;

describe('EvaluationVectorStore', function () {
    it('creates store instance', function () {
        $store = new EvaluationVectorStore;
        expect($store)->toBeInstanceOf(EvaluationVectorStore::class);
    });

    it('has correct collection name constant', function () {
        $reflection = new ReflectionClass(EvaluationVectorStore::class);
        $constant = $reflection->getConstant('COLLECTION');

        expect($constant)->toBe('cv_evaluations');
    });

    it('has correct vector size constant', function () {
        $reflection = new ReflectionClass(EvaluationVectorStore::class);
        $constant = $reflection->getConstant('VECTOR_SIZE');

        expect($constant)->toBe(768);
    });

    it('has correct embedding model constant', function () {
        $reflection = new ReflectionClass(EvaluationVectorStore::class);
        $constant = $reflection->getConstant('EMBEDDING_MODEL');

        expect($constant)->toBe('mxbai-embed-large');
    });
});
```

- [ ] **Step 2: Run test to verify it fails**

Run: `php artisan test --compact tests/Unit/EvaluationVectorStoreTest.php`
Expected: FAIL — `Class "App\Services\EvaluationVectorStore" does not exist`

- [ ] **Step 3: Write the implementation**

```php
<?php

namespace App\Services;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;
use Laravel\Ai\Embeddings;
use Laravel\Ai\Enums\Lab;
use Qdrant\Config;
use Qdrant\Http\Transport;
use Qdrant\Models\PointsStruct;
use Qdrant\Models\PointStruct;
use Qdrant\Models\Request\CreateCollection;
use Qdrant\Models\Request\SearchRequest;
use Qdrant\Models\Request\VectorParams;
use Qdrant\Models\VectorStruct;
use Qdrant\Qdrant;

class EvaluationVectorStore
{
    private const COLLECTION = 'cv_evaluations';

    private const VECTOR_SIZE = 768;

    private const EMBEDDING_MODEL = 'mxbai-embed-large';

    private ?Qdrant $client = null;

    public function __construct(
        private readonly ?string $qdrantUrl = null,
        private readonly ?string $qdrantApiKey = null,
    ) {}

    public function ensureCollectionExists(): void
    {
        try {
            $response = $this->client()->collections(self::COLLECTION)->exists();
            $data = $response->__toArray();

            if (! ($data['result']['exists'] ?? false)) {
                $createCollection = new CreateCollection;
                $createCollection->addVector(
                    new VectorParams(self::VECTOR_SIZE, VectorParams::DISTANCE_COSINE)
                );

                $this->client()->collections(self::COLLECTION)->create($createCollection);
            }
        } catch (\Throwable $e) {
            Log::warning('EvaluationVectorStore: Failed to ensure collection exists', [
                'message' => $e->getMessage(),
            ]);
        }
    }

    public function store(string $id, int $userId, string $grade, int $overallScore, string $cvText, array $embedding): void
    {
        try {
            $point = new PointStruct(
                $this->hashId($id),
                new VectorStruct($embedding),
                [
                    'user_id' => $userId,
                    'grade' => $grade,
                    'overall_score' => $overallScore,
                    'content' => mb_substr($cvText, 0, 8000),
                ]
            );

            $pointsStruct = new PointsStruct;
            $pointsStruct->addPoint($point);

            $this->client()->collections(self::COLLECTION)->points()->upsert($pointsStruct);
        } catch (\Throwable $e) {
            Log::warning('EvaluationVectorStore: Failed to store evaluation', [
                'id' => $id,
                'message' => $e->getMessage(),
            ]);
        }
    }

    /**
     * @return array<int, array{user_id: int, grade: string, overall_score: int, content: string, score: float}>
     */
    public function search(string $query, int $limit = 3): array
    {
        try {
            $queryEmbedding = Embeddings::for([$query])
                ->generate(Lab::Ollama, self::EMBEDDING_MODEL)
                ->embeddings[0];

            $searchRequest = new SearchRequest(new VectorStruct($queryEmbedding));
            $searchRequest->setLimit($limit);
            $searchRequest->setWithPayload(true);

            $response = $this->client()
                ->collections(self::COLLECTION)
                ->points()
                ->search($searchRequest);

            $data = $response->__toArray();
            $results = $data['result'] ?? [];

            return collect($results)->map(fn (array $result): array => [
                'user_id' => $result['payload']['user_id'] ?? 0,
                'grade' => $result['payload']['grade'] ?? '',
                'overall_score' => $result['payload']['overall_score'] ?? 0,
                'content' => $result['payload']['content'] ?? '',
                'score' => round($result['score'], 4),
            ])->values()->toArray();
        } catch (\Throwable $e) {
            Log::warning('EvaluationVectorStore: Search failed', [
                'message' => $e->getMessage(),
            ]);

            return [];
        }
    }

    public function generateEmbedding(string $text): array
    {
        return Embeddings::for([mb_substr($text, 0, 1500)])
            ->generate(Lab::Ollama, self::EMBEDDING_MODEL)
            ->embeddings[0];
    }

    private function client(): Qdrant
    {
        if ($this->client) {
            return $this->client;
        }

        $url = $this->qdrantUrl ?? config('services.qdrant.url', 'http://localhost:6333');
        $apiKey = $this->qdrantApiKey ?? config('services.qdrant.api_key', '');

        $config = new Config($url);

        if ($apiKey) {
            $config->setApiKey($apiKey);
        }

        $httpClient = new Client;
        $transport = new Transport($httpClient, $config);
        $this->client = new Qdrant($transport);

        return $this->client;
    }

    private function hashId(string $id): int
    {
        return abs(crc32($id));
    }
}
```

- [ ] **Step 4: Run test to verify it passes**

Run: `php artisan test --compact tests/Unit/EvaluationVectorStoreTest.php`
Expected: PASS (4 tests)

- [ ] **Step 5: Commit**

```bash
git add app/Services/EvaluationVectorStore.php tests/Unit/EvaluationVectorStoreTest.php
git commit -m "feat: add EvaluationVectorStore service for cv_evaluations Qdrant collection"
```

---

### Task 2: SearchEvaluations AI Tool

**Files:**
- Create: `app/Ai/Tools/SearchEvaluations.php`
- Test: `tests/Unit/SearchEvaluationsToolTest.php`

This tool follows the exact same pattern as `SearchResumes` but wraps `EvaluationVectorStore::search()` instead. It is scoped to the evaluator agent only.

- [ ] **Step 1: Write the failing test**

```php
<?php

// tests/Unit/SearchEvaluationsToolTest.php

use App\Ai\Tools\SearchEvaluations;
use App\Services\EvaluationVectorStore;
use Laravel\Ai\Contracts\Tool as ToolContract;

describe('SearchEvaluations Tool', function () {
    it('implements the Tool contract', function () {
        $tool = new SearchEvaluations(app(EvaluationVectorStore::class));

        expect($tool)->toBeInstanceOf(ToolContract::class);
    });

    it('has a non-empty description', function () {
        $tool = new SearchEvaluations(app(EvaluationVectorStore::class));

        expect($tool->description())->not()->toBeEmpty();
    });

    it('returns error message when query is empty', function () {
        $tool = new SearchEvaluations(app(EvaluationVectorStore::class));
        $request = new \Laravel\Ai\Tools\Request(['query' => '']);

        $result = $tool->handle($request);

        expect($result)->toContain('Error');
    });

    it('returns no-results message when search returns empty', function () {
        $mockStore = Mockery::mock(EvaluationVectorStore::class);
        $mockStore->shouldReceive('search')->andReturn([]);

        $tool = new SearchEvaluations($mockStore);
        $request = new \Laravel\Ai\Tools\Request(['query' => 'python developer resume']);

        $result = $tool->handle($request);

        expect($result)->toContain('No similar past evaluations found');
    });

    it('formats search results correctly', function () {
        $mockStore = Mockery::mock(EvaluationVectorStore::class);
        $mockStore->shouldReceive('search')->andReturn([
            [
                'user_id' => 1,
                'grade' => 'A',
                'overall_score' => 90,
                'content' => 'John Doe - Software Engineer...',
                'score' => 0.85,
            ],
        ]);

        $tool = new SearchEvaluations($mockStore);
        $request = new \Laravel\Ai\Tools\Request(['query' => 'software engineer cv', 'limit' => 3]);

        $result = $tool->handle($request);

        expect($result)->toContain('Past CV Evaluation Found');
        expect($result)->toContain('Grade: A');
        expect($result)->toContain('Overall Score: 90');
        expect($result)->toContain('John Doe - Software Engineer');
    });
});
```

- [ ] **Step 2: Run test to verify it fails**

Run: `php artisan test --compact tests/Unit/SearchEvaluationsToolTest.php`
Expected: FAIL — `Class "App\Ai\Tools\SearchEvaluations" does not exist`

- [ ] **Step 3: Write the implementation**

```php
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
            $output .= "--- Evaluation ".($i + 1)." (Grade: {$result['grade']}, Overall Score: {$result['overall_score']}/100, Similarity: {$result['score']}) ---\n";
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
```

- [ ] **Step 4: Run test to verify it passes**

Run: `php artisan test --compact tests/Unit/SearchEvaluationsToolTest.php`
Expected: PASS (5 tests)

- [ ] **Step 5: Commit**

```bash
git add app/Ai/Tools/SearchEvaluations.php tests/Unit/SearchEvaluationsToolTest.php
git commit -m "feat: add SearchEvaluations AI tool for past evaluation vector search"
```

---

### Task 3: Update CvEvaluatorAgent with Tools and New Instructions

**Files:**
- Modify: `app/Ai/Agents/CvEvaluatorAgent.php`

This is the core change. The agent must implement `HasTools`, receive both search tools, and have mandatory instructions to always search before evaluating.

- [ ] **Step 1: Update CvEvaluatorAgent**

Replace the entire file content with:

```php
<?php

namespace App\Ai\Agents;

use App\Ai\Tools\SearchEvaluations;
use App\Ai\Tools\SearchResumes;
use App\Services\EvaluationVectorStore;
use App\Services\ResumeVectorStore;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Ai\Attributes\MaxTokens;
use Laravel\Ai\Attributes\Model;
use Laravel\Ai\Attributes\Provider;
use Laravel\Ai\Attributes\Temperature;
use Laravel\Ai\Contracts\Agent;
use Laravel\Ai\Contracts\HasStructuredOutput;
use Laravel\Ai\Contracts\HasTools;
use Laravel\Ai\Enums\Lab;
use Laravel\Ai\Promptable;
use Stringable;

#[Provider(Lab::Ollama)]
#[Model('mistral-large-3:675b-cloud')]
#[Temperature(0.0)]
#[MaxTokens(4096)]
class CvEvaluatorAgent implements Agent, HasStructuredOutput, HasTools
{
    use Promptable;

    /**
     * Agent instructions for structured CV evaluation with RAG context.
     */
    public function instructions(): Stringable|string
    {
        return <<<'INSTRUCTIONS'
You are a professional CV/Resume evaluator with deep expertise in ATS systems, talent acquisition, and career coaching.

## MANDATORY: Always Search Before Evaluating

You MUST call both search tools BEFORE producing your evaluation. This is not optional.

1. **First**, call `search_resumes` with keywords extracted from the CV (role, skills, industry) to find real resume samples in the same field.
2. **Second**, call `search_evaluations` with similar keywords to find how comparable CVs were evaluated in the past.
3. **Then**, use the search results to benchmark your evaluation:
   - Compare the CV against the strongest resumes found. What does the CV do well relative to them? What is missing?
   - Look at past evaluations of similar CVs. Were there common weaknesses? Common strengths?
   - Adjust your scoring: if the search results show that strong resumes in this field typically include certain skills or formats, factor that into your assessment.
   - Use the reference material to make your reasons specific and actionable, not generic.

If both searches return no results (empty database), proceed with your expert evaluation based on general best practices.

## Evaluation Criteria

Evaluate the provided CV text across exactly 10 criteria. For each criterion, assign a score from 0 to 10 and a concise one-sentence reason.

Criteria:
1. Contact Information - completeness and correctness of name, email, phone, location, and profile links
2. Professional Summary - clarity, impact, and relevance of the opening summary
3. Work Experience - quality of descriptions, use of quantifiable achievements, and action verbs
4. Skills Section - relevance, completeness, and organisation of listed skills
5. Education - adequacy and presentation of educational background
6. ATS Compatibility - keyword density, formatting that ATS systems can parse, absence of tables/graphics-only content
7. Formatting and Readability - visual structure, whitespace, consistency, and scan-ability
8. Achievements and Impact - presence of metrics, results, and measurable accomplishments
9. Keyword Optimisation - alignment with industry-standard terms and job-specific vocabulary
10. Overall Completeness - how thorough and well-rounded the CV is

## Scoring Guidelines

- 9-10: Exceptional — matches or exceeds the strongest reference resumes
- 7-8: Strong — solid with minor improvements needed
- 5-6: Adequate — functional but notable gaps compared to reference material
- 3-4: Weak — significant improvements needed
- 0-2: Critical — major issues that would likely result in rejection

Respond ONLY with the structured JSON. No additional commentary outside the schema.
INSTRUCTIONS;
    }

    /**
     * Tools available to the agent for RAG-powered evaluation.
     */
    public function tools(): iterable
    {
        return [
            new SearchResumes(app(ResumeVectorStore::class)),
            new SearchEvaluations(app(EvaluationVectorStore::class)),
        ];
    }

    /**
     * Structured output schema for the evaluation result.
     *
     * Uses only flat scalar types supported by the Laravel AI SDK.
     * The Livewire component reconstructs the nested structure from the
     * pipe-delimited criterion fields (e.g. "contact_information_score").
     *
     * @return array<string, mixed>
     */
    public function schema(JsonSchema $schema): array
    {
        return [
            'overall_score' => $schema->integer()->required()->description('Weighted overall score from 0 to 100'),
            'grade' => $schema->string()->required()->description('Letter grade: A+, A, B+, B, C+, C, D, F'),
            'summary' => $schema->string()->required()->description('2-3 sentence executive summary of the evaluation'),

            // Per-criterion scores (0–10)
            'contact_information_score' => $schema->integer()->required(),
            'contact_information_reason' => $schema->string()->required(),
            'professional_summary_score' => $schema->integer()->required(),
            'professional_summary_reason' => $schema->string()->required(),
            'work_experience_score' => $schema->integer()->required(),
            'work_experience_reason' => $schema->string()->required(),
            'skills_section_score' => $schema->integer()->required(),
            'skills_section_reason' => $schema->string()->required(),
            'education_score' => $schema->integer()->required(),
            'education_reason' => $schema->string()->required(),
            'ats_compatibility_score' => $schema->integer()->required(),
            'ats_compatibility_reason' => $schema->string()->required(),
            'formatting_readability_score' => $schema->integer()->required(),
            'formatting_readability_reason' => $schema->string()->required(),
            'achievements_impact_score' => $schema->integer()->required(),
            'achievements_impact_reason' => $schema->string()->required(),
            'keyword_optimisation_score' => $schema->integer()->required(),
            'keyword_optimisation_reason' => $schema->string()->required(),
            'overall_completeness_score' => $schema->integer()->required(),
            'overall_completeness_reason' => $schema->string()->required(),

            // Top-level lists encoded as comma-separated strings
            'top_strengths' => $schema->string()->required()->description('Top 3 strengths, separated by ||'),
            'critical_improvements' => $schema->string()->required()->description('Top 3 most important improvements, separated by ||'),
        ];
    }
}
```

- [ ] **Step 2: Verify the file has no syntax errors**

Run: `php -l app/Ai/Agents/CvEvaluatorAgent.php`
Expected: `No syntax errors detected`

- [ ] **Step 3: Commit**

```bash
git add app/Ai/Agents/CvEvaluatorAgent.php
git commit -m "feat: add HasTools to CvEvaluatorAgent with SearchResumes and SearchEvaluations"
```

---

### Task 4: Update CvEvaluator Livewire to Store Evaluations in Qdrant

**Files:**
- Modify: `app/Livewire/CvEvaluator.php`

After saving an evaluation to the database (line ~345), generate an embedding and store it in Qdrant. This step must not break the evaluation flow if Qdrant is unavailable.

- [ ] **Step 1: Add the Qdrant storage after DB save**

In `app/Livewire/CvEvaluator.php`, add the import at the top:

```php
use App\Services\EvaluationVectorStore;
```

Then, after the `CvEvaluation::create(...)` block (after line 345 where `$savedEvaluation` is set), and before the `if (auth()->check() && $savedEvaluation)` credit deduction block, add this Qdrant storage block:

Replace the section from line 347 (`\Log::info('CvEvaluator: Saved evaluation to database', ...)`) through to just before the credit deduction `if` block (line 352) with:

```php
                \Log::info('CvEvaluator: Saved evaluation to database', ['user_id' => auth()->id()]);

                // Store evaluation embedding in Qdrant for future RAG
                try {
                    $vectorStore = app(EvaluationVectorStore::class);
                    $vectorStore->ensureCollectionExists();
                    $embedding = $vectorStore->generateEmbedding($this->cvText);
                    $vectorStore->store(
                        "eval_{$savedEvaluation->id}",
                        auth()->id(),
                        $this->result['grade'],
                        $this->result['overall_score'],
                        $this->cvText,
                        $embedding,
                    );
                    \Log::info('CvEvaluator: Stored evaluation embedding in Qdrant', [
                        'evaluation_id' => $savedEvaluation->id,
                    ]);
                } catch (\Throwable $e) {
                    \Log::warning('CvEvaluator: Failed to store evaluation in Qdrant', [
                        'message' => $e->getMessage(),
                    ]);
                }

                $this->refreshEvaluations();
```

- [ ] **Step 2: Verify no syntax errors**

Run: `php -l app/Livewire/CvEvaluator.php`
Expected: `No syntax errors detected`

- [ ] **Step 3: Run existing tests to ensure nothing broke**

Run: `php artisan test --compact tests/Feature/CvEvaluatorHistoryTest.php`
Expected: All existing tests still PASS

- [ ] **Step 4: Commit**

```bash
git add app/Livewire/CvEvaluator.php
git commit -m "feat: store evaluation embeddings in Qdrant after each evaluation"
```

---

### Task 5: VectorizeEvaluations Artisan Command

**Files:**
- Create: `app/Console/Commands/VectorizeEvaluations.php`
- Test: `tests/Feature/VectorizeEvaluationsCommandTest.php`

This command backfills existing `cv_evaluations` records into Qdrant. It follows the pattern from `ImportResumes` — processes in chunks, generates embeddings, handles retries.

- [ ] **Step 1: Write the failing test**

```php
<?php

// tests/Feature/VectorizeEvaluationsCommandTest.php

use App\Models\CvEvaluation;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

describe('evaluations:vectorize command', function () {
    it('has the correct signature', function () {
        $this->artisan('evaluations:vectorize', ['--dry-run' => true])
            ->assertSuccessful();
    });

    it('reports count in dry-run mode without processing', function () {
        $user = User::factory()->create();
        CvEvaluation::factory()->count(3)->create(['user_id' => $user->id]);

        $this->artisan('evaluations:vectorize', ['--dry-run' => true])
            ->assertSuccessful()
            ->expectsOutputToContain('3 evaluation(s) would be vectorized');
    });

    it('reports zero evaluations when none exist', function () {
        $this->artisan('evaluations:vectorize', ['--dry-run' => true])
            ->assertSuccessful()
            ->expectsOutputToContain('0 evaluation(s) would be vectorized');
    });
});
```

- [ ] **Step 2: Run test to verify it fails**

Run: `php artisan test --compact tests/Feature/VectorizeEvaluationsCommandTest.php`
Expected: FAIL — command does not exist

- [ ] **Step 3: Generate the command file**

Run: `php artisan make:command VectorizeEvaluations --no-interaction`

Then replace the generated file content with:

```php
<?php

namespace App\Console\Commands;

use App\Models\CvEvaluation;
use App\Services\EvaluationVectorStore;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Command;

#[Description('Backfill existing CV evaluations into Qdrant vector store')]
class VectorizeEvaluations extends Command
{
    protected $signature = 'evaluations:vectorize
        {--batch=5 : Number of evaluations to embed per batch}
        {--limit= : Max number of evaluations to process}
        {--dry-run : Show count without processing}';

    public function handle(EvaluationVectorStore $vectorStore): int
    {
        $query = CvEvaluation::orderBy('created_at');

        $limit = $this->option('limit');
        if ($limit) {
            $query->limit((int) $limit);
        }

        $evaluations = $query->get();
        $count = $evaluations->count();

        if ($this->option('dry-run')) {
            $this->components->info("{$count} evaluation(s) would be vectorized.");

            return self::SUCCESS;
        }

        if ($count === 0) {
            $this->components->info('No evaluations to vectorize.');

            return self::SUCCESS;
        }

        $vectorStore->ensureCollectionExists();
        $this->components->info('Qdrant collection ready.');

        $progressBar = $this->output->createProgressBar($count);
        $progressBar->start();

        $imported = 0;
        $errors = 0;

        foreach ($evaluations as $evaluation) {
            try {
                $embedding = $vectorStore->generateEmbedding($evaluation->cv_text ?? '');
                $vectorStore->store(
                    "eval_{$evaluation->id}",
                    $evaluation->user_id,
                    $evaluation->grade,
                    $evaluation->overall_score,
                    $evaluation->cv_text ?? '',
                    $embedding,
                );
                $imported++;
            } catch (\Throwable $e) {
                $this->warn("Failed to vectorize evaluation {$evaluation->id}: {$e->getMessage()}");
                $errors++;
            }

            $progressBar->advance();
        }

        $progressBar->finish();
        $this->newLine();

        $this->table(
            ['Metric', 'Value'],
            [
                ['Imported', $imported],
                ['Errors', $errors],
            ]
        );

        return self::SUCCESS;
    }
}
```

- [ ] **Step 4: Run test to verify it passes**

Run: `php artisan test --compact tests/Feature/VectorizeEvaluationsCommandTest.php`
Expected: PASS (3 tests)

- [ ] **Step 5: Commit**

```bash
git add app/Console/Commands/VectorizeEvaluations.php tests/Feature/VectorizeEvaluationsCommandTest.php
git commit -m "feat: add evaluations:vectorize artisan command to backfill Qdrant"
```

---

### Task 6: Run Pint and Full Test Suite

**Files:**
- All modified PHP files

- [ ] **Step 1: Run Pint on all modified files**

Run: `vendor/bin/pint --dirty --format agent`
Expected: All files formatted

- [ ] **Step 2: Run the full test suite**

Run: `php artisan test --compact`
Expected: All tests PASS

- [ ] **Step 3: Final commit if Pint made changes**

```bash
git add -A
git commit -m "style: apply pint formatting to RAG evaluation files"
```

(Only if Pint made changes — skip if clean.)
