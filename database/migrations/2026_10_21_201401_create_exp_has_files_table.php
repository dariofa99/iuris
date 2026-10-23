<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateExpHasFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('exp_has_files', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->integer('asig_caso_id')->unsigned();
            $table->foreign('asig_caso_id')->references('id')->on('asignacion_caso')
                ->onDelete('cascade')->onUpdate('cascade');

            $table->string('expidnumber', 30);
            $table->foreign('expidnumber')->references('expid')->on('expedientes');

            $table->integer('file_id')->unsigned();
            $table->foreign('file_id')->references('id')->on('files')
                ->onDelete('cascade')->onUpdate('cascade');

            $table->integer('type_status_id')->unsigned();
            $table->foreign('type_status_id')->references('id')->on('referencias_tablas')
                ->onDelete('cascade')->onUpdate('cascade');

            $table->integer('category_id')->unsigned();
            $table->foreign('category_id')->references('id')->on('referencias_tablas')
                ->onDelete('cascade')->onUpdate('cascade');

            $table->string('useridnumber', 12);
            $table->foreign('useridnumber')->references('idnumber')->on('users');

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
        Schema::dropIfExists('exp_has_files');
    }
}
