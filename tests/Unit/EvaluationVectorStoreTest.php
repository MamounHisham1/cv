<?php

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
