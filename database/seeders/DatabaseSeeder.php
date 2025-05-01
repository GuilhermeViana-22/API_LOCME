<?php

namespace Database\Seeders;

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
            RuleSeeder::class,
            PermissionSeeder::class,
            PassportSeeder::class,
            TipoUnidadeSeeder::class,
            TipoPerguntaSeeder::class,
            PeriodicidadeSeeder::class,
        ]);
    }
}
