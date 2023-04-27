<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AsistenciaDocentes extends Model
{
       /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'asistencia_docentes';

    /** 
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'docidnumber',
        'tipo_asis',
        'reposicion',
        'inicio',
        'fin',
        'descripcion',
						];
}
