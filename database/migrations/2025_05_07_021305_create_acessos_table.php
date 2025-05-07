<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAcessosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('acessos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('rota');
            $table->string('ip');
            $table->enum('dispositivo', ['desktop', 'mobile', 'tablet', 'outro']);
            $table->boolean('ativo')->default(true);
            $table->timestamp('data_acesso')->useCurrent();
            $table->timestamps();
            // Chave estrangeira para a tabela de usuários
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('acessos');
    }
}
