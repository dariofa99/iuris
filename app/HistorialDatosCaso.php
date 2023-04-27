<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class HistorialDatosCaso extends Model
{
    //
    protected $fillable = [
        'hisdc_datos_caso',
        'hisdc_tipo_datos_caso',
        'hisdc_expidnumber',
        'hisdc_ndias',
        'hisdc_estado',
        'hisdc_idnumberest_id',
        'hisdc_authuser_id',
        'created_at',
        'updated_at'
        ];
}
