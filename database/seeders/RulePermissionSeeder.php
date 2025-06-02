<?php

namespace Database\Seeders;

use App\Models\RulePosition;
use App\Models\RulePermission;
use Illuminate\Database\Seeder;

class RulePermissionSeeder extends Seeder
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
            for ($j = 1; $j <= 7; $j++) {
                RulePermission::create([
                    'rule_id' => $i,
                    'permission_id' => $j,
                ]);
            }
        }
    }
}
