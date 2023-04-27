<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAsesoriasDocenteTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('asesorias_docente', function (Blueprint $table) {
            $table->increments('id');
              $table->longText('comentario');
            $table->boolean('estado');
            //se adiciono manualmente
            $table->boolean('apl_shared');  

            //identificación est
            $table->string('estidnumber',12);
            $table->foreign('estidnumber')->references('idnumber')->on('users'); 
            //identificación docente
            $table->string('docidnumber',12);
            $table->foreign('docidnumber')->references('idnumber')->on('users');
            //expediente
            $table->string('expidnumber',30);
            $table->foreign('expidnumber')->references('expid')->on('expedientes'); //expid 
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
        Schema::dropIfExists('asesorias_docente');
    }
}
