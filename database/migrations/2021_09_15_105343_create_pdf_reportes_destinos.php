<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePdfReportesDestinos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pdf_reportes_destinos', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('tabla_destino');  
            $table->bigInteger('reporte_id')->unsigned();
            $table->foreign('reporte_id')->references('id')->on('pdf_reportes')->onDelete('cascade')
            ->onUpdate('cascade');
            $table->integer('status_id')->unsigned();
            $table->foreign('status_id')->references('id')->on('referencias_tablas')
            ->onDelete('cascade')->onUpdate('cascade');
            $table->string('categoria')->nullable(); 
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
        Schema::dropIfExists('pdf_reportes_destinos');
    }
}
