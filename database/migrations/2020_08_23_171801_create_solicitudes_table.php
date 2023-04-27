<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSolicitudesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('solicitudes', function (Blueprint $table) {
            $table->increments('id');
            $table->string('number')->nullable(); 
            $table->string('idnumber')->nullable();    
       
            $table->string('name')->nullable();  
            $table->string('lastname')->nullable(); 
            $table->string('tel1')->nullable();  
            $table->string('token')->nullable();  
            $table->longText('description')->nullable();  
            $table->integer('turno')->default(1);
            $table->string('mensaje')->nullable();  
            $table->dateTime('tiempo_espera')->nullable();  
            $table->integer('type_status_id')->unsigned();
            $table->foreign('type_status_id')->references('id')->on('referencias_tablas')
            ->onDelete('cascade')->onUpdate('cascade');

            $table->integer('type_category_id')->unsigned();
            $table->foreign('type_category_id')->references('id')->on('referencias_tablas')
            ->onDelete('cascade')->onUpdate('cascade');
            
            $table->integer('estrato_id')->unsigned();
            $table->foreign('estrato_id')->references('id')->on('referencias_tablas');

            $table->integer('tipodoc_id')->unsigned();
            $table->foreign('tipodoc_id')->references('id')->on('referencias_tablas'); //identificación 


            $table->timestamps();
        });

        Schema::create('solicitud_has_exp', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('solicitud_id')->unsigned();
            $table->foreign('solicitud_id')->references('id')->on('solicitudes')
            ->onDelete('cascade')->onUpdate('cascade');
            $table->integer('exp_id')->unsigned();
            $table->foreign('exp_id')->references('id')->on('expedientes')
            ->onDelete('cascade')->onUpdate('cascade');
            $table->timestamps();
        });

        Schema::create('solicitud_has_files', function (Blueprint $table) {
            $table->increments('id');
            $table->string('concept')->nullable();  
            $table->integer('solicitud_id')->unsigned();
            $table->foreign('solicitud_id')->references('id')->on('solicitudes')
            ->onDelete('cascade')->onUpdate('cascade');
            $table->integer('file_id')->unsigned();
            $table->foreign('file_id')->references('id')->on('files')
            ->onDelete('cascade')->onUpdate('cascade');

            $table->integer('type_status_id')->unsigned();
            $table->foreign('type_status_id')->references('id')->on('referencias_tablas')
            ->onDelete('cascade')->onUpdate('cascade');

            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users')
            ->onDelete('cascade')->onUpdate('cascade');

            $table->integer('type_category_id')->unsigned();
            $table->foreign('type_category_id')->references('id')->on('referencias_tablas')
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
        Schema::dropIfExists('solicitudes');
    }
}
