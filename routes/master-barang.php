<?php

use App\Http\Controllers\Pages\PatchCoreController;
use App\Http\Controllers\Pages\PLCController;
use App\Http\Controllers\Pages\UserPatchCoreController;
use App\Http\Controllers\Pages\UserPLCController;
use App\Http\Controllers\Pages\UserRouterController;
use Illuminate\Support\Facades\Route;


// data barang
Route::prefix('barang')->group(function () {
    // master barang
    Route::prefix('patch-core')->group(function () {
        Route::get('/', [PatchCoreController::class, 'index'])->name('patch.core.index');
        Route::post('/store', [PatchCoreController::class, 'store'])->name('patch.core.store');
        Route::get('/{id}/show', [PatchCoreController::class, 'show'])->name('patch.core.show');
        Route::put('/{id}/update', [PatchCoreController::class, 'update'])->name('patch.core.update');
        Route::delete('/{id}/destroy', [PatchCoreController::class, 'destroy'])->name('patch.core.destroy');
    });

    Route::prefix('plc')->group(function () {
        Route::get('/', [PLCController::class, 'index'])->name('plc.index');
        Route::post('/store', [PLCController::class, 'store'])->name('plc.store');
        Route::get('/{id}/show', [PLCController::class, 'show'])->name('plc.show');
        Route::put('/{id}/update', [PLCController::class, 'update'])->name('plc.update');
        Route::delete('/{id}/destroy', [PLCController::class, 'destroy'])->name('plc.destroy');
    });

    // stock
    Route::prefix('stock-router')->group(function () {
        Route::get('/', [UserRouterController::class, 'index'])->name('user.router.index');
        Route::post('/store', [UserRouterController::class, 'store'])->name('user.router.store');
        Route::post('/get-role', [UserRouterController::class, 'selectRole'])->name('user.router.selectRole');
        Route::get('/{id}/show', [UserRouterController::class, 'show'])->name('user.router.show');
        Route::put('/{id}/update', [UserRouterController::class, 'update'])->name('user.router.update');
        Route::delete('/{id}/destroy', [UserRouterController::class, 'destroy'])->name('user.router.destroy');
        Route::post('/addStore', [UserRouterController::class, 'addStore'])->name('user.router.addStore');
    });

    Route::prefix('stock-patch-core')->group(function () {
        Route::get('/', [UserPatchCoreController::class, 'index'])->name('user.patch.core.index');
        Route::post('/store', [UserPatchCoreController::class, 'store'])->name('user.patch.core.store');
        Route::post('/get-role', [UserPatchCoreController::class, 'selectRole'])->name('user.patch.core.selectRole');
        Route::get('/{id}/show', [UserPatchCoreController::class, 'show'])->name('user.patch.core.show');
        Route::put('/{id}/update', [UserPatchCoreController::class, 'update'])->name('user.patch.core.update');
        Route::delete('/{id}/destroy', [UserPatchCoreController::class, 'destroy'])->name('user.patch.core.destroy');
        Route::post('/addStore', [UserPatchCoreController::class, 'addStore'])->name('user.patch.core.addStore');
    });

    Route::prefix('stock-plc')->group(function () {
        Route::get('/', [UserPLCController::class, 'index'])->name('user.plc.index');
        Route::post('/store', [UserPLCController::class, 'store'])->name('user.plc.store');
        Route::post('/get-role', [UserPLCController::class, 'selectRole'])->name('user.plc.selectRole');
        Route::get('/{id}/show', [UserPLCController::class, 'show'])->name('user.plc.show');
        Route::put('/{id}/update', [UserPLCController::class, 'update'])->name('user.plc.update');
        Route::delete('/{id}/destroy', [UserPLCController::class, 'destroy'])->name('user.plc.destroy');
        Route::post('/addStore', [UserPLCController::class, 'addStore'])->name('user.plc.addStore');
    });
});
