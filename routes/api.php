<?php

use App\Http\Controllers\VerificationCodeController;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

// Rotas de Autenticação
Route::get('/', [AuthController::class, 'teste']);

Route::post('/register', [AuthController::class, 'register'])->name('.register');
Route::post('/login', [AuthController::class, 'login'])->name('.login');
Route::post('/verifycode', [VerificationCodeController::class, 'verifyCode'])->name('.verifycode');
Route::post('/mailverify', [AuthController::class, 'mailVerify'])->name('.mailverify');
Route::post('/reset', [AuthController::class, 'reset'])->name('.reset');

// Grupo de rotas protegidas por autenticação
Route::middleware('auth:api')->group(function () {
    Route::post('logout', [AuthController::class, 'logout'])->name('.logout');;
    Route::get('me', [AuthController::class, 'me'])->name('.me');;
});
