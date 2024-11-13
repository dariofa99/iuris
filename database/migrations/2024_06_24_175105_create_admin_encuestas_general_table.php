<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdminEncuestasGeneralTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    { 
        Schema::create('admin_encuestas_general', function (Blueprint $table) {
            $table->bigIncrements('id');     
            $table->string('nombre')->nullable();          
            $table->string('codigo')->nullable();   
            $table->string('version')->nullable();     
            $table->date('fecha_vigencia')->nullable();   
            $table->boolean('activo')->default(0);                    
            $table->integer('categoria_id')->unsigned();
            $table->foreign('categoria_id')->references('id')->on('referencias_tablas')
            ->onDelete('cascade')->onUpdate('cascade');        
            
            $table->timestamps();
        });

        Schema::create('encuestas_preguntas', function (Blueprint $table) {
            $table->bigIncrements('id');     
            
            $table->integer('orden'); 

            $table->integer('pregunta_id')->unsigned();
            $table->foreign('pregunta_id')->references('id')->on('references_data')
            ->onDelete('cascade')->onUpdate('cascade');    
            $table->bigInteger('encuesta_id')->unsigned();
            $table->foreign('encuesta_id')->references('id')->on('admin_encuestas_general')
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
        Schema::dropIfExists('admin_encuestas_general');
    }
}
