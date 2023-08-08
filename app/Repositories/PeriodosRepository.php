<?php
namespace App\Repositories;

use App\Periodo;
use App\Services\PeriodosService;
use Illuminate\Http\Request;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class PeriodosRepository extends BaseRepository implements PeriodosService{
   
    public function __construct(Periodo $periodo)
    {
        parent::__construct($periodo);
    }
    function index($request)
	{
		$periodos = Periodo::join('sede_periodos as sp', 'sp.periodo_id', '=', 'periodo.id')
			->where('sp.sede_id', session('sede')->id_sede)
			->orderBy('periodo.created_at', 'desc')->get();
		return $periodos;
	}

    public function store(Request $request): Periodo 
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
    public function update(Periodo $periodo,Request $request): Periodo 
    {       
        $periodo->fill($request->all());
        $periodo->save();	   
        return $periodo;
    }
    public function getPeriodoActivo(): Periodo 
    {
        $periodo = Periodo::join('sede_periodos as sp', 'sp.periodo_id', '=', 'periodo.id')
        ->where('sp.sede_id', session('sede')->id_sede)
        ->where('estado', true)
        ->first();
        return $periodo;
    }


  
}
