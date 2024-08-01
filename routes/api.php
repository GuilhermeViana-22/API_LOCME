<?php

use App\Http\Controllers\VerificationCodeController;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

// Rotas de Autenticação
Route::prefix('api')->group(function () {
    // Rota de teste para verificar se a API está funcionando
    Route::get('/', [AuthController::class, 'teste'])->name('api.test');

    // Rotas públicas para autenticação e gerenciamento de conta
    Route::post('register', [AuthController::class, 'register'])->name('api.register');
    Route::post('login', [AuthController::class, 'login'])->name('api.login');
    Route::post('verifycode', [VerificationCodeController::class, 'verifyCode'])->name('api.verifycode');
    Route::post('mailverify', [AuthController::class, 'mailVerify'])->name('api.mailverify');
    Route::post('reset', [AuthController::class, 'reset'])->name('api.reset');

    // Rotas protegidas por autenticação
    Route::middleware('auth:api')->group(function () {
        Route::post('logout', [AuthController::class, 'logout'])->name('api.logout');
        Route::get('me', [AuthController::class, 'me'])->name('api.me');
    });
});
