<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAsignacionDocenteCasoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('asignacion_docente_caso', function (Blueprint $table) {
            $table->increments('id');
            $table->boolean('activo')->default(1);  
            $table->integer('cambio_docidnumber')->nullable();  
            $table->string('docidnumber',12);
            $table->foreign('docidnumber')->references('idnumber')->on('users');
            $table->integer('asig_caso_id')->unsigned();
            $table->foreign('asig_caso_id')->references('id')->on('asignacion_caso');
            $table->integer('user_created_id');
            $table->integer('user_updated_id');
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
        Schema::dropIfExists('asignacion_docente_caso');
    }
}
