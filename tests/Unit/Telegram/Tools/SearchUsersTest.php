<?php

use App\Ai\Tools\Telegram\SearchUsers;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Ai\Tools\Request;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

describe('SearchUsers Tool', function () {
    it('finds a user by email', function () {
        $user = User::factory()->create([
            'name' => 'Alice Example',
            'email' => 'alice@example.com',
        ]);

        $tool = new SearchUsers;
        $output = $tool->handle(new Request(['query' => 'alice@example.com']));

        expect($output)->toBeString()
            ->and($output)->toContain('alice@example.com')
            ->and($output)->toContain('Alice Example');
    });

    it('returns a not-found message for no matches', function () {
        $tool = new SearchUsers;
        $output = $tool->handle(new Request(['query' => 'nonexistent-'.uniqid().'@nowhere.dev']));

        expect($output)->toBeString()
            ->and($output)->toMatch('/no users found/i');
    });
});
