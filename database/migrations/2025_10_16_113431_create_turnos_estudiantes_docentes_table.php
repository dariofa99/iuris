<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTurnosEstudiantesDocentesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('turnos_estudiantes_docentes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->date('fecha'); // Día exacto del turno
            $table->time('hora_inicio');
            $table->time('hora_fin');
            $table->integer('docente_id')->unsigned(); // 
            $table->foreign('docente_id')->references('id')->on('users');
            $table->integer('estudiante_id')->unsigned(); // 
            $table->foreign('estudiante_id')->references('id')->on('users');
            $table->integer('estado_id')->unsigned(); // 
            $table->foreign('estado_id')->references('id')->on('referencias_tablas');
            $table->timestamps();
            $table->unique(['docente_id', 'fecha', 'hora_inicio'], 'turno_unico_por_docente');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('turnos_estudiantes_docentes');
    }
}
