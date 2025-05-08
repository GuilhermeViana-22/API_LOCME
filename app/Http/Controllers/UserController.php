<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\Users\UsersIndexRequest;
use App\Http\Requests\Users\UserStoreRequest;
use App\Http\Requests\Users\UserUpdateRequest;
use App\Http\Requests\Users\UserRoleRequest;
use App\Http\Resources\UserResource;
use App\Http\Resources\UserCollection;
use Illuminate\Validation\Rules;
use Spatie\Permission\Models\Role;

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
     * @queryParam cargo_id integer Filtro por cargo. Example: 1
     * @queryParam sort string Campo para ordenação (name, email, created_at). Example: name
     * @queryParam order string Direção da ordenação (asc, desc). Example: asc
     */
    public function index(UsersIndexRequest $request)
    {
        $query = User::with(['unidade', 'cargo', 'logs'])
            ->when($request->name, fn($q) => $q->where('name', 'like', "%{$request->name}%"))
            ->when($request->email, fn($q) => $q->where('email', 'like', "%{$request->email}%"))
            ->when($request->has('active'), fn($q) => $q->where('active', $request->active))
            ->when($request->unidade_id, fn($q) => $q->where('unidade_id', $request->unidade_id))
            ->when($request->cargo_id, fn($q) => $q->where('cargo_id', $request->cargo_id));

        // Ordenação
        $sortField = $request->sort ?? 'name';
        $sortOrder = $request->order ?? 'asc';
        $query->orderBy($sortField, $sortOrder);

        $perPage = $request->per_page ?? 10;
        $users = $query->paginate($perPage);

        return new UserCollection($users);
    }

    /**
     * Criar novo usuário
     * @bodyParam name string required Nome do usuário. Example: João Silva
     * @bodyParam email string required Email do usuário. Example: joao@example.com
     * @bodyParam password string required Senha. Example: secret123
     * @bodyParam password_confirmation string required Confirmação da senha. Example: secret123
     * @bodyParam unidade_id integer ID da unidade. Example: 1
     * @bodyParam cargo_id integer ID do cargo. Example: 1
     */
    public function store(UserStoreRequest $request)
    {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'unidade_id' => $request->unidade_id,
            'cargo_id' => $request->cargo_id,
            'active' => $request->boolean('active', true),
        ]);

        return (new UserResource($user->load(['roles', 'unidade', 'cargo'])))
            ->response()
            ->setStatusCode(201);
    }

    /**
     * Mostrar detalhes de um usuário
     * @urlParam id integer required ID do usuário. Example: 1
     */
    public function show($id)
    {
        $user = User::with(['roles', 'unidade', 'cargo', 'activityLogs'])
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
     * @bodyParam cargo_id integer ID do cargo. Example: 1
     * @bodyParam active boolean Status do usuário. Example: true
     */
    public function update(UserUpdateRequest $request, $id)
    {
        $user = User::findOrFail($id);

        $data = $request->only(['name', 'email', 'unidade_id', 'cargo_id', 'active']);

        if ($request->password) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return new UserResource($user->fresh()->load(['roles', 'unidade', 'cargo']));
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
}