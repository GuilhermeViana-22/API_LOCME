<?php

namespace Database\Seeders;

use App\Models\TipoPerfil;
use Illuminate\Database\Seeder;

class TipoPerfis extends Seeder
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
                'tipo' => 'Representante',
            ],
            [
                'tipo' => 'Agente de viagens',
            ],
            [
                'tipo' => 'Agência de viagens',
            ],
            [
                'tipo' => 'Guia de turismo',
            ],
            [
                'tipo' => 'Empresa / Entidade',
            ],
        ];

        foreach ($tipos as $tipoData) {
            TipoPerfil::create($tipoData);
        }
    }
}
