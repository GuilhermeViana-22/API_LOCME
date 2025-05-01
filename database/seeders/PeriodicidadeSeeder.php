<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PeriodicidadeSeeder extends Seeder
{
    public function run()
    {
        DB::table('periodicidade')->insert([
            [
                'id' => 1,
                'tipo_periodicidade' => 'Aferição',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 2,
                'tipo_periodicidade' => 'Diário',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 3,
                'tipo_periodicidade' => 'Mensal',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
