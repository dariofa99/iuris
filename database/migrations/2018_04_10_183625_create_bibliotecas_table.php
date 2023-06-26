<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBibliotecasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bibliotecas', function (Blueprint $table) {
            $table->increments('id');
            $table->string('biblinombre', 120);
            $table->mediumText('biblidescrip');

            $table->integer('bibliid_ramaderecho')->unsigned();//ramadelderecho
            $table->foreign('bibliid_ramaderecho')->references('id')->on('rama_derecho');

            $table->integer('bibliid_tipoarchivo')->unsigned();//tipo archivo formato, ley, juridiprudencia
            $table->foreign('bibliid_tipoarchivo')->references('id')->on('tipo_archivo'); 

            $table->string('biblidocnompropio',200);
            $table->string('biblidocnomgen',250);
            $table->string('biblidoctamano',60);
            $table->string('biblidocruta',500);
            $table->string('bibliestado', 2)->default('1');
            $table->string('bibliusercreated',12)->nullable();
            $table->string('bibliuserupdated',12)->nullable();
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
        Schema::dropIfExists('bibliotecas');
    }
}
