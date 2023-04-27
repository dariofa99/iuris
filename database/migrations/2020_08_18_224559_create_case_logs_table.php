<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCaseLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('case_logs', function (Blueprint $table) {
            $table->increments('id');
            $table->string('concept')->nullable();  
            $table->dateTime('notification_date')->nullable();  
            $table->longText('description')->nullable();  
            $table->boolean('shared')->default(0);   
                      
            $table->integer('type_log_id')->unsigned();
            $table->foreign('type_log_id')->references('id')->on('referencias_tablas')
            ->onDelete('cascade')->onUpdate('cascade');
/* 
            $table->bigInteger('type_category_id')->unsigned();
            $table->foreign('type_category_id')->references('id')->on('references_data')
            ->onDelete('cascade')->onUpdate('cascade'); */

            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users')
            ->onDelete('cascade')->onUpdate('cascade');

            $table->integer('exp_id')->unsigned();
            $table->foreign('exp_id')->references('id')->on('expedientes')
            ->onDelete('cascade')->onUpdate('cascade');   
            
            $table->integer('type_status_id')->unsigned();
            $table->foreign('type_status_id')->references('id')->on('referencias_tablas')
            ->onDelete('cascade')->onUpdate('cascade');
            $table->timestamps();
        });

        Schema::create('caselog_has_files', function (Blueprint $table) {
            $table->increments('id');
           
            $table->integer('caselog_id')->unsigned();
            $table->foreign('caselog_id')->references('id')->on('case_logs')
            ->onDelete('cascade')->onUpdate('cascade');

            $table->integer('file_id')->unsigned();
            $table->foreign('file_id')->references('id')->on('files')
            ->onDelete('cascade')->onUpdate('cascade');

            $table->integer('type_status_id')->unsigned();
            $table->foreign('type_status_id')->references('id')->on('referencias_tablas')
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
        Schema::dropIfExists('case_logs');
    }
}
