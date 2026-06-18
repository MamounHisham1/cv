<?php

namespace App\Filament\Pages;

use App\Jobs\NotifyTelegramNewUser;
use App\Models\TelegramBotToken;
use App\Services\TelegramService;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\HtmlString;
use SergiX44\Nutgram\Nutgram;

class TelegramBot extends Page
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedChatBubbleLeftRight;

    protected static ?int $navigationSort = 55;

    protected string $view = 'filament.pages.telegram-bot';

    public static function canAccess(): bool
    {
        return auth()->user()?->email === config('services.telegram.admin_email');
    }

    public function getBreadcrumb(): string
    {
        return 'Telegram Bot';
    }

    public static function getNavigationLabel(): string
    {
        return 'Telegram Bot';
    }

    public static function getNavigationGroup(): ?string
    {
        return 'System';
    }

    /**
     * @return array<string, mixed>
     */
    protected function getViewData(): array
    {
        $user = auth()->user();

        // Resolve the admin chat server-side so the Blade view never resolves
        // TelegramService directly (which would throw if Nutgram were unbound).
        $adminChat = app()->bound(Nutgram::class)
            ? app(TelegramService::class)->getAdminChat()
            : null;

        return [
            'tokens' => $user->telegramBotTokens()->latest()->get(),
            'chats' => $user->telegramChats()->latest()->get(),
            'webhookUrl' => config('services.telegram.webhook_url') ?: url('/webhooks/telegram'),
            'botConfigured' => filled(config('services.telegram.bot_token')),
            'adminChat' => $adminChat,
        ];
    }

    /**
     * @return array<int, Action>
     */
    protected function getHeaderActions(): array
    {
        return [
            Action::make('generateKey')
                ->label('Generate API Key')
                ->icon(Heroicon::OutlinedKey)
                ->schema([
                    TextInput::make('label')
                        ->label('Label (e.g. "Laptop")')
                        ->required()
                        ->maxLength(50),
                ])
                ->action(function (array $data): void {
                    $result = TelegramBotToken::generate(auth()->user(), $data['label']);

                    Notification::make()
                        ->title('API Key Generated')
                        ->success()
                        ->body(new HtmlString(
                            Blade::render('<div class="space-y-1"><p>Copy this key now — it won\'t be shown again.</p><code class="block rounded bg-gray-100 p-2 font-mono text-xs dark:bg-gray-800">{{ $token }}</code></div>', ['token' => $result['token']])
                        ))
                        ->persistent()
                        ->send();
                }),

            Action::make('setWebhook')
                ->label('Set Webhook')
                ->icon(Heroicon::OutlinedCloudArrowUp)
                ->requiresConfirmation()
                ->action(function (): void {
                    $secret = config('services.telegram.webhook_secret');

                    if (! is_string($secret) || $secret === '') {
                        Notification::make()
                            ->title('TELEGRAM_WEBHOOK_SECRET not set')
                            ->danger()
                            ->body('Set TELEGRAM_WEBHOOK_SECRET in .env before registering the webhook.')
                            ->persistent()
                            ->send();

                        return;
                    }

                    try {
                        $bot = app(Nutgram::class);
                        $url = config('services.telegram.webhook_url') ?: url('/webhooks/telegram');
                        $bot->setWebhook($url, secret_token: $secret);

                        Notification::make()
                            ->title('Webhook set successfully')
                            ->success()
                            ->body($url)
                            ->send();
                    } catch (\Throwable $e) {
                        Notification::make()
                            ->title('Failed to set webhook')
                            ->danger()
                            ->body($e->getMessage())
                            ->send();
                    }
                }),

            Action::make('deleteWebhook')
                ->label('Delete Webhook')
                ->icon(Heroicon::OutlinedCloudArrowDown)
                ->requiresConfirmation()
                ->color('danger')
                ->action(function (): void {
                    try {
                        $bot = app(Nutgram::class);
                        $bot->deleteWebhook();

                        Notification::make()
                            ->title('Webhook deleted successfully')
                            ->success()
                            ->send();
                    } catch (\Throwable $e) {
                        Notification::make()
                            ->title('Failed to delete webhook')
                            ->danger()
                            ->body($e->getMessage())
                            ->send();
                    }
                }),

            Action::make('testNewUserAlert')
                ->label('Test New-User Alert')
                ->icon(Heroicon::OutlinedPaperAirplane)
                ->requiresConfirmation()
                ->action(function (): void {
                    NotifyTelegramNewUser::dispatch(auth()->user());

                    Notification::make()
                        ->title('Test alert dispatched')
                        ->success()
                        ->body('A new-user signup notification has been queued for your Telegram chat.')
                        ->send();
                }),
        ];
    }

    public function revokeToken(string $id): void
    {
        $token = auth()->user()->telegramBotTokens()->where('id', $id)->first();

        if (! $token || method_exists($token, 'isRevoked') && $token->isRevoked()) {
            Notification::make()->title('Token not found or already revoked.')->warning()->send();

            return;
        }

        $token->revoke();

        Notification::make()
            ->title('API key revoked')
            ->success()
            ->body(($token->label ?? 'Key').' can no longer be used.')
            ->send();
    }

    public function revokeChat(string $id): void
    {
        $chat = auth()->user()->telegramChats()->where('id', $id)->first();

        if (! $chat) {
            Notification::make()->title('Chat not found.')->warning()->send();

            return;
        }

        $chat->deauthorize();

        Notification::make()
            ->title('Chat deauthorized')
            ->success()
            ->body(($chat->username ? '@'.$chat->username : ($chat->first_name ?: $chat->chat_id)).' must re-authenticate to use the bot.')
            ->send();
    }
}
