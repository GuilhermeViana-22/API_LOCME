<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAgentesViagensTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('Agentes_Viagens', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nome_completo');
            $table->string('cpf');
            $table->string('email');
            $table->string('whatsapp');
            $table->string('cidade');
            $table->char('uf', 2);
            $table->boolean('vinculado_agencia');
            $table->string('cnpj_agencia_vinculada');
            $table->boolean('tem_cnpj_proprio');
            $table->string('portfolio_redes_sociais');
            $table->boolean('aceita_contato_representantes');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('Agentes_Viagens');
    }
}
