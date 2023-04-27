<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePdfReportesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pdf_reportes', function (Blueprint $table) {
            $table->bigIncrements('id');       
            $table->string('nombre_reporte');   
            $table->longText('reporte');   
            $table->longText('report_keys');  
            $table->longText('configuraciones');  
            $table->boolean('is_copy')->default('0');  
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
        Schema::dropIfExists('pdf_reportes');
    }
}
