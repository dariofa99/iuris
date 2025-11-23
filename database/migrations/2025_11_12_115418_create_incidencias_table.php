<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIncidenciasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('incidencias', function (Blueprint $table) {
            $table->increments('id');
            $table->longText('motivo')->nullable();
            /*   $table->integer('asig_caso_id')->unsigned();
            $table->foreign('asig_caso_id')->references('id')->on('asignacion_caso');     */
            $table->integer('user_id')->unsigned(); // 
            $table->foreign('user_id')->references('id')->on('users');
            $table->integer('categoria_id')->unsigned(); // 
            $table->foreign('categoria_id')->references('id')->on('referencias_tablas');
            $table->integer('estado_id')->unsigned(); // 
            $table->foreign('estado_id')->references('id')->on('referencias_tablas');
            $table->timestamps();
        });

        Schema::create('incidencias_has_files', function (Blueprint $table) {
            $table->bigIncrements('id');


            $table->integer('file_id')->unsigned();
            $table->foreign('file_id')->references('id')->on('files')
                ->onDelete('cascade')->onUpdate('cascade');

            $table->integer('type_status_id')->unsigned();
            $table->foreign('type_status_id')->references('id')->on('referencias_tablas')
                ->onDelete('cascade')->onUpdate('cascade');

            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users')
                ->onDelete('cascade')->onUpdate('cascade');
            $table->integer('incidencia_id')->unsigned();
            $table->foreign('incidencia_id')->references('id')->on('asignacion_caso')
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
        Schema::dropIfExists('incidencias');
    }
}
