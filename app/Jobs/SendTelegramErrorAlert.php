<?php

namespace App\Jobs;

use App\Services\TelegramService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class SendTelegramErrorAlert implements ShouldQueue
{
    use Queueable;

    public int $tries = 2;

    /** @var array<int, int> */
    public array $backoff = [5, 15];

    public function __construct(
        public string $message,
        public ?string $file = null,
        public ?int $line = null,
        public ?string $level = 'ERROR',
        public ?string $exceptionClass = null,
    ) {}

    public function handle(TelegramService $telegram): void
    {
        try {
            $text = mb_substr($this->message, 0, 1500);

            $body = "💥 Application Error\n\n"
                ."Level: {$this->level}\n";

            if ($this->exceptionClass) {
                $body .= "Type: {$this->exceptionClass}\n";
            }

            $body .= "Message: {$text}\n";

            if ($this->file) {
                $body .= 'Location: '.$this->file;
                if ($this->line) {
                    $body .= ":{$this->line}";
                }
            }

            $telegram->sendMessage($body);
        } catch (\Throwable) {
        }
    }
}
