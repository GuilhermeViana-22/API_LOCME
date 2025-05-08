<?php

namespace App\Http\Controllers;

use App\Models\Questionario;
use App\Models\Pergunta;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class QuestionarioController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/questionarios",
     *     summary="Lista todos os questionários",
     *     description="Retorna uma lista de todos os questionários com suas perguntas",
     *     tags={"Questionarios"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="Lista de questionários",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="success",
     *                 type="boolean",
     *                 example=true
     *             ),
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(
     *                     type="object",
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="titulo", type="string", example="Questionário de Satisfação"),
     *                     @OA\Property(property="descricao", type="string", example="Descrição do questionário"),
     *                     @OA\Property(property="perguntas", type="array",
     *                         @OA\Items(
     *                             type="object",
     *                             @OA\Property(property="id", type="integer", example=1),
     *                             @OA\Property(property="texto", type="string", example="Você está satisfeito com nosso serviço?")
     *                         )
     *                     )
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Erro interno do servidor",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Erro ao listar questionários"),
     *             @OA\Property(property="error", type="string", example="Mensagem de erro detalhada")
     *         )
     *     )
     * )
     */
    public function index()
    {
        try {
            $questionarios = Questionario::with('perguntas')->get();
            return response()->json([
                'success' => true,
                'data' => $questionarios
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao listar questionários',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @OA\Post(
     *     path="/api/questionarios",
     *     summary="Cria um novo questionário",
     *     description="Cria um novo questionário com as perguntas associadas",
     *     tags={"Questionarios"},
     *     security={{"bearerAuth": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"titulo", "periodicidade", "data_inicio", "publico_alvo", "criado_por"},
     *             @OA\Property(property="titulo", type="string", example="Questionário de Satisfação"),
     *             @OA\Property(property="descricao", type="string", example="Descrição do questionário"),
     *             @OA\Property(property="periodicidade", type="string", example="mensal"),
     *             @OA\Property(property="data_inicio", type="string", format="date", example="2023-01-01"),
     *             @OA\Property(property="data_termino", type="string", format="date", example="2023-12-31"),
     *             @OA\Property(property="ativo", type="boolean", example=true),
     *             @OA\Property(property="publico_alvo", type="string", example="clientes"),
     *             @OA\Property(property="criado_por", type="integer", example=1),
     *             @OA\Property(
     *                 property="perguntas",
     *                 type="array",
     *                 @OA\Items(
     *                     type="object",
     *                     @OA\Property(property="texto", type="string", example="Você está satisfeito com nosso serviço?")
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Questionário criado com sucesso",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Questionário criado com sucesso"),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="titulo", type="string", example="Questionário de Satisfação"),
     *                 @OA\Property(property="perguntas", type="array",
     *                     @OA\Items(
     *                         type="object",
     *                         @OA\Property(property="id", type="integer", example=1),
     *                         @OA\Property(property="texto", type="string", example="Você está satisfeito com nosso serviço?")
     *                     )
     *                 )
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
     *                 example={"titulo": {"O campo titulo é obrigatório."}}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Erro interno do servidor",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Erro ao criar questionário"),
     *             @OA\Property(property="error", type="string", example="Mensagem de erro detalhada")
     *         )
     *     )
     * )
     */
    public function store(Request $request)
    {
        try {
            $data = $request->validate([
                'titulo' => 'required|string|max:100',
                'descricao' => 'nullable|string',
                'periodicidade' => 'required|string',
                'data_inicio' => 'required|date',
                'data_termino' => 'nullable|date',
                'ativo' => 'nullable|boolean',
                'publico_alvo' => 'required|string',
                'criado_por' => 'required|integer',
                'perguntas' => 'nullable|array',
                'perguntas.*.texto' => 'required|string',
            ]);

            $questionario = Questionario::create($data);

            if (!empty($data['perguntas'])) {
                foreach ($data['perguntas'] as $pergunta) {
                    Pergunta::create([
                        'questionario_id' => $questionario->id,
                        'texto' => $pergunta['texto'],
                    ]);
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'Questionário criado com sucesso',
                'data' => $questionario->load('perguntas')
            ], Response::HTTP_CREATED);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Dados inválidos',
                'errors' => $e->errors()
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao criar questionário',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/questionarios/{id}",
     *     summary="Exibe um questionário específico",
     *     description="Retorna os detalhes de um questionário com suas perguntas",
     *     tags={"Questionarios"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID do questionário",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Questionário encontrado",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="titulo", type="string", example="Questionário de Satisfação"),
     *                 @OA\Property(property="perguntas", type="array",
     *                     @OA\Items(
     *                         type="object",
     *                         @OA\Property(property="id", type="integer", example=1),
     *                         @OA\Property(property="texto", type="string", example="Você está satisfeito com nosso serviço?")
     *                     )
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Questionário não encontrado",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Questionário não encontrado")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Erro interno do servidor",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Erro ao recuperar questionário"),
     *             @OA\Property(property="error", type="string", example="Mensagem de erro detalhada")
     *         )
     *     )
     * )
     */
    public function show(Questionario $questionario)
    {
        try {
            return response()->json([
                'success' => true,
                'data' => $questionario->load('perguntas')
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao recuperar questionário',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @OA\Put(
     *     path="/api/questionarios/{id}",
     *     summary="Atualiza um questionário",
     *     description="Atualiza os dados de um questionário existente",
     *     tags={"Questionarios"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID do questionário",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="titulo", type="string", example="Questionário Atualizado"),
     *             @OA\Property(property="descricao", type="string", example="Nova descrição"),
     *             @OA\Property(property="periodicidade", type="string", example="trimestral"),
     *             @OA\Property(property="data_inicio", type="string", format="date", example="2023-01-01"),
     *             @OA\Property(property="data_termino", type="string", format="date", example="2023-12-31"),
     *             @OA\Property(property="ativo", type="boolean", example=true),
     *             @OA\Property(property="publico_alvo", type="string", example="colaboradores")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Questionário atualizado com sucesso",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Questionário atualizado com sucesso"),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="titulo", type="string", example="Questionário Atualizado")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Questionário não encontrado",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Questionário não encontrado")
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
     *                 example={"titulo": {"O campo titulo é obrigatório."}}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Erro interno do servidor",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Erro ao atualizar questionário"),
     *             @OA\Property(property="error", type="string", example="Mensagem de erro detalhada")
     *         )
     *     )
     * )
     */
    public function update(Request $request, Questionario $questionario)
    {
        try {
            $data = $request->validate([
                'titulo' => 'sometimes|string|max:100',
                'descricao' => 'nullable|string',
                'periodicidade' => 'sometimes|string',
                'data_inicio' => 'sometimes|date',
                'data_termino' => 'nullable|date',
                'ativo' => 'nullable|boolean',
                'publico_alvo' => 'sometimes|string',
            ]);

            $questionario->update($data);

            return response()->json([
                'success' => true,
                'message' => 'Questionário atualizado com sucesso',
                'data' => $questionario
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Dados inválidos',
                'errors' => $e->errors()
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao atualizar questionário',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @OA\Delete(
     *     path="/api/questionarios/{id}",
     *     summary="Remove um questionário",
     *     description="Remove permanentemente um questionário do sistema",
     *     tags={"Questionarios"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID do questionário",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Questionário removido com sucesso",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Questionário removido com sucesso")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Questionário não encontrado",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Questionário não encontrado")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Erro interno do servidor",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Erro ao remover questionário"),
     *             @OA\Property(property="error", type="string", example="Mensagem de erro detalhada")
     *         )
     *     )
     * )
     */
    public function destroy(Questionario $questionario)
    {
        try {
            $questionario->delete();

            return response()->json([
                'success' => true,
                'message' => 'Questionário removido com sucesso'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao remover questionário',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}