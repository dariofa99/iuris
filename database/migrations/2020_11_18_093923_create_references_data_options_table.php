<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReferencesDataOptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('references_data_options', function (Blueprint $table) {
            $table->increments('id');
            $table->string('value')->nullable();   
            $table->boolean('status')->default(1);     
            $table->boolean('active_other_input')->default(0);                           
            $table->integer('references_data_id')->unsigned();
            $table->foreign('references_data_id')->references('id')->on('references_data')
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
        Schema::dropIfExists('references_data_options');
    }
}
