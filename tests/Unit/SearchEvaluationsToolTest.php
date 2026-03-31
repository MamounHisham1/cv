<?php

use App\Ai\Tools\SearchEvaluations;
use App\Services\EvaluationVectorStore;
use Laravel\Ai\Contracts\Tool as ToolContract;
use Laravel\Ai\Tools\Request;

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
        $request = new Request(['query' => '']);

        $result = $tool->handle($request);

        expect($result)->toContain('Error');
    });

    it('returns no-results message when search returns empty', function () {
        $mockStore = Mockery::mock(EvaluationVectorStore::class);
        $mockStore->shouldReceive('search')->andReturn([]);

        $tool = new SearchEvaluations($mockStore);
        $request = new Request(['query' => 'python developer resume']);

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
        $request = new Request(['query' => 'software engineer cv', 'limit' => 3]);

        $result = $tool->handle($request);

        expect($result)->toContain('Past CV Evaluation');
        expect($result)->toContain('Grade: A');
        expect($result)->toContain('Overall Score: 90');
        expect($result)->toContain('John Doe - Software Engineer');
    });
});
