<?php

namespace App\Services;

use Exception;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class OllamaService
{
    protected string $baseUrl;

    protected string $model;

    protected int $timeout;

    protected array $messages = [];

    protected string $prompt = '';

    protected int $maxTokens = 1000;

    protected float $temperature = 0.3;

    public function __construct()
    {
        $this->baseUrl = config('services.ollama.url', 'http://localhost:11434');
        $this->model = config('services.ollama.model', env('OLLAMA_MODEL', 'llama3.1:8b'));
        $this->timeout = config('services.ollama.timeout', 300);
    }

    public function withModel(string $model): self
    {
        $this->model = $model;

        return $this;
    }

    public function withMaxTokens(int $maxTokens): self
    {
        $this->maxTokens = $maxTokens;

        return $this;
    }

    public function withTemperature(float $temperature): self
    {
        $this->temperature = $temperature;

        return $this;
    }

    public function withSystemMessage(string $content): self
    {
        $this->messages[] = ['role' => 'system', 'content' => $content];

        return $this;
    }

    public function withUserMessage(string $content): self
    {
        $this->messages[] = ['role' => 'user', 'content' => $content];

        return $this;
    }

    public function withAssistantMessage(string $content): self
    {
        $this->messages[] = ['role' => 'assistant', 'content' => $content];

        return $this;
    }

    public function reset(): self
    {
        $this->messages = [];
        $this->prompt = '';
        $this->maxTokens = 1000;
        $this->temperature = 0.3;
        $this->model = config('services.ollama.model', env('OLLAMA_MODEL', 'llama3.1:8b'));

        return $this;
    }

    public function generateText(string $input = ''): string
    {
        $prompt = $input ?: $this->prompt;

        if (empty($prompt)) {
            throw new Exception('No prompt provided for text generation.');
        }

        $messages = [
            ['role' => 'user', 'content' => $prompt],
        ];

        return $this->chatCompletion($messages);
    }

    public function chatCompletion(?array $messages = null): string
    {
        $messagesToUse = $messages ?: $this->messages;

        if (empty($messagesToUse)) {
            throw new Exception('No messages provided for chat completion.');
        }

        try {
            $response = Http::timeout($this->timeout)
                ->connectTimeout(10)
                ->post("{$this->baseUrl}/api/chat", [
                    'model' => $this->model,
                    'messages' => $messagesToUse,
                    'stream' => false,
                    'options' => [
                        'temperature' => $this->temperature,
                        'num_predict' => $this->maxTokens,
                    ],
                ]);

            if (! $response->successful()) {
                throw new Exception("Ollama API error: {$response->body()}");
            }

            $result = $response->json();

            if (! isset($result['message']['content'])) {
                throw new Exception('Invalid response format from Ollama API');
            }

            $content = $result['message']['content'];
            $content = $this->cleanThinkingTags($content);

            Log::info('Ollama chat completion successful', [
                'model' => $this->model,
                'response_length' => strlen($content),
            ]);

            return $content;
        } catch (Exception $e) {
            Log::error('Ollama API request failed', [
                'error' => $e->getMessage(),
                'model' => $this->model,
            ]);

            throw new Exception("Failed to get response from Ollama: {$e->getMessage()}");
        }
    }

    protected function cleanThinkingTags(string $content): string
    {
        $content = preg_replace('/<think.*?<\/think>/s', '', $content);
        $content = preg_replace('/<\/?think>/s', '', $content);

        return trim($content);
    }

    public function isAvailable(): bool
    {
        try {
            $response = Http::timeout(5)->connectTimeout(5)->get("{$this->baseUrl}/api/tags");

            return $response->successful();
        } catch (Exception $e) {
            Log::warning('Ollama availability check failed', [
                'error' => $e->getMessage(),
            ]);

            return false;
        }
    }

    public function getAvailableModels(): array
    {
        try {
            $response = Http::timeout($this->timeout)->get("{$this->baseUrl}/api/tags");

            if (! $response->successful()) {
                return [];
            }

            $result = $response->json();

            return collect($result['models'] ?? [])
                ->pluck('name')
                ->toArray();
        } catch (Exception $e) {
            Log::error('Failed to get Ollama models', [
                'error' => $e->getMessage(),
            ]);

            return [];
        }
    }

    public function generateEmbedding(string $text): array
    {
        try {
            $response = Http::timeout($this->timeout)
                ->connectTimeout(10)
                ->post("{$this->baseUrl}/api/embed", [
                    'model' => config('services.ollama.embedding_model', 'mxbai-embed-large'),
                    'input' => $text,
                ]);

            if (! $response->successful()) {
                throw new Exception("Ollama embedding API error: {$response->body()}");
            }

            $result = $response->json();

            return $result['embeddings'][0] ?? throw new Exception('No embedding in Ollama response');
        } catch (Exception $e) {
            Log::error('Ollama embedding generation failed', [
                'error' => $e->getMessage(),
            ]);

            throw new Exception("Failed to generate embedding with Ollama: {$e->getMessage()}");
        }
    }
}
