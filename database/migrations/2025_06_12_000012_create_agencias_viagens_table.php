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

            $table->string('endereco');
            $table->string('cidade');
            $table->string('estado');
            $table->string('cep');
            $table->string('pais');

            $table->integer('tipo_operacao_id');
            $table->integer('segmento_principal_id');

            $table->boolean('recebe_representantes')->default(false)->nullable();
            $table->boolean('necessita_agendamento')->default(false)->nullable();
            $table->boolean('atende_freelance')->default(false)->nullable();
            $table->boolean('excursoes_proprias')->default(false)->nullable();
            $table->boolean('aceita_excursoes_outras')->default(false)->nullable();
            $table->boolean('divulgar')->default(false)->nullable();

            $table->string('cadastur');
            $table->string('instagram')->default('')->nullable();

            $table->text('descricao_livre')->nullable();
            $table->string('logo_path')->default('')->nullable();

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
