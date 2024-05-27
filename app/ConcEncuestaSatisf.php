<?php

namespace App;

use App\Traits\RefDataManage;
use Illuminate\Database\Eloquent\Model;

class ConcEncuestaSatisf extends Model
{
    use RefDataManage;
    protected $table = 'conc_encuesta_satisf';
    protected $fillable = [
    'fecha_registro',
    'tipo_usuario_id',
    'conciliacion_id',
    'user_id',  
    'token' 
];


public function aditional_data()
{
    return $this->hasMany(ConcEncSatifAditionalData::class, 'enc_satisf_id', 'id');
}

public function conciliacion()
{
    return $this->belongsTo(Conciliacion::class, 'conciliacion_id', 'id');
}

}
