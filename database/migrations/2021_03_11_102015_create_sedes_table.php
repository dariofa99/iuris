<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSedesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sedes', function (Blueprint $table) {
            $table->increments('id_sede');
            $table->string('nombre');  
            $table->string('ubicacion')->nullable();
            $table->longText('horario_atencion')->nullable();
            $table->timestamps();
        });

        Schema::create('sede_usuarios', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users')
            ->onUpdate('cascade')->onDelete('cascade');
            $table->integer('sede_id')->unsigned();
            $table->foreign('sede_id')->references('id_sede')->on('sedes')
            ->onUpdate('cascade')->onDelete('cascade');
            $table->timestamps();
        });

        Schema::create('sede_expedientes', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('expediente_id')->unsigned();
            $table->foreign('expediente_id')->references('id')->on('expedientes')
            ->onUpdate('cascade')->onDelete('cascade');
            $table->integer('sede_id')->unsigned();
            $table->foreign('sede_id')->references('id_sede')->on('sedes')
            ->onUpdate('cascade')->onDelete('cascade');
            $table->timestamps();
        });

        Schema::create('sede_solicitudes', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('solicitud_id')->unsigned();
            $table->foreign('solicitud_id')->references('id')->on('solicitudes')
            ->onUpdate('cascade')->onDelete('cascade');
            $table->integer('sede_id')->unsigned();
            $table->foreign('sede_id')->references('id_sede')->on('sedes')
            ->onUpdate('cascade')->onDelete('cascade');
            $table->timestamps();
        });

        Schema::create('sede_autorizaciones', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('autorizacion_id')->unsigned();
            $table->foreign('autorizacion_id')->references('id')->on('autorizaciones')
            ->onUpdate('cascade')->onDelete('cascade');
            $table->integer('sede_id')->unsigned();
            $table->foreign('sede_id')->references('id_sede')->on('sedes')
            ->onUpdate('cascade')->onDelete('cascade');
            $table->timestamps();
        });

        Schema::create('sede_conciliaciones', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('conciliacion_id')->unsigned();
            $table->foreign('conciliacion_id')->references('id')->on('conciliaciones')
            ->onUpdate('cascade')->onDelete('cascade');
            $table->integer('sede_id')->unsigned();
            $table->foreign('sede_id')->references('id_sede')->on('sedes')
            ->onUpdate('cascade')->onDelete('cascade');
            $table->timestamps();
        });

        Schema::create('sede_oficinas', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('oficina_id')->unsigned();
            $table->foreign('oficina_id')->references('id')->on('oficinas')
            ->onUpdate('cascade')->onDelete('cascade');
            $table->integer('sede_id')->unsigned();
            $table->foreign('sede_id')->references('id_sede')->on('sedes')
            ->onUpdate('cascade')->onDelete('cascade');
            $table->timestamps();
        });

        Schema::create('sede_segmentos', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('segmento_id')->unsigned();
            $table->foreign('segmento_id')->references('id')->on('segmentos')
            ->onUpdate('cascade')->onDelete('cascade');
            $table->integer('sede_id')->unsigned();
            $table->foreign('sede_id')->references('id_sede')->on('sedes')
            ->onUpdate('cascade')->onDelete('cascade');
            $table->timestamps();
        });

        Schema::create('sede_periodos', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('periodo_id')->unsigned();
            $table->foreign('periodo_id')->references('id')->on('periodo')
            ->onUpdate('cascade')->onDelete('cascade');
            $table->integer('sede_id')->unsigned();
            $table->foreign('sede_id')->references('id_sede')->on('sedes')
            ->onUpdate('cascade')->onDelete('cascade');
            $table->timestamps();
        });

        Schema::create('sede_bibliotecas', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('biblioteca_id')->unsigned();
            $table->foreign('biblioteca_id')->references('id')->on('bibliotecas')
            ->onUpdate('cascade')->onDelete('cascade');
            $table->integer('sede_id')->unsigned();
            $table->foreign('sede_id')->references('id_sede')->on('sedes')
            ->onUpdate('cascade')->onDelete('cascade');
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
        Schema::dropIfExists('sedes');
    }
}
