<?php

use App\Http\Controllers\InterpretacoesController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UnidadeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CargoController;
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
Route::get('/me', [AuthController::class, 'me'])->name('me');
Route::get('/log', [DashboardController::class, 'log'])->name('log');


// Rotas para gestão de cargos (protegidas)
Route::prefix('cargos')->group(function () {
    Route::get('/', [CargoController::class, 'index'])->name('api.cargos.index');
    Route::post('/', [CargoController::class, 'store'])->name('api.cargos.store');
    Route::get('/{id}', [CargoController::class, 'show'])->name('api.cargos.show');
    Route::put('/{id}', [CargoController::class, 'update'])->name('api.cargos.update');
    Route::delete('/{id}', [CargoController::class, 'destroy'])->name('api.cargos.destroy');
});




Route::prefix('interpretacoes')->group(function () {
    // Rota para listar todas as interpretações (com possibilidade de filtro por job)
    Route::get('/', [InterpretacoesController::class, 'index'])
        ->name('interpretacoes.index');

    // Rota para exibir o formulário de upload/criação
    Route::get('/create', [InterpretacoesController::class, 'create'])
        ->name('interpretacoes.create');

    // Rota para processar o upload de arquivos (XML ou JSON)
    Route::post('/', [InterpretacoesController::class, 'store'])
        ->name('interpretacoes.store');

    // Rota para exibir uma interpretação específica
    Route::get('/{interpretacoes}', [InterpretacoesController::class, 'show'])
        ->name('interpretacoes.show');

    // Rota para exibir o formulário de edição
    Route::get('/{interpretacoes}/edit', [InterpretacoesController::class, 'edit'])
        ->name('interpretacoes.edit');

    // Rota para atualizar uma interpretação
    Route::put('/{interpretacoes}', [InterpretacoesController::class, 'update'])
        ->name('interpretacoes.update');

    // Rota para deletar uma interpretação (soft delete)
    Route::delete('/{interpretacoes}', [InterpretacoesController::class, 'destroy'])
        ->name('interpretacoes.destroy');

    // Rotas adicionais específicas para funcionalidades do sistema

    // Pesquisar por número de job
    Route::get('/job/{job}', [InterpretacoesController::class, 'searchByJob'])
        ->name('interpretacoes.searchByJob');

    // Upload específico de XML
    Route::post('/upload-xml', [InterpretacoesController::class, 'uploadXml'])
        ->name('interpretacoes.uploadXml');

    // Upload específico de JSON
    Route::post('/upload-json', [InterpretacoesController::class, 'uploadJson'])
        ->name('interpretacoes.uploadJson');

    // Atualizar status de uma interpretação
    Route::patch('/{interpretacoes}/status', [InterpretacoesController::class, 'updateStatus'])
        ->name('interpretacoes.updateStatus');

    // Restaurar uma interpretação deletada (soft delete)
    Route::post('/{interpretacoes}/restore', [InterpretacoesController::class, 'restore'])
        ->name('interpretacoes.restore');
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
    // Rotas de autenticação do usuário
    Route::post('/logout', [AuthController::class, 'logout'])->name('api.logout');
    Route::put('/profile', [AuthController::class, 'updateProfile'])->name('api.updateProfile');
    Route::delete('/delete', [AuthController::class, 'delete'])->name('api.delete');

    // Rotas de gerenciamento de conta
    Route::get('/profile', [ProfileController::class, 'show']);
    Route::put('/profile', [ProfileController::class, 'update']);

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

//rota teste
Route::get('/teste', [AuthController::class, 'teste'])->name('teste');
