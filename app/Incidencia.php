<?php

namespace App;


use Illuminate\Database\Eloquent\Model;

class Incidencia extends Model
{
    
    protected $table = 'incidencias';
    protected $fillable = [
        'motivo',
        'user_id',
        'categoria_id',
        'estado_id',
        //'asig_caso_id'
    ];


    // Relaciones
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function categoria()
    {
        return $this->belongsTo(TablaReferencia::class, 'categoria_id');
    }
    public function estado()
    {
        return $this->belongsTo(TablaReferencia::class, 'estado_id');
    }

    public function asignacion()
    {
        return $this->hasOne(AsignacionCaso::class, 'asig_caso_id', 'id')
            ->where('asigest_id', $this->user->idnumber)
            ->where('activo', 1);
    }

    public function estados()
    {
        return $this->hasMany(IncidenciaEstado::class);
    }

     public function asignaciones()
    {
   
        return $this->belongsToMany(AsignacionCaso::class, 'incidencias_asignacion', 'incidencia_id', 'asig_caso_id')
            ->withPivot('incidencia_id', 'asig_caso_id', 'id')
            ->withTimestamps();
    
    }
}
