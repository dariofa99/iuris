<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReposAsisDocentesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('repos_asis_docentes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('asistencia_id')->unsigned();
            $table->foreign('asistencia_id')
                ->references('id')->on('asistencia_docentes')->onDelete('cascade')
                ->onUpdate('cascade');
            $table->integer('asistencia_repo_id')->unsigned();
            $table->foreign('asistencia_repo_id')
                ->references('id')->on('asistencia_docentes')->onDelete('cascade')
                ->onUpdate('cascade');
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
        Schema::dropIfExists('repos_asis_docentes');
    }
}
