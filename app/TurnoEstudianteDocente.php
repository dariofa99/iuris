<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TurnoEstudianteDocente extends Model
{
    protected $table = 'turnos_estudiantes_docentes';
    //protected $primaryKey = 'trndid';
    //public $timestamps = false;

   protected $fillable = [
        'docente_id',
        'estudiante_id',
        'fecha',
        'hora_inicio',
        'hora_fin',
        'estado_id',
        'motivo',
    ];

    public function estudiante()
    {
        return $this->belongsTo(User::class, 'estudiante_id', 'id');
    }

    public function estado()
    {
        return $this->belongsTo(TablaReferencia::class, 'estado_id', 'id');
    }
}