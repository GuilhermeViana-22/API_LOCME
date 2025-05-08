<?php

namespace App\Http\Controllers;

use App\Http\Requests\ResponderPorTipoRequest;
use App\Models\Pergunta;
use App\Models\Resposta;
use App\Models\TipoPergunta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;  // ← Adicione esta linha

class QuestionarioController extends Controller
{
    /**
     * Listar perguntas por tipo
     */
    public function listarPerguntasPorTipo($tipoId)
    {
        try {
            $tipo = TipoPergunta::findOrFail($tipoId);

            $perguntas = Pergunta::with('tipoPergunta')
                ->where('tipo_pergunta_id', $tipoId)
                ->orderBy('id')
                ->get(['id', 'pergunta', 'tipo_pergunta_id']);

            return response()->json([
                'success' => true,
                'data' => [
                    'tipo' => $tipo,
                    'perguntas' => $perguntas,
                    'total_perguntas' => $perguntas->count()
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao listar perguntas',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Responder perguntas de um tipo específico
     */
    public function responderPorTipo(ResponderPorTipoRequest $request, $tipoId)
    {
        DB::beginTransaction();

        try {
            $tipo = TipoPergunta::findOrFail($tipoId);
            $validated = $request->validated();

            // Obter o último questionario_id usado pelo usuário
            $ultimoQuestionario = Resposta::where('user_id', $validated['user_id'])
                                        ->max('questionario_id') ?? 0;

            $novoQuestionarioId = $ultimoQuestionario + 1;

            foreach ($validated['respostas'] as $resposta) {
                Resposta::create([ // Usamos create() em vez de updateOrCreate()
                    'user_id' => $validated['user_id'],
                    'pergunta_id' => $resposta['pergunta_id'],
                    'questionario_id' => $novoQuestionarioId,
                    'tipo_pergunta_id' => $tipoId,
                    'resposta' => $resposta['resposta']
                ]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => "Questionário #$novoQuestionarioId do tipo {$tipo->tipo_pergunta} salvo com sucesso!",
                'questionario_id' => $novoQuestionarioId
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Erro ao salvar respostas',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Verificar progresso do usuário
     */
    public function verificarProgresso()
    {
        try {
            $userId = auth()->id();
            $tipos = TipoPergunta::all();

            $progresso = [];

            foreach ($tipos as $tipo) {
                $totalPerguntas = Pergunta::where('tipo_pergunta_id', $tipo->id)->count();
                $respondidas = Resposta::where('user_id', $userId)
                    ->where('tipo_pergunta_id', $tipo->id)
                    ->count();

                $progresso[] = [
                    'tipo_id' => $tipo->id,
                    'tipo_pergunta' => $tipo->tipo_pergunta,
                    'respondidas' => $respondidas,
                    'total_perguntas' => $totalPerguntas,
                    'porcentagem' => $totalPerguntas > 0
                        ? round(($respondidas / $totalPerguntas) * 100, 2)
                        : 0
                ];
            }

            return response()->json([
                'success' => true,
                'data' => $progresso
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao verificar progresso',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
