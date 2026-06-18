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
        // Always bind Nutgram, even with an empty token. Nutgram tolerates an
        // empty token at construction; any outbound call simply throws, which
        // TelegramService::sendToChat() already swallows. Binding unconditionally
        // lets the Filament page and the queued jobs degrade gracefully instead
        // of throwing BindingResolutionException when the bot is unconfigured.
        $this->app->singleton(Nutgram::class, function (): Nutgram {
            $token = (string) config('services.telegram.bot_token', '');
            $configArray = (array) config('nutgram.config', []);

            $config = new Configuration(
                clientTimeout: (int) ($configArray['timeout'] ?? Configuration::DEFAULT_CLIENT_TIMEOUT),
                pollingTimeout: (int) ($configArray['pollingTimeout'] ?? Configuration::DEFAULT_POLLING_TIMEOUT),
                pollingAllowedUpdates: $configArray['allowedUpdates'] ?? Configuration::DEFAULT_ALLOWED_UPDATES,
            );

            return new Nutgram($token, $config);
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
