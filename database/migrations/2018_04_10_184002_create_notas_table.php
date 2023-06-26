<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNotasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notas', function (Blueprint $table) {
            $table->increments('id');
            $table->longText('nota');           
            //identificación est
            $table->string('estidnumber',12);
            $table->foreign('estidnumber')->references('idnumber')->on('users'); 
            //identificación docente
            $table->string('docidnumber',12);
            $table->foreign('docidnumber')->references('idnumber')->on('users');
            //expediente
            $table->string('expidnumber',30);
            $table->foreign('expidnumber')->references('expid')->on('expedientes'); //expid 
            

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
        Schema::dropIfExists('notas');
    }
}
