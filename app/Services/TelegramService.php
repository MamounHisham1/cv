<?php

namespace App\Services;

use App\Models\TelegramChat;
use App\Models\User;
use SergiX44\Nutgram\Nutgram;
use SergiX44\Nutgram\Telegram\Properties\ParseMode;
use Throwable;

class TelegramService
{
    public function __construct(public Nutgram $bot) {}

    /**
     * Resolve the most recently authorized admin chat, if any.
     */
    public function getAdminChat(): ?TelegramChat
    {
        $admin = User::where('email', config('services.telegram.admin_email'))->first();

        if ($admin === null) {
            return null;
        }

        return $admin->telegramChats()
            ->whereNotNull('authorized_at')
            ->latest()
            ->first();
    }

    /**
     * Send a message to the admin chat. Returns false when there is no admin chat.
     */
    public function sendMessage(string $text, ?ParseMode $parseMode = null): bool
    {
        $chat = $this->getAdminChat();

        if ($chat === null) {
            return false;
        }

        $this->sendToChat($chat->chat_id, $text, $parseMode);

        return true;
    }

    /**
     * Send a message to a specific chat, swallowing any delivery errors.
     */
    public function sendToChat(int|string $chatId, string $text, ?ParseMode $parseMode = null): void
    {
        try {
            $this->bot->sendMessage($text, chat_id: $chatId, parse_mode: $parseMode);
        } catch (Throwable) {
            // Swallowed intentionally: outbound notifications must never throw
            // back into the caller (e.g. the error-alert logger) to avoid loops.
        }
    }
}
