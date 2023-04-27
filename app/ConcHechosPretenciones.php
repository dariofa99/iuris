<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ConcHechosPretenciones extends Model
{
    protected $table = 'conc_hechos_preten';

    protected $fillable = [
        'descripcion',
        'conciliacion_id',
        'tipo_id',   
        'estado_id',
        'user_id'   
    ];

    public function conciliacion()
    {
        return $this->belongsTo(Conciliacion::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
