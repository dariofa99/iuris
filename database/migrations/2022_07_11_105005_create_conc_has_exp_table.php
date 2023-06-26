<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateConcHasExpTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('conc_has_exp', function (Blueprint $table) {
            $table->increments('id');
            $table->string('concepto')->nullable();
            $table->integer('conciliacion_id')->unsigned();
            $table->foreign('conciliacion_id')->references('id')->on('conciliaciones')
            ->onDelete('cascade')->onUpdate('cascade');
            $table->integer('exp_id')->unsigned();
            $table->foreign('exp_id')->references('id')->on('expedientes')
            ->onDelete('cascade')->onUpdate('cascade');
            $table->integer('type_status_id')->unsigned();
            $table->foreign('type_status_id')->references('id')->on('referencias_tablas')
            ->onDelete('cascade')->onUpdate('cascade');   
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users')
            ->onDelete('cascade')->onUpdate('cascade');       
            $table->integer('actuacion_id')->unsigned();
            $table->foreign('actuacion_id')->references('id')->on('actuacions')
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
        Schema::dropIfExists('conc_has_exp');
    }
}
