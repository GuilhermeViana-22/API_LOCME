<?php

namespace App\Http\Controllers;

use App\Models\Questionario;
use App\Models\Pergunta; // vamos presumir que j� existe
use Illuminate\Http\Request;

class QuestionarioController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/questionarios",
     *     summary="Lista todos os question�rios",
     *     tags={"Questionarios"}
     * )
     */
    public function index()
    {
        $questionarios = Questionario::with('perguntas')->get();
        return response()->json($questionarios);
    }

    /**
     * @OA\Post(
     *     path="/api/questionarios",
     *     summary="Cria um novo questionário",
     *     tags={"Questionarios"}
     * )
     */
    public function store(Request $request)
    {
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

        return response()->json($questionario->load('perguntas'), 201);
    }

    /**
     * @OA\Get(
     *     path="/api/questionarios/{id}",
     *     summary="Exibe um question�rio espec�fico",
     *     tags={"Questionarios"}
     * )
     */
    public function show(Questionario $questionario)
    {
        return response()->json($questionario->load('perguntas'));
    }

    /**
     * @OA\Put(
     *     path="/api/questionarios/{id}",
     *     summary="Atualiza um question�rio",
     *     tags={"Questionarios"}
     * )
     */
    public function update(Request $request, Questionario $questionario)
    {
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

        return response()->json($questionario);
    }

    /**
     * @OA\Delete(
     *     path="/api/questionarios/{id}",
     *     summary="Remove um question�rio",
     *     tags={"Questionarios"}
     * )
     */
    public function destroy(Questionario $questionario)
    {
        $questionario->delete();

        return response()->json(['message' => 'Question�rio removido com sucesso']);
    }
}
