<?php

namespace App\Services;

use Laravel\Ai\Embeddings;
use Laravel\Ai\Enums\Lab;
use Qdrant\Models\Filter\Condition\MatchString;
use Qdrant\Models\Filter\Filter;
use Qdrant\Models\PointsStruct;
use Qdrant\Models\PointStruct;
use Qdrant\Models\Request\CreateCollection;
use Qdrant\Models\Request\SearchRequest;
use Qdrant\Models\Request\VectorParams;
use Qdrant\Models\VectorStruct;

class ResumeVectorStore extends VectorStore
{
    private const COLLECTION = 'resume_samples';

    private const VECTOR_SIZE = 768;

    private const EMBEDDING_MODEL = 'mxbai-embed-large';

    public function ensureCollectionExists(): void
    {
        $response = $this->client()->collections(self::COLLECTION)->exists();
        $data = $response->__toArray();

        if (! ($data['result']['exists'] ?? false)) {
            $createCollection = new CreateCollection;
            $createCollection->addVector(
                new VectorParams(self::VECTOR_SIZE, VectorParams::DISTANCE_COSINE)
            );

            $this->client()->collections(self::COLLECTION)->create($createCollection);
        }
    }

    public function store(string $id, string $content, string $category, array $embedding): void
    {
        $point = new PointStruct(
            $this->hashId($id),
            new VectorStruct($embedding),
            [
                'category' => $category,
                'content' => mb_substr($content, 0, 8000),
            ]
        );

        $pointsStruct = new PointsStruct;
        $pointsStruct->addPoint($point);

        $this->client()->collections(self::COLLECTION)->points()->upsert($pointsStruct);
    }

    public function storeBatch(array $items): void
    {
        $pointsStruct = new PointsStruct;

        foreach ($items as $item) {
            $point = new PointStruct(
                $this->hashId($item['id']),
                new VectorStruct($item['embedding']),
                [
                    'role' => $item['role'] ?? '',
                    'source' => $item['source'] ?? '',
                    'content' => mb_substr($item['content'], 0, 8000),
                ]
            );
            $pointsStruct->addPoint($point);
        }

        $this->client()->collections(self::COLLECTION)->points()->upsert($pointsStruct);
    }

    /**
     * Search using a pre-computed embedding (avoids redundant embedding generation).
     *
     * @param  array<int, float>  $embedding
     * @return array<int, array{role: string, source: string, content: string, score: float}>
     */
    public function searchByEmbedding(array $embedding, int $limit = 5, ?string $role = null, ?string $source = null): array
    {
        $searchRequest = new SearchRequest(new VectorStruct($embedding));
        $searchRequest->setLimit($limit);
        $searchRequest->setWithPayload(true);

        $filter = new Filter;
        if ($role) {
            $filter->addMust(new MatchString('role', $role));
        }
        if ($source) {
            $filter->addMust(new MatchString('source', $source));
        }
        if ($role || $source) {
            $searchRequest->setFilter($filter);
        }

        $response = $this->client()
            ->collections(self::COLLECTION)
            ->points()
            ->search($searchRequest);

        $data = $response->__toArray();
        $results = $data['result'] ?? [];

        return collect($results)->map(fn (array $result): array => [
            'role' => $result['payload']['role'] ?? '',
            'source' => $result['payload']['source'] ?? '',
            'content' => $result['payload']['content'] ?? '',
            'score' => round($result['score'], 4),
        ])->values()->toArray();
    }

    public function search(string $query, int $limit = 5, ?string $role = null, ?string $source = null): array
    {
        $queryEmbedding = Embeddings::for([$query])
            ->generate(Lab::Ollama, self::EMBEDDING_MODEL)
            ->embeddings[0];

        $searchRequest = new SearchRequest(new VectorStruct($queryEmbedding));
        $searchRequest->setLimit($limit);
        $searchRequest->setWithPayload(true);

        $filter = new Filter;
        if ($role) {
            $filter->addMust(new MatchString('role', $role));
        }
        if ($source) {
            $filter->addMust(new MatchString('source', $source));
        }
        if ($role || $source) {
            $searchRequest->setFilter($filter);
        }

        $response = $this->client()
            ->collections(self::COLLECTION)
            ->points()
            ->search($searchRequest);

        $data = $response->__toArray();
        $results = $data['result'] ?? [];

        return collect($results)->map(fn ($result) => [
            'role' => $result['payload']['role'] ?? '',
            'source' => $result['payload']['source'] ?? '',
            'content' => $result['payload']['content'] ?? '',
            'score' => round($result['score'], 4),
        ])->values()->toArray();
    }

    public function generateEmbedding(string $text): array
    {
        return Embeddings::for([$text])
            ->generate(Lab::Ollama, self::EMBEDDING_MODEL)
            ->embeddings[0];
    }

    public function collectionInfo(): ?array
    {
        $response = $this->client()->collections(self::COLLECTION)->info();

        return $response->__toArray()['result'] ?? null;
    }
}
