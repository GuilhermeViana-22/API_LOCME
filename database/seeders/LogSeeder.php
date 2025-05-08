<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Log;
use Carbon\Carbon;

class LogSeeder extends Seeder
{
    public function run()
    {
        $dataInicial = Carbon::now()->subMonths(4);

        // Rotas disponíveis
        $rotasDisponiveis = [
            '/dashboard',
            '/login',
            '/unidades',
            '/cargos',
            '/page' . rand(1, 5)
        ];

        $logs = [];
        for ($i = 0; $i < 30; $i++) {
            $logs[] = [
                'client_id' => 'client_' . str_pad($i + 1, 3, '0', STR_PAD_LEFT),
                'client_ip' => '192.168.' . rand(1, 255) . '.' . rand(1, 255),
                'name' => 'User ' . ($i + 1),
                'autenticado' => (bool)rand(0, 1),
                'rota' => $rotasDisponiveis[array_rand($rotasDisponiveis)],
                'created_at' => now(),
                'updated_at' => now()
            ];
        }

        foreach ($logs as $log) {
            Log::create($log);
        }
    }
}
