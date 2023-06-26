<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHistorialDatosCasosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('historial_datos_casos', function (Blueprint $table) {
            $table->increments('id');
            $table->longText('hisdc_datos_caso');
            $table->integer('hisdc_tipo_datos_caso')->unsigned(); // 
            $table->foreign('hisdc_tipo_datos_caso')->references('id')->on('referencias_tablas'); //identificación 
            $table->string('hisdc_expidnumber',30);
            $table->foreign('hisdc_expidnumber')->references('expid')->on('expedientes'); //expid 
            $table->integer('hisdc_ndias');
            $table->boolean('hisdc_estado');
            $table->integer('hisdc_idnumberest_id');
            $table->integer('hisdc_authuser_id');
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
        Schema::dropIfExists('historial_datos_casos');
    }
}
