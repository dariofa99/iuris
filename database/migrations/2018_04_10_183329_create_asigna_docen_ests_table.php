<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAsignaDocenEstsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('asigna_docent_ests', function (Blueprint $table) {
            $table->increments('id');
            $table->string('asgedidnumberest',12);
            $table->foreign('asgedidnumberest')->references('idnumber')->on('users'); 
            $table->string('asgedidnumberdocen',12);
            $table->foreign('asgedidnumberdocen')->references('idnumber')->on('users');
             
            $table->integer('asgedidperiodo')->unsigned();
            $table->foreign('asgedidperiodo')->references('id')->on('periodo');
            $table->boolean('confirmado')->default(0);
            /*$table->integer('asgedidcursando')->unsigned();
            $table->foreign('asgedidcursando')->references('id')->on('referencias_tablas'); 
*/
            $table->string('asgedusercreated',12)->nullable();
            $table->string('asgeduserupdated',12)->nullable();  
            
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
        Schema::dropIfExists('asigna_docent_ests');
    }
}
