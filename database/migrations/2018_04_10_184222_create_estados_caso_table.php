<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEstadosCasoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('estados_caso', function (Blueprint $table) {
            $table->increments('id');
              $table->string('comentario',4000);
            //identificación user
            $table->string('useridnumber',12);
            $table->foreign('useridnumber')->references('idnumber')->on('users');
            //expediente
            $table->string('expidnumber',30);
            $table->foreign('expidnumber')->references('expid')->on('expedientes'); //expid 
            //estado
            $table->integer('ref_estado_id')->unsigned();
            $table->foreign('ref_estado_id')->references('id')->on('ref_estados');
            //motivo_cierre_caso
            $table->integer('ref_motivo_estado_id')->unsigned();
            $table->foreign('ref_motivo_estado_id')->references('id')->on('ref_motivos_estado_caso');
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
        Schema::dropIfExists('estados_caso');
    }
}
