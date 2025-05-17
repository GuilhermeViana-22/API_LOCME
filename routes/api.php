<?php

use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\NotificationController;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UnidadeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CargoController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PerguntaController;
use App\Http\Controllers\QuestionarioController;

use Illuminate\Support\Facades\Route;

// Rotas públicas para autenticação e gerenciamento de conta
Route::post('/register', [AuthController::class, 'register'])->name('api.register');
Route::post('/login', [AuthController::class, 'login'])->name('api.login');
Route::post('/mailverify', [AuthController::class, 'mailVerify'])->name('api.mailverify');
Route::post('/reset', [AuthController::class, 'reset'])->name('api.reset');

// Rotas de teste (se realmente necessário)
Route::get('/me', [AuthController::class, 'me'])->name('me');


  // Rotas para gestão de cargos (protegidas)
    Route::prefix('cargos')->group(function () {
        Route::get('/', [CargoController::class, 'index'])->name('api.cargos.index');
        Route::post('/', [CargoController::class, 'store'])->name('api.cargos.store');
        Route::get('/{id}', [CargoController::class, 'show'])->name('api.cargos.show');
        Route::put('/{id}', [CargoController::class, 'update'])->name('api.cargos.update');
        Route::delete('/{id}', [CargoController::class, 'destroy'])->name('api.cargos.destroy');
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

    // Rotas para gestão de cargos (protegidas)
    Route::prefix('perguntas')->group(function () {
        Route::get('/', [PerguntaController::class, 'index'])->name('api.perguntas.index');
        Route::post('/', [PerguntaController::class, 'store'])->name('api.perguntas.store');
        Route::get('/{id}', [PerguntaController::class, 'show'])->name('api.perguntas.show');
        Route::put('/{id}', [PerguntaController::class, 'update'])->name('api.perguntas.update');
        Route::delete('/{id}', [PerguntaController::class, 'destroy'])->name('api.perguntas.destroy');
    });








    Route::prefix('questionarios')->group(function () {
        // Rotas para perguntas
        Route::get('tipo/{tipoId}/perguntas', [QuestionarioController::class, 'listarPerguntasPorTipo'])
            ->name('api.questionarios.perguntas.por-tipo');

        // Rotas para respostas
        Route::post('tipo/{tipoId}/responder', [QuestionarioController::class, 'responderPorTipo'])
            ->name('api.questionarios.responder.por-tipo');

        Route::get('/progresso', [QuestionarioController::class, 'verificarProgresso'])
            ->name('api.questionarios.progresso');

        // Rotas existentes que você mencionou
        Route::get('/responder', [QuestionarioController::class, 'responder'])->name('api.questionarios.responder');
        Route::get('/{id}/respostas', [QuestionarioController::class, 'respostas'])->name('api.questionarios.respostas');
        Route::get('/{id}/respostas/usuario', [QuestionarioController::class, 'respostasUsuario'])->name('api.questionarios.respostas.usuario');
    });


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
