<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cptonota extends Model
{
    protected $table = 'cptonotas';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
    	'cpntnombre',
    	'cpntusercreated',
    	'cpntuserupdated'

    ];

}
