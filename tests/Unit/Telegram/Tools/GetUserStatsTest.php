<?php

use App\Ai\Tools\Telegram\GetUserStats;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Ai\Tools\Request;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

describe('GetUserStats Tool', function () {
    it('returns user statistics', function () {
        User::factory()->count(5)->create();

        $tool = new GetUserStats;
        $output = $tool->handle(new Request([]));

        expect($output)->toBeString()
            ->and($output)->not->toBeEmpty()
            ->and($output)->toMatch('/\d/');
    });
});
