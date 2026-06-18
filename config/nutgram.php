<?php

use SergiX44\Nutgram\Configuration;

return [

    /*
    |--------------------------------------------------------------------------
    | Bot Token
    |--------------------------------------------------------------------------
    |
    | The Telegram bot token issued by @BotFather.
    |
    */

    'token' => env('TELEGRAM_BOT_TOKEN'),

    /*
    |--------------------------------------------------------------------------
    | Configuration
    |--------------------------------------------------------------------------
    |
    | The Nutgram Configuration object. See vendor/nutgram/nutgram/src/Configuration.php
    | for all available options.
    |
    */

    'config' => [
        'pollingTimeout' => (int) env('TELEGRAM_POLLING_TIMEOUT', 10),
        'timeout' => (int) env('TELEGRAM_REQUEST_TIMEOUT', 60),
        'debug' => (bool) env('TELEGRAM_DEBUG', env('APP_DEBUG', false)),
        'allowedUpdates' => Configuration::DEFAULT_ALLOWED_UPDATES,
    ],

];
