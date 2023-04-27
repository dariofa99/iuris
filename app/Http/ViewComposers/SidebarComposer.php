<?php 
namespace App\Http\ViewComposers;

use Illuminate\View\View;
use DB;
use App\Sede;
use Illuminate\Support\Facades\Auth;

/**
*  
*/
class SidebarComposer
{
	
	public function compose(View $view)
	{
        $sedes = Sede::pluck('nombre','id_sede') ;
		$auth_conciliaciones = 0;
		if(Auth::user()) $auth_conciliaciones = Auth::user()->conciliaciones()->where('tipo_usuario_id','<>',199)->get();


		$view->with(['sedes'=>$sedes,'auth_conciliaciones'=>$auth_conciliaciones]);
	}

	
}


