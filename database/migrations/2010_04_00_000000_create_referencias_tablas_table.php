<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReferenciasTablasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('referencias_tablas', function (Blueprint $table) {
            $table->increments('id');
            $table->string('ref_value');
            $table->string('ref_nombre');
            $table->string('categoria');
            $table->string('color');
            $table->string('tabla_ref');
            $table->timestamps();
        });


        ///
        Schema::create('ref_tipproceso', function (Blueprint $table) {
            $table->increments('id');
             $table->string('ref_tipproceso');            
            $table->timestamps();
        });

        ///
        Schema::create('ref_reqasis', function (Blueprint $table) {
            $table->increments('reqid_refasis');
             $table->string('ref_reqasistencia');            
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
        Schema::dropIfExists('referencias_tablas');
        Schema::dropIfExists('ref_tipproceso');
        Schema::dropIfExists('ref_reqasis');
    }
}
