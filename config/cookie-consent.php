<?php

return [

    'enabled' => env('COOKIE_CONSENT_ENABLED', true),

    'cookie_name' => 'laravel_cookie_consent',

    'cookie_lifetime' => 365,

    'categories' => [
        'necessary' => [
            'enabled' => true,
            'toggleable' => false,
            'default' => true,
            'name' => 'Necessary',
            'description' => 'These cookies are required for the website to function properly and cannot be disabled.',
        ],
        'analytics' => [
            'enabled' => true,
            'toggleable' => true,
            'default' => false,
            'name' => 'Analytics',
            'description' => 'Help us improve by allowing anonymous usage statistics and performance tracking.',
        ],
        'marketing' => [
            'enabled' => true,
            'toggleable' => true,
            'default' => false,
            'name' => 'Marketing',
            'description' => 'Enable personalized content and targeted advertising based on your preferences.',
        ],
    ],

    'texts' => [
        'message' => 'We use cookies to enhance your experience, analyze traffic, and deliver personalized content.',
        'agree' => 'Accept All',
        'reject' => 'Reject Non-Essential',
        'customize' => 'Customize',
        'learn_more' => 'Learn More',
    ],

];
