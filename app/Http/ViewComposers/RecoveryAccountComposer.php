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
use Illuminate\Support\Facades\DB;
use App\Sede;
use App\TablaReferencia;
use Illuminate\Support\Facades\Auth;

/**
*  
*/
class RecoveryAccountComposer
{
	
	public function compose(View $view)
	{

		$tipodoc = TablaReferencia::where(['categoria'=>'tipo_doc','tabla_ref'=>'users'])
		->where('ref_nombre','<>','Sin definir')
		->pluck('ref_nombre','id'); 

		

		$view->with(['tipodoc'=>$tipodoc]);
	}

	
}


