<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Pages\DashboardController;
use App\Http\Controllers\Pages\Pemukiman\DesaController;
use App\Http\Controllers\Pages\Pemukiman\KampungController;
use App\Http\Controllers\Pages\Pemukiman\RTController;
use App\Http\Controllers\Pages\Pemukiman\RWController;
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

Route::middleware(['auth'])->group(function() {
    // logout
    Route::get('/logout', [LoginController::class, 'logout'])->name('logout');

    // dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // data users
    Route::prefix('users')->group(function() {
        Route::get('/', [UserController::class, 'index'])->name('user.index');
        Route::get('/create', [UserController::class, 'create'])->name('user.create');
        Route::post('/store', [UserController::class, 'store'])->name('user.store');
        Route::get('/{id}/edit', [UserController::class, 'edit'])->name('user.edit');
        Route::put('/{id}/update', [UserController::class, 'update'])->name('user.update');
        Route::delete('/{id}/destroy', [UserController::class, 'destroy'])->name('user.destroy');
    });
    
    // data type pelanggan
    Route::prefix('types')->group(function() {
        Route::get('/', [TypeController::class, 'index'])->name('type.index');
        Route::post('/store', [TypeController::class, 'store'])->name('type.store');
        Route::get('/{id}/show', [TypeController::class, 'show'])->name('type.show');
        Route::put('/{id}/update', [TypeController::class, 'update'])->name('type.update');
        Route::delete('/{id}/destroy', [TypeController::class, 'destroy'])->name('type.destroy');
    });
    
    Route::prefix('master-settlement')->group(function() {
        // kampung
        Route::prefix('settlement')->group(function() {
            Route::get('/', [KampungController::class, 'index'])->name('kampung.index');
            Route::post('/store', [KampungController::class, 'store'])->name('kampung.store');
            Route::get('/{id}/show', [KampungController::class, 'show'])->name('kampung.show');
            Route::put('/{id}/update', [KampungController::class, 'update'])->name('kampung.update');
            Route::delete('/{id}/destroy', [KampungController::class, 'destroy'])->name('kampung.destroy');
        });
    
        // desa
        Route::prefix('village')->group(function() {
            Route::get('/', [DesaController::class, 'index'])->name('desa.index');
            Route::post('/store', [DesaController::class, 'store'])->name('desa.store');
            Route::get('/{id}/show', [DesaController::class, 'show'])->name('desa.show');
            Route::put('/{id}/update', [DesaController::class, 'update'])->name('desa.update');
            Route::delete('/{id}/destroy', [DesaController::class, 'destroy'])->name('desa.destroy');
        });
    
        // rt
        Route::prefix('rt')->group(function() {
            Route::get('/', [RTController::class, 'index'])->name('rt.index');
            Route::post('/store', [RTController::class, 'store'])->name('rt.store');
            Route::get('/{id}/show', [RTController::class, 'show'])->name('rt.show');
            Route::put('/{id}/update', [RTController::class, 'update'])->name('rt.update');
            Route::delete('/{id}/destroy', [RTController::class, 'destroy'])->name('rt.destroy');
        });
    
        // rw
        Route::prefix('rw')->group(function() {
            Route::get('/', [RWController::class, 'index'])->name('rw.index');
            Route::post('/store', [RWController::class, 'store'])->name('rw.store');
            Route::get('/{id}/show', [RWController::class, 'show'])->name('rw.show');
            Route::put('/{id}/update', [RWController::class, 'update'])->name('rw.update');
            Route::delete('/{id}/destroy', [RWController::class, 'destroy'])->name('rw.destroy');
        });
    });
    
    Route::prefix('master-region')->group(function() {
        // kabupaten
        Route::prefix('regencies')->group(function() {
            Route::get('/', [KabupatenController::class, 'index'])->name('kabupaten.index');
            Route::post('/store', [KabupatenController::class, 'store'])->name('kabupaten.store');
            Route::get('/{id}/show', [KabupatenController::class, 'show'])->name('kabupaten.show');
            Route::put('/{id}/update', [KabupatenController::class, 'update'])->name('kabupaten.update');
            Route::delete('/{id}/destroy', [KabupatenController::class, 'destroy'])->name('kabupaten.destroy');
        });
        
        // kecamatan
        Route::prefix('district')->group(function() {
            Route::get('/', [KecamatanController::class, 'index'])->name('kecamatan.index');
            Route::post('/store', [KecamatanController::class, 'store'])->name('kecamatan.store');
            Route::get('/{id}/show', [KecamatanController::class, 'show'])->name('kecamatan.show');
            Route::put('/{id}/update', [KecamatanController::class, 'update'])->name('kecamatan.update');
            Route::delete('/{id}/destroy', [KecamatanController::class, 'destroy'])->name('kecamatan.destroy');
        });
    });
});
