<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePrinAtencionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('prin_atencions', function (Blueprint $table) {
            $table->increments('id');
            $table->string('atcregistro_id',12);
            $table->string('atctipodoc_id', 5);
            $table->string('atcnumeroid', 12);
            $table->string('atcnombre', 50);
            $table->string('atcapellido', 50);
            $table->string('atcnombrecomp', 100);
            $table->string('atcdirec', 50);
            $table->string('atctipolugreside_id', 5);
            $table->string('atctelmovil', 12);
            $table->string('atcemail', 60);
            $table->date('atcfecnaci');
            $table->string('atcgenero_id', 5);
            $table->string('atcetnia_id', 5);
            $table->string('atcetniacual', 20);
            $table->string('atcocupa_id', 5);
            $table->string('atcocupacual', 20);
            $table->string('atcescolaridad_id', 5);
            $table->string('atcssocial_id', 5);
            $table->string('atcestadocivil_id', 80);
            $table->string('atcdiscapa_si', 2);
            $table->string('atcdiscapacual', 20);
            $table->date('atcfecultaseso');
            $table->date('atcfecproxaseso');
            $table->string('atcobservagerales', 500);
            $table->string('atcusercreated', 12);
            $table->string('atcuserupdated', 12);
            $table->date('atcfecha');
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
        Schema::dropIfExists('prin_atencions');
    }
}
