<?php

use App\Ai\Tools\SearchResumes;
use App\Services\ResumeVectorStore;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Ai\Tools\Request;

uses(RefreshDatabase::class);

function makeRequest(array $args): Request
{
    return new Request($args);
}

describe('SearchResumes Tool', function () {
    it('returns error for empty query', function () {
        $vectorStore = mock(ResumeVectorStore::class);
        $tool = new SearchResumes($vectorStore);

        $result = $tool->handle(makeRequest(['query' => '', 'role' => null, 'source' => null, 'limit' => 3]));

        expect($result)->toBeString()
            ->and($result)->toContain('Error');
    });

    it('returns no results message when search finds nothing', function () {
        $vectorStore = mock(ResumeVectorStore::class);
        $vectorStore->shouldReceive('search')
            ->once()
            ->with('nonexistent query', 3, null, null)
            ->andReturn([]);

        $tool = new SearchResumes($vectorStore);

        $result = $tool->handle(makeRequest(['query' => 'nonexistent query', 'role' => null, 'source' => null, 'limit' => 3]));

        expect($result)->toBeString()
            ->and($result)->toContain('No similar resume samples found');
    });

    it('returns formatted results when search finds matches', function () {
        $vectorStore = mock(ResumeVectorStore::class);
        $vectorStore->shouldReceive('search')
            ->once()
            ->with('software engineer', 3, null, null)
            ->andReturn([
                [
                    'role' => 'Software Engineer',
                    'source' => 'decisions_csv',
                    'content' => 'Senior Software Engineer with 10 years of experience in Java, Spring Boot, and AWS.',
                    'score' => 0.92,
                ],
                [
                    'role' => 'Data Scientist',
                    'source' => 'master_jsonl',
                    'content' => 'Software Developer skilled in Python and machine learning.',
                    'score' => 0.85,
                ],
            ]);

        $tool = new SearchResumes($vectorStore);

        $result = $tool->handle(makeRequest(['query' => 'software engineer', 'role' => null, 'source' => null, 'limit' => 3]));

        expect($result)->toBeString()
            ->and($result)->toContain('Software Engineer')
            ->and($result)->toContain('Data Scientist')
            ->and($result)->toContain('decisions_csv')
            ->and($result)->toContain('master_jsonl')
            ->and($result)->toContain('0.92')
            ->and($result)->toContain('Senior Software Engineer');
    });

    it('returns correct tool description', function () {
        $vectorStore = mock(ResumeVectorStore::class);
        $tool = new SearchResumes($vectorStore);

        $description = $tool->description();

        expect($description)->toBeString()
            ->and($description)->toContain('resume samples')
            ->and($description)->toContain('45');
    });

    it('has correct schema definition', function () {
        $vectorStore = mock(ResumeVectorStore::class);
        $tool = new SearchResumes($vectorStore);

        $schema = mock(JsonSchema::class);
        $schema->shouldReceive('string')->andReturnSelf();
        $schema->shouldReceive('integer')->andReturnSelf();
        $schema->shouldReceive('description')->andReturnSelf();

        $result = $tool->schema($schema);

        expect($result)->toHaveKeys(['query', 'role', 'source', 'limit']);
    });
});
