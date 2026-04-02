<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use NotificationChannels\WebPush\WebPushChannel;
use NotificationChannels\WebPush\WebPushMessage;

class ReferralSignedUpNotification extends Notification
{
    use Queueable;

    public function __construct(
        public User $referredUser,
    ) {}

    /**
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database', 'mail', WebPushChannel::class];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject("Your friend {$this->referredUser->name} just joined!")
            ->line("Great news! {$this->referredUser->name} just joined using your referral.")
            ->line("You've earned referral credits for this signup.")
            ->action('View Referrals', url('/referrals'))
            ->line('Thank you for using our application!');
    }

    /**
     * @return array{referred_user_name: string, referred_user_id: int}
     */
    public function toArray(object $notifiable): array
    {
        return [
            'referred_user_name' => $this->referredUser->name,
            'referred_user_id' => $this->referredUser->id,
            'url' => route('referrals'),
        ];
    }

    public function toWebPush(object $notifiable, mixed $subscription): WebPushMessage
    {
        return WebPushMessage::create()
            ->title('New Referral!')
            ->body("Great news! {$this->referredUser->name} joined using your referral")
            ->action('View', 'view', url('/referrals'));
    }
}
