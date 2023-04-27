<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAutorizacionesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('autorizaciones', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nombre_estudiante');
            $table->integer('num_identificacion');
            $table->string('doc_expedicion');
            $table->string('num_carne');
            $table->string('calidad_de');
            $table->string('tipo_proceso');
            $table->string('num_radicado');
            $table->string('juzgado');
            $table->date('fecha_autorizado')->nullable();
            $table->string('genero')->nullable();
            $table->boolean('estado')->default(0);

            $table->integer('asig_caso_id')->unsigned();
            $table->foreign('asig_caso_id')->references('id')->on('asignacion_caso');
            $table->integer('user_solicitante_id')->unsigned();
            $table->foreign('user_solicitante_id')->references('id')->on('users'); //expid 
            $table->integer('user_aprobo_id')->unsigned();
            $table->foreign('user_aprobo_id')->references('id')->on('users'); 
         
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
        //
    }
}
