<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Pages\ChattingController;
use App\Http\Controllers\Pages\ComplainController;
use App\Http\Controllers\Pages\DashboardController;
use App\Http\Controllers\Pages\HistoryController;
use App\Http\Controllers\Pages\Jaringan\MicRadiusController;
use App\Http\Controllers\Pages\Jaringan\ODCController;
use App\Http\Controllers\Pages\Jaringan\ODPController;
use App\Http\Controllers\Pages\Jaringan\OLTController;
use App\Http\Controllers\Pages\Jaringan\RouterController;
use App\Http\Controllers\Pages\Jaringan\VlanController;
use App\Http\Controllers\Pages\MacAddressController;
use App\Http\Controllers\Pages\PagesController;
use App\Http\Controllers\Pages\Pemukiman\DesaController;
use App\Http\Controllers\Pages\Pemukiman\KampungController;
use App\Http\Controllers\Pages\Pemukiman\RTController;
use App\Http\Controllers\Pages\Pemukiman\RWController;
use App\Http\Controllers\Pages\SpamController;
use App\Http\Controllers\Pages\SwitchPerangkatController;
use App\Http\Controllers\Pages\Wablass\ReportController;
use App\Http\Controllers\Pages\Wilayah\KabupatenController;
use App\Http\Controllers\Pages\Wilayah\KecamatanController;
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
