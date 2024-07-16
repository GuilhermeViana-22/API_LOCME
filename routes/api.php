<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

// Rotas de Autenticação



Route::post('/register', [AuthController::class, 'register'])->name('.register');
Route::post('/login', [AuthController::class, 'login'])->name('.login');

// Grupo de rotas protegidas por autenticação
Route::middleware('auth:api')->group(function () {

    Route::get('user', function (Request $request) {
        return $request->user();
    });

    Route::post('logout', [AuthController::class, 'logout'])->name('.logout');;
    Route::get('me', [AuthController::class, 'me'])->name('.me');;

    Route::get('teste', [AuthController::class, 'teste']);

});
