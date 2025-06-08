<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmpresas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('empresas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('atividade_id')->constrained('empresas_atividades');
            $table->string('cnpj');
            $table->string('nome_fantasia');
            $table->string('razao_social');
            $table->foreignId('situacao_id')->constrained('empresas_situacoes');
            $table->foreignId('tipo_estabelecimento_id')->constrained('tipos_estabelecimentos');
            $table->foreignId('natureza_id')->constrained('empresas_naturezas');
            $table->foreignId('porte_id')->constrained('empresas_portes');
            $table->string('endereco');
            $table->string('uf');
            $table->string('municipio');
            $table->dateTime('data_abertura');
            $table->string('telefone');
            $table->string('email');
            $table->string('website');
            $table->string('idiomas');
            $table->foreignId('tipo_hospedagem_id')->constrained('tipos_hospedagens');
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
        Schema::dropIfExists('empresas');
    }
}
