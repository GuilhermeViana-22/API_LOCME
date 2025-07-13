<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePerfisUnidadesLocalidadesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('perfis_unidades_localidades', function (Blueprint $table) {
            $table->id();
            $table->integer('perfil_id');
            $table->integer('tipo_perfil_id');
            $table->integer('unidade_localidade_id');
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
        Schema::dropIfExists('perfis_unidades_localidades');
    }
}
