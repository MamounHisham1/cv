<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use NotificationChannels\WebPush\WebPushChannel;
use NotificationChannels\WebPush\WebPushMessage;

class CreditsLowNotification extends Notification
{
    use Queueable;

    public function __construct(
        public int $remainingCredits,
        public int $threshold,
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
            ->subject("Your credits are running low - Only {$this->remainingCredits} credits remaining")
            ->line('Your credits are running low!')
            ->line("You have {$this->remainingCredits} credits remaining.")
            ->line('Consider purchasing more credits to continue using our services.')
            ->action('Purchase Credits', url('/credits'))
            ->line('Thank you for using our application!');
    }

    /**
     * @return array{remaining_credits: int, threshold: int}
     */
    public function toArray(object $notifiable): array
    {
        return [
            'remaining_credits' => $this->remainingCredits,
            'threshold' => $this->threshold,
            'url' => route('credits.history'),
        ];
    }

    public function toWebPush(object $notifiable, mixed $subscription): WebPushMessage
    {
        return WebPushMessage::create()
            ->title('Low Credits Alert')
            ->body("Only {$this->remainingCredits} credits remaining")
            ->action('Purchase', 'purchase', url('/credits'));
    }
}
