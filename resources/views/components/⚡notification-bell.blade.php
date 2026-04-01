<?php

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

new class extends Component
{
    public int $unreadCount = 0;

    /**
     * @var array<int, array{id: int, type: string, data: array<string, mixed>, read_at: ?string, created_at: string}>
     */
    public array $notifications = [];

    public bool $dropdownOpen = false;

    public function mount(): void
    {
        $this->loadNotifications();
    }

    public function loadNotifications(): void
    {
        $user = Auth::user();

        if (! $user) {
            return;
        }

        $this->unreadCount = $user->unreadNotifications()->count();

        $this->notifications = $user->notifications()
            ->latest()
            ->take(10)
            ->get()
            ->map(fn ($notification) => [
                'id' => $notification->id,
                'type' => $notification->type,
                'data' => $notification->data,
                'read_at' => $notification->read_at,
                'created_at' => $notification->created_at,
            ])
            ->toArray();
    }

    public function markAsRead(string $notificationId): void
    {
        $user = Auth::user();

        if (! $user) {
            return;
        }

        $notification = $user->notifications()->where('id', $notificationId)->first();

        if ($notification) {
            $notification->markAsRead();
        }

        $this->loadNotifications();
    }

    public function markAllAsRead(): void
    {
        $user = Auth::user();

        if (! $user) {
            return;
        }

        $user->unreadNotifications->markAsRead();
        $this->loadNotifications();
    }

    public function toggleDropdown(): void
    {
        $this->dropdownOpen = ! $this->dropdownOpen;
    }

    public function getNotificationIcon(string $type): string
    {
        return match ($type) {
            'App\\Notifications\\EvaluationCompletedNotification' => 'check-circle',
            'App\\Notifications\\CreditsLowNotification' => 'alert-triangle',
            'App\\Notifications\\ReferralSignedUpNotification' => 'users',
            'App\\Notifications\\OtpSentNotification' => 'shield',
            default => 'bell',
        };
    }

    public function getNotificationTitle(array $data, string $type): string
    {
        return match ($type) {
            'App\\Notifications\\EvaluationCompletedNotification' => "Score: {$data['score']}/100 ({$data['grade']})",
            'App\\Notifications\\CreditsLowNotification' => "Credits: {$data['remaining_credits']} remaining",
            'App\\Notifications\\ReferralSignedUpNotification' => "{$data['referred_user_name']} joined",
            'App\\Notifications\\OtpSentNotification' => 'OTP Sent',
            default => 'Notification',
        };
    }

    public function getRelativeTime(string $createdAt): string
    {
        $created = new DateTime($createdAt);
        $now = new DateTime;
        $diff = $now->diff($created);

        if ($diff->y > 0) {
            return $diff->y.' year'.($diff->y > 1 ? 's' : '').' ago';
        }

        if ($diff->m > 0) {
            return $diff->m.' month'.($diff->m > 1 ? 's' : '').' ago';
        }

        if ($diff->d > 0) {
            return $diff->d.' day'.($diff->d > 1 ? 's' : '').' ago';
        }

        if ($diff->h > 0) {
            return $diff->h.' hour'.($diff->h > 1 ? 's' : '').' ago';
        }

        if ($diff->i > 0) {
            return $diff->i.' min ago';
        }

        return 'Just now';
    }

    public function render()
    {
        return view('livewire.notification-bell');
    }
};
