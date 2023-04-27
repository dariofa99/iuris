<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCitacionesEstudianteTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('citaciones_estudiante', function (Blueprint $table) {
            $table->increments('id');
            $table->string('motivo');            
            $table->string('fecha');
            $table->date('fecha_corta');
            $table->string('hora');
            $table->string('docente_fullname');
            $table->string('docidnumber');
            $table->integer('user_created_id');
            $table->integer('user_updated_id');
            $table->integer('asignacion_caso_id')->unsigned();
            $table->foreign('asignacion_caso_id')->references('id')->on('asignacion_caso')
            ->onUpdate('cascade')->onDelete('cascade');
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
        Schema::dropIfExists('citaciones_estudiante');
    }
}
