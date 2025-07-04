<?php

use App\Http\Controllers\NotificationController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PositionController;
use App\Http\Controllers\PerfilController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\DashboardController;

use Illuminate\Support\Facades\Route;

// Rotas públicas para autenticação e gerenciamento de conta
Route::post('/register', [AuthController::class, 'register'])->name('api.register');
Route::post('/login', [AuthController::class, 'login'])->name('api.login');
Route::post('/mailverify', [AuthController::class, 'mailVerify'])->name('api.mailverify');
Route::post('/reset', [AuthController::class, 'reset'])->name('api.reset');

// Rotas de teste (se realmente necessário)
Route::get('/log', [DashboardController::class, 'log'])->name('log');

// Listar permissões
Route::get('/permissions', [PermissionController::class, 'index']);
// Criar nova permissão
Route::post('/permissions', [PermissionController::class, 'store']);
// Vincular permissão a uma regra
Route::post('/rules/{rule}/permissions', [PermissionController::class, 'attachToRule']);

// Grupo de rotas NÃO protegidas
Route::get('/perfil/tipos', [PerfilController::class, 'tiposPerfis']);




// Grupo de rotas protegidas por autenticação
Route::middleware('auth:api')->group(function () {
    Route::get('/me', [AuthController::class, 'me'])->name('me');
    Route::get('/validar', [AuthController::class, 'validar'])->name('api.validar');

    // Rotas para agentes de viagem
    Route::prefix('agentes-viagem')->group(function () {
        Route::get('/', [\App\Http\Controllers\Api\AgenteViagemController::class, 'index']);
        Route::get('/buscar', [\App\Http\Controllers\Api\AgenteViagemController::class, 'buscar']);
        Route::get('/{id}', [\App\Http\Controllers\Api\AgenteViagemController::class, 'show']);
    });

    // Rotas de autenticação do utilizador
    Route::post('/logout', [AuthController::class, 'logout'])->name('api.logout');
    Route::delete('/delete', [AuthController::class, 'delete'])->name('api.delete');

    // Rotas de gerenciamento de perfil
    Route::get('/perfil', [PerfilController::class, 'show']);
    Route::put('/perfil', [PerfilController::class, 'update']);
    Route::post('/perfil/completar', [PerfilController::class, 'completar']);
    Route::post('/perfil/updateavatar', [PerfilController::class, 'updateAvatar']);

    // Rotas de atividades e notificações
    Route::get('/notifications', [NotificationController::class, 'index'])->name('api.notifications');

    // Rotas para gestão de cargos (protegidas)
    Route::prefix('positions')->group(function () {
        Route::get('/', [PositionController::class, 'index'])->name('api.positions.index');
        Route::post('/', [PositionController::class, 'store'])->name('api.positions.store');
        Route::get('/{id}', [PositionController::class, 'show'])->name('api.positions.show');
        Route::put('/{id}', [PositionController::class, 'update'])->name('api.positions.update');
        Route::delete('/{id}', [PositionController::class, 'destroy'])->name('api.positions.destroy');
    });

    Route::prefix('users')->group(function () {
        Route::get('/', [UserController::class, 'index']);
        Route::post('/', [UserController::class, 'store']);
        Route::get('/{id}', [UserController::class, 'show']);
        Route::put('/{id}', [UserController::class, 'update']);
        Route::delete('/{id}', [UserController::class, 'destroy']);
        Route::post('/{id}/activate', [UserController::class, 'activate']);
        Route::post('/{id}/deactivate', [UserController::class, 'deactivate']);
        Route::post('/{id}/assign-role', [UserController::class, 'assignRole']);
        Route::post('/{id}/remove-role', [UserController::class, 'removeRole']);
    });
});
