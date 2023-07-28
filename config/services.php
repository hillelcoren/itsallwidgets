<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Stripe, Mailgun, SparkPost and others. This file provides a sane
    | default location for this type of information, allowing packages
    | to have a conventional place to find your various credentials.
    |
    */

    'google' => [
        'client_id' => env('GOOGLE_CLIENT_ID'),
        'client_secret' => env('GOOGLE_CLIENT_SECRET'),
        'redirect' => env('GOOGLE_CALLBACK_URL'),
    ],

    'analytics' => [
        'tracking_id' => env('TRACKING_ID'),
    ],

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
    ],

    'ses' => [
        'key' => env('SES_KEY'),
        'secret' => env('SES_SECRET'),
        'region' => env('SES_REGION', 'us-east-1'),
    ],

    'sparkpost' => [
        'secret' => env('SPARKPOST_SECRET'),
    ],

    'stripe' => [
        'model' => App\Model\User::class,
        'key' => env('STRIPE_KEY'),
        'secret' => env('STRIPE_SECRET'),
    ],

    'twitter' => [
        'consumer_key' => env('TWITTER_CONSUMER_KEY'),
        'consumer_secret' => env('TWITTER_CONSUMER_SECRET'),
        'access_token' => env('TWITTER_ACCESS_TOKEN'),
        'access_secret' => env('TWITTER_ACCESS_SECRET'),
        'bearer_token' => env('TWITTER_BEARER_TOKEN'),
    ],

    'twitter_streams' => [
        'consumer_key' => env('TWITTER_STREAMS_CONSUMER_KEY'),
        'consumer_secret' => env('TWITTER_STREAMS_CONSUMER_SECRET'),
        'access_token' => env('TWITTER_STREAMS_ACCESS_TOKEN'),
        'access_secret' => env('TWITTER_STREAMS_ACCESS_SECRET'),
        'bearer_token' => env('TWITTER_STREAMS_BEARER_TOKEN'),
    ],

    'facebook_poster' => [
    	'app_id' => env('FACEBOOK_APP_ID'),
    	'app_secret' => env('FACEBOOK_APP_SECRET'),
    	'access_token' => env('FACEBOOK_ACCESS_TOKEN'),
    ],

    'meetup' => [
        'key' => env('MEETUP_API_KEY'),
    ],

    'feeds' => [
        'blogs' => env('FEED_BLOGS'),
        'videos' => env('FEED_VIDEOS'),
    ],

    'youtube' => [
        'key' => env('YOUTUBE_API_KEY'),
    ],

    'twitch' => [
        'client_id' => env('TWITCH_CLIENT_ID'),
        'client_secret' => env('TWITCH_SECRET'),
        'redirect' => env('TWITCH_REDIRECT_URI')
    ],
];
