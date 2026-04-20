<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePersonasExternasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('personas_externas', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('tipo_persona');

            $table->boolean('estado')->default(1);
            $table->integer('categoria_id')->unsigned();
            $table->foreign('categoria_id')->references('id')->on('referencias_tablas')
                ->onDelete('cascade')->onUpdate('cascade');
            $table->timestamps();
        });



        Schema::create('personas_externas_preguntas', function (Blueprint $table) {
            $table->bigIncrements('id');
            // $table->string('con_has_user_id');

            $table->integer('orden');
            $table->boolean('estado')->default(1);
            $table->integer('persona_externa_id')->unsigned();
            $table->foreign('persona_externa_id')->references('id')->on('personas_externas')
                ->onDelete('cascade')->onUpdate('cascade');
            $table->integer('pregunta_id')->unsigned();
            $table->foreign('pregunta_id')->references('id')->on('references_data')
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
        Schema::dropIfExists('personas_externas');
        Schema::dropIfExists('personas_externas_preguntas');
    }
}
