<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Rule;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class MasterUserSeeder extends Seeder
{
    public function run()
    {
        // Criando o usuário master
        $master = User::create([
            'name' => 'Master User',
            'email' => 'master@master.com',
            'password' => Hash::make('abcd=1234') // Coloque a senha desejada aqui
        ]);

        // Marcando o usuário master como verificado (opcional, dependendo do seu sistema)
        $master->email_verified_at = now();
        $master->save();
    }
}
