<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRevisionesActuacionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('revisiones_actuacion', function (Blueprint $table) {
            $table->increments('id');
            $table->string('rev_actexpid',30);
            $table->foreign('rev_actexpid')->references('expid')->on('expedientes'); //expid 
            
            //Actuacion parent
            $table->integer('parent_rev_actid')->unsigned();
            $table->foreign('parent_rev_actid')->references('id')->on('actuacions') ->onDelete('cascade');;
            //Actuacion child
            $table->integer('rev_actid')->unsigned();
            $table->foreign('rev_actid')->references('id')->on('actuacions') ->onDelete('cascade');;
            
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
        Schema::dropIfExists('revisiones_actuacion');
    }
}
