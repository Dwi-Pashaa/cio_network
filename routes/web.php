<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Pages\DashboardController;
use App\Http\Controllers\Pages\Jaringan\MikrotikController;
use App\Http\Controllers\Pages\ProsedurController;
use App\Http\Controllers\Pages\PublicCustomerController;
use App\Http\Controllers\Pages\TroubleshootController;
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

// Public Registration Wizard (tanpa auth) — harus sebelum grup auth karena /pendaftaran-baru/{id} di master-pendaftaran
require __DIR__ . '/public-pendaftaran.php';

Route::middleware(['auth'])->group(function () {
    // logout
    Route::get('/logout', [LoginController::class, 'logout'])->name('logout');

    // dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/get-detail-count/{id}/{text}', [DashboardController::class, 'getDetailCount'])->name('dashboard.getDetailCount');

    // Monitoring MikroTik DHCP Hub
    Route::prefix('monitoring/mikrotik')->group(function () {
        Route::get('/', [MikrotikController::class, 'index'])->middleware('can:monitoring mikrotik')->name('mikrotik.dhcp');
        Route::get('/data', [MikrotikController::class, 'getDhcpData'])->middleware('can:monitoring mikrotik')->name('mikrotik.dhcp.data');
        Route::get('/interfaces', [MikrotikController::class, 'getInterfaces'])->middleware('can:monitoring traffic mikrotik')->name('mikrotik.dhcp.interfaces');
        Route::get('/traffic', [MikrotikController::class, 'getTraffic'])->middleware('can:monitoring traffic mikrotik')->name('mikrotik.dhcp.traffic');
        Route::get('/system-resource', [MikrotikController::class, 'getSystemResource'])->middleware('can:monitoring mikrotik')->name('mikrotik.dhcp.resource');
        Route::get('/customer-by-mac', [MikrotikController::class, 'getCustomerByMac'])->name('mikrotik.dhcp.customer_by_mac');
        Route::post('/sync-db', [MikrotikController::class, 'syncDatabase'])->middleware('can:monitoring mikrotik')->name('mikrotik.dhcp.sync_db');
        Route::post('/reboot', [MikrotikController::class, 'reboot'])->middleware('can:reboot mikrotik')->name('mikrotik.dhcp.reboot');
        Route::get('/stream', [MikrotikController::class, 'stream'])->name('mikrotik.dhcp.stream');
    });

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

    // Troubleshoot Tracking
    Route::get('/ticket/search-customer', [TroubleshootController::class, 'searchCustomer'])
        ->name('troubleshoot.search-customer');
    Route::get('/ticket/technicians-by-organization', [TroubleshootController::class, 'getTechniciansByOrganization'])
        ->name('troubleshoot.technicians-by-organization');
    Route::resource('ticket', TroubleshootController::class)
        ->only(['index', 'create', 'store', 'show', 'edit', 'update', 'destroy'])
        ->names([
            'index'  => 'troubleshoot.index',
            'create' => 'troubleshoot.create',
            'store'  => 'troubleshoot.store',
            'show'   => 'troubleshoot.show',
            'edit'   => 'troubleshoot.edit',
            'update' => 'troubleshoot.update',
            'destroy' => 'troubleshoot.destroy',
        ]);

    Route::get('/ticket/{id}/detail', [TroubleshootController::class, 'detail'])
        ->name('troubleshoot.detail');
    Route::get('/ticket/{id}/tracking', [TroubleshootController::class, 'tracking'])
        ->name('troubleshoot.tracking');
    Route::put('/ticket/{id}/status', [TroubleshootController::class, 'updateStatus'])
        ->name('troubleshoot.update-status');
    Route::post('/ticket/update-location', [TroubleshootController::class, 'updateLocation'])
        ->name('troubleshoot.update-location');
    Route::get('/ticket/{id}/tracking-data', [TroubleshootController::class, 'getTrackingData'])
        ->name('troubleshoot.tracking-data');
    Route::post('/ticket/{id}/progress/{step}', [TroubleshootController::class, 'uploadProgress'])
        ->name('troubleshoot.progress.upload');
});

Route::get('/search-customer', [PublicCustomerController::class, 'searchPage'])->name('public.customer.search');
Route::post('/search-customer', [PublicCustomerController::class, 'search'])->name('public.customer.search.post');
Route::get('/clientarea-login', [PublicCustomerController::class, 'proxyLogin'])->name('public.customer.clientarea_login');

Route::get('/reset-wifi', [PublicCustomerController::class, 'resetWifiPage'])->name('public.customer.reset_wifi');
Route::post('/reset-wifi/search', [PublicCustomerController::class, 'searchCustomerForReset'])->name('public.customer.reset_wifi.search');
Route::post('/reset-wifi/submit', [PublicCustomerController::class, 'submitResetPassword'])->name('public.customer.reset_wifi.submit');
