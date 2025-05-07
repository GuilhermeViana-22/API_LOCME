<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Unidade;

class UnidadesTableSeeder extends Seeder
{
    public function run()
    {
        // Limpar a tabela primeiro (opcional)
        Unidade::truncate();

        // Criar até 2 unidades
        Unidade::factory()->count(15)->create();
    }
}
