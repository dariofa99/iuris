<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIncidenciasEstadoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('incidencias_estado', function (Blueprint $table) {
            $table->increments('id');
            $table->string('motivo')->nullable();
            $table->integer('incidencia_id')->unsigned();
            $table->foreign('incidencia_id')->references('id')->on('asignacion_caso');
            $table->integer('user_id')->unsigned(); // 
            $table->foreign('user_id')->references('id')->on('users');
            $table->integer('estado_id')->unsigned(); // 
            $table->foreign('estado_id')->references('id')->on('referencias_tablas');
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
        Schema::dropIfExists('incidencias_estado');
    }
}
