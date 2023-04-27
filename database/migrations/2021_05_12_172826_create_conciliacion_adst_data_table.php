<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateConciliacionAdstDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('conciliacion_adst_data', function (Blueprint $table) {
            $table->increments('id');
            $table->string('value')->nullable();  
            $table->string('value_is_other')->nullable();                             
            $table->integer('reference_data_id')->unsigned();
            $table->foreign('reference_data_id')->references('id')->on('references_static_table')
            ->onDelete('cascade')->onUpdate('cascade');         
            $table->integer('reference_data_option_id')->unsigned(); 
            $table->foreign('reference_data_option_id')->references('id') 
            ->on('references_static_data_options')->onDelete('cascade')->onUpdate('cascade');
            $table->integer('conciliacion_id')->unsigned();
            $table->foreign('conciliacion_id')->references('id')->on('conciliaciones')->onDelete('cascade')
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
        Schema::dropIfExists('conciliacion_adst_data');
    }
}
