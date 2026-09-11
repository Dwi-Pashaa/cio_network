<?php

return [
    /*
    |--------------------------------------------------------------------------
    | MikroTik RouterOS Connection Settings
    |--------------------------------------------------------------------------
    */
    'host'      => env('MIKROTIK_HOST', '42.62.176.169'),
    'port'      => (int) env('MIKROTIK_PORT', 8728),
    'user'      => env('MIKROTIK_USER', 'dwi1234'),
    'pass'      => env('MIKROTIK_PASS', 'dwi1234'),
    'timeout'   => (int) env('MIKROTIK_TIMEOUT', 15),
    'cache_ttl' => (int) env('MIKROTIK_CACHE_TTL', 300), // 300 detik (5 menit)
];
