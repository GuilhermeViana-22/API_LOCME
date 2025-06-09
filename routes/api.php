<?php

use App\Http\Controllers\NotificationController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UnidadeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PositionController;
use App\Http\Controllers\ProfileController;
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


// Listar permissões
Route::get('/permissions', [PermissionController::class, 'index']);
// Criar nova permissão
Route::post('/permissions', [PermissionController::class, 'store']);
// Vincular permissão a uma regra
Route::post('/rules/{rule}/permissions', [PermissionController::class, 'attachToRule']);

// Rotas adicionais (atualizar, deletar, etc.)


// // Rotas para gestão de usuários (protegidas e com verificação de permissões)
// Route::prefix('users')->group(function () {
//     //listar todos os ususarios
//     Route::get('/', [UserController::class, 'index'])->name('api.users.index');
//     //rotas para salvar dados do user
//     Route::post('/', [UserController::class, 'store'])->name('api.users.store')
//         ->middleware('permission:create_users');
//     //rotas para mostrar dados do user
//     Route::get('/{id}', [UserController::class, 'show'])->name('api.users.show')
//         ->middleware('permission:view_users');
//     //rotas para atualizar dados do user
//     Route::put('/{id}', [UserController::class, 'update'])->name('api.users.update')
//         ->middleware('permission:edit_users');
//     //rotas para deletar de forma inteligente dados do user
//     Route::delete('/{id}', [UserController::class, 'destroy'])->name('api.users.destroy')
//         ->middleware('permission:delete_users');

//     // Rotas adicionais para gerenciamento de usuários
//     Route::post('/{id}/activate', [UserController::class, 'activate'])->name('api.users.activate')
//         ->middleware('permission:manage_users');
//     Route::post('/{id}/deactivate', [UserController::class, 'deactivate'])->name('api.users.deactivate')
//         ->middleware('permission:manage_users');
//     Route::post('/{id}/assign-role', [UserController::class, 'assignRole'])->name('api.users.assignRole')
//         ->middleware('permission:assign_roles');
// });

// Grupo de rotas protegidas por autenticação
Route::middleware('auth:api')->group(function () {
    Route::get('/me', [AuthController::class, 'me'])->name('me');
    Route::get('/validar', [AuthController::class, 'validar'])->name('api.validar');

    // Rotas de autenticação do usuário
    Route::post('/logout', [AuthController::class, 'logout'])->name('api.logout');
    Route::delete('/delete', [AuthController::class, 'delete'])->name('api.delete');

    // Rotas de gerenciamento de conta
    Route::get('/profile', [ProfileController::class, 'show']);
    Route::put('/profile', [ProfileController::class, 'update']);
    Route::post('/profile/completar', [ProfileController::class, 'completar']);
    Route::post('/profile/updateavatar', [ProfileController::class, 'updateAvatar']);

    // Rotas de atividades e notificações
    Route::get('/notifications', [NotificationController::class, 'index'])->name('api.notifications');

    // Rotas para gestão de unidades (protegidas)
    Route::prefix('unidades')->group(function () {
        Route::get('/', [UnidadeController::class, 'index'])->name('api.unidades.index');
        Route::post('/', [UnidadeController::class, 'store'])->name('api.unidades.store');
        Route::get('/{id}', [UnidadeController::class, 'show'])->name('api.unidades.show');
        Route::put('/{id}', [UnidadeController::class, 'update'])->name('api.unidades.update');
        Route::delete('/{id}', [UnidadeController::class, 'destroy'])->name('api.unidades.destroy');
    });
});
