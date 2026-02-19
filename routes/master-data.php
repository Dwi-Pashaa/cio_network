<?php

use App\Http\Controllers\Pages\RoleController;
use App\Http\Controllers\Pages\UserController;
use Illuminate\Support\Facades\Route;


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
