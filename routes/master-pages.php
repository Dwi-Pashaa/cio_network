<?php

use App\Http\Controllers\Pages\ChattingController;
use App\Http\Controllers\Pages\ComplainController;
use App\Http\Controllers\Pages\HistoryController;
use App\Http\Controllers\Pages\PagesController;
use App\Http\Controllers\Pages\SpamController;
use App\Http\Controllers\Pages\SwitchPerangkatController;
use App\Http\Controllers\Pages\Wablass\ReportController;
use Illuminate\Support\Facades\Route;

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

    Route::prefix('switch')->group(function () {
        Route::get('/', [SwitchPerangkatController::class, 'index'])->name('switch.index');
        Route::post('/store', [SwitchPerangkatController::class, 'store'])->name('switch.store');
        Route::get('/{id}/show', [SwitchPerangkatController::class, 'show'])->name('switch.show');
        Route::put('/{id}/update', [SwitchPerangkatController::class, 'update'])->name('switch.update');
        Route::delete('/{id}/destroy', [SwitchPerangkatController::class, 'destroy'])->name('switch.destroy');
    });

    Route::prefix('spam')->group(function () {
        Route::get('/', [SpamController::class, 'index'])->name('spam.index');
        Route::put('/{id}/outSpam', [SpamController::class, 'outSpam'])->name('spam.outSpam');
        Route::put('/{id}/outSwitch', [SpamController::class, 'outSwitch'])->name('spam.outSwitch');
        Route::delete('/{id}/reject', [SpamController::class, 'reject'])->name('spam.reject');
    });
});

Route::prefix('input-datas')->group(function () {
    Route::get('/{slug}', [PagesController::class, 'getPagesBySlug'])->name('input.data.index')->middleware(['page.password']);
    Route::post('/confirm-password', [PagesController::class, 'confirmPagesPassword'])->name('input.data.confirm.password');
    Route::post('/save', [PagesController::class, 'saveCustomerToSpan'])->name('input.data.saveCustomerToSpan');
    Route::post('/check-mac-address', [PagesController::class, 'checkMacAddress'])->name('input.data.checkMacAddress');
});

Route::prefix('chatting')->group(function () {
    Route::get('/', [ChattingController::class, 'index'])->name('chatting.index');
    Route::post('/store', [ChattingController::class, 'store'])->name('chatting.store');
    Route::delete('/{id}/destroy', [ChattingController::class, 'destroy'])->name('chatting.destroy');
});

// data hostory
Route::prefix('history')->group(function () {
    Route::get('/', [HistoryController::class, 'index'])->name('history.index');
});

// report wablass
Route::prefix('report')->group(function () {
    Route::get('/', [ReportController::class, 'index'])->name('report.index');
});
