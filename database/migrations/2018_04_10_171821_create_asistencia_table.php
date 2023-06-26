<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAsistenciaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('asistencia', function (Blueprint $table) {
            $table->increments('id');
            $table->string('astdescrip_asist')->nullable();
            $table->dateTime('astfecha');
            $table->string('astid_estudent',12);
            $table->foreign('astid_estudent')->references('idnumber')->on('users'); //identificación
            $table->integer('astid_lugar')->unsigned();//por definir
            $table->foreign('astid_lugar')->references('id')->on('referencias_tablas'); //identificación
            $table->integer('astid_tip_asist')->unsigned();//1->falta, 2->reposicion, 3->
            $table->foreign('astid_tip_asist')->references('id')->on('referencias_tablas'); //identificación
            $table->string('astusercreated',12)->nullable();
            $table->string('astuserupdated',12)->nullable();
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
        Schema::dropIfExists('asistencia');
    }
}
