<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Telegram Bot Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration for Telegram bot integration with the chat system.
    | Get your bot token from @BotFather on Telegram.
    |
    */

    'bot_token' => env('TELEGRAM_BOT_TOKEN'),
    
    'chat_id' => env('TELEGRAM_CHAT_ID'),
    
    /*
    |--------------------------------------------------------------------------
    | Webhook Settings
    |--------------------------------------------------------------------------
    |
    | These settings control the webhook functionality for receiving
    | Telegram updates in real-time.
    |
    */
    
    'webhook_enabled' => env('TELEGRAM_WEBHOOK_ENABLED', false),
    
    'webhook_url' => env('TELEGRAM_WEBHOOK_URL', env('APP_URL') . '/api/telegram/webhook'),
    
    /*
    |--------------------------------------------------------------------------
    | Notification Settings
    |--------------------------------------------------------------------------
    |
    | Control which types of notifications are sent to Telegram
    |
    */
    
    'notify_new_messages' => env('TELEGRAM_NOTIFY_NEW_MESSAGES', true),
    
    'notify_new_sessions' => env('TELEGRAM_NOTIFY_NEW_SESSIONS', true),
    
    /*
    |--------------------------------------------------------------------------
    | Message Templates
    |--------------------------------------------------------------------------
    |
    | Customize the message templates sent to Telegram
    |
    */
    
    'templates' => [
        'new_message' => '🔔 New chat message from {name}',
        'new_session' => '👋 New chat session started',
        'reply_sent' => '✅ Reply sent successfully'
    ]
];
