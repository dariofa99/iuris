<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSolicitudHasConcTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('solicitud_has_conc', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('solicitud_id')->unsigned();
            $table->foreign('solicitud_id')->references('id')->on('solicitudes')
            ->onDelete('cascade')->onUpdate('cascade');
            $table->integer('conciliacion_id')->unsigned();
            $table->foreign('conciliacion_id')->references('id')->on('conciliaciones')
            ->onDelete('cascade')->onUpdate('cascade');
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
        Schema::dropIfExists('solicitud_has_conc');
    }
}
