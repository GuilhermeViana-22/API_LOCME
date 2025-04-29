<?php

namespace App\Http\Controllers;

use App\Models\Cargo;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests\Cargos\CargoStoreRequest;
use App\Http\Requests\Cargos\CargoUpdateRequest;

class CargoController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/cargos",
     *     summary="Lista todos os cargos cadastrados",
     *     tags={"Cargos"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="Listagem de cargos bem-sucedida",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Cargos recuperados com sucesso"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Erro interno",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Falha ao recuperar cargos"),
     *             @OA\Property(property="error", type="string")
     *         )
     *     )
     * )
     */
    public function index()
    {
        try {
            $cargos = Cargo::all();

            return response()->json([
                'success' => true,
                'message' => 'Cargos recuperados com sucesso',
                'data' => $cargos
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Falha ao recuperar cargos',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @OA\Post(
     *     path="/api/cargos",
     *     summary="Cria um novo cargo",
     *     tags={"Cargos"},
     *     security={{"bearerAuth": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"nome_cargo", "nivel_hierarquico", "departamento"},
     *             @OA\Property(property="nome_cargo", type="string", maxLength=100, example="Gerente de Vendas"),
     *             @OA\Property(property="nivel_hierarquico", type="integer", example=3),
     *             @OA\Property(property="departamento", type="string", maxLength=50, example="Vendas"),
     *             @OA\Property(property="descricao", type="string", example="Responsável por toda a equipe de vendas", nullable=true)
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Cargo criado com sucesso",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Cargo criado com sucesso"),
     *             @OA\Property(property="data", type="object", ref="#/components/schemas/Cargo")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Erro de validação",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Erro de validação"),
     *             @OA\Property(property="errors", type="object")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Erro interno",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Falha ao criar cargo"),
     *             @OA\Property(property="error", type="string")
     *         )
     *     )
     * )
     */
    public function store(CargoStoreRequest $request)
    {
        try {
            $cargo = Cargo::create($request->validated());

            return response()->json([
                'success' => true,
                'message' => 'Cargo criado com sucesso',
                'data' => $cargo
            ], Response::HTTP_CREATED);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Falha ao criar cargo',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/cargos/{id}",
     *     summary="Obtém um cargo específico",
     *     tags={"Cargos"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID do cargo",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Cargo recuperado com sucesso",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Cargo recuperado com sucesso"),
     *             @OA\Property(property="data", type="object", ref="#/components/schemas/Cargo")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Cargo não encontrado",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Cargo não encontrado")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Erro interno",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Falha ao recuperar cargo"),
     *             @OA\Property(property="error", type="string")
     *         )
     *     )
     * )
     */
    public function show($id)
    {
        try {
            $cargo = Cargo::find($id);

            if (!$cargo) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cargo não encontrado'
                ], Response::HTTP_NOT_FOUND);
            }

            return response()->json([
                'success' => true,
                'message' => 'Cargo recuperado com sucesso',
                'data' => $cargo
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Falha ao recuperar cargo',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @OA\Put(
     *     path="/api/cargos/{id}",
     *     summary="Atualiza um cargo existente",
     *     tags={"Cargos"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID do cargo",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="nome_cargo", type="string", maxLength=100, example="Gerente de Vendas"),
     *             @OA\Property(property="nivel_hierarquico", type="integer", example=3),
     *             @OA\Property(property="departamento", type="string", maxLength=50, example="Vendas"),
     *             @OA\Property(property="descricao", type="string", example="Responsável por toda a equipe de vendas", nullable=true)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Cargo atualizado com sucesso",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Cargo atualizado com sucesso"),
     *             @OA\Property(property="data", type="object", ref="#/components/schemas/Cargo")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Cargo não encontrado",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Cargo não encontrado")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Erro de validação",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Erro de validação"),
     *             @OA\Property(property="errors", type="object")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Erro interno",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Falha ao atualizar cargo"),
     *             @OA\Property(property="error", type="string")
     *         )
     *     )
     * )
     */
    public function update(CargoUpdateRequest $request, $id)
    {
        try {
            $cargo = Cargo::find($id);

            if (!$cargo) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cargo não encontrado'
                ], Response::HTTP_NOT_FOUND);
            }

            $cargo->update($request->validated());

            return response()->json([
                'success' => true,
                'message' => 'Cargo atualizado com sucesso',
                'data' => $cargo
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Falha ao atualizar cargo',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @OA\Delete(
     *     path="/api/cargos/{id}",
     *     summary="Remove um cargo",
     *     tags={"Cargos"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID do cargo",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Cargo removido com sucesso",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Cargo removido com sucesso")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Cargo não encontrado",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Cargo não encontrado")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Erro interno",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Falha ao remover cargo"),
     *             @OA\Property(property="error", type="string")
     *         )
     *     )
     * )
     */
    public function destroy($id)
    {
        try {
            $cargo = Cargo::find($id);

            if (!$cargo) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cargo não encontrado'
                ], Response::HTTP_NOT_FOUND);
            }

            $cargo->delete();

            return response()->json([
                'success' => true,
                'message' => 'Cargo removido com sucesso'
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Falha ao remover cargo',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}