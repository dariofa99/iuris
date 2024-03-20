<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateConcencsatAditionalDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('concencsat_aditional_data', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('value')->nullable();  
            $table->string('value_is_other')->nullable();                             
            $table->integer('reference_data_id')->unsigned();
            $table->foreign('reference_data_id')->references('id')->on('references_data')
            ->onDelete('cascade')->onUpdate('cascade');         
            $table->integer('reference_data_option_id')->unsigned(); 
            $table->foreign('reference_data_option_id')->references('id') 
            ->on('references_data_options')->onDelete('cascade')->onUpdate('cascade');
            $table->bigInteger('enc_satisf_id')->unsigned();
            $table->foreign('enc_satisf_id')
            ->references('id')->on('conc_encuesta_satisf')
            ->onDelete('cascade')
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
        Schema::dropIfExists('concencsat_aditional_data');
    }
}
