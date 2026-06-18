<?php

use App\Jobs\NotifyTelegramNewUser;
use App\Listeners\NotifyTelegramNewUserListener;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Queue;

describe('New User Telegram Notification', function () {
    it('dispatches the new user notification job when a user registers', function () {
        Queue::fake();

        $user = User::factory()->create();

        event(new Registered($user));

        Queue::assertPushed(NotifyTelegramNewUser::class);
    });

    it('the listener dispatches the job', function () {
        Queue::fake();

        $user = User::factory()->create();

        (new NotifyTelegramNewUserListener)->handle(new Registered($user));

        Queue::assertPushed(NotifyTelegramNewUser::class);
    });
});
