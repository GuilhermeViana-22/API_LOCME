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
     * Lista todas as perguntas
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
            ], Response::HTTP_NOT_FOUND);
        }
    }

    /**
     * Cria uma nova pergunta
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
     * Exibe uma pergunta específica
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
     * Atualiza uma pergunta
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
     * Remove uma pergunta
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
