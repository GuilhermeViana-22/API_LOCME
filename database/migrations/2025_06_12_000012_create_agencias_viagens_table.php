<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAgenciasViagensTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('agencias_viagens', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nome_agencia');
            $table->string('cnpj')->unique();
            $table->string('razao_social');
            $table->string('nome_fantasia');
            $table->string('email_institucional');
            $table->string('telefone_whatsapp');
            $table->string('cidade');
            $table->char('uf', 2);
            $table->string('endereco_completo');
            $table->string('cep', 10);
            $table->unsignedBigInteger('tipo_operacao');
            $table->boolean('recebe_representantes');
            $table->boolean('necessita_agendamento');
            $table->boolean('atende_freelance');
            $table->string('cadastur');
            $table->string('instagram');
            $table->integer('segmento_principal_id');
            $table->char('excursoes_proprias');
            $table->char('aceita_excursoes_outras');
            $table->text('descricao_livre');
            $table->string('logo_path');
            $table->boolean('divulgar');
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
        Schema::dropIfExists('agencias_viagens');
    }
}
