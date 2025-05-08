<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Illuminate\Validation\Rules;

class UserController extends Controller
{
    // Listar todos os usuários (com paginação)
    public function index()
    {
        $users = User::with(['roles', 'unidade', 'cargo'])
                    ->orderBy('name')
                    ->paginate(10);

        return response()->json($users);
    }

    // Criar novo usuário
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'unidade_id' => 'nullable|exists:unidades,id',
            'cargo_id' => 'nullable|exists:cargos,id',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'unidade_id' => $request->unidade_id,
            'cargo_id' => $request->cargo_id,
        ]);

        return response()->json($user, 201);
    }

    // Mostrar detalhes de um usuário
    public function show($id)
    {
        $user = User::with(['roles', 'unidade', 'cargo', 'activityLogs'])
                   ->findOrFail($id);

        return response()->json($user);
    }

    // Atualizar usuário
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|string|email|max:255|unique:users,email,'.$user->id,
            'password' => ['sometimes', 'confirmed', Rules\Password::defaults()],
            'unidade_id' => 'nullable|exists:unidades,id',
            'cargo_id' => 'nullable|exists:cargos,id',
        ]);

        $data = $request->all();

        if ($request->has('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return response()->json($user);
    }

    // Remover usuário
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return response()->json(null, 204);
    }

    // Métodos adicionais
    public function activate($id)
    {
        $user = User::findOrFail($id);
        $user->update(['active' => true]);

        return response()->json(['message' => 'User activated']);
    }

    public function deactivate($id)
    {
        $user = User::findOrFail($id);
        $user->update(['active' => false]);

        return response()->json(['message' => 'User deactivated']);
    }

    public function assignRole(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $request->validate([
            'role' => 'required|exists:roles,name'
        ]);

        $user->assignRole($request->role);

        return response()->json(['message' => 'Role assigned successfully']);
    }
}