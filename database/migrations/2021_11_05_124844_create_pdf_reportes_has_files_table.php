<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePdfReportesHasFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pdf_reportes_has_files', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('seccion');
            $table->longText('configuracion');
            $table->bigInteger('pdf_reporte_id')->unsigned();
            $table->foreign('pdf_reporte_id')->references('id')->on('pdf_reportes')->onDelete('cascade')
            ->onUpdate('cascade');
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
        Schema::dropIfExists('pdf_reportes_has_files');
    }
}
