<?php

namespace App\Listeners;

use App\Jobs\NotifyTelegramNewUser;
use Illuminate\Auth\Events\Registered;

class NotifyTelegramNewUserListener
{
    public function handle(Registered $event): void
    {
        try {
            NotifyTelegramNewUser::dispatch($event->user);
        } catch (\Throwable) {
        }
    }
}
