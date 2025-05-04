<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\UserController;

Route::prefix('users')->group(function () {
    Route::get('/', [UserController::class,'index'])->name('admin.users.index');
    Route::get('/create', [UserController::class,'create'])->name('admin.users.create');
    Route::post('/store', [UserController::class,'store'])->name('admin.users.store');
    Route::get('/{id}', [UserController::class,'show'])->name('admin.users.show');
    Route::get('/{id}/edit', [UserController::class,'edit'])->name('admin.users.edit');
    Route::post('/{id}/update', [UserController::class,'update'])->name('admin.users.update');
    Route::post('/{id}/updateRoles', [UserController::class,'updateRoles'])->name('admin.users.updateRoles');
    Route::post('/{id}/updatePermissions', [UserController::class,'updatePermissions'])->name('admin.users.updatePermissions');
});