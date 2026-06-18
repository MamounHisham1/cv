<?php

namespace App\Console\Commands;

use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use SergiX44\Nutgram\Nutgram;

#[Signature('telegram:delete-webhook')]
#[Description('Remove the registered Telegram bot webhook.')]
class DeleteTelegramWebhook extends Command
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

        try {
            $bot->deleteWebhook();

            $this->info('Telegram webhook deleted.');

            return self::SUCCESS;
        } catch (\Throwable $e) {
            $this->error("Failed to delete webhook: {$e->getMessage()}");

            return self::FAILURE;
        }
    }
}
