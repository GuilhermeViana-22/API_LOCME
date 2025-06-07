<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {

            /// colunas do user

            $table->id();
            $table->string('name', 255);
            $table->string('cpf', 14);
            $table->string('email', 255)->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');

            /// colunas do perfil / todas nullable porque tem que ser cadastradas logo após o cadastro/primeiro login

            $table->date('data_nascimento')->nullable(); // Alterado para date
            $table->string('telefone_celular', 20)->nullable();
            $table->string('cargo_funcao', 255)->nullable();
            $table->integer('empresa_atual_id')->nullable(); // Mantido como inteiro sem FK
            $table->string('foto_perfil', 255)->nullable();
            $table->string('cidade', 255)->nullable();
            $table->string('estado', 255)->nullable();
            $table->string('email_contato', 255)->nullable();
            $table->string('linkedin', 255)->nullable();
            $table->string('bio', 500)->nullable();
            $table->boolean('visibilidade_telefone')->default(true); // Alterado para boolean
            $table->boolean('visibilidade_email')->default(true); // Alterado para boolean
            $table->boolean('visibilidade_linkedin')->default(true); // Alterado para boolean

            /// colunas extras que podem ser usadas

            $table->string('genero', 20)->nullable(); // Alterado para string
            $table->integer('position_id')->nullable(); // Mantido como inteiro sem FK
            $table->integer('unidade_id')->nullable(); // Mantido como inteiro sem FK
            $table->integer('status_id')->nullable(); // Mantido como inteiro sem FK
            $table->timestamp('ultimo_acesso')->nullable(); // Alterado para timestamp
            $table->boolean('ativo')->default(true); // Alterado para boolean
            $table->integer('situacao_id')->nullable(); // Mantido como inteiro sem FK
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
