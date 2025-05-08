<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUnidadesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('unidades', function (Blueprint $table) {
            $table->id();
            $table->string('nome_unidade', 100);
            $table->string('codigo_unidade', 20)->unique();
            $table->integer('tipo_unidade_id');
            $table->string('endereco_rua', 100);
            $table->string('endereco_numero', 20);
            $table->string('endereco_complemento', 50)->nullable();
            $table->string('endereco_bairro', 50);
            $table->string('endereco_cidade', 50);
            $table->char('endereco_estado', 2);
            $table->string('endereco_cep', 10);
            $table->string('telefone_principal', 20);
            $table->string('email_unidade', 100)->nullable();
            $table->date('data_inauguracao')->nullable();
            $table->integer('quantidade_setores')->nullable();
            $table->boolean('ativo')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('unidades');
    }
}
