<?php

namespace App\Http\Controllers;

use App\Http\Requests\Unidades\UnidadeStoreRequest;
use App\Http\Requests\Unidades\UnidadeUpdateRequest;
use App\Http\Requests\Unidades\UnidadesIndexRequest;
use App\Http\Requests\Unidades\UnidadesDeleteRequest;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Response;
use App\Models\Unidade;

class UnidadeController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/unidades",
     *     summary="Lista todas as unidades cadastradas no sistema Decola",
     *     description="Retorna um array com todas as unidades do sistema",
     *     tags={"Unidades"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="Listagem de unidades bem-sucedida",
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
     *                 example="Unidades recuperadas com sucesso"
     *             ),
     *
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Erro interno do servidor",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="success",
     *                 type="boolean",
     *                 example=false
     *             ),
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="Falha ao recuperar unidades"
     *             ),
     *             @OA\Property(
     *                 property="error",
     *                 type="string",
     *                 example="Mensagem detalhada do erro"
     *             )
     *         )
     *     )
     * )
     */
    public function index(UnidadesIndexRequest $request)
    {
        try {
            $query = Unidade::query();

            // Filtros opcionais
            if ($request->filled('nome_unidade')) {
                $query->where('nome_unidade', 'like', '%' . $request->nome_unidade . '%');
            }

            if ($request->filled('ativo')) {
                $query->where('ativo', $request->ativo);
            }

            if ($request->filled('tipo_unidade_id')) {
                $query->where('tipo_unidade_id', $request->tipo_unidade_id);
            }

            if ($request->filled('created_at')) {
                $query->whereDate('created_at', '>=', $request->created_at);
            }

            if ($request->filled('codigo_unidade')) {
                $query->where('codigo_unidade', 'like', '%' . $request->codigo_unidade . '%');
            }

            // Ordenação padrão
            $unidades = $query->orderBy('nome_unidade', 'asc')->get();

            return response()->json([
                'success' => true,
                'message' => 'Unidades recuperadas com sucesso',
                'data' => $unidades
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Falha ao recuperar unidades',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }


    /**
     * @OA\Tag(
     *     name="Unidades",
     *     description="Gerenciamento de unidades do sistema"
     * )
     */

    /**
     * @OA\Post(
     *     path="/api/unidades",
     *     summary="Cria uma nova unidade",
     *     tags={"Unidades"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"nome_unidade", "codigo_unidade", "tipo_unidade_id", "endereco_rua", "endereco_numero", "endereco_bairro", "endereco_cidade", "endereco_estado", "endereco_cep", "telefone_principal"},
     *             @OA\Property(property="nome_unidade", type="string", maxLength=100, example="Unidade Central"),
     *             @OA\Property(property="codigo_unidade", type="string", maxLength=20, example="UC001"),
     *             @OA\Property(property="tipo_unidade_id", type="integer", example=1),
     *             @OA\Property(property="endereco_rua", type="string", maxLength=100, example="Rua Principal"),
     *             @OA\Property(property="endereco_numero", type="string", maxLength=20, example="123"),
     *             @OA\Property(property="endereco_complemento", type="string", maxLength=50, example="Sala 45", nullable=true),
     *             @OA\Property(property="endereco_bairro", type="string", maxLength=50, example="Centro"),
     *             @OA\Property(property="endereco_cidade", type="string", maxLength=50, example="S�o Paulo"),
     *             @OA\Property(property="endereco_estado", type="string", maxLength=2, example="SP"),
     *             @OA\Property(property="endereco_cep", type="string", maxLength=10, example="01001000"),
     *             @OA\Property(property="telefone_principal", type="string", maxLength=20, example="1133334444"),
     *             @OA\Property(property="email_unidade", type="string", format="email", maxLength=100, example="contato@unidade.com", nullable=true),
     *             @OA\Property(property="data_inauguracao", type="string", format="date", example="2023-01-01", nullable=true),
     *             @OA\Property(property="quantidade_setores", type="integer", example=5, nullable=true),
     *             @OA\Property(property="ativo", type="boolean", example=true, nullable=true)
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Unidade criada com sucesso",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Unidade criada com sucesso"),
     *             @OA\Property(property="data", type="object")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Erro de valida��o",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Erro de valida��o"),
     *             @OA\Property(property="errors", type="object")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Erro interno",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Falha ao criar unidade"),
     *             @OA\Property(property="error", type="string")
     *         )
     *     )
     * )
     */

    public function store(UnidadeStoreRequest $request)
    {

        try {
            $unidade = Unidade::create($request->validated());
            return response()->json([
                'success' => true,
                'message' => 'Unidade criada com sucesso',
                'data' => $unidade
            ], Response::HTTP_CREATED);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Falha ao criar unidade',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }


    /**
     * @OA\Get(
     *     path="/api/unidades/{id}",
     *     summary="Obt�m uma unidade espec�fica",
     *     tags={"Unidades"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID da unidade",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Unidade retornada com sucesso",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Unidade recuperada com sucesso"),
     *             @OA\Property(property="data", type="object")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Unidade n�o encontrada",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Unidade n�o encontrada")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Erro interno",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Falha ao recuperar unidade"),
     *             @OA\Property(property="error", type="string")
     *         )
     *     )
     * )
     */
    public function show($id)
    {
        try {
            $unidade = Unidade::find($id);

            if (!$unidade) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unidade n�o encontrada'
                ], Response::HTTP_NOT_FOUND);
            }

            return response()->json([
                'success' => true,
                'message' => 'Unidade recuperada com sucesso',
                'data' => $unidade
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Falha ao recuperar unidade',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }


    /**
     * @OA\Put(
     *     path="/api/unidades/{id}",
     *     summary="Atualiza uma unidade existente",
     *     tags={"Unidades"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID da unidade",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="nome_unidade", type="string", maxLength=100, example="Unidade Central"),
     *             @OA\Property(property="codigo_unidade", type="string", maxLength=20, example="UC001"),
     *             @OA\Property(property="tipo_unidade_id", type="integer", example=1),
     *             @OA\Property(property="endereco_rua", type="string", maxLength=100, example="Rua Principal"),
     *             @OA\Property(property="endereco_numero", type="string", maxLength=20, example="123"),
     *             @OA\Property(property="endereco_complemento", type="string", maxLength=50, nullable=true, example="Sala 45"),
     *             @OA\Property(property="endereco_bairro", type="string", maxLength=50, example="Centro"),
     *             @OA\Property(property="endereco_cidade", type="string", maxLength=50, example="S�o Paulo"),
     *             @OA\Property(property="endereco_estado", type="string", maxLength=2, example="SP"),
     *             @OA\Property(property="endereco_cep", type="string", maxLength=10, example="01001000"),
     *             @OA\Property(property="telefone_principal", type="string", maxLength=20, example="1133334444"),
     *             @OA\Property(property="email_unidade", type="string", format="email", maxLength=100, nullable=true, example="contato@unidade.com"),
     *             @OA\Property(property="data_inauguracao", type="string", format="date", nullable=true, example="2023-01-01"),
     *             @OA\Property(property="quantidade_setores", type="integer", nullable=true, example=5),
     *             @OA\Property(property="ativo", type="boolean", nullable=true, example=true)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Unidade atualizada com sucesso",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Unidade atualizada com sucesso"),
     *             @OA\Property(property="data", type="object")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Unidade n�o encontrada",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Unidade n�o encontrada")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Erro de valida��o",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Erro de valida��o"),
     *             @OA\Property(property="errors", type="object")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Erro interno",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Falha ao atualizar unidade"),
     *             @OA\Property(property="error", type="string")
     *         )
     *     )
     * )
     */
    public function update(UnidadeUpdateRequest $request, $id)
    {
        try {
            $unidade = Unidade::find($id);

            if (!$unidade) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unidade n�o encontrada'
                ], Response::HTTP_NOT_FOUND);
            }

            $validator = Validator::make($request->all(), []);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erro de valida��o',
                    'errors' => $validator->errors()
                ], Response::HTTP_UNPROCESSABLE_ENTITY);
            }

            $unidade->update($request->all());

            return response()->json([
                'success' => true,
                'message' => 'Unidade atualizada com sucesso',
                'data' => $unidade
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Falha ao atualizar unidade',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @OA\Delete(
     *     path="/api/unidades/{id}",
     *     summary="Remove uma unidade",
     *     tags={"Unidades"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID da unidade",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Unidade removida com sucesso",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Unidade removida com sucesso")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Unidade n�o encontrada",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Unidade n�o encontrada")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Erro interno",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Falha ao remover unidade"),
     *             @OA\Property(property="error", type="string")
     *         )
     *     )
     * )
     */
    public function destroy(UnidadesDeleteRequest $request)
    {
        try {
            $unidade = Unidade::find($request->get('id'));
            if (!$unidade) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unidade nâo encontrada'
                ], Response::HTTP_NOT_FOUND);
            }

            if (!$unidade) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unidade nâo encontrada'
                ], Response::HTTP_NOT_FOUND);
            }

            $unidade->delete();

            return response()->json([
                'success' => true,
                'message' => 'Unidade removida com sucesso'
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Falha ao remover unidade',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
