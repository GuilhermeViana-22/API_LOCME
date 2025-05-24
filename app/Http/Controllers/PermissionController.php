<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use App\Models\Rule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\JsonResponse;

/**
 * @OA\Tag(
 *     name="Permissões",
 *     description="Gerenciamento de permissões"
 * )
 */
class PermissionController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/permissions",
     *     tags={"Permissões"},
     *     summary="Listar todas as permissões",
     *     @OA\Response(
     *         response=200,
     *         description="Lista de permissões retornada com sucesso"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Erro interno no servidor"
     *     )
     * )
     */
    public function index(): JsonResponse
    {
        $permissions = Permission::all();
        return response()->json($permissions);
    }

    /**
     * @OA\Post(
     *     path="/api/permissions",
     *     tags={"Permissões"},
     *     summary="Criar uma nova permissão",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name"},
     *             @OA\Property(property="name", type="string", example="editar_usuarios"),
     *             @OA\Property(property="description", type="string", example="Permissão para editar usuários")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Permissão criada com sucesso"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Dados inválidos"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Erro interno no servidor"
     *     )
     * )
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|unique:permissions',
            'description' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $permission = Permission::create($request->only('name', 'description'));

        return response()->json([
            'success' => true,
            'data' => $permission
        ], 201);
    }

    /**
     * @OA\Post(
     *     path="/api/rules/{rule}/permissions",
     *     tags={"Permissões"},
     *     summary="Vincular permissão a uma regra",
     *     @OA\Parameter(
     *         name="rule",
     *         in="path",
     *         required=true,
     *         description="ID da regra",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"permission_id"},
     *             @OA\Property(property="permission_id", type="integer", example=1)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Permissão vinculada à regra com sucesso"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Dados inválidos"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Erro interno no servidor"
     *     )
     * )
     */
    public function attachToRule(Request $request, Rule $rule): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'permission_id' => 'required|exists:permissions,id'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Dados inválidos',
                'errors' => $validator->errors()
            ], 422);
        }

        $rule->permissions()->attach($request->permission_id);

        return response()->json([
            'success' => true,
            'message' => 'Permissão vinculada à regra com sucesso'
        ]);
    }
}
