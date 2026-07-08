<?php

use App\Http\Controllers\Pages\Jaringan\MicRadiusController;
use App\Http\Controllers\Pages\Jaringan\ODCController;
use App\Http\Controllers\Pages\Jaringan\ODPController;
use App\Http\Controllers\Pages\Jaringan\OLTController;
use App\Http\Controllers\Pages\Jaringan\RouterController;
use App\Http\Controllers\Pages\Jaringan\ServerController;
use App\Http\Controllers\Pages\Jaringan\VlanController;
use App\Http\Controllers\Pages\MacAddressController;
use Illuminate\Support\Facades\Route;

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
        Route::get('/generate-code', [OLTController::class, 'generateCode'])->name('olt.generateCode');
        Route::post('/store', [OLTController::class, 'store'])->name('olt.store');
        Route::get('/{id}/show', [OLTController::class, 'show'])->name('olt.show');
        Route::put('/{id}/update', [OLTController::class, 'update'])->name('olt.update');
        Route::delete('/{id}/destroy', [OLTController::class, 'destroy'])->name('olt.destroy');
    });

    // server
    Route::prefix('server')->group(function () {
        Route::get('/', [ServerController::class, 'index'])->name('server.index');
        Route::get('/generate-code', [ServerController::class, 'generateCode'])->name('server.generateCode');
        Route::post('/store', [ServerController::class, 'store'])->name('server.store');
        Route::get('/{id}/show', [ServerController::class, 'show'])->name('server.show');
        Route::put('/{id}/update', [ServerController::class, 'update'])->name('server.update');
        Route::delete('/{id}/destroy', [ServerController::class, 'destroy'])->name('server.destroy');
    });

    // mic radius
    Route::prefix('mic-radius')->group(function () {
        Route::get('/', [MicRadiusController::class, 'index'])->name('mic.radius.index');
        Route::post('/store', [MicRadiusController::class, 'store'])->name('mic.radius.store');
        Route::get('/{id}/show', [MicRadiusController::class, 'show'])->name('mic.radius.show');
        Route::put('/{id}/update', [MicRadiusController::class, 'update'])->name('mic.radius.update');
        Route::delete('/{id}/destroy', [MicRadiusController::class, 'destroy'])->name('mic.radius.destroy');
        Route::get('/{id}/mix-login', [MicRadiusController::class, 'mixLogin'])->name('mic.radius.mixLogin');
    });
});

Route::prefix('mac-address')->group(function () {
    Route::get('/', [MacAddressController::class, 'index'])->name('mac.address.index');
    Route::post('/store', [MacAddressController::class, 'store'])->name('mac.address.store');
    Route::get('/{id}/show', [MacAddressController::class, 'show'])->name('mac.address.show');
    Route::put('/{id}/update', [MacAddressController::class, 'update'])->name('mac.address.update');
    Route::delete('/{id}/destroy', [MacAddressController::class, 'destroy'])->name('mac.address.destroy');
    Route::post('/toggle-mac-activation', [MacAddressController::class, 'toggleMacValidation'])->name('mac.address.toggleMacValidation');
    Route::get('/print-label', [MacAddressController::class, 'cetakLabel'])->name('mac.address.cetakLabel');
    Route::get('/{id}/get-customer', [MacAddressController::class, 'getCustomer'])->name('mac.address.getCustomer');
    Route::post('/switch-used', [MacAddressController::class, 'switchUsed'])->name('mac.address.switchUsed');
    Route::get('/statistics', [MacAddressController::class, 'statistic'])->name('mac.address.statistic');
});
