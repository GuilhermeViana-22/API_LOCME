<?php

namespace Database\Seeders;

use App\Models\Rule;
use App\Models\RuleUser;
use Illuminate\Database\Seeder;

class RuleUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Criando as regras
        for ($i = 1; $i <= 5; $i++) {
            for ($j = 1; $j <= 32; $j++) {
                RuleUser::create([
                    'rule_id' => $i,
                    'user_id' => $j,
                ]);
            }
        }
    }
}
