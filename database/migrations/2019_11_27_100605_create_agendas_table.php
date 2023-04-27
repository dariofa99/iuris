<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAgendasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('agendas', function (Blueprint $table) {
            $table->increments('id_agen');
            $table->string('agen_idnumber',12);
            $table->foreign('agen_idnumber')->references('idnumber')->on('users');
            $table->integer('agen_tipo')->unsigned(); // 
            $table->foreign('agen_tipo')->references('id')->on('referencias_tablas'); //identificación 
            $table->time('agen_hora_inicio');
            $table->time('agen_hora_fin');
            $table->date('agen_fecha');
            $table->string('agen_motivo',100);
            $table->longText('agen_anotacion');
            $table->integer('agen_novedad')->unsigned(); // 
            $table->foreign('agen_novedad')->references('id')->on('referencias_tablas'); //identificación 
            $table->integer('agen_created_id');
            $table->integer('agen_updated_id');
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
        Schema::dropIfExists('agendas');
    }
}
