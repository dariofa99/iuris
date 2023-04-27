<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MotivoAsigCaso extends Model
{
     protected $table = 'ref_mot_asig_caso';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
    		'nom_motivo'
    ];
}
