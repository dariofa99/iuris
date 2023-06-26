<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CitacionEstudiantes extends Model
{
    protected $table = 'citaciones_estudiante';

     protected $fillable = [
        'motivo',
        'fecha',
        'fecha_corta',
        'hora',
        'docente_fullname',
        'docidnumber',
        'user_created_id',
        'user_updated_id',
        'asignacion_caso_id',
    ];

    public function asignacion()
    {
        return $this->belongsTo(AsignacionCaso::class, 'asignacion_caso_id', 'id');
    }
}
