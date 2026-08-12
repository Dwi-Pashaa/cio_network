<?php

use App\Http\Controllers\Pages\PublicPendaftaranController;
use Illuminate\Support\Facades\Route;

Route::prefix('pendaftaran-baru')->group(function () {
    Route::get('/', [PublicPendaftaranController::class, 'index'])->name('public.pendaftaran.index');
    Route::get('/get-pages', [PublicPendaftaranController::class, 'getPages'])->name('public.pendaftaran.get_pages');
    Route::post('/store', [PublicPendaftaranController::class, 'store'])
        ->middleware('throttle:5,1')
        ->name('public.pendaftaran.store');
    Route::get('/{kode}/download', [PublicPendaftaranController::class, 'download'])->name('public.pendaftaran.download');
});

Route::get('/search-pendaftaran', [PublicPendaftaranController::class, 'search'])->name('public.pendaftaran.search');
