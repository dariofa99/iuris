<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateConcUsersHasReplegalTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('conc_users_has_replegal', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('user_convocado_id')->unsigned(); // 
            $table->foreign('user_convocado_id')->references('id')->on('users')
            ->onDelete('cascade')
            ->onUpdate('cascade');; //Tipo de asistencia: asistencia, permiso, reposicion 
            
            $table->integer('user_replegal_id')->unsigned(); // 
            $table->foreign('user_replegal_id')->references('id')->on('users')
            ->onDelete('cascade')
            ->onUpdate('cascade');; //Tipo de asistencia: asistencia, permiso, reposicion 
            
            $table->integer('conciliacion_id')->unsigned();
            $table->foreign('conciliacion_id')
            ->references('id')->on('conciliaciones')->onDelete('cascade')
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
        Schema::dropIfExists('conc_users_has_replegal');
    }
}
