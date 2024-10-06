<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateExpedienteHasUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('expediente_has_users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('tipo_usuario_id')->unsigned(); // 
            $table->foreign('tipo_usuario_id')->references('id')->on('referencias_tablas'); 
            $table->integer('expediente_id')->unsigned(); // 
            $table->foreign('expediente_id')->references('id')->on('expedientes');
            $table->integer('user_id')->unsigned(); // 
            $table->foreign('user_id')->references('id')->on('users');
            $table->integer('estado_id')->unsigned(); // 
            $table->foreign('estado_id')->references('id')->on('referencias_tablas');
           
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
        Schema::dropIfExists('expediente_has_users');
    }
}
