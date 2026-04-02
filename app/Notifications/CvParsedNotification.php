<?php

namespace App\Notifications;

use App\Models\Cv;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use NotificationChannels\WebPush\WebPushChannel;
use NotificationChannels\WebPush\WebPushMessage;

class CvParsedNotification extends Notification
{
    use Queueable;

    public function __construct(
        public Cv $cv,
    ) {}

    public function via(object $notifiable): array
    {
        $channels = ['database'];

        if ($notifiable->getNotificationPreference('push_enabled', true)) {
            $channels[] = WebPushChannel::class;
        }

        return $channels;
    }

    public function toArray(object $notifiable): array
    {
        return [
            'cv_id' => $this->cv->id,
            'cv_title' => $this->cv->title,
            'url' => route('cv.edit', $this->cv->id),
        ];
    }

    public function toWebPush(object $notifiable, mixed $subscription): WebPushMessage
    {
        return WebPushMessage::create()
            ->title('CV Import Complete')
            ->body("\"{$this->cv->title}\" has been imported and parsed successfully.")
            ->action('Edit CV', 'edit', route('cv.edit', $this->cv->id));
    }
}
