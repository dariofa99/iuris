<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateConcReportCompartidoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('conc_report_compartido', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->string('token',100)->nullable();
            $table->string('clave',100)->nullable();
            $table->dateTime('fecha_exp_token')->nullable();

            $table->integer('conciliacion_id')->unsigned();
            $table->foreign('conciliacion_id')->references('id')->on('conciliaciones')
            ->onDelete('cascade')->onUpdate('cascade');
            
            $table->integer('status_id')->unsigned();
            $table->foreign('status_id')->references('id')->on('referencias_tablas')
            ->onDelete('cascade')->onUpdate('cascade'); 
            

            $table->integer('means_id')->unsigned();
            $table->foreign('means_id')->references('id')->on('referencias_tablas')
            ->onDelete('cascade')->onUpdate('cascade'); 

            $table->integer('category_id')->unsigned();
            $table->foreign('category_id')->references('id')->on('referencias_tablas')
            ->onDelete('cascade')->onUpdate('cascade'); 


            $table->timestamps();
        });

        Schema::create('conc_report_comp_files', function (Blueprint $table) {
            $table->increments('id');           
           
            $table->integer('file_id')->unsigned();
            $table->foreign('file_id')->references('id')->on('files')
            ->onDelete('cascade')->onUpdate('cascade');

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
        Schema::dropIfExists('conc_report_compartido');
    }
}
