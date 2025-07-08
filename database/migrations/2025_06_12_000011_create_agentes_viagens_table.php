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
        Schema::create('agentes_viagens', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nome_completo');
            $table->string('cpf');
            $table->date('data_nascimento');
            $table->string('email');
            $table->string('whatsapp');
            $table->char('uf', 2);
            $table->string('cidade');
            $table->string('portfolio_redes_sociais');
            $table->boolean('vinculado_agencia')->default(false)->nullable();
            $table->integer('agencia_id')->nullable();
            $table->boolean('tem_cnpj_proprio')->default(false)->nullable();
            $table->string('cnpj_proprio')->nullable();
            $table->boolean('aceita_contato_representantes')->default(false)->nullable();
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
        Schema::dropIfExists('agentes_viagens');
    }
}
