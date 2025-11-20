<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateConcComentHasFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('conc_coment_has_files', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('comentario_id')->unsigned();
            $table->foreign('comentario_id')->references('id')->on('conciliaciones_comentarios')
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
        Schema::dropIfExists('conc_coment_has_files');
    }
}
