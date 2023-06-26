<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RefAsignacionCaso extends Model
{
     protected $table = 'ref_asignacion';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
    		'nombre_asig'
    ];
}
