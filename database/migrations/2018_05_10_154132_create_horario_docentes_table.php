<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHorarioDocentesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('horario_docentes', function (Blueprint $table) {
            $table->increments('id');

            $table->string('docidnumber',12);
            $table->foreign('docidnumber')->references('idnumber')->on('users');
            $table->string('horas_a');            
            $table->string('horas_b');
            $table->string('num_max_est');
            $table->string('num_est_a');
            $table->string('num_est_b');

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
        Schema::dropIfExists('horario_docentes');
    }
}
