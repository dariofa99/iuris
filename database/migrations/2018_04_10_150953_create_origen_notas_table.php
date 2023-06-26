<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrigenNotasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('origen_notas', function (Blueprint $table) {
            $table->increments('id');
             $table->string('orgntsnombre',100)->nullable();
            $table->string('orgntsusercreated',12)->nullable();
            $table->string('orgntsuserupdated',12)->nullable();
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
        Schema::dropIfExists('origen_notas');
    }
}
