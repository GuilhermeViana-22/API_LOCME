<?php

namespace Database\Seeders;

use App\Models\Cargo;
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
            CargoSeeder::class,
            RuleSeeder::class,
            RuleUserSeeder::class,
            RulePermissionSeeder::class,
            LogSeeder::class,
            PermissionSeeder::class,
            PassportSeeder::class,
            UserSeeder::class,
        ]);
    }
}
