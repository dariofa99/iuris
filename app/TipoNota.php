<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TipoNota extends Model
{
    protected $table = 'tipo_nota';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
    	'tpntnombre'
    ];
}
