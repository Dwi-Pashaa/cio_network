<?php

use GuzzleHttp\Cookie\CookieJar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/
use Illuminate\Support\Facades\Http;


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/olt/login', function () {

    $baseUrl = 'http://42.62.176.6:206';
    $username = 'dwi12345';
    $password = 'dwi12345';

    $session = Http::withOptions([
        'verify' => false,
    ])->get($baseUrl);

    $cookies = collect($session->cookies()->toArray())
        ->pluck('Value', 'Name')
        ->toArray();

    $key = md5($username . ':' . $password);
    $value = base64_encode($password);

    $login = Http::withOptions([
        'verify' => false,
    ])
        ->withHeaders([
            'Origin' => $baseUrl,
            'Referer' => $baseUrl . '/',
            'Content-Type' => 'application/json',
        ])
        ->withCookies($cookies, parse_url($baseUrl, PHP_URL_HOST))
        ->post($baseUrl . '/userlogin?form=login', [
            'method' => 'set',
            'param' => [
                'name' => $username,
                'key' => $key,
                'value' => $value,
                'captcha_v' => '',
                'captcha_f' => ''
            ]
        ]);

    $token = $login->header('X-Token');

    if (!$token) {
        return response()->json([
            'status' => false,
            'message' => 'Login gagal',
            'response' => $login->json()
        ]);
    }

    session([
        'olt_token' => $token,
        'olt_cookies' => $cookies
    ]);

    return response()->json([
        'status' => true,
        'token' => $token
    ]);
});

Route::get('/olt/ports', function () {

    $baseUrl = 'http://42.62.176.6:206';

    $token = session('olt_token');
    $cookies = session('olt_cookies');

    return response()->json([
        'token' => $token,
        'cookies' => $cookies
    ]);

    if (!$token) {
        return response()->json([
            'status' => false,
            'message' => 'Belum login'
        ]);
    }

    $ports = Http::withOptions([
        'verify' => false,
    ])
        ->withHeaders([
            'X-Token' => $token,
        ])
        ->withCookies($cookies, parse_url($baseUrl, PHP_URL_HOST))
        ->get($baseUrl . '/switch_port', [
            'form' => 'portlist_info',
            't' => now()->timestamp * 1000
        ]);

    return response()->json($ports->json());
});
