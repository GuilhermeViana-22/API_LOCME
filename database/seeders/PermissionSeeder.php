<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Criando as permissões básicas
        Permission::create(['name' => 'create', 'description' => 'Permissão para criar novos recursos']);
        Permission::create(['name' => 'read', 'description' => 'Permissão para ler ou visualizar recursos']);
        Permission::create(['name' => 'update', 'description' => 'Permissão para atualizar recursos existentes']);
        Permission::create(['name' => 'delete', 'description' => 'Permissão para excluir recursos']);

        // Adicionando novas permissões
        Permission::create(['name' => 'generate_reports', 'description' => 'Permissão para gerar relatórios']);
        Permission::create(['name' => 'export_files', 'description' => 'Permissão para exportar arquivos']);
        Permission::create(['name' => 'download_system_info', 'description' => 'Permissão para baixar informações do sistema']);

    }
}
