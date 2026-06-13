<?php

namespace App\Services;

use GuzzleHttp\Client;
use Qdrant\Config;
use Qdrant\Http\Transport;
use Qdrant\Qdrant;

abstract class VectorStore
{
    protected ?Qdrant $client = null;

    public function __construct(
        protected readonly ?string $qdrantUrl = null,
        protected readonly ?string $qdrantApiKey = null,
    ) {}

    protected function client(): Qdrant
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

    protected function hashId(string $id): int
    {
        return abs(crc32($id));
    }
}
