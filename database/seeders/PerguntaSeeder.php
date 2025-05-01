<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Pergunta;
use Illuminate\Support\Facades\DB;

class PerguntaSeeder extends Seeder
{
    public function run()
    {
        $perguntas = [
            ["Como você lida com feedbacks negativos?", 1, now()->format('Y-m-d'), 1],
            ["Descreva uma situação em que você precisou trabalhar em equipe.", 1, now()->format('Y-m-d'), 1],
            ["Como você prioriza tarefas com prazos conflitantes?", 1, now()->format('Y-m-d'), 1],
            ["Como você resolveu um conflito no ambiente de trabalho?", 1, now()->format('Y-m-d'), 1],
            ["Como você se adapta a diferentes estilos de comunicação?", 1, now()->format('Y-m-d'), 1],
            ["Como você mantém a motivação em projetos de longo prazo?", 1, now()->format('Y-m-d'), 1],
            ["Descreva um momento em que você precisou ser resiliente.", 1, now()->format('Y-m-d'), 1],
            ["Como você lida com mudanças inesperadas no trabalho?", 1, now()->format('Y-m-d'), 1],
            ["Como você dá feedback para colegas de equipe?", 1, now()->format('Y-m-d'), 1],
            ["Como você estabelece relações profissionais positivas?", 1, now()->format('Y-m-d'), 1],
            ["Como você gerencia o estresse em períodos de alta pressão?", 1, now()->format('Y-m-d'), 1],
            ["Descreva uma decisão difícil que você precisou tomar.", 1, now()->format('Y-m-d'), 1],
            ["Como você delega tarefas quando necessário?", 1, now()->format('Y-m-d'), 1],
            ["Como você lida com colegas que têm opiniões divergentes?", 1, now()->format('Y-m-d'), 1],
            ["Como você promove a colaboração em sua equipe?", 1, now()->format('Y-m-d'), 1],
            ["Como você se mantém organizado com múltiplas responsabilidades?", 1, now()->format('Y-m-d'), 1],
            ["Como você lida com críticas construtivas?", 1, now()->format('Y-m-d'), 1],
            ["Descreva como você lidou com um erro cometido no trabalho.", 1, now()->format('Y-m-d'), 1],
            ["Como você se comunica com pessoas mais introvertidas/extrovertidas?", 1, now()->format('Y-m-d'), 1],
            ["Como você mantém o foco em ambientes com distrações?", 1, now()->format('Y-m-d'), 1],
            ["Como você negocia prazos ou recursos quando necessário?", 1, now()->format('Y-m-d'), 1],
            ["Como você contribui para um ambiente de trabalho positivo?", 1, now()->format('Y-m-d'), 1],
            ["Como você lida com tarefas que estão fora da sua zona de conforto?", 1, now()->format('Y-m-d'), 1],
            ["Como você demonstra empatia no ambiente profissional?", 1, now()->format('Y-m-d'), 1]
        ];

        foreach ($perguntas as $pergunta) {
            Pergunta::firstOrCreate(
                ['pergunta' => $pergunta[0]],
                [
                    'pergunta' => $pergunta[0],
                    'tipo_pergunta_id' => $pergunta[1],
                    'tipo_periodicidade_id' => $pergunta[3]
                ]
            );
        }
    }
}
