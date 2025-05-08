<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('respostas', function (Blueprint $table) {
            $table->id();
            $table->integer('questionario_id');
            $table->foreignId('pergunta_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->boolean('resposta');
            $table->timestamps();

            // Garante que cada usuário só responda uma vez cada pergunta
            $table->unique(['pergunta_id', 'user_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('respostas');
    }
};