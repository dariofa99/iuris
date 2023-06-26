<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateConcReportDescargadoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('conc_report_descargado', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->longText('data')->nullable();
            $table->bigInteger('conc_report_comp_id')->unsigned();
            $table->foreign('conc_report_comp_id')->references('id')->on('conc_report_compartido')
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
        Schema::dropIfExists('conc_report_descargado');
    }
}
