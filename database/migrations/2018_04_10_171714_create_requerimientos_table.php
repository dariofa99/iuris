<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRequerimientosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('requerimientos', function (Blueprint $table) {
            $table->increments('id');
            // eliminado $table->string('reqid',12);//identificador del requerimiento

            $table->string('reqexpid',30);//foranea
            $table->foreign('reqexpid')->references('expid')->on('expedientes'); //

            $table->string('reqidsolicitan',12);//solicitante
             $table->foreign('reqidsolicitan')->references('idnumber')->on('users'); //identificación 

            $table->string('reqidest',12);//estudiante
            $table->foreign('reqidest')->references('idnumber')->on('users'); //

            // eliminado $table->string('reqidmotivo', 2 )->nullable();
            
            $table->string('reqmotivo', 100)->default('.');

            $table->string('reqdescrip', 700)->default('.');
            $table->date('reqfecha')->nullable();

            $table->string('reqhora')->nullable();//nuevo campo
            $table->string('reqid_asistencia', 2)->nullable();//nuevo campo
            $table->text('reqcomentario_est')->nullable();//nuevo campo
            $table->text('reqcomentario_coorprac')->nullable();//nuevo campo
            $table->boolean('reqentregado')->nullable()->default(0);//nuevo campo

            $table->boolean('evaluado')->default(0);//fecha hora compromiso

            $table->string('notas', 225)->nullable()->default('');
           // $table->string('reqestado', 2)->default('0');
            $table->string('requsercreated',12)->nullable();
            $table->string('requserupdated',12)->nullable();
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
        Schema::dropIfExists('requerimientos');
    }
}
