<?php

namespace App\Logging;

use App\Jobs\SendTelegramErrorAlert;
use Illuminate\Support\Facades\Cache;
use Monolog\Handler\AbstractProcessingHandler;
use Monolog\Logger;
use Monolog\LogRecord;

class TelegramErrorHandler extends AbstractProcessingHandler
{
    /**
     * Factory invoked by Laravel's "custom" log driver.
     *
     * @param  array<string, mixed>  $config
     */
    public function __invoke(array $config): Logger
    {
        return new Logger('telegram', [$this]);
    }

    /**
     * Forward only ERROR-or-above records to the admin Telegram chat.
     */
    protected function write(LogRecord $record): void
    {
        try {
            if ($record->level->value < Logger::ERROR) {
                return;
            }

            $message = (string) $record->message;
            $fingerprint = md5($message);

            if (Cache::has("telegram_error:{$fingerprint}")) {
                return;
            }

            Cache::put("telegram_error:{$fingerprint}", true, now()->addMinutes(5));

            $context = $record->context;

            $exception = $context['exception'] ?? null;
            $file = $context['exception_file'] ?? null;
            $line = $context['exception_line'] ?? null;
            $exceptionClass = null;

            if ($exception instanceof \Throwable) {
                $file ??= $exception->getFile();
                $line ??= $exception->getLine();
                $exceptionClass = $exception::class;
            } elseif (is_string($exception) && $exception !== '') {
                $exceptionClass = $exception;
            }

            SendTelegramErrorAlert::dispatch(
                $message,
                $file,
                $line,
                strtolower($record->level->getName()),
                $exceptionClass,
            );
        } catch (\Throwable) {
        }
    }
}
