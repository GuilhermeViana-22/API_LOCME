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
     *     summary="Lista todos os cargos cadastrados no sistema",
     *     description="Retorna um array com todos os cargos do sistema",
     *     tags={"Cargos"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="Listagem de cargos bem-sucedida",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="success",
     *                 type="boolean",
     *                 example=true
     *             ),
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="Cargos recuperados com sucesso"
     *             ),
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(
     *                     type="object",
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="nome", type="string", example="Gerente"),
     *                     @OA\Property(property="created_at", type="string", format="date-time"),
     *                     @OA\Property(property="updated_at", type="string", format="date-time")
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Erro interno do servidor",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Falha ao recuperar cargos"),
     *             @OA\Property(property="error", type="string", example="Mensagem detalhada do erro")
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
     *     summary="Cria um novo cargo no sistema",
     *     description="Cadastra um novo cargo com os dados fornecidos",
     *     tags={"Cargos"},
     *     security={{"bearerAuth": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"nome"},
     *             @OA\Property(property="nome", type="string", example="Analista de TI")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Cargo criado com sucesso",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Cargo criado com sucesso"),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="nome", type="string", example="Analista de TI"),
     *                 @OA\Property(property="created_at", type="string", format="date-time"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Erro de validação",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Dados inválidos"),
     *             @OA\Property(
     *                 property="errors",
     *                 type="object",
     *                 example={"nome": {"O campo nome é obrigatório."}}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Erro interno do servidor",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Falha ao criar cargo"),
     *             @OA\Property(property="error", type="string", example="Mensagem detalhada do erro")
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
     *     summary="Recupera um cargo específico",
     *     description="Retorna os dados de um cargo pelo seu ID",
     *     tags={"Cargos"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID do cargo",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Cargo recuperado com sucesso",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Cargo recuperado com sucesso"),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="nome", type="string", example="Gerente"),
     *                 @OA\Property(property="created_at", type="string", format="date-time"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time")
     *             )
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
     *         description="Erro interno do servidor",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Falha ao recuperar cargo"),
     *             @OA\Property(property="error", type="string", example="Mensagem detalhada do erro")
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
     *     description="Atualiza os dados de um cargo pelo seu ID",
     *     tags={"Cargos"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID do cargo a ser atualizado",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"nome"},
     *             @OA\Property(property="nome", type="string", example="Analista de TI Jr")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Cargo atualizado com sucesso",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Cargo atualizado com sucesso"),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="nome", type="string", example="Analista de TI Jr"),
     *                 @OA\Property(property="created_at", type="string", format="date-time"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time")
     *             )
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
     *             @OA\Property(property="message", type="string", example="Dados inválidos"),
     *             @OA\Property(
     *                 property="errors",
     *                 type="object",
     *                 example={"nome": {"O campo nome é obrigatório."}}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Erro interno do servidor",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Falha ao atualizar cargo"),
     *             @OA\Property(property="error", type="string", example="Mensagem detalhada do erro")
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
     *     summary="Remove um cargo do sistema",
     *     description="Remove permanentemente um cargo pelo seu ID",
     *     tags={"Cargos"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID do cargo a ser removido",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Cargo removido com sucesso",
     *         @OA\JsonContent(
     *             type="object",
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
     *         description="Erro interno do servidor",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Falha ao remover cargo"),
     *             @OA\Property(property="error", type="string", example="Mensagem detalhada do erro")
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