<?php

return [
    'api_key' => env('VFCASH_API_KEY'),
    'api_url' => env('VFCASH_API_URL', 'https://vf-cash.softland.tech/api/v1'),
    'webhook_secret' => env('VFCASH_WEBHOOK_SECRET'),

    /*
    |--------------------------------------------------------------------------
    | Pricing (EGP)
    |--------------------------------------------------------------------------
    | Exchange rate: 1 USD = 50 EGP
    | Pro = $6/mo = 300 EGP → 100 credits
    | Enterprise = $20/mo = 1000 EGP → 500 credits
    |
    | Topup packs: volume discount (more credits per EGP at higher tiers)
    */
    'plans' => [
        'pro' => [
            'name' => 'Pro',
            'price_egp' => 300,
            'credits' => 100,
            'description' => '100 monthly credits for interviews, evaluations, and AI chat',
        ],
        'enterprise' => [
            'name' => 'Enterprise',
            'price_egp' => 1000,
            'credits' => 500,
            'description' => '500 monthly credits with priority access',
        ],
    ],

    'topups' => [
        'topup_50' => [
            'name' => 'Starter',
            'price_egp' => 60,
            'credits' => 50,
            'badge' => null,
        ],
        'topup_120' => [
            'name' => 'Standard',
            'price_egp' => 120,
            'credits' => 120,
            'badge' => 'Popular',
        ],
        'topup_300' => [
            'name' => 'Pro Pack',
            'price_egp' => 250,
            'credits' => 300,
            'badge' => 'Best Value',
        ],
    ],
];
