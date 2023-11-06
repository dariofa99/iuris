<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateExpedientesPausaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('expedientes_pausa', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->date('fecha_inicial')->nullable();
            $table->date('fecha_final')->nullable();
            $table->integer('estado_id')->unsigned();
            $table->foreign('estado_id')->references('id')->on('referencias_tablas')
                ->onDelete('cascade')->onUpdate('cascade');          
            $table->integer('asig_caso_id')->unsigned();
            $table->foreign('asig_caso_id')->references('id')->on('asignacion_caso')
                ->onDelete('cascade')->onUpdate('cascade');
            $table->integer('userestud_id')->unsigned();
            $table->foreign('userestud_id')->references('id')->on('users')
                ->onDelete('cascade')->onUpdate('cascade');
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
        Schema::dropIfExists('expedientes_pausa');
    }
}
