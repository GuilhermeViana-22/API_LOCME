<?php

namespace Database\Seeders;

use App\Models\Position;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            UserSeeder::class,
            PositionSeeder::class,
            RuleSeeder::class,
            RulePositionSeeder::class,
            RulePermissionSeeder::class,
            LogSeeder::class,
            PermissionSeeder::class,
            PassportSeeder::class,

            TipoPerfis::class,
            AtividadesSeeder::class,
            UnidadesLocalidadesSeeder::class,
            ProdutosServicoesSeeder::class
        ]);
    }
}
