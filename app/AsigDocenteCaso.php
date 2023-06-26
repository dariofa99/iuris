<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AsigDocenteCaso extends Model
{
  protected $table = 'asignacion_docente_caso';
	protected $fillable = [
		'docidnumber',
		'cambio_docidnumber',
		'asig_caso_id',
		'user_updated_id',
		'user_created_id'
	];
				
	public function docente()
    {
        return $this->hasOne(User::class, 'idnumber', 'docidnumber');
	}
		public function asignacion_estudiante()
    {
        return $this->belongsTo(AsignacionCaso::class,'asig_caso_id');
	}
	
                

}
