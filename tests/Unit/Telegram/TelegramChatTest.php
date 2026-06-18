<?php

use App\Models\TelegramChat;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

describe('TelegramChat', function () {
    it('authorizes a chat for a user', function () {
        $user = User::factory()->create();
        $chat = new TelegramChat(['chat_id' => 123]);

        $chat->authorize($user, ['username' => 'admin']);

        expect($chat->isAuthorized())->toBeTrue()
            ->and($chat->authorized_at)->not->toBeNull()
            ->and($chat->user_id)->toBe($user->id)
            ->and($chat->username)->toBe('admin');
    });

    it('deauthorizes a chat', function () {
        $user = User::factory()->create();
        $chat = TelegramChat::create([
            'chat_id' => 456,
            'user_id' => $user->id,
            'authorized_at' => now(),
        ]);

        expect($chat->isAuthorized())->toBeTrue();

        $chat->deauthorize();

        expect($chat->isAuthorized())->toBeFalse()
            ->and($chat->authorized_at)->toBeNull();
    });

    it('finds a chat by chat id', function () {
        $user = User::factory()->create();
        $chat = TelegramChat::create([
            'chat_id' => 789,
            'user_id' => $user->id,
            'username' => 'tester',
        ]);

        $found = TelegramChat::findForChat(789);

        expect($found)->not->toBeNull()
            ->and($found->id)->toBe($chat->id);
        expect(TelegramChat::findForChat(999999))->toBeNull();
    });
});
