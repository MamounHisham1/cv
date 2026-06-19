<?php

namespace App\Notifications;

use App\Models\CoverLetter;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use NotificationChannels\WebPush\WebPushChannel;
use NotificationChannels\WebPush\WebPushMessage;

/**
 * Fired when an async AI cover-letter draft finishes. Mirrors
 * EvaluationCompletedNotification: database channel (lights up the
 * NotificationBell) + optional WebPush.
 */
class CoverLetterGeneratedNotification extends Notification
{
    use Queueable;

    public function __construct(
        public CoverLetter $coverLetter,
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
            'cover_letter_id' => $this->coverLetter->id,
            'title' => $this->coverLetter->title,
            'url' => route('cover-letters.index'),
        ];
    }

    public function toWebPush(object $notifiable, mixed $subscription): WebPushMessage
    {
        return WebPushMessage::create()
            ->title('Cover Letter Ready')
            ->body('Your AI-drafted cover letter is ready to edit.')
            ->action('View', 'view', route('cover-letters.index'));
    }
}
