<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReferencesStaticTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('references_static_table', function (Blueprint $table) {
            $table->increments('id');
            $table->string('display_name');
            $table->string('name');
            $table->string('categories')->nullable();   
            $table->string('section')->nullable();  
            $table->boolean('is_visible')->default(1);   
            $table->string('table');
            $table->integer('type_data_id')->unsigned();
            $table->foreign('type_data_id')->references('id')->on('referencias_tablas')
            ->onDelete('cascade')->onUpdate('cascade');
            $table->timestamps();   
        });   
    
        Schema::create('references_static_data_options', function (Blueprint $table) {
            $table->increments('id');
            $table->string('value')->nullable();   
            $table->boolean('status')->default(1);     
            $table->boolean('active_other_input')->default(0);                           
            $table->integer('references_static_data_id')->unsigned();
            $table->foreign('references_static_data_id')->references('id')->on('references_static_table')
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
        Schema::dropIfExists('references_static_table');
    }
}
