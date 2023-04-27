<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateActuacionesHasFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('actuaciones_has_files', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('actuacion_id')->unsigned();
            $table->foreign('actuacion_id')->references('id')->on('actuacions')
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
        Schema::dropIfExists('actuaciones_has_files');
    }
}
