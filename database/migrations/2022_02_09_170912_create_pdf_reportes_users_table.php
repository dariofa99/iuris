<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePdfReportesUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pdf_reportes_users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('tipo_usuario_id')->unsigned(); // 
            $table->foreign('tipo_usuario_id')->references('id')->on('referencias_tablas'); 
            $table->integer('tipo_firma_id')->unsigned(); // 
            $table->foreign('tipo_firma_id')->references('id')->on('referencias_tablas'); //Tipo de asistencia: asistencia, permiso, reposicion 
         
            $table->integer('conciliacion_id')->unsigned(); // 
            $table->foreign('conciliacion_id')->references('id')->on('conciliaciones');
            $table->bigInteger('pdf_reporte_id')->unsigned(); // 
            $table->foreign('pdf_reporte_id')->references('id')->on('pdf_reportes_destinos');
            $table->boolean('firmado')->default(0);
            $table->boolean('revocado')->default(0);
            $table->dateTime('fecha_firma')->nullable();
            $table->string('token',100)->nullable();
            $table->string('codigo',100)->nullable();
            $table->integer('user_id')->unsigned(); // 
            $table->foreign('user_id')->references('id')->on('users'); //Tipo de asistencia: asistencia, permiso, reposicion 
           
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
        Schema::dropIfExists('pdf_reportes_users');
    }
}
