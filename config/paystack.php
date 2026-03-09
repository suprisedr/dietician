<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Paystack Keys
    |--------------------------------------------------------------------------
    |
    | The Paystack publishable key and secret key. You can find your keys
    | in your Paystack dashboard: https://dashboard.paystack.com/settings/developer
    |
    */

    'public_key' => env('PAYSTACK_PUBLIC_KEY'),

    'secret_key' => env('PAYSTACK_SECRET_KEY'),

    /*
    |--------------------------------------------------------------------------
    | Paystack Base URL
    |--------------------------------------------------------------------------
    |
    | This is the base URL for Paystack API requests. You shouldn't need to
    | change this unless you're using a custom Paystack setup.
    |
    */

    'base_url' => env('PAYSTACK_BASE_URL', 'https://api.paystack.co'),

    /*
    |--------------------------------------------------------------------------
    | Paystack Webhook Secret
    |--------------------------------------------------------------------------
    |
    | This is the webhook secret used to verify webhook requests from Paystack.
    | You can find this in your Paystack dashboard under webhooks.
    |
    */

    'webhook_secret' => env('PAYSTACK_WEBHOOK_SECRET'),
];
