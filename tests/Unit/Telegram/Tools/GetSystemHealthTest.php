<?php

use App\Ai\Tools\Telegram\GetSystemHealth;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Ai\Tools\Request;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

describe('GetSystemHealth Tool', function () {
    it('returns system health info', function () {
        $tool = new GetSystemHealth;
        $output = $tool->handle(new Request([]));

        expect($output)->toBeString()
            ->and($output)->not->toBeEmpty()
            ->and($output)->toMatch('/\d|failed|queue|cache|health|ok|up|down/i');
    });
});
