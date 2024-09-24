<?php

namespace App;

use App\Traits\RefDataManage;
use Illuminate\Database\Eloquent\Model;

class ExpEncuestaSatisf extends Model
{
    use RefDataManage;
    protected $table = 'exp_encuesta_satisf';
    protected $fillable = [
    'fecha_registro',
    'exp_id',
    'user_id',  
    'token' 
];


public function aditional_data()
{
    return $this->hasMany(ExpEncSatifAditionalData::class, 'exp_satisf_id', 'id');
}

public function expediente()
{
    return $this->belongsTo(Expediente::class, 'exp_id', 'id');
}

}
