<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TelegramChat extends Model
{
    use HasFactory;

    protected $fillable = [
        'chat_id',
        'user_id',
        'telegram_user_id',
        'username',
        'first_name',
        'authorized_at',
        'conversation_id',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'authorized_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Find a chat record by its Telegram chat id.
     */
    public static function findForChat(int|string $chatId): ?self
    {
        return self::where('chat_id', $chatId)->first();
    }

    /**
     * Determine whether the chat has been authorized.
     */
    public function isAuthorized(): bool
    {
        return $this->authorized_at !== null;
    }

    /**
     * Authorize the chat for the given user, persisting optional metadata.
     *
     * @param  array{telegram_user_id?: int|string|null, username?: string|null, first_name?: string|null}  $meta
     */
    public function authorize(User $user, array $meta = []): self
    {
        $this->forceFill([
            'user_id' => $user->id,
            'authorized_at' => now(),
            'telegram_user_id' => $meta['telegram_user_id'] ?? $this->telegram_user_id,
            'username' => $meta['username'] ?? $this->username,
            'first_name' => $meta['first_name'] ?? $this->first_name,
        ])->save();

        return $this;
    }

    /**
     * Deauthorize the chat while keeping the record for re-authentication.
     */
    public function deauthorize(): void
    {
        $this->forceFill(['authorized_at' => null])->save();
    }
}
