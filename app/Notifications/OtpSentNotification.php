<?php

namespace App\Notifications;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class OtpSentNotification extends Notification
{
    use Queueable;

    public function __construct(
        public string $maskedOtpCode,
        public Carbon $expiresAt,
    ) {}

    /**
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * @return array{masked_otp_code: string, expires_at: string}
     */
    public function toArray(object $notifiable): array
    {
        return [
            'masked_otp_code' => $this->maskedOtpCode,
            'expires_at' => $this->expiresAt->toDateTimeString(),
        ];
    }
}
