<?php

return [
    'secret_key' => env('STRIPE_SECRET_KEY'),
    'publishable_key' => env('STRIPE_PUBLISHABLE_KEY'),
    'webhook_secret' => env('STRIPE_WEBHOOK_SECRET'),

    'currency' => env('STRIPE_CURRENCY', 'usd'),

    'platform_fee_percentage' => 3,

    // Normal completion
    'doctor_percentage'   => 90,
    'platform_percentage' => 10,

    // Late Cancel (after 24 hours)
    'late_cancel_doctor_percentage'   => 70,
    'late_cancel_platform_percentage' => 30,

    // No Show
    'no_show_doctor_percentage'   => 90,
    'no_show_platform_percentage' => 10,

    //commission for in-person appointments
    'platform_in_person_percentage' => 10,
];
