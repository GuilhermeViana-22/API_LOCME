<?php

use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\NotificationController;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UnidadeController;
use App\Http\Controllers\CargoController;
use App\Http\Controllers\PerguntaController;
use Illuminate\Support\Facades\Route;

// Rotas públicas para autenticação e gerenciamento de conta
Route::post('/register', [AuthController::class, 'register'])->name('api.register');
Route::post('/login', [AuthController::class, 'login'])->name('api.login');
Route::post('/mailverify', [AuthController::class, 'mailVerify'])->name('api.mailverify');
Route::post('/reset', [AuthController::class, 'reset'])->name('api.reset');

// Rotas de teste (se realmente necessário)
Route::get('/me', [AuthController::class, 'me'])->name('me');


// Grupo de rotas protegidas por autenticação
Route::middleware('auth:api')->group(function () {
    // Rotas de autenticação do usuário
    Route::post('/logout', [AuthController::class, 'logout'])->name('api.logout');
    Route::put('/profile', [AuthController::class, 'updateProfile'])->name('api.updateProfile');
    Route::delete('/delete', [AuthController::class, 'delete'])->name('api.delete');

    // Rotas de atividades e notificações
    Route::get('/activity', [ActivityLogController::class, 'index'])->name('api.activityLog');
    Route::get('/notifications', [NotificationController::class, 'index'])->name('api.notifications');

    // Rotas para gestão de unidades (protegidas)
    Route::prefix('unidades')->group(function () {
        Route::get('/', [UnidadeController::class, 'index'])->name('api.unidades.index');
        Route::post('/', [UnidadeController::class, 'store'])->name('api.unidades.store');
        Route::get('/{id}', [UnidadeController::class, 'show'])->name('api.unidades.show');
        Route::put('/{id}', [UnidadeController::class, 'update'])->name('api.unidades.update');
        Route::delete('/{id}', [UnidadeController::class, 'destroy'])->name('api.unidades.destroy');
    });

    // Rotas para gestão de cargos (protegidas)
    Route::prefix('cargos')->group(function () {
        Route::get('/', [CargoController::class, 'index'])->name('api.cargos.index');
        Route::post('/', [CargoController::class, 'store'])->name('api.cargos.store');
        Route::get('/{id}', [CargoController::class, 'show'])->name('api.cargos.show');
        Route::put('/{id}', [CargoController::class, 'update'])->name('api.cargos.update');
        Route::delete('/{id}', [CargoController::class, 'destroy'])->name('api.cargos.destroy');
    });

    // Rotas para gestão de cargos (protegidas)
    Route::prefix('perguntas')->group(function () {
        Route::get('/', [PerguntaController::class, 'index'])->name('api.perguntas.index');
        Route::post('/', [PerguntaController::class, 'store'])->name('api.perguntas.store');
        Route::get('/{id}', [PerguntaController::class, 'show'])->name('api.perguntas.show');
        Route::put('/{id}', [PerguntaController::class, 'update'])->name('api.perguntas.update');
        Route::delete('/{id}', [PerguntaController::class, 'destroy'])->name('api.perguntas.destroy');
    });
});


Route::get('/teste', [AuthController::class, 'teste'])->name('teste');
