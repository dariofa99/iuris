<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAudienciaConciliacionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('conciliacion_audiencias', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('id_conciliacion')->unsigned();
            $table->foreign('id_conciliacion')->references('id')->on('conciliaciones')
            ->onDelete('cascade')->onUpdate('cascade');
            $table->string('access_code');
            $table->date('fecha');
            $table->string('hora',10);
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
        Schema::dropIfExists('audiencia_conciliacion');
    }
}
