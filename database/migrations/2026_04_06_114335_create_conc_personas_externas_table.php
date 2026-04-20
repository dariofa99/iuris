<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateConcPersonasExternasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('conc_personas_externas', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->date('fecha_registro');
            /* $table->string('token');
            $table->integer('tipo_usuario_id')->unsigned(); // 
            $table->foreign('tipo_usuario_id')
                ->references('id')->on('referencias_tablas'); */
            $table->integer('conciliacion_id')->unsigned();
            $table->foreign('conciliacion_id')
                ->references('id')->on('conciliaciones')->onDelete('cascade')
                ->onUpdate('cascade');
            /* $table->integer('periodo_id')->unsigned();
            $table->foreign('periodo_id')->references('id')->on('periodo')
                ->onDelete('cascade')->onUpdate('cascade'); */
                
            $table->integer('persona_externa_id')->unsigned();
            $table->foreign('persona_externa_id')
                ->references('id')->on('personas_externas')
                ->onDelete('cascade')->onUpdate('cascade');


            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')
                ->references('id')->on('users')->onDelete('cascade')
                ->onUpdate('cascade');
            $table->timestamps();
        });

        Schema::create('concpersext_aditional_data', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('value')->nullable();  
            $table->string('value_is_other')->nullable();                             
            $table->integer('reference_data_id')->unsigned();
            $table->foreign('reference_data_id')->references('id')->on('references_data')
            ->onDelete('cascade')->onUpdate('cascade');         
            $table->integer('reference_data_option_id')->unsigned(); 
            $table->foreign('reference_data_option_id')->references('id') 
            ->on('references_data_options')->onDelete('cascade')->onUpdate('cascade');
            $table->bigInteger('concpersext_id')->unsigned();
            $table->foreign('concpersext_id')
            ->references('id')->on('conc_personas_externas')
            ->onDelete('cascade')
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
        Schema::dropIfExists('conc_personas_externas');
        Schema::dropIfExists('concpersext_aditional_data');
    }
}
