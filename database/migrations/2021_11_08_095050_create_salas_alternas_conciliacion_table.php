<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSalasAlternasConciliacionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('conciliacion_salas_alternas', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('id_conciliacion')->unsigned();
            $table->foreign('id_conciliacion')->references('id')->on('conciliaciones')
            ->onDelete('cascade')->onUpdate('cascade');
            $table->string('idnumber',12);
            $table->foreign('idnumber')->references('idnumber')->on('users')
            ->onDelete('cascade')->onUpdate('cascade');
            $table->date('fecha');
            $table->string('access');
            $table->string('token_access');
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
        Schema::dropIfExists('salas_alternas_conciliacion');
    }
}
