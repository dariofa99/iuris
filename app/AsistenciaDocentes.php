<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AsistenciaDocentes extends Model
{
  /**
   * The database table used by the model.
   *
   * @var string
   */
  protected $table = 'asistencia_docentes';

  /** 
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
    'docidnumber',
    'tipo_asis',
    'reposicion',
    'inicio',
    'fin',
    'descripcion',
    'categoria',
    'turno_docente_id',
    'minutos_reponer',
  ];

   public function reposiciones()
   {
      return $this->belongsToMany(AsistenciaDocentes::class, 'repos_asis_docentes', 'asistencia_id', 'asistencia_repo_id')
         ->withPivot('asistencia_id', 'asistencia_repo_id', 'id')->withTimestamps();
   }
   public function tipoAsistencia()
   {
      return $this->belongsTo(TablaReferencia::class, 'tipo_asis');
   }
}
