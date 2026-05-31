<?php

namespace App\Models;

use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\HasDatabaseNotifications;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Laravel\Fortify\TwoFactorAuthenticatable;
use NotificationChannels\WebPush\PushSubscription;

#[Fillable(['name', 'email', 'password', 'otp_code', 'otp_expires_at', 'otp_verified_at', 'google_id', 'avatar'])]
#[Hidden(['password', 'two_factor_secret', 'two_factor_recovery_codes', 'remember_token'])]
class User extends Authenticatable implements FilamentUser
{
    use HasDatabaseNotifications, HasFactory, Notifiable, TwoFactorAuthenticatable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'otp_expires_at' => 'datetime',
            'otp_verified_at' => 'datetime',
            'notification_preferences' => 'array',
        ];
    }

    /**
     * Get the user's initials
     */
    public function initials(): string
    {
        return Str::of($this->name)
            ->explode(' ')
            ->take(2)
            ->map(fn ($word) => Str::substr($word, 0, 1))
            ->implode('');
    }

    /**
     * Determine whether the user can access the Filament admin panel.
     */
    public function canAccessPanel(Panel $panel): bool
    {
        return $this->email === 'mamounprogrammer@gmail.com';
    }

    /**
     * Get the user's CVs
     */
    public function cvs(): HasMany
    {
        return $this->hasMany(Cv::class);
    }

    /**
     * Get the user's CV evaluations
     */
    public function cvEvaluations(): HasMany
    {
        return $this->hasMany(CvEvaluation::class);
    }

    public function skillCategories(): HasMany
    {
        return $this->hasMany(UserSkillCategory::class);
    }

    public function creditBalance(): HasOne
    {
        return $this->hasOne(CreditBalance::class);
    }

    public function creditTransactions(): HasMany
    {
        return $this->hasMany(CreditTransaction::class);
    }

    public function sentMails(): HasMany
    {
        return $this->hasMany(SentMail::class);
    }

    public function referralCode(): HasOne
    {
        return $this->hasOne(ReferralCode::class);
    }

    /**
     * Get the user's push subscriptions for web push notifications.
     *
     * @return MorphMany<PushSubscription, $this>
     */
    public function pushSubscriptions(): MorphMany
    {
        return $this->morphMany(PushSubscription::class, 'subscribable');
    }

    /**
     * Get the route notification for WebPush.
     *
     * @return Collection<int, PushSubscription>
     */
    public function routeNotificationForWebPush()
    {
        return $this->pushSubscriptions;
    }

    public function hasCredits(): bool
    {
        return ($this->creditBalance?->balance ?? 0) > 0;
    }

    /**
     * Get notification preference value.
     */
    public function getNotificationPreference(string $key, mixed $default = true): mixed
    {
        $preferences = $this->notification_preferences ?? [];

        return $preferences[$key] ?? $default;
    }

    /**
     * Update notification preference.
     */
    public function updateNotificationPreference(string $key, mixed $value): void
    {
        $preferences = $this->notification_preferences ?? [];
        $preferences[$key] = $value;
        $this->notification_preferences = $preferences;
        $this->save();
    }
}
