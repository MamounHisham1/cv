<?php

namespace App\Notifications;

use App\Models\InterviewEvaluation;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use NotificationChannels\WebPush\WebPushChannel;
use NotificationChannels\WebPush\WebPushMessage;

class InterviewEvaluationCompletedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public InterviewEvaluation $evaluation,
    ) {}

    public function via(object $notifiable): array
    {
        $channels = ['database'];

        if ($notifiable->getNotificationPreference('evaluation_notifications', true)) {
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
            ->markdown('mail.interview-evaluation', [
                'name' => $notifiable->name,
                'score' => $this->evaluation->overall_score,
                'grade' => $this->evaluation->grade,
                'summary' => $this->evaluation->summary,
                'strengths' => $this->evaluation->strengths,
                'improvements' => $this->evaluation->improvements,
            ]);
    }

    public function toArray(object $notifiable): array
    {
        return [
            'evaluation_id' => $this->evaluation->id,
            'session_id' => $this->evaluation->interview_session_id,
            'score' => $this->evaluation->overall_score,
            'grade' => $this->evaluation->grade,
            'url' => route('ai.interview'),
        ];
    }

    public function toWebPush(object $notifiable, mixed $subscription): WebPushMessage
    {
        return WebPushMessage::create()
            ->title('Interview Evaluation Ready')
            ->body("You scored {$this->evaluation->overall_score}/100 (Grade: {$this->evaluation->grade})")
            ->action('View Results', 'view', route('ai.interview'));
    }
}
