<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateConciliacionPdfTemporalTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('conciliacion_pdf_temporal', function (Blueprint $table) {
            $table->bigIncrements('id');
            
            $table->integer('status_id')->unsigned();
            $table->foreign('status_id')->references('id')->on('referencias_tablas')
            ->onDelete('cascade')->onUpdate('cascade');
            $table->bigInteger('reporte_pdf_id')->unsigned();
            $table->foreign('reporte_pdf_id')->references('id')->on('pdf_reportes')->onDelete('cascade')
            ->onUpdate('cascade');
            $table->integer('conciliacion_id')->unsigned();
            $table->foreign('conciliacion_id')->references('id')->on('conciliaciones')->onDelete('cascade')
            ->onUpdate('cascade');
            $table->bigInteger('parent_reporte_pdf_id')->unsigned();
            $table->foreign('parent_reporte_pdf_id')->references('id')->on('pdf_reportes')->onDelete('cascade')
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
        Schema::dropIfExists('conciliacion_pdf_temporal');
    }
}
