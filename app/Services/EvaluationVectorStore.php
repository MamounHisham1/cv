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
