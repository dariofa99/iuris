<?php

namespace App;


use Illuminate\Database\Eloquent\Model;

class AdminEncuestas extends Model
{
   
    protected $table = 'admin_encuestas_general';
    protected $fillable = [
    'nombre',
    'codigo',
    'version',
    "activo",
    'fecha_vigencia',  
    'categoria_id' 
];


public function preguntas()
{
    return $this->belongsToMany(ReferencesData::class, 'encuestas_preguntas', 'encuesta_id', 'pregunta_id')
        ->withPivot('id', 'orden')
        ->withTimestamps();
}


}
