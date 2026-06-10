<?php

use App\Http\Controllers\Pages\CustomerController;
use App\Http\Controllers\Pages\PaketController;
use App\Http\Controllers\Pages\PriceController;
use App\Http\Controllers\Pages\TypeController;
use App\Http\Controllers\Pages\TypeCustomerController;
use Illuminate\Support\Facades\Route;

// data type pelanggan
Route::prefix('tipe=customer')->group(function () {
    Route::get('/', [TypeCustomerController::class, 'index'])->name('type.customer.index');
    Route::post('/store', [TypeCustomerController::class, 'store'])->name('type.customer.store');
    Route::get('/{id}/show', [TypeCustomerController::class, 'show'])->name('type.customer.show');
    Route::put('/{id}/update', [TypeCustomerController::class, 'update'])->name('type.customer.update');
    Route::delete('/{id}/destroy', [TypeCustomerController::class, 'destroy'])->name('type.customer.destroy');
});

Route::prefix('types')->group(function () {
    Route::get('/', [TypeController::class, 'index'])->name('type.index');
    Route::post('/store', [TypeController::class, 'store'])->name('type.store');
    Route::get('/{id}/show', [TypeController::class, 'show'])->name('type.show');
    Route::put('/{id}/update', [TypeController::class, 'update'])->name('type.update');
    Route::delete('/{id}/destroy', [TypeController::class, 'destroy'])->name('type.destroy');
});

Route::prefix('paket')->group(function () {
    Route::get('/', [PaketController::class, 'index'])->name('paket.index');
    Route::post('/store', [PaketController::class, 'store'])->name('paket.store');
    Route::get('/{id}/show', [PaketController::class, 'show'])->name('paket.show');
    Route::put('/{id}/update', [PaketController::class, 'update'])->name('paket.update');
    Route::delete('/{id}/destroy', [PaketController::class, 'destroy'])->name('paket.destroy');
});

Route::prefix('price')->group(function () {
    Route::get('/', [PriceController::class, 'index'])->name('price.index');
    Route::post('/store', [PriceController::class, 'store'])->name('price.store');
    Route::get('/{id}/show', [PriceController::class, 'show'])->name('price.show');
    Route::put('/{id}/update', [PriceController::class, 'update'])->name('price.update');
    Route::delete('/{id}/destroy', [PriceController::class, 'destroy'])->name('price.destroy');
});

// data pelanggan
Route::prefix('customer')->group(function () {
    Route::get('/', [CustomerController::class, 'index'])->name('customer.index');
    Route::get('/create', [CustomerController::class, 'create'])->name('customer.create')->can('buat pelanggan');
    Route::post('/store', [CustomerController::class, 'store'])->name('customer.store')->can('buat pelanggan');
    Route::post('/check-email', [CustomerController::class, 'checkEmail'])->name('customer.check-email');
    Route::get('/{id}/edit', [CustomerController::class, 'edit'])->name('customer.edit')->can('ubah pelanggan');
    Route::put('/{id}/update', [CustomerController::class, 'update'])->name('customer.update')->can('ubah pelanggan');
    Route::delete('/{id}/destroy', [CustomerController::class, 'destroy'])->name('customer.destroy');
    Route::get('/export', [CustomerController::class, 'export'])->name('customer.export');
    Route::get('/exportToGoogleSheet', [CustomerController::class, 'exportToGoogleSheet'])->name('customer.exportToGoogleSheet');
    Route::post('/get-select', [CustomerController::class, 'getSelect'])->name('customer.getSelect');
    Route::post('/send-notif', [CustomerController::class, 'notif'])->name('customer.notif');
    Route::post('/switch-olt', [CustomerController::class, 'switchOlt'])->name('customer.switchOlt');
    Route::post('/verify-email-on-demand', [CustomerController::class, 'verifyEmailOnDemand'])->name('customer.verify-email-on-demand');
    Route::post('/verify-wa-on-demand', [CustomerController::class, 'verifyWaOnDemand'])->name('customer.verify-wa-on-demand');
});
