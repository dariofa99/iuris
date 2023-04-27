<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCptonotasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cptonotas', function (Blueprint $table) {
            $table->increments('id');
            $table->string('cpntnombre',100)->nullable();
            $table->string('cpntusercreated',12)->nullable();
            $table->string('cpntuserupdated',12)->nullable(); 
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
        Schema::dropIfExists('cptonotas');
    }
}
