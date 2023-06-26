<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAsistenciaDocentesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('asistencia_docentes', function (Blueprint $table) {
            $table->increments('id');

            $table->string('docidnumber',12);
            $table->foreign('docidnumber')->references('idnumber')->on('users');//identificación docente

            $table->integer('tipo_asis')->unsigned(); // 
            $table->foreign('tipo_asis')->references('id')->on('referencias_tablas'); //Tipo de asistencia: asistencia, permiso, reposicion 
            $table->boolean('reposicion')->default(0);
            
            $table->dateTime('inicio');
            $table->dateTime('fin');
            $table->longText('descripcion');

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
        Schema::dropIfExists('asistencia_docentes');
    }
}
