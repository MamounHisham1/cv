<?php

function telegramUpdatePayload(): array
{
    return [
        'update_id' => 100,
        'message' => [
            'message_id' => 1,
            'date' => now()->timestamp,
            'chat' => ['id' => 12345, 'type' => 'private'],
            'from' => ['id' => 1, 'is_bot' => false, 'first_name' => 'Test'],
            'text' => 'hello',
        ],
    ];
}

describe('Telegram Webhook', function () {
    it('rejects requests with an invalid secret token', function () {
        config(['services.telegram.webhook_secret' => 'secret123']);

        $this->withHeaders([
            'X-Telegram-Bot-Api-Secret-Token' => 'wrong-token',
        ])
            ->postJson('/webhooks/telegram', ['update_id' => 1])
            ->assertUnauthorized();
    });

    it('rejects requests when no webhook secret is configured (fails closed)', function () {
        config([
            'services.telegram.webhook_secret' => null,
            'services.telegram.bot_token' => '123:dummy',
        ]);

        // No secret header present, and none configured: the webhook must NOT
        // process the update — it must fail closed with 401.
        $this->postJson('/webhooks/telegram', telegramUpdatePayload())
            ->assertUnauthorized();
    });

    it('accepts requests with a valid secret token', function () {
        config([
            'services.telegram.webhook_secret' => 'secret123',
            'services.telegram.bot_token' => '123:dummy',
        ]);

        $this->withHeaders([
            'X-Telegram-Bot-Api-Secret-Token' => 'secret123',
        ])
            ->postJson('/webhooks/telegram', telegramUpdatePayload())
            ->assertOk();
    });

    it('is excluded from csrf protection', function () {
        config([
            'services.telegram.webhook_secret' => 'secret123',
            'services.telegram.bot_token' => '123:dummy',
        ]);

        $this->withHeaders([
            'X-Telegram-Bot-Api-Secret-Token' => 'secret123',
        ])
            ->postJson('/webhooks/telegram', ['update_id' => 2])
            ->assertOk();
    });
});
