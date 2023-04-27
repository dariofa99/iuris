<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RamaDerecho extends Model
{
     protected $table = 'rama_derecho';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
    	'ramadernombre'
    ];
}
