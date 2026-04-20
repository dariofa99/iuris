<?php

namespace App;

use App\Traits\RefDataManage;
use Illuminate\Database\Eloquent\Model;

class ConcPersonasExternas extends Model
{
    use RefDataManage;
    protected $table = 'conc_personas_externas';
    protected $fillable = [
        'fecha_registro',
        'conciliacion_id',
        'persona_externa_id',
        'user_id'
        ];


    public function aditional_data()
    {
        return $this->hasMany(ConcPerExtAditionalData::class, 'concpersext_id', 'id');
    }


    public function conciliacion()
    {
        return $this->belongsTo(Conciliacion::class, 'conciliacion_id', 'id');
    }

    public function persona()
    {
        return $this->belongsTo(AdminPersonas::class, 'persona_externa_id', 'id');
    }
}
