<?php

use App\Http\Controllers\Pages\BarangOperasional\BarangOperasionalController;
use App\Http\Controllers\Pages\BarangOperasional\TipeBarangOperasionalController;
use Illuminate\Support\Facades\Route;

Route::prefix('barang-operasional')->group(function () {
    // Tipe Barang
    Route::prefix('tipe')->group(function () {
        Route::get('/', [TipeBarangOperasionalController::class, 'index'])
            ->middleware('can:lihat tipe barang operasional')
            ->name('tipe-barang-operasional.index');
        Route::post('/store', [TipeBarangOperasionalController::class, 'store'])
            ->middleware('can:tambah tipe barang operasional')
            ->name('tipe-barang-operasional.store');
        Route::get('/{id}/show', [TipeBarangOperasionalController::class, 'show'])
            ->name('tipe-barang-operasional.show');
        Route::put('/{id}/update', [TipeBarangOperasionalController::class, 'update'])
            ->middleware('can:edit tipe barang operasional')
            ->name('tipe-barang-operasional.update');
        Route::delete('/{id}/destroy', [TipeBarangOperasionalController::class, 'destroy'])
            ->middleware('can:hapus tipe barang operasional')
            ->name('tipe-barang-operasional.destroy');
    });

    // Barang Operasional
    Route::get('/', [BarangOperasionalController::class, 'index'])
        ->middleware('can:lihat barang operasional')
        ->name('barang-operasional.index');
    Route::get('/barang', [BarangOperasionalController::class, 'index'])
        ->middleware('can:lihat barang operasional')
        ->name('barang-operasional.barang.index');
    Route::get('/tipe-info/{id}', [BarangOperasionalController::class, 'getTipeInfo'])
        ->name('barang-operasional.tipe-info');
    Route::post('/store', [BarangOperasionalController::class, 'store'])
        ->middleware('can:tambah barang operasional')
        ->name('barang-operasional.store');
    Route::get('/{id}/show', [BarangOperasionalController::class, 'show'])
        ->name('barang-operasional.show');
    Route::put('/{id}/update', [BarangOperasionalController::class, 'update'])
        ->middleware('can:edit barang operasional')
        ->name('barang-operasional.update');
    Route::delete('/{id}/destroy', [BarangOperasionalController::class, 'destroy'])
        ->middleware('can:hapus barang operasional')
        ->name('barang-operasional.destroy');

    // Distribusi (Admin -> User) & Transfer (User -> Teknisi)
    Route::post('/distribusi', [BarangOperasionalController::class, 'distribusi'])
        ->name('barang-operasional.distribusi');
    Route::post('/transfer-teknisi', [BarangOperasionalController::class, 'transferTeknisi'])
        ->name('barang-operasional.transfer-teknisi');

    // Riwayat Transfer
    Route::get('/riwayat', [BarangOperasionalController::class, 'riwayat'])
        ->middleware('can:lihat riwayat transfer barang')
        ->name('barang-operasional.riwayat');
});
