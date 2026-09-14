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
            // Turno al que pertenece el registro
            $table->unsignedInteger('turno_docente_id');
            $table->foreign('turno_docente_id')
                ->references('id')
                ->on('turnos_docentes');
            // Docente
            $table->string('docidnumber', 12);
            $table->foreign('docidnumber')
                ->references('idnumber')
                ->on('users');
            // Asistió / No asistió / Permiso / Pendiente por asistir
            $table->unsignedInteger('tipo_asis');
            $table->foreign('tipo_asis')
                ->references('id')
                ->on('referencias_tablas');
            // turno / reposicion
            $table->enum('categoria', [
                'turno',
                'reposicion'
            ])->default('turno')->nullable();
            // Hora real de asistencia
            $table->dateTime('inicio')->nullable();
            $table->dateTime('fin')->nullable();
            // Minutos que debe reponer
            $table->integer('minutos_reponer')->default(0);
            $table->longText('descripcion')->nullable();

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
