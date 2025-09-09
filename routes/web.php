<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Pages\ComplainController;
use App\Http\Controllers\Pages\CustomerController;
use App\Http\Controllers\Pages\DashboardController;
use App\Http\Controllers\Pages\Jaringan\ODCController;
use App\Http\Controllers\Pages\Jaringan\ODPController;
use App\Http\Controllers\Pages\Jaringan\OLTController;
use App\Http\Controllers\Pages\Jaringan\RouterController;
use App\Http\Controllers\Pages\Jaringan\VlanController;
use App\Http\Controllers\Pages\PagesController;
use App\Http\Controllers\Pages\Pemukiman\DesaController;
use App\Http\Controllers\Pages\Pemukiman\KampungController;
use App\Http\Controllers\Pages\Pemukiman\RTController;
use App\Http\Controllers\Pages\Pemukiman\RWController;
use App\Http\Controllers\Pages\RoleController;
use App\Http\Controllers\Pages\SpamController;
use App\Http\Controllers\Pages\TypeController;
use App\Http\Controllers\Pages\UserController;
use App\Http\Controllers\Pages\Wilayah\KabupatenController;
use App\Http\Controllers\Pages\Wilayah\KecamatanController;
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

Route::middleware(['auth'])->group(function () {
    // logout
    Route::get('/logout', [LoginController::class, 'logout'])->name('logout');

    // dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/get-detail-count/{id}/{text}', [DashboardController::class, 'getDetailCount'])->name('dashboard.getDetailCount');

    // data role
    Route::prefix('role')->group(function () {
        Route::get('/', [RoleController::class, 'index'])->name('role.index');
        Route::post('/store', [RoleController::class, 'store'])->name('role.store');
        Route::get('/{id}/permission', [RoleController::class, 'permission'])->name('role.permission');
        Route::put('/{id}/savePermission', [RoleController::class, 'savePermission'])->name('role.savePermission');
        Route::get('/{id}/show', [RoleController::class, 'show'])->name('role.show');
        Route::put('/{id}/update', [RoleController::class, 'update'])->name('role.update');
        Route::delete('/{id}/destroy', [RoleController::class, 'destroy'])->name('role.destroy');
    });

    // data users
    Route::prefix('users')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('user.index');
        Route::get('/create', [UserController::class, 'create'])->name('user.create');
        Route::post('/store', [UserController::class, 'store'])->name('user.store');
        Route::get('/{id}/edit', [UserController::class, 'edit'])->name('user.edit');
        Route::put('/{id}/update', [UserController::class, 'update'])->name('user.update');
        Route::delete('/{id}/destroy', [UserController::class, 'destroy'])->name('user.destroy');
    });

    // data type pelanggan
    Route::prefix('types')->group(function () {
        Route::get('/', [TypeController::class, 'index'])->name('type.index');
        Route::post('/store', [TypeController::class, 'store'])->name('type.store');
        Route::get('/{id}/show', [TypeController::class, 'show'])->name('type.show');
        Route::put('/{id}/update', [TypeController::class, 'update'])->name('type.update');
        Route::delete('/{id}/destroy', [TypeController::class, 'destroy'])->name('type.destroy');
    });

    // data pelanggan
    Route::prefix('customer')->group(function () {
        Route::get('/', [CustomerController::class, 'index'])->name('customer.index');
        Route::get('/create', [CustomerController::class, 'create'])->name('customer.create')->can('buat pelanggan');
        Route::post('/store', [CustomerController::class, 'store'])->name('customer.store')->can('buat pelanggan');
        Route::get('/{id}/edit', [CustomerController::class, 'edit'])->name('customer.edit')->can('ubah pelanggan');
        Route::put('/{id}/update', [CustomerController::class, 'update'])->name('customer.update')->can('ubah pelanggan');
        Route::delete('/{id}/destroy', [CustomerController::class, 'destroy'])->name('customer.destroy');
        Route::get('/export', [CustomerController::class, 'export'])->name('customer.export');
        Route::get('/exportToGoogleSheet', [CustomerController::class, 'exportToGoogleSheet'])->name('customer.exportToGoogleSheet');
    });

    // master jaringan
    Route::prefix('master-network')->group(function () {
        // router
        Route::prefix('router')->group(function () {
            Route::get('/', [RouterController::class, 'index'])->name('router.index');
            Route::post('/store', [RouterController::class, 'store'])->name('router.store');
            Route::get('/{id}/show', [RouterController::class, 'show'])->name('router.show');
            Route::put('/{id}/update', [RouterController::class, 'update'])->name('router.update');
            Route::delete('/{id}/destroy', [RouterController::class, 'destroy'])->name('router.destroy');
        });

        // vlan
        Route::prefix('vlan')->group(function () {
            Route::get('/', [VlanController::class, 'index'])->name('vlan.index');
            Route::post('/store', [VlanController::class, 'store'])->name('vlan.store');
            Route::get('/{id}/show', [VlanController::class, 'show'])->name('vlan.show');
            Route::put('/{id}/update', [VlanController::class, 'update'])->name('vlan.update');
            Route::delete('/{id}/destroy', [VlanController::class, 'destroy'])->name('vlan.destroy');
        });

        // odc
        Route::prefix('odc')->group(function () {
            Route::get('/', [ODCController::class, 'index'])->name('odc.index');
            Route::post('/store', [ODCController::class, 'store'])->name('odc.store');
            Route::get('/{id}/show', [ODCController::class, 'show'])->name('odc.show');
            Route::put('/{id}/update', [ODCController::class, 'update'])->name('odc.update');
            Route::delete('/{id}/destroy', [ODCController::class, 'destroy'])->name('odc.destroy');
        });

        // odc
        Route::prefix('odp')->group(function () {
            Route::get('/', [ODPController::class, 'index'])->name('odp.index');
            Route::post('/store', [ODPController::class, 'store'])->name('odp.store');
            Route::get('/{id}/show', [ODPController::class, 'show'])->name('odp.show');
            Route::put('/{id}/update', [ODPController::class, 'update'])->name('odp.update');
            Route::delete('/{id}/destroy', [ODPController::class, 'destroy'])->name('odp.destroy');
        });

        // olt
        Route::prefix('olt')->group(function () {
            Route::get('/', [OLTController::class, 'index'])->name('olt.index');
            Route::post('/store', [OLTController::class, 'store'])->name('olt.store');
            Route::get('/{id}/show', [OLTController::class, 'show'])->name('olt.show');
            Route::put('/{id}/update', [OLTController::class, 'update'])->name('olt.update');
            Route::delete('/{id}/destroy', [OLTController::class, 'destroy'])->name('olt.destroy');
        });
    });

    // master pemukiman
    Route::prefix('master-settlement')->group(function () {
        // kampung
        Route::prefix('settlement')->group(function () {
            Route::get('/', [KampungController::class, 'index'])->name('kampung.index');
            Route::post('/store', [KampungController::class, 'store'])->name('kampung.store');
            Route::get('/{id}/show', [KampungController::class, 'show'])->name('kampung.show');
            Route::put('/{id}/update', [KampungController::class, 'update'])->name('kampung.update');
            Route::delete('/{id}/destroy', [KampungController::class, 'destroy'])->name('kampung.destroy');
        });

        // desa
        Route::prefix('village')->group(function () {
            Route::get('/', [DesaController::class, 'index'])->name('desa.index');
            Route::post('/store', [DesaController::class, 'store'])->name('desa.store');
            Route::get('/{id}/show', [DesaController::class, 'show'])->name('desa.show');
            Route::put('/{id}/update', [DesaController::class, 'update'])->name('desa.update');
            Route::delete('/{id}/destroy', [DesaController::class, 'destroy'])->name('desa.destroy');
        });

        // rt
        Route::prefix('rt')->group(function () {
            Route::get('/', [RTController::class, 'index'])->name('rt.index');
            Route::post('/store', [RTController::class, 'store'])->name('rt.store');
            Route::get('/{id}/show', [RTController::class, 'show'])->name('rt.show');
            Route::put('/{id}/update', [RTController::class, 'update'])->name('rt.update');
            Route::delete('/{id}/destroy', [RTController::class, 'destroy'])->name('rt.destroy');
        });

        // rw
        Route::prefix('rw')->group(function () {
            Route::get('/', [RWController::class, 'index'])->name('rw.index');
            Route::post('/store', [RWController::class, 'store'])->name('rw.store');
            Route::get('/{id}/show', [RWController::class, 'show'])->name('rw.show');
            Route::put('/{id}/update', [RWController::class, 'update'])->name('rw.update');
            Route::delete('/{id}/destroy', [RWController::class, 'destroy'])->name('rw.destroy');
        });
    });

    // master wilayah
    Route::prefix('master-region')->group(function () {
        // kabupaten
        Route::prefix('regencies')->group(function () {
            Route::get('/', [KabupatenController::class, 'index'])->name('kabupaten.index');
            Route::post('/store', [KabupatenController::class, 'store'])->name('kabupaten.store');
            Route::get('/{id}/show', [KabupatenController::class, 'show'])->name('kabupaten.show');
            Route::put('/{id}/update', [KabupatenController::class, 'update'])->name('kabupaten.update');
            Route::delete('/{id}/destroy', [KabupatenController::class, 'destroy'])->name('kabupaten.destroy');
        });

        // kecamatan
        Route::prefix('district')->group(function () {
            Route::get('/', [KecamatanController::class, 'index'])->name('kecamatan.index');
            Route::post('/store', [KecamatanController::class, 'store'])->name('kecamatan.store');
            Route::get('/{id}/show', [KecamatanController::class, 'show'])->name('kecamatan.show');
            Route::put('/{id}/update', [KecamatanController::class, 'update'])->name('kecamatan.update');
            Route::delete('/{id}/destroy', [KecamatanController::class, 'destroy'])->name('kecamatan.destroy');
        });
    });

    // master hlaman
    Route::prefix('master-pages')->group(function () {
        // kabupaten
        Route::prefix('listing')->group(function () {
            Route::get('/', [PagesController::class, 'index'])->name('halaman.index');
            Route::post('/store', [PagesController::class, 'store'])->name('halaman.store');
            Route::get('/{id}/show', [PagesController::class, 'show'])->name('halaman.show');
            Route::put('/{id}/update', [PagesController::class, 'update'])->name('halaman.update');
            Route::delete('/{id}/destroy', [PagesController::class, 'destroy'])->name('halaman.destroy');
        });

        Route::prefix('complain')->group(function () {
            Route::get('/', [ComplainController::class, 'index'])->name('complain.index');
            Route::post('/store', [ComplainController::class, 'store'])->name('complain.store');
            Route::get('/{id}/show', [ComplainController::class, 'show'])->name('complain.show');
            Route::put('/{id}/update', [ComplainController::class, 'update'])->name('complain.update');
            Route::delete('/{id}/destroy', [ComplainController::class, 'destroy'])->name('complain.destroy');
        });

        Route::prefix('spam')->group(function () {
            Route::get('/', [SpamController::class, 'index'])->name('spam.index');
            Route::put('/{id}/outSpam', [SpamController::class, 'outSpam'])->name('spam.outSpam');
        });
    });
});

Route::prefix('input-datas')->group(function () {
    Route::get('/{slug}', [PagesController::class, 'getPagesBySlug'])->name('input.data.index')->middleware(['page.password']);
    Route::post('/confirm-password', [PagesController::class, 'confirmPagesPassword'])->name('input.data.confirm.password');
    Route::post('/save', [PagesController::class, 'saveCustomerToSpan'])->name('input.data.saveCustomerToSpan');
});

Route::prefix('complain')->group(function () {
    Route::get('/{slug}', [ComplainController::class, 'getPagesBySlug'])->name('complain.show.form');
    Route::post('/send-to-wa', [ComplainController::class, 'sendToWa'])->name('compalin.sendToWa');
});
