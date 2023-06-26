<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AsignacionCaso extends Model
{
       /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'asignacion_caso';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
							'anotacion',
							'asigest_id',
							'asiguser_id',
							'asigexp_id',
                            'periodo_id',
                            'fecha_asig',
							'ref_asig_id',
                            'ref_mot_asig_id'
						];

/*
public function estudianteact()
    {
        return $this->belongsTo(User::class, 'asigest_id', 'idnumber');
    }*/

    public function estudiante()
    {
        return $this->belongsTo(User::class, 'asigest_id', 'idnumber');
    }

    public function motivo_asig()
    {
        return $this->belongsTo(MotivoAsigCaso::class, 'ref_mot_asig_id', 'id');
    }

    public function tipo_asig()
    {
        return $this->belongsTo(RefAsignacionCaso::class, 'ref_asig_id', 'id');
    }

    public function expediente()
    {
        return $this->belongsTo(Expediente::class, 'asigexp_id', 'expid');
    }

    public function asig_docente()
    {
        return $this->hasOne(AsigDocenteCaso::class, 'asig_caso_id', 'id')
        ->where('activo',1);
    }

    public function citaciones()
    {
        return $this->hasMany(CitacionEstudiantes::class, 'asignacion_caso_id', 'id');
    }

    public function autorizaciones()
    {
        return $this->hasMany(Autorizacion::class, 'asig_caso_id', 'id');
    }


}
