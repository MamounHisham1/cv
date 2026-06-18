<?php

namespace App\Jobs;

use App\Models\User;
use App\Services\TelegramService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class NotifyTelegramNewUser implements ShouldQueue
{
    use Queueable;

    public int $tries = 3;

    /** @var array<int, int> */
    public array $backoff = [10, 30, 60];

    public function __construct(public User $user) {}

    public function handle(TelegramService $telegram): void
    {
        try {
            $message = "👤 New user registered\n\n"
                ."Name: {$this->user->name}\n"
                ."Email: {$this->user->email}\n"
                .'Signed up: '.$this->user->created_at->format('Y-m-d H:i T');

            $telegram->sendMessage($message);
        } catch (\Throwable) {
        }
    }
}
