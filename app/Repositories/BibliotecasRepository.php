<?php
namespace App\Repositories;

use App\Biblioteca;
use App\Services\BibliotecasService;

use Illuminate\Http\Request;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class BibliotecasRepository extends BaseRepository implements BibliotecasService{
   
    public function __construct(Biblioteca $bl)
    {
        parent::__construct($bl);
    }
    function index($request)
	{
        $this->applyValidateSede();
		$bibliotecas = $this->query
        ->where('bibliestado',$request->has('bibliestado') ? $request->input('bibliestado') : 1)
        ->Criterio($request)
        ->orderBy('created_at','desc')
        ->paginate(5);
		return $bibliotecas;
	}

    public function store(Request $request): Biblioteca 
    {
        $this->model->prdfecha_inicio = $request->input('prdfecha_inicio');
        $this->model->prdfecha_fin = $request->input('prdfecha_fin');
        $this->model->prddes_periodo = $request->input('prddes_periodo');
        $this->model->estado = $request->has('estado') ? $request->input('estado') : 0;
        $this->model->prdusercreated = currentUser()->idnumber;
        $this->model->prduserupdated = currentUser()->idnumber;
        $this->model->save();
        if (session('sede')) {
			$this->model->sedes()->attach(session('sede')->id_sede);
		}
        return $this->model;
    }
    public function update(Biblioteca $periodo,Request $request): Biblioteca 
    {       
        $periodo->fill($request->all());
        $periodo->save();	   
        return $periodo;
    }
 


  
}
