<?php

use App\Http\Controllers\Pages\Wilayah\KabupatenController;
use App\Http\Controllers\Pages\Wilayah\KecamatanController;
use Illuminate\Support\Facades\Route;

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
