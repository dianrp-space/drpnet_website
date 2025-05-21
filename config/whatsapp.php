<?php

return [
    /*
    |--------------------------------------------------------------------------
    | WhatsApp API Configuration
    |--------------------------------------------------------------------------
    |
    | This file contains configuration for WhatsApp API integration.
    |
    */

    // WhatsApp API URL
    'api_url' => env('WHATSAPP_API_URL', ''),

    // WhatsApp API Token
    'api_token' => env('WHATSAPP_API_TOKEN', ''),

    // WhatsApp Sender Number (with country code, no + sign) 
    'sender' => env('WHATSAPP_SENDER', ''),

    // Enable WhatsApp Notifications
    'enabled' => env('WHATSAPP_ENABLED', false),
    
    /*
    |--------------------------------------------------------------------------
    | Notification Templates
    |--------------------------------------------------------------------------
    |
    | Templates for various notifications that can be sent via WhatsApp.
    | Variables are enclosed in {{double_curly_braces}}
    |
    */
    'templates' => [
        // New deposit notification
        'deposit_success' => 'Hello {{name}}, your deposit of {{amount}} has been received successfully. Your current balance is {{balance}}. Thank you for using our services.',
        
        // Order/purchase notification
        'purchase_success' => 'Hello {{name}}, your purchase of {{product}} for {{amount}} has been completed successfully. Thank you for your purchase!',
        
        // Balance transfer notification (sender)
        'transfer_sent' => 'Hello {{name}}, you have successfully transferred {{amount}} to {{recipient}}. Your current balance is {{balance}}.',
        
        // Balance transfer notification (receiver)
        'transfer_received' => 'Hello {{name}}, you have received {{amount}} from {{sender}}. Your current balance is {{balance}}.',
        
        // Low balance warning
        'low_balance' => 'Hello {{name}}, your balance is running low ({{balance}}). Please deposit funds to continue using our services.',
    ],
]; 