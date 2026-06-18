<?php

namespace App\Providers;

use App\Telegram\TelegramBot;
use Illuminate\Support\ServiceProvider;
use SergiX44\Nutgram\Configuration;
use SergiX44\Nutgram\Nutgram;

class TelegramServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Always bind Nutgram, even when no bot token is configured. Nutgram's
        // constructor rejects an empty token, so we substitute a non-empty
        // placeholder — any real outbound call against it fails, and
        // TelegramService::sendToChat() already swallows those failures. Binding
        // unconditionally lets the Filament page and the queued jobs degrade
        // gracefully instead of throwing BindingResolutionException when the
        // bot is unconfigured.
        $this->app->singleton(Nutgram::class, function (): Nutgram {
            $token = (string) config('services.telegram.bot_token', '');
            $configArray = (array) config('nutgram.config', []);

            $config = new Configuration(
                clientTimeout: (int) ($configArray['timeout'] ?? Configuration::DEFAULT_CLIENT_TIMEOUT),
                pollingTimeout: (int) ($configArray['pollingTimeout'] ?? Configuration::DEFAULT_POLLING_TIMEOUT),
                pollingAllowedUpdates: $configArray['allowedUpdates'] ?? Configuration::DEFAULT_ALLOWED_UPDATES,
            );

            return new Nutgram($token === '' ? '0:unconfigured' : $token, $config);
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if (! filled(config('services.telegram.bot_token'))) {
            return;
        }

        $bot = $this->app->make(Nutgram::class);

        $this->app->make(TelegramBot::class)->register($bot);
    }
}
