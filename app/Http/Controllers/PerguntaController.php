<?php

namespace App\Http\Controllers;

use App\Models\Pergunta;
use App\Http\Requests\Perguntas\PerguntaStoreRequest;
use App\Http\Requests\Perguntas\PerguntaUpdateRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class PerguntaController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/perguntas",
     *     summary="Lista todas as perguntas",
     *     description="Retorna uma lista com todas as perguntas cadastradas no sistema",
     *     tags={"Perguntas"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="Lista de perguntas retornada com sucesso",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="questionario_id", type="integer", example=1),
     *                 @OA\Property(property="texto", type="string", example="Qual é seu nível de satisfação?"),
     *                 @OA\Property(property="created_at", type="string", format="date-time"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Erro interno do servidor",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Erro ao listar perguntas"),
     *             @OA\Property(property="error", type="string", example="Mensagem detalhada do erro")
     *         )
     *     )
     * )
     */
    public function index(): JsonResponse
    {
        try {
            $perguntas = Pergunta::all();
            return response()->json($perguntas);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erro ao listar perguntas',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @OA\Post(
     *     path="/api/perguntas",
     *     summary="Cria uma nova pergunta",
     *     description="Cadastra uma nova pergunta no sistema",
     *     tags={"Perguntas"},
     *     security={{"bearerAuth": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"questionario_id", "texto"},
     *             @OA\Property(property="questionario_id", type="integer", example=1),
     *             @OA\Property(property="texto", type="string", example="Qual é seu nível de satisfação?")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Pergunta criada com sucesso",
     *         @OA\JsonContent(
     *             @OA\Property(property="id", type="integer", example=1),
     *             @OA\Property(property="questionario_id", type="integer", example=1),
     *             @OA\Property(property="texto", type="string", example="Qual é seu nível de satisfação?"),
     *             @OA\Property(property="created_at", type="string", format="date-time"),
     *             @OA\Property(property="updated_at", type="string", format="date-time")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Dados inválidos",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Os dados fornecidos são inválidos"),
     *             @OA\Property(
     *                 property="errors",
     *                 type="object",
     *                 example={"texto": {"O campo texto é obrigatório."}}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Erro interno do servidor",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Erro ao criar pergunta"),
     *             @OA\Property(property="error", type="string", example="Mensagem detalhada do erro")
     *         )
     *     )
     * )
     */
    public function store(PerguntaStoreRequest $request): JsonResponse
    {
        try {
            $pergunta = Pergunta::create($request->validated());
            return response()->json($pergunta, Response::HTTP_CREATED);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erro ao criar pergunta',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/perguntas/{id}",
     *     summary="Exibe uma pergunta específica",
     *     description="Retorna os detalhes de uma pergunta pelo seu ID",
     *     tags={"Perguntas"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID da pergunta",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Pergunta encontrada",
     *         @OA\JsonContent(
     *             @OA\Property(property="id", type="integer", example=1),
     *             @OA\Property(property="questionario_id", type="integer", example=1),
     *             @OA\Property(property="texto", type="string", example="Qual é seu nível de satisfação?"),
     *             @OA\Property(property="created_at", type="string", format="date-time"),
     *             @OA\Property(property="updated_at", type="string", format="date-time")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Pergunta não encontrada",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Pergunta não encontrada")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Erro interno do servidor",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Erro ao exibir pergunta"),
     *             @OA\Property(property="error", type="string", example="Mensagem detalhada do erro")
     *         )
     *     )
     * )
     */
    public function show(Pergunta $pergunta): JsonResponse
    {
        try {
            return response()->json($pergunta);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erro ao exibir pergunta',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @OA\Put(
     *     path="/api/perguntas/{id}",
     *     summary="Atualiza uma pergunta",
     *     description="Atualiza os dados de uma pergunta existente",
     *     tags={"Perguntas"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID da pergunta",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="questionario_id", type="integer", example=1),
     *             @OA\Property(property="texto", type="string", example="Qual é seu nível de satisfação? (atualizado)")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Pergunta atualizada com sucesso",
     *         @OA\JsonContent(
     *             @OA\Property(property="id", type="integer", example=1),
     *             @OA\Property(property="questionario_id", type="integer", example=1),
     *             @OA\Property(property="texto", type="string", example="Qual é seu nível de satisfação? (atualizado)"),
     *             @OA\Property(property="created_at", type="string", format="date-time"),
     *             @OA\Property(property="updated_at", type="string", format="date-time")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Pergunta não encontrada",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Pergunta não encontrada")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Dados inválidos",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Os dados fornecidos são inválidos"),
     *             @OA\Property(
     *                 property="errors",
     *                 type="object",
     *                 example={"texto": {"O campo texto é obrigatório."}}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Erro interno do servidor",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Erro ao atualizar pergunta"),
     *             @OA\Property(property="error", type="string", example="Mensagem detalhada do erro")
     *         )
     *     )
     * )
     */
    public function update(PerguntaUpdateRequest $request, Pergunta $pergunta): JsonResponse
    {
        try {
            $pergunta->update($request->validated());
            return response()->json($pergunta);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erro ao atualizar pergunta',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @OA\Delete(
     *     path="/api/perguntas/{id}",
     *     summary="Remove uma pergunta",
     *     description="Remove permanentemente uma pergunta do sistema",
     *     tags={"Perguntas"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID da pergunta",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Pergunta removida com sucesso",
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Pergunta não encontrada",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Pergunta não encontrada")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Erro interno do servidor",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Erro ao excluir pergunta"),
     *             @OA\Property(property="error", type="string", example="Mensagem detalhada do erro")
     *         )
     *     )
     * )
     */
    public function destroy(Pergunta $pergunta): JsonResponse
    {
        try {
            $pergunta->delete();
            return response()->json(null, Response::HTTP_NO_CONTENT);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erro ao excluir pergunta',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}