<?php 
namespace App\Http\ViewComposers;

use Illuminate\View\View;
use App\RamaDerecho;
use App\User;
use App\Segmento;
use App\MotivoEstadoCaso;
use App\Estado;
use App\ReqAsistencia;
use App\Periodo;
use App\Role;
use App\RefAsignacionCaso;
use App\MotivoAsigCaso;
use DB;
use \App\TablaReferencia;



/**
*  
*/
class SolicitudesComposer
{
	
	public function compose(View $view)
	{
		//$estrato = DB::table('referencias_tablas')
		//->where(['tabla_ref'=>'users','categoria'=>'estrato'])->pluck('ref_nombre','id'); 
     

		$categories = TablaReferencia::where(['categoria'=>'type_category','tabla_ref'=>'solicitudes'])
		->where('ref_nombre','<>','Sin definir')
		->pluck('ref_nombre','id'); 
		

		$view->with(['categories'=>$categories]);
	}

	
}


