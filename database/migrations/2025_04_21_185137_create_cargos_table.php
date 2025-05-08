<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateCargosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
{
    Schema::create('cargos', function (Blueprint $table) {
        $table->id();
        $table->string('nome_cargo', 100);
        $table->integer('nivel_hierarquico');
        $table->string('departamento', 50);
        $table->text('descricao')->nullable();
        $table->timestamps();
    });

    // Alterando a tabela para usar utf8mb4, caso não tenha sido criada com essa codificação
    DB::statement("ALTER TABLE cargos CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;");
}


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cargos');
    }
}
