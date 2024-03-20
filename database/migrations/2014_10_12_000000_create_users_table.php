<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
             $table->increments('id');
            $table->boolean('active')->default(1);
           
            
            $table->string('idnumber', 12)->unique();
            $table->string('name', 60)->nullable();
            $table->string('lastname', 60)->nullable();
            $table->string('email', 60)->unique();
            $table->string('password', 60);
            $table->string('accesofvir', 1)->default('0');
            $table->string('description', 80)->nullable();
            $table->string('tel1', 12)->nullable();
            $table->string('tel2', 12)->nullable();
            $table->string('address', 100)->nullable();
           // $table->string('idrol', 1)->default('0');
            $table->string('codigo_estudiantil', 100)->nullable();
            $table->string('image')->default('default.jpg');;
            $table->date('datecreated')->nullable();

            $table->integer('genero_id')->unsigned();
            $table->foreign('genero_id')->references('id')->on('referencias_tablas'); //identificación  

            $table->integer('estadocivil_id')->unsigned();
            $table->foreign('estadocivil_id')->references('id')->on('referencias_tablas'); //identificación 

            $table->integer('estrato_id')->unsigned();
            $table->foreign('estrato_id')->references('id')->on('referencias_tablas'); 
            //            identificación 
            $table->integer('cursando_id')->unsigned();
            $table->foreign('cursando_id')->references('id')->on('referencias_tablas'); //
            
            $table->integer('tipodoc_id')->unsigned();
            $table->foreign('tipodoc_id')->references('id')->on('referencias_tablas'); //identificación 

            $table->integer('tipopers_id')->unsigned();
            $table->foreign('tipopers_id')->references('id')->on('referencias_tablas'); //identificación 


            $table->date('fechanacimien')->nullable();
            $table->string('pbesena',5)->nullable();            
            $table->boolean('pbepersondiscap')->default(0);
            $table->string('pbevictimconflic',5)->nullable();
            $table->string('pbeadultomayor',5)->nullable();
            $table->string('pbeminoetnica',5)->nullable();
            $table->string('pbemadrecomuni',5)->nullable();
            $table->string('pbecabezaflia',5)->nullable();
            $table->string('pbeninguna',5)->nullable();

            $table->string('usercreated', 12)->nullable();
            $table->string('userupdated', 12)->nullable();

            $table->string('confirm_token')->nullable();
            $table->boolean('active_asignacion')->default(0);

            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
