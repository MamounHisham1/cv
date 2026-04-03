<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use NotificationChannels\WebPush\WebPushChannel;
use NotificationChannels\WebPush\WebPushMessage;

class CreditsGrantedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public int $amount,
        public string $reason,
        public int $newBalance,
    ) {}

    public function via(object $notifiable): array
    {
        $channels = ['database'];

        if ($notifiable->getNotificationPreference('credit_notifications', true)) {
            if ($notifiable->getNotificationPreference('push_enabled', true)) {
                $channels[] = WebPushChannel::class;
            }

            if ($notifiable->getNotificationPreference('email_enabled', true)) {
                $channels[] = 'mail';
            }
        }

        return $channels;
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->markdown('mail.credits-granted', [
                'name' => $notifiable->name,
                'amount' => $this->amount,
                'reason' => $this->reason,
                'newBalance' => $this->newBalance,
            ]);
    }

    public function toArray(object $notifiable): array
    {
        return [
            'amount' => $this->amount,
            'reason' => $this->reason,
            'new_balance' => $this->newBalance,
            'url' => route('credits.history'),
        ];
    }

    public function toWebPush(object $notifiable, mixed $subscription): WebPushMessage
    {
        return WebPushMessage::create()
            ->title("{$this->amount} Credits Added")
            ->body("You received {$this->amount} credits. Reason: {$this->reason}. New balance: {$this->newBalance}")
            ->action('View', 'view', route('credits.history'));
    }
}
