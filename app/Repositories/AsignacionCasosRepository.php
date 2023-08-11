<?php
namespace App\Repositories;

use App\AsignacionCaso;
use App\Expediente;
use App\Services\AsignacionCasosService;
use App\Services\ExpedientesService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Event;

class AsignacionCasosRepository extends BaseRepository implements AsignacionCasosService{
   
    public function __construct(AsignacionCaso $model)
    {
        parent::__construct($model);
    }
    public function store(Request $request) : AsignacionCaso
    { 
        
        $this->model->anotacion = ($request->has('anotacion')) ? $request->input('anotacion') : 'asignado';
        $this->model->activo = ($request->has('activo')) ? $request->input('activo') : 1;
        $this->model->procesojud_id = ($request->has('procesojud_id')) ? $request->input('procesojud_id') : 1;
        $this->model->asigest_id = ($request->has('asigest_id')) ? $request->input('asigest_id') : null;
        $this->model->asiguser_id = currentUser()->idnumber;
        $this->model->asigexp_id = ($request->has('asigexp_id')) ? $request->input('asigexp_id') : null;
        $this->model->fecha_asig = date('Y-m-d H:i:s');
        $this->model->periodo_id = ($request->has('periodo_id'));
        $this->model->ref_asig_id = ($request->has('ref_asig_id')) ? $request->input('ref_asig_id') : 1;
        $this->model->ref_mot_asig_id = ($request->has('ref_mot_asig_id')) ? $request->input('ref_mot_asig_id') : 1;
        $this->model->save(); 
        return $this->model;
    }

    public function update(AsignacionCaso $expediente,Request $request) : AsignacionCaso
    {    
        $expediente->fill($request->all());
        $expediente->save();
        return $expediente;
    }
    
  
}




?>