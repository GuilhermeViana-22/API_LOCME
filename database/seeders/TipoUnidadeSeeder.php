<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TipoUnidade;

class TipoUnidadeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        TipoUnidade::create(['tipo' => 'Online']);
        TipoUnidade::create(['tipo' => 'Franqueada']);
    }
}
