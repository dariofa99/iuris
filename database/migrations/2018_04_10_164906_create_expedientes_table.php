<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateExpedientesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('expedientes', function (Blueprint $table) {
            $table->increments('id');
             $table->string('expid',30)->unique();
            $table->date('expfecha');

            $table->integer('expramaderecho_id')->unsigned();
            $table->foreign('expramaderecho_id')->references('id')->on('rama_derecho'); //identificación 

            //Este campo debe ser foraneo de la tabla ref_estados
            $table->integer('expestado_id')->unsigned();
            $table->foreign('expestado_id')->references('id')->on('ref_estados'); //identificación usuario
            

            $table->string('expidnumber',12);
            $table->foreign('expidnumber')->references('idnumber')->on('users'); //identificación usuario
            

            $table->integer('exptipoproce_id')->unsigned(); //Tipo Procedimiento
            $table->foreign('exptipoproce_id')->references('id')->on('ref_tipproceso'); //identificación 

            $table->integer('exptipocaso_id')->unsigned(); // 
            $table->foreign('exptipocaso_id')->references('id')->on('referencias_tablas'); //identificación 


            $table->string('expdesccorta',60)->nullable(); //

            $table->string('expidnumberest',12);
            $table->foreign('expidnumberest')->references('idnumber')->on('users'); //identificación 
            

            $table->integer('expdepto_id')->unsigned(); // 
            $table->foreign('expdepto_id')->references('id')->on('referencias_tablas'); //identificación 

            $table->integer('expmunicipio_id')->unsigned(); // 
            $table->foreign('expmunicipio_id')->references('id')->on('referencias_tablas'); //identificación 

            $table->integer('exptipovivien_id')->unsigned(); // 
            $table->foreign('exptipovivien_id')->references('id')->on('referencias_tablas'); //identificación 




            $table->string('expperacargo',2)->default('.'); // 
            $table->integer('expingremensual')->default('0'); // 
            $table->integer('expegremensual')->default('0'); //
            $table->longText('exphechos')->nullable(); //  
            $table->longText('exprtaest')->nullable(); // 

            $table->string('expjuzoent',60)->nullable(); // 
            $table->string('expnumproc',60)->nullable(); //
            $table->string('exppersondemandante',60)->nullable(); //
            $table->string('exppersondemandada',60)->nullable(); //
            //eliminados $table->string('exptipoactuacion',2)->default('0'); //

             $table->date('expfechalimite')->nullable();
             //eliminados $table->string('expcierrecasocpto',2)->default('0');
            $table->string('expcierrecasonotaest',500)->nullable();
            $table->string('expcierrecasonotadocen', 500)->nullable();
            $table->string('expidnumberdocen', 12)->nullable();
            $table->date('expfecha_res')->nullable();


            $table->string('expusercreated',12)->nullable();
            $table->string('expuserupdated',12)->nullable();
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
        Schema::dropIfExists('expedientes');
    }
}
