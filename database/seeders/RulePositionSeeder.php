<?php

namespace Database\Seeders;

use App\Models\Rule;
use App\Models\RulePosition;
use Illuminate\Database\Seeder;

class RulePositionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * @return void
     */
    public function run()
    {
        // Criando as regras
        for ($i = 1; $i <= 5; $i++) {
            for ($j = 1; $j <= 32; $j++) {
                RulePosition::create([
                    'rule_id' => $i,
                    'position_id' => $j,
                ]);
            }
        }
    }
}
