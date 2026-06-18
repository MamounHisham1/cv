<?php

namespace App\Console\Commands;

use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use SergiX44\Nutgram\Nutgram;

#[Signature('telegram:set-webhook')]
#[Description('Register the Telegram bot webhook with Telegram.')]
class SetTelegramWebhook extends Command
{
    public function handle(): int
    {
        $token = (string) config('services.telegram.bot_token', '');

        if ($token === '') {
            $this->error('TELEGRAM_BOT_TOKEN not set.');

            return self::FAILURE;
        }

        /** @var Nutgram $bot */
        $bot = app(Nutgram::class);

        $url = (string) (config('services.telegram.webhook_url') ?: url('/webhooks/telegram'));
        $secret = config('services.telegram.webhook_secret');

        if (! is_string($secret) || $secret === '') {
            $this->error('TELEGRAM_WEBHOOK_SECRET not set. Aborting to avoid registering an unauthenticated webhook.');

            return self::FAILURE;
        }

        try {
            $bot->setWebhook($url, secret_token: $secret);

            $this->info("Telegram webhook registered: {$url}");

            return self::SUCCESS;
        } catch (\Throwable $e) {
            $this->error("Failed to register webhook: {$e->getMessage()}");

            return self::FAILURE;
        }
    }
}
