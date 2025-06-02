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
            PositionSeeder::class,
            RuleSeeder::class,
            RulePositionSeeder::class,
            RulePermissionSeeder::class,
            LogSeeder::class,
            PermissionSeeder::class,
            PassportSeeder::class,
            UserSeeder::class,
        ]);
    }
}
