<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use SergiX44\Nutgram\Nutgram;
use SergiX44\Nutgram\RunningMode\Webhook;
use Throwable;

class TelegramWebhookController extends Controller
{
    /**
     * Receive a Telegram webhook update and forward it to the bot.
     */
    public function __invoke(Request $request): Response
    {
        $secret = config('services.telegram.webhook_secret');

        // Fail closed: when no secret is configured, reject the request outright
        // rather than processing an attacker-supplied update. Telegram sets the
        // X-Telegram-Bot-Api-Secret-Token header on every delivery when a
        // secret_token was passed to setWebhook, so a missing/empty configured
        // secret is a misconfiguration that must not silently become unauthenticated.
        if (! is_string($secret) || $secret === '') {
            Log::error('Telegram webhook rejected: webhook secret is not configured.');

            return response('Unauthorized', 401);
        }

        $provided = (string) $request->header('X-Telegram-Bot-Api-Secret-Token', '');

        if (! hash_equals($secret, $provided)) {
            return response('Unauthorized', 401);
        }

        if (! app()->bound(Nutgram::class)) {
            return response('OK', 200);
        }

        /** @var Nutgram $bot */
        $bot = app(Nutgram::class);

        $bot->setRunningMode(new Webhook);

        try {
            $bot->run();
        } catch (Throwable $e) {
            Log::error('Telegram webhook processing failed', ['exception' => $e]);
        }

        return response('OK', 200);
    }
}
