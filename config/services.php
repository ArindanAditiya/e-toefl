<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
        'scheme' => 'https',
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'telegram-bot-api' => [
        'token' => env('TELEGRAM_BOT_TOKEN'),
        'group_id' => env('TELEGRAM_BOT_GROUPID'),
    ],

    'whatsapp' => [
        'token' => env('WHATSAPP_TOKEN'),
    ],

    'brevo' => [
        'active' => env('BREVO_ACTIVE', false),
        'api_key' => env('BREVO_API_KEY'),
        'sender_email' => env('BREVO_SENDER_EMAIL', 'admin@etoefl.mahatbi.or.id'),
        'sender_name' => env('BREVO_SENDER_NAME', "e-TOEFL Ma'had Aly Hasyim Asy'ari Tebuireng"),
    ],

    'admin' => [
        'wa' => env('ADMIN_WA', '@mahadalytbi'),
        'email' => env('ADMIN_EMAIL', 'mahadaly@tebuireng.ac.id'),
        'address' => env('ADMIN_ADDRESS', 'Jl. Irian Jaya No. 10, Tebuireng, Cukir, Kec. Diwek, Kabupaten Jombang, Jawa Timur 61471'),
    ],

    'turnstile' => [
        'site_key' => env('TURNSTILE_SITE_KEY', '1x00000000000000000000AA'),
        'secret_key' => env('TURNSTILE_SECRET_KEY', '1x00000000000000000000000000000000BB'),
    ],

    'google' => [
        'client_id' => env('GOOGLE_CLIENT_ID'),
        'client_secret' => env('GOOGLE_CLIENT_SECRET'),
        'redirect' => env('GOOGLE_REDIRECT_URI', env('APP_URL', 'http://toefl.test') . '/auth/google/callback'),
    ],

];
