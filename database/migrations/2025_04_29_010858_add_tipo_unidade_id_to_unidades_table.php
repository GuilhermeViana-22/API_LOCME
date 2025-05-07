<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('unidades', function (Blueprint $table) {
            $table->integer('tipo_unidade_id');
            $table->dropColumn('tipo_unidade'); // Remove a coluna antiga
        });
    }

    public function down()
    {
        Schema::table('unidades', function (Blueprint $table) {
            $table->dropForeign(['tipo_unidade_id']);
            $table->string('tipo_unidade', 50); // Recria a coluna antiga se necess�rio
        });
    }
};
