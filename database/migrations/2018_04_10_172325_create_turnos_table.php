<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTurnosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('turnos', function (Blueprint $table) {
            $table->increments('id');
            $table->string('trnid_estudent',12);
            $table->foreign('trnid_estudent')->references('idnumber')->on('users'); //identificación 

            $table->integer('trnid_color')->unsigned();//0->naranja, 1->Azul oscuro, 2->verde, 3->gris, 4->rojo
            $table->foreign('trnid_color')->references('id')->on('referencias_tablas'); // 

            $table->integer('trnid_horario')->unsigned();//0->8a10, 1->10a12, 2->2a4, 3->4a6
            $table->foreign('trnid_horario')->references('id')->on('referencias_tablas'); // 
          
            $table->integer('trnid_periodo')->unsigned();//segun la tabla periodo
            $table->foreign('trnid_periodo')->references('id')->on('periodo'); // 

            $table->integer('trnid_dia')->unsigned();//0->8a10, 1->10a12, 2->2a4, 3->4a6
            $table->foreign('trnid_dia')->references('id')->on('referencias_tablas'); //

            $table->integer('trnid_oficina')->unsigned();//0->8a10, 1->10a12, 2->2a4, 3->4a6
            $table->foreign('trnid_oficina')->references('id')->on('oficinas'); //


            $table->string('trnuserupdated',12)->nullable();
            $table->string('trnusercreated',12)->nullable();
           
            
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
        Schema::dropIfExists('turnos');
    }
}
