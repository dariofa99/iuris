<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Estado extends Model
{
    protected $table = 'ref_estados';

    protected $fillable = [
    	'nombre_estado'
    ];
}
