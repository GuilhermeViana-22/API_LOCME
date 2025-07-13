<?php

namespace Database\Seeders;

use App\Models\Atividade;
use Illuminate\Database\Seeder;

class AtividadesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Criando as regras
        $tipos = [
            [
                'atividade' => 'Atividade 1',
            ],
            [
                'atividade' => 'Atividade 2',
            ],
            [
                'atividade' => 'Atividade 3',
            ],
            [
                'atividade' => 'Atividade 4',
            ],
            [
                'atividade' => 'Atividade 5',
            ],
        ];

        foreach ($tipos as $tipoData) {
            Atividade::create($tipoData);
        }
    }
}
