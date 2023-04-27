<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNotasExtTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notas_ext', function (Blueprint $table) {
            $table->increments('id');
            $table->longText('nota');           
            //identificación est
            $table->string('estidnumber',12);
            $table->foreign('estidnumber')->references('idnumber')->on('users'); 
            //identificación docente
            $table->string('extidnumber',12);
            $table->foreign('extidnumber')->references('idnumber')->on('users');
            
            $table->integer('cptnotaid')->unsigned();
            $table->foreign('cptnotaid')->references('id')->on('cptonotas'); //concepto nota 

            $table->integer('segid')->unsigned();
            $table->foreign('segid')->references('id')->on('segmentos'); //segmento 

            $table->integer('perid')->unsigned();
            $table->foreign('perid')->references('id')->on('periodo'); //periodo 

            $table->integer('orgntsid')->unsigned();
            $table->foreign('orgntsid')->references('id')->on('origen_notas'); //origen nota 
            
            $table->integer('tpntid')->unsigned();
            $table->foreign('tpntid')->references('id')->on('tipo_nota'); //tipo nota 
            $table->string('tbl_org_id');
            
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
        Schema::dropIfExists('notas_ext');
    }
}
