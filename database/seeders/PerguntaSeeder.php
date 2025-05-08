<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Pergunta;

class PerguntaSeeder extends Seeder
{
    public function run()
    {
        $tipoPerguntaId = 1;
        $tipoPeriodicidadeId = 1;

        $perguntas = [
            "Como você lida com feedbacks negativos?",
            "Descreva uma situação em que você precisou trabalhar em equipe.",
            "Como você prioriza tarefas com prazos conflitantes?",
            "Como você resolveu um conflito no ambiente de trabalho?",
            "Como você se adapta a diferentes estilos de comunicação?",
            "Como você mantém a motivação em projetos de longo prazo?",
            "Descreva um momento em que você precisou ser resiliente.",
            "Como você lida com mudanças inesperadas no trabalho?",
            "Como você dá feedback para colegas de equipe?",
            "Como você estabelece relações profissionais positivas?",
            "Como você gerencia o estresse em períodos de alta pressão?",
            "Descreva uma decisão difícil que você precisou tomar.",
            "Como você delega tarefas quando necessário?",
            "Como você lida com colegas que têm opiniões divergentes?",
            "Como você promove a colaboração em sua equipe?",
            "Como você se mantém organizado com múltiplas responsabilidades?",
            "Como você lida com críticas construtivas?",
            "Descreva como você lidou com um erro cometido no trabalho.",
            "Como você se comunica com pessoas mais introvertidas/extrovertidas?",
            "Como você mantém o foco em ambientes com distrações?",
            "Como você negocia prazos ou recursos quando necessário?",
            "Como você contribui para um ambiente de trabalho positivo?",
            "Como você lida com tarefas que estão fora da sua zona de conforto?",
            "Como você demonstra empatia no ambiente profissional?"
        ];

        $dados = array_map(function($texto) use ($tipoPerguntaId, $tipoPeriodicidadeId) {
            return [
                'pergunta' => $texto,
                'tipo_pergunta_id' => $tipoPerguntaId,
                'tipo_periodicidade_id' => $tipoPeriodicidadeId,
                'created_at' => now(),
                'updated_at' => now()
            ];
        }, $perguntas);

        Pergunta::insert($dados);
    }
}
