<?php

namespace Database\Seeders;

use App\Models\UnidadeLocalidade;
use Illuminate\Database\Seeder;

class UnidadesLocalidadesSeeder extends Seeder
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
                'unidade_localidade' => 'Norte',
            ],
            [
                'unidade_localidade' => 'Sul',
            ],
            [
                'unidade_localidade' => 'Leste',
            ],
            [
                'unidade_localidade' => 'Oeste',
            ],
            [
                'unidade_localidade' => 'Nordeste',
            ],
            [
                'unidade_localidade' => 'Noroeste',
            ],
            [
                'unidade_localidade' => 'Sudeste',
            ],
            [
                'unidade_localidade' => 'Sudoeste',
            ],
        ];

        foreach ($tipos as $tipoData) {
            UnidadeLocalidade::create($tipoData);
        }
    }
}
