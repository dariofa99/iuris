<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateConciliacionesEstadosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('conciliaciones_estados', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('concepto')->nullable();
            $table->integer('type_status_id')->unsigned();
            $table->foreign('type_status_id')->references('id')->on('referencias_tablas')
            ->onDelete('cascade')->onUpdate('cascade');
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users')
            ->onDelete('cascade')->onUpdate('cascade');
            $table->integer('conciliacion_id')->unsigned();
            $table->foreign('conciliacion_id')->references('id')->on('conciliaciones')->onDelete('cascade')
            ->onUpdate('cascade');
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
        Schema::dropIfExists('conciliaciones_estados');
    }
}
