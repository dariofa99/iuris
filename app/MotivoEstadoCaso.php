<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MotivoEstadoCaso extends Model
{
     protected $table = 'ref_motivos_estado_caso';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
    		'nombre_motivo'
    ];
}
