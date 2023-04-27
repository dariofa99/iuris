<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class WebService extends Model
{
     protected $connection = 'prueba';
     protected $table = 'akademico.consultoriosj';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'cod_alumno',
        'cedula',
        'matriculado_consultorios',
        'matriculado_consutoriosj'
    ]; 
}
