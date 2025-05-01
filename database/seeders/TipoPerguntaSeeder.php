<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TipoPerguntaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('tipo_pergunta')->insert([
            [
                'id' => 1,
                'tipo_pergunta' => 'Pessoal',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 2,
                'tipo_pergunta' => 'Empresarial',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
