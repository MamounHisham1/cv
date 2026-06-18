<?php

use App\Jobs\SendTelegramErrorAlert;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Queue;

describe('Telegram Error Alert', function () {
    beforeEach(function () {
        Queue::fake();
        Cache::flush();
    });

    it('dispatches an error alert job when an error is logged to the telegram channel', function () {
        Log::channel('telegram')->error('Test error message '.uniqid());

        Queue::assertPushed(SendTelegramErrorAlert::class);
    });

    it('does not spam duplicate errors within the throttle window', function () {
        $message = 'Duplicate throttle test '.uniqid();

        Log::channel('telegram')->error($message);
        Log::channel('telegram')->error($message);

        Queue::assertPushed(SendTelegramErrorAlert::class, 1);
    });

    it('ignores info level logs', function () {
        Log::channel('telegram')->info('Just some info '.uniqid());

        Queue::assertNotPushed(SendTelegramErrorAlert::class);
    });
});
