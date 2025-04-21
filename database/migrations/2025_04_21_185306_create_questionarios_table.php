<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQuestionariosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('questionarios', function (Blueprint $table) {
            $table->id();
            $table->string('titulo', 100);
            $table->text('descricao')->nullable();
            $table->enum('periodicidade', [""]);
            $table->date('data_inicio');
            $table->date('data_termino')->nullable();
            $table->boolean('ativo')->nullable();
            $table->enum('publico_alvo', [""]);
            $table->integer('criado_por');
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
        Schema::dropIfExists('questionarios');
    }
}
