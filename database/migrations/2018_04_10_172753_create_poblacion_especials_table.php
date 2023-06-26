<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePoblacionEspecialsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('poblacion_especials', function (Blueprint $table) {
            $table->increments('id');
             $table->string('pbenombre')->nullable();
            $table->string('pbeusercreated',12)->nullable();
            $table->string('pbeuserupdated',12)->nullable();
            
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
        Schema::dropIfExists('poblacion_especials');
    }
}
