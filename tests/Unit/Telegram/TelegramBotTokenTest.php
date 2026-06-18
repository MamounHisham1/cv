<?php

use App\Models\TelegramBotToken;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

describe('TelegramBotToken', function () {
    it('generates a token and returns the plaintext and model', function () {
        $user = User::factory()->create();

        $result = TelegramBotToken::generate($user, 'Test');

        expect($result['token'])->toBeString()
            ->and($result['model'])->toBeInstanceOf(TelegramBotToken::class)
            ->and($result['model']->user_id)->toBe($user->id)
            ->and($result['model']->label)->toBe('Test');
    });

    it('validates a previously generated token', function () {
        $user = User::factory()->create();
        $result = TelegramBotToken::generate($user, 'Test');

        $model = TelegramBotToken::validate($result['token']);

        expect($model)->not->toBeNull()
            ->and($model->id)->toBe($result['model']->id)
            ->and($model->last_used_at)->not->toBeNull();
    });

    it('returns null for an invalid token', function () {
        expect(TelegramBotToken::validate('tbot_invalidgarbage'))->toBeNull();
    });

    it('returns null for a revoked token', function () {
        $user = User::factory()->create();
        $result = TelegramBotToken::generate($user, 'Test');

        $result['model']->revoke();

        expect($result['model']->isRevoked())->toBeTrue();
        expect(TelegramBotToken::validate($result['token']))->toBeNull();
    });

    it('hashes the token in the database', function () {
        $user = User::factory()->create();
        $result = TelegramBotToken::generate($user, 'Test');

        expect($result['model']->token_hash)
            ->not->toBe($result['token'])
            ->toBe(hash('sha256', $result['token']));
    });
});
