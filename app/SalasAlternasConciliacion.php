<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SalasAlternasConciliacion extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'conciliacion_salas_alternas';

    /** 
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
							'id_conciliacion',
							'idnumber',
							'fecha',
							'access',
                            'token_access'
						];
    
}
