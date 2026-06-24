<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Pages\DashboardController;
use App\Http\Controllers\Pages\ProsedurController;
use App\Http\Controllers\Pages\PublicCustomerController;
use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [LoginController::class, 'index'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('post.login');

Broadcast::routes(['middleware' => ['auth']]);
require base_path('routes/channels.php');

Route::middleware(['auth'])->group(function () {
    // logout
    Route::get('/logout', [LoginController::class, 'logout'])->name('logout');

    // dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/get-detail-count/{id}/{text}', [DashboardController::class, 'getDetailCount'])->name('dashboard.getDetailCount');

    require __DIR__ . '/master-data.php';
    require __DIR__ . '/master-barang.php';
    require __DIR__ . '/master-customer.php';
    require __DIR__ . '/master-jaringan.php';
    require __DIR__ . '/master-pemukiman.php';
    require __DIR__ . '/master-wilayah.php';
    require __DIR__ . '/master-pages.php';

    // Prosedur: hanya bisa submit jika sudah login
    Route::post('/prosedur/store', [ProsedurController::class, 'storeProsedurSpam'])->name('public.prosedur.store');

    Route::get('/pergantian', [ProsedurController::class, 'index'])->name('public.prosedur');
    Route::get('/pergantian/search-customer', [ProsedurController::class, 'searchCustomer'])->name('public.prosedur.search_customer');
    Route::get('/pergantian/get-router-by-mac', [ProsedurController::class, 'getRouterByMac'])->name('public.prosedur.get_router_by_mac');
});

// Route::prefix('complain')->group(function () {
//     Route::get('/{slug}', [ComplainController::class, 'getPagesBySlug'])->name('complain.show.form');
//     Route::post('/send-to-wa', [ComplainController::class, 'sendToWa'])->name('compalin.sendToWa');
// });

// Route::prefix('switch')->group(function () {
//     Route::get('/{slug}', [SwitchPerangkatController::class, 'getPagesBySlug'])->name('switch.data.index')->middleware(['page.password']);
//     Route::post('/confirm-switch-password', [SwitchPerangkatController::class, 'confirmPagesPassword'])->name('switch.data.confirm.password');
//     Route::post('/save-switch-device', [SwitchPerangkatController::class, 'saveSwitchDevice'])->name('switch.data.save');
// });

Route::get('/search-customer', [PublicCustomerController::class, 'searchPage'])->name('public.customer.search');
Route::post('/search-customer', [PublicCustomerController::class, 'search'])->name('public.customer.search.post');
Route::get('/clientarea-login', [PublicCustomerController::class, 'proxyLogin'])->name('public.customer.clientarea_login');

Route::get('/reset-wifi', [PublicCustomerController::class, 'resetWifiPage'])->name('public.customer.reset_wifi');
Route::post('/reset-wifi/search', [PublicCustomerController::class, 'searchCustomerForReset'])->name('public.customer.reset_wifi.search');
Route::post('/reset-wifi/submit', [PublicCustomerController::class, 'submitResetPassword'])->name('public.customer.reset_wifi.submit');
