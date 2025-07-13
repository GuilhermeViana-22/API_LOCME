<?php

namespace Database\Seeders;

use App\Models\ProdutoServico;
use Illuminate\Database\Seeder;

class ProdutosServicoesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Criando as regras
        $tipos = [
            [
                'produto_servico' => 'Produto/Serviço 1',
            ],
            [
                'produto_servico' => 'Produto/Serviço 2',
            ],
            [
                'produto_servico' => 'Produto/Serviço 3',
            ],
        ];

        foreach ($tipos as $tipoData) {
            ProdutoServico::create($tipoData);
        }
    }
}
