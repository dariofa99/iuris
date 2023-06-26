<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateConcEstReportGeneradoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('conc_est_report_generado', function (Blueprint $table) {
            $table->bigIncrements('id');

          
            $table->dateTime('fecha_exp_token')->nullable();

            $table->integer('conciliacion_id')->unsigned();
            $table->foreign('conciliacion_id')->references('id')->on('conciliaciones')
            ->onDelete('cascade')->onUpdate('cascade');
            
            $table->integer('status_id')->unsigned();
            $table->foreign('status_id')->references('id')->on('referencias_tablas')
            ->onDelete('cascade')->onUpdate('cascade'); 
            
            
            $table->bigInteger('reporte_id')->unsigned();
            $table->foreign('reporte_id')->references('id')->on('pdf_reportes')
            ->onDelete('cascade')->onUpdate('cascade');

          /*   $table->integer('category_id')->unsigned();
            $table->foreign('category_id')->references('id')->on('referencias_tablas')
            ->onDelete('cascade')->onUpdate('cascade');  */

           /*  $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users')
            ->onUpdate('cascade')->onDelete('cascade'); */

            

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
        Schema::dropIfExists('conc_est_report_generado');
    }
}
