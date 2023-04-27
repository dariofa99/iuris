<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTurnosDocentesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('turnos_docentes', function (Blueprint $table) {
            $table->increments('id');
            $table->string('trnd_docidnumber',12);
            $table->foreign('trnd_docidnumber')->references('idnumber')->on('users'); //identificación
            $table->string('trnd_dia',9);
            $table->time('trnd_hora_inicio');
            $table->time('trnd_hora_fin');
            $table->integer('trndid_periodo')->unsigned();//segun la tabla periodo
            $table->foreign('trndid_periodo')->references('id')->on('periodo'); // 
            $table->string('trndusercreated',12)->nullable();
            $table->string('trnduserupdated',12)->nullable(); 
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
        Schema::dropIfExists('turnos_docentes');
    }
}
