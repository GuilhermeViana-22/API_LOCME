<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Cargo;

class CargoSeeder extends Seeder
{
    public function run()
    {
        $cargos = [
            // Cargo, Nível Hierárquico, Departamento, Descrição (opcional)
            ['Auxiliar Administrativo', 1, 'Administrativo', 'Responsavel por tarefas administrativas gerais.'],
            ['Assistente Administrativo', 2, 'Administrativo', 'Auxilia nas atividades administrativas e operacionais.'],
            ['Coordenador Pedagogico', 3, 'Pedagogia', 'Coordena a equipe pedagogica e os planos de ensino.'],
            ['Professor de Educacao Basica', 4, 'Pedagogia', 'Responsavel pelo ensino e acompanhamento de alunos.'],
            ['Assistente de Direcao', 5, 'Administrativo', 'Auxilia a direcao na organizacao e gestao da escola.'],
            ['Diretor Pedagogico', 6, 'Pedagogia', 'Responsavel pela organizacao pedagogica da escola.'],
            ['Gerente de TI', 6, 'Tecnologia', 'Gerencia os recursos tecnologicos da escola.'],
            ['Diretor de Operacoes', 7, 'Administrativo', 'Gerencia as operacoes administrativas da escola.'],
            ['Vice-Diretor', 8, 'Administrativo', 'Assiste a direcao nas tarefas administrativas e pedagogicas.'],
            ['Diretor Geral', 9, 'Administrativo', 'Responsavel pela gestao geral da escola.'],
            ['Presidente', 10, 'Executivo', 'Responsavel pela estrategia e gestao maxima da instituicao.']
        ];


        // Inserção em massa
        foreach ($cargos as $cargo) {
            Cargo::create([
                'nome_cargo' => $cargo[0],
                'nivel_hierarquico' => $cargo[1],
                'departamento' => $cargo[2],
                'descricao' => $cargo[3] ?? null,  // Descrição é opcional
            ]);
        }
    }
}
