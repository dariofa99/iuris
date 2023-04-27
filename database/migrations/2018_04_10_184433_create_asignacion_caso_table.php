<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAsignacionCasoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('asignacion_caso', function (Blueprint $table) {
            $table->increments('id');
            $table->string('anotacion');
            $table->boolean('activo')->default(1);

            $table->string('asigest_id',12);
            $table->foreign('asigest_id')->references('idnumber')->on('users');

            $table->string('asiguser_id',12);
            $table->foreign('asiguser_id')->references('idnumber')->on('users');

            $table->string('asigexp_id',30);
            $table->foreign('asigexp_id')->references('expid')->on('expedientes');

            $table->integer('periodo_id')->unsigned();
            $table->foreign('periodo_id')->references('id')->on('periodo');
            $table->timestamp('fecha_asig');
            $table->integer('ref_asig_id')->unsigned();
            $table->foreign('ref_asig_id')->references('id')->on('ref_asignacion');

            $table->integer('ref_mot_asig_id')->unsigned();
            $table->foreign('ref_mot_asig_id')->references('id')->on('ref_mot_asig_caso');
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
        Schema::dropIfExists('asignacion_caso');
    }
}
