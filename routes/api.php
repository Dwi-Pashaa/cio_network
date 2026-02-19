<?php

use GuzzleHttp\Cookie\CookieJar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Broadcast;
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

Route::get('/check-report', function () {

    $url = config('wablas.api_url') . '/api/report/message';

    $token = config('wablas.token');
    $secret_key = config('wablas.secret_key');

    $response = Http::withHeaders([
        'Authorization' => $token . '.' . $secret_key,
    ])->get($url, [
        'date'       => request('date', '2022-04-11'),
        'perPage'    => request('perPage', 100),
        'phone'      => request('phone'),
        'page'       => request('page', 1),
        'message_id' => request('message_id'),
        'type'       => request('type'),
        'status'     => request('status'),
    ]);

    return $response->json();
});

Route::get('/mix-auto', function () {

    $baseUrl = 'https://mixcio.topsetting.com:973';

    // Buat CookieJar
    $cookieJar = new CookieJar();

    // LOGIN
    $login = Http::withOptions([
        'verify'  => false,
        'cookies' => $cookieJar, // ✅ bukan true
    ])->asForm()->post($baseUrl . '/rad-admin/post', [
        'username' => 'dwi12345',
        'password' => 'dwi12345',
    ]);

    // REQUEST DATA (pakai cookie yang sama)
    $response = Http::withOptions([
        'verify'  => false,
        'cookies' => $cookieJar, // ✅ pakai jar yang sama
    ])->post($baseUrl . '/rad-get-data/wablastlog', [
        'page'    => 1,
        'perPage' => 10,
    ]);

    return $response->body();
});
