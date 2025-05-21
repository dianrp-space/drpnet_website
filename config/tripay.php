<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Tripay Configuration
    |--------------------------------------------------------------------------
    |
    | This file contains configuration for Tripay payment gateway integration.
    |
    */

    // Tripay Merchant Code
    'merchant_code' => env('TRIPAY_MERCHANT_CODE', ''),

    // Tripay API Key
    'api_key' => env('TRIPAY_API_KEY', ''),

    // Tripay Private Key
    'private_key' => env('TRIPAY_PRIVATE_KEY', ''),

    // Tripay Environment (sandbox or production)
    'sandbox' => env('TRIPAY_SANDBOX', true),

    // API URL based on environment
    'api_url' => env('TRIPAY_SANDBOX', true)
        ? 'https://tripay.co.id/api-sandbox/'
        : 'https://tripay.co.id/api/',

    // Payment Return URL
    'return_url' => env('TRIPAY_RETURN_URL', config('app.url') . '/deposit/payment/completed'),

    // Callback URL
    'callback_url' => env('TRIPAY_CALLBACK_URL', config('app.url') . '/payment/callback'),
]; 