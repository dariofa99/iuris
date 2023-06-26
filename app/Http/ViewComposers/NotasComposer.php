<?php 
namespace App\Http\ViewComposers;

use Illuminate\View\View;

use App\Cptonota;
use App\Segmento;

use App\Periodo;
use App\TablaReferencia;

/**
* 
*/
class NotasComposer
{
	
	public function compose(View $view)
	{

		
		$cptonota = Cptonota::pluck('cpntnombre','id');
		$periodo = Periodo::join('sede_periodos as sp','sp.periodo_id','=','periodo.id')
		->where('sp.sede_id',session('sede')->id_sede)
		->where('estado',true)->first();
		$segmento = Segmento::join('sede_segmentos as sg','sg.segmento_id','=','segmentos.id')
		->where('sg.sede_id',session('sede')->id_sede)
		->where('perid',$periodo->id)
		->get();
		$periodo = Periodo::join('sede_periodos as sp','sp.periodo_id','=','periodo.id')
		->where('sp.sede_id',session('sede')->id_sede)
		->where('estado',true)->get();
		$dias = TablaReferencia::where(['categoria'=>'dias_turno','tabla_ref'=>'turnos'])
        ->pluck('ref_nombre','id'); 
	
			//dd($segmento);
		$view->with(['segmentos'=>$segmento])
	
		->with(['periodos'=>$periodo])
		
		->with(['cptonota'=>$cptonota]);
	}

	
}


