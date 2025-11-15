<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Pusher App ID
    |--------------------------------------------------------------------------
    |
    | This value is the unique identifier for your Pusher application.
    | You can find it in your Pusher dashboard.
    |
    */

    'app_id' => env('PUSHER_APP_ID'),

    /*
    |--------------------------------------------------------------------------
    | Pusher App Key
    |--------------------------------------------------------------------------
    |
    | This value is the key for your Pusher application.
    | You can find it in your Pusher dashboard.
    |
    */

    'app_key' => env('PUSHER_APP_KEY'),

    /*
    |--------------------------------------------------------------------------
    | Pusher App Secret
    |--------------------------------------------------------------------------
    |
    | This value is the secret for your Pusher application.
    | You can find it in your Pusher dashboard.
    |
    */

    'app_secret' => env('PUSHER_APP_SECRET'),

    /*
    |--------------------------------------------------------------------------
    | Pusher App Cluster
    |--------------------------------------------------------------------------
    |
    | This value is the cluster for your Pusher application.
    | You can find it in your Pusher dashboard.
    |
    */

    'app_cluster' => env('PUSHER_APP_CLUSTER', 'mt1'),

    /*
    |--------------------------------------------------------------------------
    | Pusher Use TLS
    |--------------------------------------------------------------------------
    |
    | This option specifies whether to use TLS when connecting to Pusher.
    |
    */

    'use_tls' => true,

];
