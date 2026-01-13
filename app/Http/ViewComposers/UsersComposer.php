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
class UsersComposer
{
	
	public function compose(View $view)
	{

		$tipodoc = TablaReferencia::where(['categoria'=>'tipo_doc','tabla_ref'=>'users'])
		->where('ref_nombre','<>','Sin definir')
		->pluck('ref_nombre','id'); 

		$tipopers = TablaReferencia::where(['categoria'=>'tipo_persona','tabla_ref'=>'users'])
		->where('ref_nombre','<>','Sin definir')
		->pluck('ref_nombre','id'); 

		$sedes = Sede::all() ;
		$roles = DB::table('roles')->where(function($query){
			if(!Auth::guest() and !currentUser()->hasRole('amatai')){
				return $query->where('id','<>',1);
			}
		}) 
		
		->pluck('display_name','id'); 
		
        $estrato = DB::table('referencias_tablas')
		->where(['tabla_ref'=>'users','categoria'=>'estrato'])
		->pluck('ref_nombre','id'); 
        $genero = DB::table('referencias_tablas')
		->where(['tabla_ref'=>'users','categoria'=>'genero'])
		->where('ref_nombre','<>','Sin definir')
		->pluck('ref_nombre','id'); 
		
        $estcivil = DB::table('referencias_tablas')
		->where(['tabla_ref'=>'users','categoria'=>'estado_civil'])
		->pluck('ref_nombre','id'); 
        $cursando = TablaReferencia::where(['categoria'=>'cursando','tabla_ref'=>'turnos'])->pluck('ref_nombre','id'); 
		$ramas_derecho = DB::table('rama_derecho')
		->groupBy('subrama')
		->pluck('subrama','id'); 
		$roles_profext= Role::leftjoin('permission_role','roles.id','=','permission_role.role_id')
		->where('permission_role.permission_id','14')
		->pluck('roles.display_name','roles.id');
		//$ramas_derecho = (array) $ramas_derecho;

		$view->with(['tipodoc'=>$tipodoc])
			 ->with(['estrato'=>$estrato])
			 ->with(['sedes'=>$sedes])
			 ->with(['ramas_derecho'=>$ramas_derecho])
			 ->with(['genero'=>$genero])
			 ->with(['roles_profext'=>$roles_profext])
			 ->with(['roles'=>$roles])
			 ->with(['estcivil'=>$estcivil]) 
			 ->with([
				'cursando'=>$cursando,
				"tipopers"=>$tipopers
			]);
	}

	
}


