<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('tipo_unidades', function (Blueprint $table) {
            $table->id();
            $table->string('tipo', 50)->unique(); // 'Online', 'Franqueada', etc.
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('tipo_unidades');
    }
};