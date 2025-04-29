<?php

namespace App\Http\Controllers;

use App\Http\Requests\Unidades\UnidadeStoreRequest;
use App\Models\Unidade;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

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
    public function index()
    {
        try {
            $unidades = Unidade::all();

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
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\UnidadeStoreRequest  $request
     * @return \Illuminate\Http\JsonResponse
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
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        try {
            $unidade = Unidade::find($id);

            if (!$unidade) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unidade não encontrada'
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
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        try {
            $unidade = Unidade::find($id);

            if (!$unidade) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unidade não encontrada'
                ], Response::HTTP_NOT_FOUND);
            }

            $validator = Validator::make($request->all(), [
                'nome_unidade' => 'sometimes|string|max:100',
                'codigo_unidade' => 'sometimes|string|max:20|unique:unidades,codigo_unidade,' . $id,
                'tipo_unidade' => 'sometimes|string',
                'endereco_rua' => 'sometimes|string|max:100',
                'endereco_numero' => 'sometimes|string|max:20',
                'endereco_complemento' => 'nullable|string|max:50',
                'endereco_bairro' => 'sometimes|string|max:50',
                'endereco_cidade' => 'sometimes|string|max:50',
                'endereco_estado' => 'sometimes|string|size:2',
                'endereco_cep' => 'sometimes|string|max:10',
                'telefone_principal' => 'sometimes|string|max:20',
                'email_unidade' => 'nullable|email|max:100',
                'data_inauguracao' => 'nullable|date',
                'quantidade_setores' => 'nullable|integer',
                'ativo' => 'nullable|boolean'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erro de validação',
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
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        try {
            $unidade = Unidade::find($id);

            if (!$unidade) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unidade não encontrada'
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
