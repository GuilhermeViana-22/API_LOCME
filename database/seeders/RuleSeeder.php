<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Rule;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class RuleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Criando as regras
        $rules = [
            [
                'name' => 'master',
                'description' => 'Papel com acesso completo a todas as funcionalidades.'
            ],
            [
                'name' => 'admin',
                'description' => 'Administrador com permissões elevadas.'
            ],
            [
                'name' => 'editor',
                'description' => 'Editor com permissões para criar e editar conteúdo.'
            ],
            [
                'name' => 'viewer',
                'description' => 'Visualizador com permissões apenas para visualizar conteúdo.'
            ],
            [
                'name' => 'invited',
                'description' => 'Usuário convidado com acesso limitado.'
            ],
        ];

        foreach ($rules as $ruleData) {
            Rule::create($ruleData);
        }

    }
}
