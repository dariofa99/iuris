<?php
namespace App\Repositories;

use App\AsigDocenteCaso;
use App\Expediente;
use App\Services\AsignacionDocenteCasosService;
use App\Services\ExpedientesService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Event;

class AsignacionDocenteCasosRepository extends BaseRepository implements AsignacionDocenteCasosService{
   
    public function __construct(AsigDocenteCaso $model)
    {
        parent::__construct($model);
    }
    public function store(Request $request) : AsigDocenteCaso
    {
                
            $this->model->docidnumber=$request->input('docidnumber') ;
            $this->model->activo= ($request->has('activo')) ? $request->input('activo') : 1;
            $this->model->cambio_docidnumber= $request->has('cambio_docidnumber') ? $request->input('cambio_docidnumber') : null ;
            $this->model->asig_caso_id= $request->input('asig_caso_id')  ;
            $this->model->user_created_id= auth()->user()->idnumber;  ;
            $this->model->user_updated_id= auth()->user()->idnumber; ;
            $this->model->save();
                   
        return $this->model;
    }

    public function update(AsigDocenteCaso $expediente,Request $request) : AsigDocenteCaso
    {    
        $expediente->fill($request->all());
        $expediente->user_updated_id = auth()->user()->idnumber;
        $expediente->save();
        return $expediente;
    }
    
  
}




?>