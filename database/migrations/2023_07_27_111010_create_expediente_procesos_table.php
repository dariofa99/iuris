<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateExpedienteProcesosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('expediente_procesos', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->date('fecha')->nullable();
            $table->string('hora', 20)->nullable();
            $table->string('comentario')->nullable();
            $table->integer('estado_id')->unsigned();
            $table->foreign('estado_id')->references('id')->on('referencias_tablas')
                ->onDelete('cascade')->onUpdate('cascade');          
            $table->integer('asig_caso_id')->unsigned();
            $table->foreign('asig_caso_id')->references('id')->on('asignacion_caso')
                ->onDelete('cascade')->onUpdate('cascade');
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users')
                ->onDelete('cascade')->onUpdate('cascade');
             $table->timestamps();
        });

        Schema::create('expprocesos_has_files', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('expproc_id')->unsigned();
            $table->foreign('expproc_id')->references('id')->on('expediente_procesos')
            ->onDelete('cascade')->onUpdate('cascade'); 
            $table->integer('file_id')->unsigned();
            $table->foreign('file_id')->references('id')->on('files')
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
        Schema::dropIfExists('expediente_procesos');
        Schema::dropIfExists('expprocesos_has_files');
    }
}
