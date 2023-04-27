<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateActuacionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('actuacions', function (Blueprint $table) {
            $table->increments('id');
            $table->string('actid',3)->nullable();//este será el codigo en caso de fijar conceptos

            $table->string('actexpid',30);//foranea
            $table->foreign('actexpid')->references('expid')->on('expedientes'); //

            $table->string('actidnumberest',10);//foranea
            $table->foreign('actidnumberest')->references('idnumber')->on('users'); //


            $table->string('actnombre', 60 )->default('.');
            $table->string('actdescrip', 2000)->default('.');
            $table->date('actfecha')->nullable();
            $table->date('fecha_limit')->nullable(); 
            $table->date('actdocenfechamod')->nullable();
            $table->string('actdocenrecomendac', 10000)->default('.');
            $table->string('notas', 225)->nullable();
            $table->integer('actestado_id')->unsigned();
            $table->foreign('actestado_id')->references('id')->on('referencias_tablas'); //estado 
            $table->integer('actcategoria_id')->unsigned();
            $table->foreign('actcategoria_id')->references('id')->on('referencias_tablas')
            ->onDelete('cascade')->onUpdate('cascade'); ; //estado 


             $table->string('actdocnompropio');
            $table->string('actdocnomgen');
            $table->string('actdocruta');
            //Guarda los documentos del docente, se agrego manualmente en la db::Dario
            $table->string('actdocnompropio_docente')->nullable();
            $table->string('actdocnomgen_docente')->nullable();
            $table->string('actdocruta_docente')->nullable();
            $table->string('actusercreated',12)->nullable();
            $table->string('actuserupdated',12)->nullable();


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
        Schema::dropIfExists('actuacions');
    }
}
