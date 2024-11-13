<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateExpEncuestaSatisfTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('exp_encuesta_satisf', function (Blueprint $table) {
            $table->bigIncrements('id');
           
            $table->date('fecha_registro'); 
            $table->string('token');           

            $table->integer('encuesta_id')->unsigned();
            $table->foreign('encuesta_id')
            ->references('id')->on('admin_encuestas_general')
            ->onDelete('cascade')->onUpdate('cascade');

            $table->integer('exp_id')->unsigned();
            $table->foreign('exp_id')
            ->references('id')->on('expedientes')
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
        Schema::dropIfExists('exp_encuesta_satisf');
    }
}
