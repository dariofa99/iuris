<?php

namespace App;

use App\Traits\RefDataManage;
use Illuminate\Database\Eloquent\Model;

class ConcEncuestaSatisf extends Model
{
    use RefDataManage;
    protected $table = 'conc_encuesta_satisf';
    protected $fillable = [
    'tipo_usuario_id',
    'conciliacion_id',
    'user_id',   
];


public function aditional_data()
{
    return $this->hasMany(ConcEncSatifAditionalData::class, 'enc_satisf_id', 'id');
}

}
