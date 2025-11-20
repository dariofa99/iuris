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
        'procesojud_id',
        'asiguser_id',
        'asigexp_id',
        'periodo_id',
        'fecha_asig',
        'ref_asig_id',
        'ref_mot_asig_id',
        'fecha_eva'
    ];


    public function user_asig()
    {
        return $this->belongsTo(User::class, 'asiguser_id', 'idnumber');
    }

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
            ->where('activo', 1);
    }

    public function citaciones()
    {
        return $this->hasMany(CitacionEstudiantes::class, 'asignacion_caso_id', 'id');
    }

    public function autorizaciones()
    {
        return $this->hasMany(Autorizacion::class, 'asig_caso_id', 'id');
    }

    public function procesosJudiciales()
    {
        return $this->hasMany(ProcesoJudicialExpediente::class, 'asig_caso_id', 'id');
    }

    public function estadoProcJudicial()
    {
        return $this->belongsTo(TablaReferencia::class, 'procesojud_id', 'id');
    }

    public function periodo()
    {
        return $this->belongsTo(Periodo::class, 'periodo_id', 'id');
    }
    public function pausas()
    {
        return $this->hasMany(ExpedientePausas::class, 'asig_caso_id', 'id');
    }

    public function estadosProcJudCount()
    {
        $esprocesos = $this->procesosJudiciales()
            ->where('estado_id', '=', $this->procesojud_id)->get();
        return count($esprocesos);
    }

       public function docentes()
    {
        return $this->hasMany(AsigDocenteCaso::class, 'asig_caso_id', 'id');
    }

     public function incidencias()
    {
   
        return $this->belongsToMany(Incidencia::class, 'incidencias_asignacion', 'asig_caso_id', 'incidencia_id')
            ->withPivot('incidencia_id', 'asig_caso_id', 'id')
            ->withTimestamps();
    
    }

}
