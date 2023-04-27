<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateConfiguracionesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('configuraciones', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nombre_largo');      
            $table->string('nombre_corto');  
            $table->string('descripcion')->nullable();
            $table->timestamps();
        });
  
        Schema::create('configuraciones_sede', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('configuracion_id')->unsigned();
            $table->foreign('configuracion_id')->references('id')->on('configuraciones')
            ->onUpdate('cascade')->onDelete('cascade');
            $table->integer('sede_id')->unsigned();
            $table->foreign('sede_id')->references('id_sede')->on('sedes')
            ->onUpdate('cascade')->onDelete('cascade');
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
        Schema::dropIfExists('configuraciones');
    }
}
