<?php

namespace App\Telegram;

use App\Ai\Agents\AdminAssistantAgent;
use App\Models\TelegramBotToken;
use App\Models\TelegramChat;
use Illuminate\Support\Facades\Log;
use SergiX44\Nutgram\Nutgram;
use Throwable;

class TelegramBot
{
    /**
     * Pattern matching admin API keys issued from the dashboard.
     */
    public const KEY_PATTERN = '/^tbot_[a-zA-Z0-9]{20,}$/';

    /**
     * Attach all middleware, commands, text handlers and fallback to the bot.
     */
    public function register(Nutgram $bot): void
    {
        $bot->middleware(Middleware\AuthorizeChat::class);

        $bot->onCommand('start', fn (Nutgram $bot) => $this->handleStart($bot));
        $bot->onCommand('help', fn (Nutgram $bot) => $this->handleHelp($bot));
        $bot->onCommand('logout', fn (Nutgram $bot) => $this->handleLogout($bot));

        $bot->onText('.*', fn (Nutgram $bot) => $this->handleText($bot));

        $bot->fallback(fn (Nutgram $bot) => $bot->sendMessage(
            "I didn't understand that. Use /help to see what I can do."
        ));
    }

    /**
     * Greet the user, or prompt for authentication when not authorized.
     */
    protected function handleStart(Nutgram $bot): void
    {
        $chat = $this->chat($bot);

        if ($chat?->isAuthorized()) {
            $bot->sendMessage(
                "You're authorized. Ask me anything — user stats, revenue, read logs, system health, etc. Use /help for commands."
            );

            return;
        }

        $bot->sendMessage('🔒 This bot is admin-only. Send your API key (from the admin dashboard) to authenticate.');
    }

    /**
     * List the available commands and bot capabilities.
     */
    protected function handleHelp(Nutgram $bot): void
    {
        $bot->sendMessage(
            "Here's what I can do:\n\n"
            ."/start — check status & authenticate\n"
            ."/help — show this help message\n"
            ."/logout — unlink this chat from your account\n\n"
            .'Or just send me a question about the app — user stats, revenue, logs, system health, and more.'
        );
    }

    /**
     * Deauthorize the current chat.
     */
    protected function handleLogout(Nutgram $bot): void
    {
        $chatId = $bot->chatId();

        if ($chatId === null) {
            return;
        }

        TelegramChat::findForChat($chatId)?->deauthorize();

        $bot->sendMessage("You've been logged out. Send your API key to authenticate again.");
    }

    /**
     * Branch every non-command text message into key-auth or AI handling.
     */
    protected function handleText(Nutgram $bot): void
    {
        $text = (string) ($bot->message()?->text ?? '');

        if ($this->isCommand($text)) {
            return;
        }

        $chat = $this->chat($bot);

        if ($chat?->isAuthorized()) {
            $this->handleAiMessage($bot, $chat, $text);

            return;
        }

        if (preg_match(self::KEY_PATTERN, trim($text))) {
            $this->handleKeyAuthentication($bot, $text);

            return;
        }

        $bot->sendMessage('🔒 This bot is admin-only. Send your API key to authenticate.');
    }

    /**
     * Validate a pasted API key and authorize the chat on success.
     */
    protected function handleKeyAuthentication(Nutgram $bot, string $text): void
    {
        $chatId = $bot->chatId();

        if ($chatId === null) {
            return;
        }

        $token = TelegramBotToken::validate(trim($text));

        if ($token === null) {
            $bot->sendMessage('❌ Invalid or revoked API key. Generate a new one from the admin dashboard.');

            return;
        }

        $user = $token->user;

        $chat = TelegramChat::findForChat($chatId) ?? new TelegramChat(['chat_id' => $chatId]);

        $chat->authorize($user, [
            'telegram_user_id' => $bot->userId(),
            'username' => $bot->user()?->username,
            'first_name' => $bot->user()?->first_name,
        ]);

        $name = $user->name ?? 'there';

        $bot->sendMessage("✅ Authenticated! Welcome, {$name}. Ask me anything.");
    }

    /**
     * Forward an authorized message to the admin assistant agent and reply.
     */
    protected function handleAiMessage(Nutgram $bot, TelegramChat $chat, string $text): void
    {
        $user = $chat->user;

        try {
            $bot->sendChatAction('typing');

            $agent = new AdminAssistantAgent;

            if ($chat->conversation_id !== null) {
                $agent = $agent->continue($chat->conversation_id, as: $user);
            } else {
                $agent = $agent->forUser($user);
            }

            $response = $agent->prompt($text);

            if ($chat->conversation_id === null && $response->conversationId !== null) {
                $chat->update(['conversation_id' => $response->conversationId]);
            }

            $bot->sendMessage((string) $response);
        } catch (Throwable $e) {
            Log::error('Telegram AI request failed', ['exception' => $e]);

            $bot->sendMessage('⚠️ Something went wrong processing your request. Please try again.');
        }
    }

    /**
     * Resolve the chat record for the current update, if any.
     */
    protected function chat(Nutgram $bot): ?TelegramChat
    {
        $chatId = $bot->chatId();

        if ($chatId === null) {
            return null;
        }

        return TelegramChat::findForChat($chatId);
    }

    /**
     * Determine whether the given text is a bot command.
     */
    protected function isCommand(string $text): bool
    {
        $command = explode(' ', trim($text), 2)[0];

        return str_starts_with($command, '/');
    }
}
