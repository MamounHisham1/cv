<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class TelegramBotToken extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'label',
        'token_hash',
        'last_used_at',
        'revoked_at',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'last_used_at' => 'datetime',
            'revoked_at' => 'datetime',
            'created_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Generate a new bot token for the given user.
     *
     * @return array{token: string, model: self}
     */
    public static function generate(User $user, string $label): array
    {
        $plaintext = 'tbot_'.Str::random(60);

        $model = self::create([
            'user_id' => $user->id,
            'label' => $label,
            'token_hash' => hash('sha256', $plaintext),
        ]);

        return [
            'token' => $plaintext,
            'model' => $model,
        ];
    }

    /**
     * Validate a plaintext token and return the active model, or null.
     */
    public static function validate(string $plaintext): ?self
    {
        $token = self::where('token_hash', hash('sha256', $plaintext))
            ->whereNull('revoked_at')
            ->first();

        if ($token === null) {
            return null;
        }

        $token->forceFill(['last_used_at' => now()])->save();

        return $token;
    }

    /**
     * Revoke the token so it can no longer be used.
     */
    public function revoke(): void
    {
        $this->forceFill(['revoked_at' => now()])->save();
    }

    /**
     * Determine whether the token has been revoked.
     */
    public function isRevoked(): bool
    {
        return $this->revoked_at !== null;
    }
}
