<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TablaReferencia extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'referencias_tablas';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
						    'ref_nombre',
                            'ref_value',
						    'tabla_ref',
                            'categoria',
						  ];



                           

}
 