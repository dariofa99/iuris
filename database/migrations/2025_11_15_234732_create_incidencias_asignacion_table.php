<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIncidenciasAsignacionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('incidencias_asignacion', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('incidencia_id')->unsigned();
            $table->foreign('incidencia_id')->references('id')->on('incidencias');
            $table->integer('asig_caso_id')->unsigned();
            $table->foreign('asig_caso_id')->references('id')->on('asignacion_caso');
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
        Schema::dropIfExists('incidencias_asignacion');
    }
}
