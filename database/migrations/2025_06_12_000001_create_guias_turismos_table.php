<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGuiasTurismosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('Guias_Turismos', function (Blueprint $table) {
            $table->id();
            $table->text('apelido');
            $table->text('whatsapp');
            $table->text('email_contato');
            $table->date('data_nascimento');
            $table->text('cadastur')->nullable();
            $table->bigInteger('abrangencia_id');
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
        Schema::dropIfExists('Guias_Turismos');
    }
}
