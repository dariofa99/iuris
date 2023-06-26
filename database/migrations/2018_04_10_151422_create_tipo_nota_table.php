<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTipoNotaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tipo_nota', function (Blueprint $table) {
            $table->increments('id');
            $table->string('tpntnombre',100)->nullable();
            $table->string('tpntsusercreated',12)->nullable();
            $table->string('tpntsuserupdated',12)->nullable();
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
        Schema::dropIfExists('tipo_nota');
    }
}
