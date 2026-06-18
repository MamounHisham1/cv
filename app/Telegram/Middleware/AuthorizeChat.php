<?php

namespace App\Telegram\Middleware;

use App\Models\TelegramChat;
use SergiX44\Nutgram\Nutgram;

class AuthorizeChat
{
    /**
     * Allow authorized chats through to every handler; restrict unauthorized
     * chats to the /start, /help commands and the API-key authentication flow.
     */
    public function __invoke(Nutgram $bot, $next): void
    {
        $chatId = $bot->chatId();

        if ($chatId === null) {
            return;
        }

        $chat = TelegramChat::findForChat($chatId);

        if ($chat?->isAuthorized()) {
            $next($bot);

            return;
        }

        $text = (string) ($bot->message()?->text ?? '');
        $command = explode(' ', trim($text), 2)[0];

        if (in_array($command, ['/start', '/help'], true)) {
            $next($bot);

            return;
        }

        if (preg_match('/^tbot_[a-zA-Z0-9]{20,}$/', trim($text))) {
            $next($bot);

            return;
        }

        $bot->sendMessage('🔒 This bot is admin-only. Send your API key to authenticate.');
    }
}
