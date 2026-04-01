<?php

namespace App\Notifications;

use App\Models\CvEvaluation;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use NotificationChannels\WebPush\WebPushChannel;
use NotificationChannels\WebPush\WebPushMessage;

class EvaluationCompletedNotification extends Notification
{
    use Queueable;

    public function __construct(
        public CvEvaluation $evaluation,
    ) {}

    /**
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database', WebPushChannel::class];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject("Your CV evaluation is ready - Score: {$this->evaluation->overall_score}/100 (Grade: {$this->evaluation->grade})")
            ->line('Your CV evaluation is complete!')
            ->line("Score: {$this->evaluation->overall_score}/100")
            ->line("Grade: {$this->evaluation->grade}")
            ->action('View Evaluation', url('/evaluator'))
            ->line('Thank you for using our application!');
    }

    /**
     * @return array{evaluation_id: int, score: int, grade: string}
     */
    public function toArray(object $notifiable): array
    {
        return [
            'evaluation_id' => $this->evaluation->id,
            'score' => $this->evaluation->overall_score,
            'grade' => $this->evaluation->grade,
        ];
    }

    public function toWebPush(object $notifiable, mixed $subscription): WebPushMessage
    {
        return WebPushMessage::create()
            ->title('CV Evaluation Ready')
            ->body("Your CV evaluation is ready! Score: {$this->evaluation->overall_score}/100")
            ->action('View', 'view', url('/evaluator'));
    }
}
