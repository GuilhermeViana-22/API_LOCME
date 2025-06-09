<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Hash;
use App\Http\Resources\UserCollection;
use App\Http\Requests\Users\UserRoleRequest;
use App\Http\Requests\Users\UserStoreRequest;
use App\Http\Requests\Users\UsersIndexRequest;
use App\Http\Requests\Users\UserUpdateRequest;
use Illuminate\Http\Response;

class UserController extends Controller
{
    /**
     * Listar todos os usuários (com paginação)
     * @queryParam page integer Página atual. Example: 1
     * @queryParam per_page integer Itens por página. Example: 10
     * @queryParam name string Filtro por nome. Example: João
     * @queryParam email string Filtro por email. Example: joao@example.com
     * @queryParam active boolean Filtro por status. Example: true
     * @queryParam unidade_id integer Filtro por unidade. Example: 1
     * @queryParam position_id integer Filtro por position. Example: 1
     * @queryParam sort string Campo para ordenação (name, email, created_at). Example: name
     * @queryParam order string Direção da ordenação (asc, desc). Example: asc
     */
    public function index(UsersIndexRequest $request)
{
    try {
        $query = User::query()->with(['unidade', 'position',  'logs']);
        $query = $this->applyFilters($query, $request);

        // Ordenação padrão
        $sortField = $request->input('sort', 'name');
        $sortOrder = $request->input('order', 'asc');
        $query->orderBy($sortField, $sortOrder);

        $perPage = $request->input('per_page', 10);
        $users = $query->paginate($perPage);

        return $this->buildPaginatedResponse($users, $request);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Falha ao recuperar usuários',
            'error' => $e->getMessage()
        ], Response::HTTP_INTERNAL_SERVER_ERROR);
    }
}

    /**
     * Criar novo usuário
     * @bodyParam name string required Nome do usuário. Example: João Silva
     * @bodyParam email string required Email do usuário. Example: joao@example.com
     * @bodyParam password string required Senha. Example: secret123
     * @bodyParam password_confirmation string required Confirmação da senha. Example: secret123
     * @bodyParam unidade_id integer ID da unidade. Example: 1
     * @bodyParam position_id integer ID do position. Example: 1
     */
    public function store(UserStoreRequest $request)
    {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'unidade_id' => $request->unidade_id,
            'position_id' => $request->position_id,
            'active' => $request->boolean('active', true),
        ]);

        return (new UserResource($user->load(['roles', 'unidade', 'position'])))
            ->response()
            ->setStatusCode(201);
    }

    /**
     * Mostrar detalhes de um usuário
     * @urlParam id integer required ID do usuário. Example: 1
     */
    public function show($id)
    {
        $user = User::with(['unidade', 'position'])
            ->findOrFail($id);

        return new UserResource($user);
    }

    /**
     * Atualizar usuário
     * @urlParam id integer required ID do usuário. Example: 1
     * @bodyParam name string Nome do usuário. Example: João Silva
     * @bodyParam email string Email do usuário. Example: joao@example.com
     * @bodyParam password string Senha. Example: newsecret123
     * @bodyParam password_confirmation string Confirmação da senha. Example: newsecret123
     * @bodyParam unidade_id integer ID da unidade. Example: 1
     * @bodyParam position_id integer ID do position. Example: 1
     * @bodyParam active boolean Status do usuário. Example: true
     */
    public function update(UserUpdateRequest $request, $id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Usuário não encontrado.'
            ], 404);
        }

        try {
            $data = $request->validated();

            // Remover confirmação de senha se existir
            unset($data['password_confirmation']);

            // Se senha foi enviada, hash e mantém, senão remove para não atualizar
            if (!empty($data['password'])) {
                $data['password'] = Hash::make($data['password']);
            } else {
                unset($data['password']);
            }

            // Atualiza o usuário com os dados filtrados
            $user->update($data);

            return response()->json([
                'success' => true,
                'data' => new UserResource($user->fresh()->load(['unidade', 'position', 'logs', 'rulesUser.rule.permissions'])),
                'message' => 'Dados do usuário atualizados com sucesso.'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao atualizar os dados do usuário.',
                'error' => env('APP_DEBUG') ? $e->getMessage() : null
            ], 500);
        }
    }



    /**
     * Remover usuário
     * @urlParam id integer required ID do usuário. Example: 1
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return response()->json(null, 204);
    }

    /**
     * Ativar usuário
     * @urlParam id integer required ID do usuário. Example: 1
     */
    public function activate($id)
    {
        $user = User::findOrFail($id);
        $user->update(['active' => true]);

        return response()->json([
            'message' => 'User activated successfully',
            'data' => new UserResource($user)
        ]);
    }

    /**
     * Desativar usuário
     * @urlParam id integer required ID do usuário. Example: 1
     */
    public function deactivate($id)
    {
        $user = User::findOrFail($id);
        $user->update(['active' => false]);

        return response()->json([
            'message' => 'User deactivated successfully',
            'data' => new UserResource($user)
        ]);
    }

    /**
     * Atribuir papel ao usuário
     * @urlParam id integer required ID do usuário. Example: 1
     * @bodyParam role string required Nome do papel. Example: admin
     */
    public function assignRole(UserRoleRequest $request, $id)
    {
        $user = User::findOrFail($id);
        $role = Role::findByName($request->role);

        $user->assignRole($role);

        return response()->json([
            'message' => 'Role assigned successfully',
            'data' => new UserResource($user->load('roles'))
        ]);
    }

    /**
     * Remover papel do usuário
     * @urlParam id integer required ID do usuário. Example: 1
     * @bodyParam role string required Nome do papel. Example: admin
     */
    public function removeRole(UserRoleRequest $request, $id)
    {
        $user = User::findOrFail($id);
        $role = Role::findByName($request->role);

        $user->removeRole($role);

        return response()->json([
            'message' => 'Role removed successfully',
            'data' => new UserResource($user->load('roles'))
        ]);
    }


    /**
     * Aplica todos os filtros à query
     */
    private function applyFilters($query, $request)
    {
        if ($request->filled('ativo')) {
            $query->where('ativo', $request->ativo);
        }

        if ($request->filled('unidade_id')) {
            $query->where('unidade_id', $request->unidade_id);
        }

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->filled('name')) {
            $query->where('name', 'like', '%' . $request->name . '%'); // Busca parcial
        }

        if ($request->filled('email')) {
            $query->where('email', 'like', '%' . $request->email . '%'); // Busca parcial
        }

        return $query;
    }

    /**
     * Executa a paginação dos resultados
     */
    private function paginateResults($query, $request)
    {
        $perPage = $request->input('per_page', 15);
        return $query->paginate($perPage);
    }

    /**
     * Constroi a resposta padronizada com paginação
     */
    private function buildPaginatedResponse($paginatedResults, $request)
{
    return response()->json([
        'success' => true,
        'message' => 'Usuários recuperados com sucesso',
        'data' => [
            'data' => UserResource::collection($paginatedResults->items()),
            'meta' => [
                'total' => $paginatedResults->total(),
                'per_page' => $paginatedResults->perPage(),
                'current_page' => $paginatedResults->currentPage(),
                'last_page' => $paginatedResults->lastPage(),
                'from' => $paginatedResults->firstItem(),
                'to' => $paginatedResults->lastItem(),
            ],
            'links' => [
                'first' => $paginatedResults->url(1),
                'last' => $paginatedResults->url($paginatedResults->lastPage()),
                'prev' => $paginatedResults->previousPageUrl(),
                'next' => $paginatedResults->nextPageUrl(),
            ],
        ],
    ], Response::HTTP_OK);
}
    /**
     * Constroi os metadados da paginação
     */
    private function buildMetaData($paginatedResults, $request)
    {
        return [
            'current_page' => $paginatedResults->currentPage(),
            'per_page' => $paginatedResults->perPage(),
            'total' => $paginatedResults->total(),
            'last_page' => $paginatedResults->lastPage(),
            'from' => $paginatedResults->firstItem(),
            'to' => $paginatedResults->lastItem(),
            'filters_applied' => $request->except(['page', 'per_page'])
        ];
    }

    /**
     * Constroi os links de navegação
     */
    private function buildPaginationLinks($paginatedResults)
    {
        return [
            'first' => $paginatedResults->url(1),
            'last' => $paginatedResults->url($paginatedResults->lastPage()),
            'prev' => $paginatedResults->previousPageUrl(),
            'next' => $paginatedResults->nextPageUrl()
        ];
    }
}
