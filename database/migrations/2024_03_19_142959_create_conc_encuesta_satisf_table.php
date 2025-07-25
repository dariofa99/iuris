<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateConcEncuestaSatisfTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('conc_encuesta_satisf', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->date('fecha_registro');
            $table->string('token');
            $table->integer('tipo_usuario_id')->unsigned(); // 
            $table->foreign('tipo_usuario_id')
                ->references('id')->on('referencias_tablas');
            $table->integer('conciliacion_id')->unsigned();
            $table->foreign('conciliacion_id')
                ->references('id')->on('conciliaciones')->onDelete('cascade')
                ->onUpdate('cascade');
            $table->integer('periodo_id')->unsigned();
            $table->foreign('periodo_id')->references('id')->on('periodo')
                ->onDelete('cascade')->onUpdate('cascade');
            $table->integer('encuesta_id')->unsigned();
            $table->foreign('encuesta_id')
                ->references('id')->on('admin_encuestas_general')
                ->onDelete('cascade')->onUpdate('cascade');
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')
                ->references('id')->on('users')->onDelete('cascade')
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
        Schema::dropIfExists('conc_encuesta_satisf');
    }
}
