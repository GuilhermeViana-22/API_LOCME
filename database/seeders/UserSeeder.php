<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();

        // Criando 30 usu�rios
        foreach (range(1, 30) as $index) {
            User::create([
                'name' => $faker->name,
                'password' => Hash::make('password123'), // Senha fixa para todos os usu�rios
                'cpf' => $faker->numerify('###########'), // Gerar um CPF com 11 n�meros
                'email' => $faker->unique()->userName.'@empresa.com', // E-mail corporativo gerado
                'data_nascimento' => $faker->date(),
                'telefone_celular' => $faker->phoneNumber,
                'genero' => $faker->randomElement(['masculino', 'feminino', 'outro']),
                'position_id' => $faker->numberBetween(1, 10), // Exemplo, ajuste conforme seus cargos
                'unidade_id' => $faker->numberBetween(1, 5), // Exemplo, ajuste conforme suas unidades
                'status_id' => $faker->numberBetween(1, 3), // Exemplo, ajuste conforme seus status
                'situacao_id' => $faker->numberBetween(1, 2), // Exemplo, ajuste conforme suas situa��es
                'foto_perfil' => null, // Caso queira adicionar fotos de perfil, voc� pode usar uma URL aleat�ria ou criar imagens fake
                'ativo' => $faker->boolean(90), // 90% de chance de o usu�rio ser ativo
            ]);
        }
    }
}
