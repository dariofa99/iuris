<?php

namespace App;


use Illuminate\Database\Eloquent\Model;

class AdminPersonas extends Model
{

    protected $table = 'personas_externas';
    protected $fillable = [
        'tipo_persona',
        'estado',
        'categoria_id'

    ];


    public function preguntas()
    {
        return $this->belongsToMany(ReferencesData::class, 'personas_externas_preguntas', 'persona_externa_id', 'pregunta_id')
            ->withPivot('id', 'orden', 'estado')
            ->withTimestamps();
    }
}
