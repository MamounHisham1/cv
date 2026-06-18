<?php

namespace App\Console\Commands;

use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use SergiX44\Nutgram\Nutgram;

#[Signature('telegram:poll')]
#[Description('Run the Telegram bot in long-polling mode (dev).')]
class PollTelegram extends Command
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

        $this->info('Polling... (Ctrl+C to stop)');

        $bot->run();

        return self::SUCCESS;
    }
}
