<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateConciliacionesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('conciliaciones', function (Blueprint $table) {
            $table->increments('id');
            $table->dateTime('fecha_radicado');                        
            $table->string('num_conciliacion');   
            $table->string('auto_admisorio')->default("0");
            $table->string('token')->nullable();          
            $table->integer('estado_id')->unsigned(); // 
            $table->foreign('estado_id')->references('id')->on('referencias_tablas'); //Tipo de asistencia: asistencia, permiso, reposicion 
            $table->integer('categoria_id')->unsigned(); // 
            $table->foreign('categoria_id')->references('id')->on('referencias_tablas'); 
            $table->integer('periodo_id')->unsigned(); // 
            $table->foreign('periodo_id')->references('id')->on('periodo'); //Tipo de asistencia: asistencia, permiso, reposicion 
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users')
            ->onUpdate('cascade')->onDelete('cascade');
            //Tipo de asistencia: asistencia, permiso, reposicion 
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
        Schema::dropIfExists('conciliaciones');
    }
}
