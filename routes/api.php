<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\VerificationCodeController;


Route::post('/register', [AuthController::class, 'register'])->name('api.register');
Route::post('/login', [AuthController::class, 'login'])->name('api.login');
Route::post('/verifycode', [VerificationCodeController::class, 'verifyCode'])->name('api.verifycode');
Route::post('/mailverify', [AuthController::class, 'mailVerify'])->name('api.mailverify');
Route::post('/reset', [AuthController::class, 'reset'])->name('api.reset');
Route::get('/me', [AuthController::class, 'me'])->name('me');
Route::get('/teste', [AuthController::class, 'teste'])->name('teste');
Route::get('/hi', [AuthController::class, 'hi'])->name('hi');

// Grupo de rotas protegidas por autenticação
Route::middleware('auth:api')->group(function () {

    Route::post('/logout', [AuthController::class, 'logout'])->name('api.logout');
    Route::put('/profile', [AuthController::class, 'updateProfile'])->name('api.updateProfile');
    Route::delete('/delete', [AuthController::class, 'delete'])->name('api.delete');
    Route::get('/activity', [ActivityLogController::class, 'index'])->name('api.activityLog');
    Route::get('/notifications', [NotificationController::class, 'index'])->name('api.notifications');
});
