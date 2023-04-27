<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSegmentosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('segmentos', function (Blueprint $table) {
            $table->increments('id');
            $table->string('segnombre',100)->nullable();
            $table->date('fecha_inicio');
            $table->date('fecha_fin');
            $table->boolean('estado')->default(0);
            $table->boolean('act_fc')->default(0);
            $table->date('fecha_corte')->nullable();
            $table->boolean('est_evaluado')->default(0);
            $table->integer('perid')->unsigned();
            $table->foreign('perid')->references('id')->on('periodo'); //periodo 

            $table->string('segusercreated',12)->nullable();
            $table->string('seguserupdated',12)->nullable(); 
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
        Schema::dropIfExists('segmentos');
    }
}
