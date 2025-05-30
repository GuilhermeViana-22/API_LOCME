<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInterpretacoesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('interpretacoes', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->integer('xml');
            $table->integer('json');
            $table->integer('status');
            $table->integer('resultado');
            $table->integer('job');
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
        Schema::dropIfExists('interpretacoes');
    }
}
