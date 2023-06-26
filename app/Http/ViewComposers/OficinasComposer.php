<?php 
namespace App\Http\ViewComposers;

use Illuminate\View\View;

use App\Cptonota;
use App\Segmento;

use App\Periodo;

use App\TablaReferencia;
use DB;


/**
* 
*/
class OficinasComposer
{
	
	public function compose(View $view)
	{

		
        $cptonota = Cptonota::pluck('cpntnombre','id');
		$segmento = Segmento::join('sede_segmentos as sg','sg.segmento_id','=','segmentos.id')
		->where('sg.sede_id',session('sede')->id_sede)
		->where('estado',true)->first();
		$periodo = Periodo::join('sede_periodos as sp','sp.periodo_id','=','periodo.id')
		->where('sp.sede_id',session('sede')->id_sede)
		->where('estado',true)->first();
		$dias = TablaReferencia::where(['categoria'=>'dias_turno','tabla_ref'=>'turnos'])
        ->pluck('ref_nombre','id');  
	
			
		$view->with(['segmento'=>$segmento])
		->with(['periodo'=>$periodo])		
        ->with(['cptonota'=>$cptonota])
        ->with(['dias'=>$dias]);
	}

	
}


