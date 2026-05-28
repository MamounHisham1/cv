<?php

return [
    'token_rate' => 1,
    'token_unit' => 1000,
    'markup' => 3,

    'minimum_charge' => [
        'ai_evaluation' => 1,
        'ai_parse' => 1,
        'ai_builder_message' => 1,
        'ai_interview' => 3,
    ],

    'plans' => [
        'free' => [
            'monthly_credits' => 30,
            'free_builder_messages' => 5,
        ],
        'pro' => [
            'monthly_credits' => 100,
            'free_builder_messages' => 50,
        ],
        'enterprise' => [
            'monthly_credits' => 500,
            'free_builder_messages' => null,
        ],
    ],

    'referrals' => [
        'signup_reward' => 10,
        'invitee_signup_bonus' => 8,
        'purchase_bonus' => 15,
        'invitee_purchase_bonus' => 15,
    ],
];
