<?php

use App\Http\Controllers\Pages\Pemukiman\DesaController;
use App\Http\Controllers\Pages\Pemukiman\KampungController;
use App\Http\Controllers\Pages\Pemukiman\RTController;
use App\Http\Controllers\Pages\Pemukiman\RWController;
use Illuminate\Support\Facades\Route;

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
