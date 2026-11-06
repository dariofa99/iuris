<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAutorizacionesHasEstadosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('autorizaciones_has_estados', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->string('comentario');

            $table->integer('autorizacion_id')->unsigned(); // 
            $table->foreign('autorizacion_id')->references('id')->on('autorizaciones');

            $table->integer('estado_id')->unsigned(); // 
            $table->foreign('estado_id')->references('id')->on('referencias_tablas');

            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users')
                ->onDelete('cascade')->onUpdate('cascade');

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
        Schema::dropIfExists('autorizaciones_has_estados');
    }
}
