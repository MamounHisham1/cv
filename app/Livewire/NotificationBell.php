<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;
use Livewire\Component;

class NotificationBell extends Component
{
    public int $unreadCount = 0;

    public array $notifications = [];

    public function mount(): void
    {
        $this->loadData();
    }

    #[On('notification-received')]
    public function loadData(): void
    {
        if (! Auth::check()) {
            $this->unreadCount = 0;
            $this->notifications = [];

            return;
        }

        $this->unreadCount = Auth::user()->unreadNotifications()->count();
        $this->notifications = Auth::user()->notifications()->latest()->limit(10)->get()->map(fn ($n) => [
            'id' => $n->id,
            'type' => $n->type,
            'data' => $n->data,
            'read_at' => $n->read_at,
            'created_at' => $n->created_at,
        ])->toArray();
    }

    public function markAsRead(string $id): void
    {
        if (! Auth::check()) {
            return;
        }

        Auth::user()->notifications()->where('id', $id)->update(['read_at' => now()]);

        $notification = Auth::user()->notifications()->where('id', $id)->first();

        if ($notification && isset($notification->data['url'])) {
            $this->redirect($notification->data['url']);
        }

        $this->loadData();
    }

    public function markAllAsRead(): void
    {
        if (! Auth::check()) {
            return;
        }

        Auth::user()->unreadNotifications()->update(['read_at' => now()]);
        $this->loadData();
    }

    public function getNotificationIcon(string $type): string
    {
        return match ($type) {
            'App\Notifications\EvaluationCompletedNotification' => 'check-circle',
            'App\Notifications\CreditsLowNotification' => 'alert-triangle',
            'App\Notifications\CreditsGrantedNotification' => 'star',
            'App\Notifications\CvParsedNotification' => 'document-text',
            'App\Notifications\ReferralSignedUpNotification' => 'users',
            'App\Notifications\OtpSentNotification' => 'shield-check',
            default => 'bell',
        };
    }

    public function getNotificationTitle(array $data, string $type): string
    {
        return match ($type) {
            'App\Notifications\EvaluationCompletedNotification' => "Evaluation complete: {$data['score']}/100 ({$data['grade']})",
            'App\Notifications\CreditsLowNotification' => "Low credits: {$data['remaining_credits']} remaining",
            'App\Notifications\CreditsGrantedNotification' => "+{$data['amount']} credits added",
            'App\Notifications\CvParsedNotification' => "CV imported: {$data['cv_title']}",
            'App\Notifications\ReferralSignedUpNotification' => "{$data['referred_user_name']} joined!",
            'App\Notifications\OtpSentNotification' => 'OTP code sent',
            default => 'Notification',
        };
    }

    public function getRelativeTime(string $datetime): string
    {
        $diff = now()->diffInMinutes(new \DateTime($datetime));

        return match (true) {
            $diff < 1 => 'Just now',
            $diff < 60 => "{$diff}m ago",
            $diff < 1440 => intdiv($diff, 60).'h ago',
            default => intdiv($diff, 1440).'d ago',
        };
    }

    public function render()
    {
        return view('livewire.notification-bell');
    }
}
